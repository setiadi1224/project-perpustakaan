<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $ip = $request->ip();
        $email = $request->email;

        /* =========================
         1. CEK IP BLOCKED
        ========================= */
        if (DB::table('blocked_ips')->where('ip', $ip)->exists()) {
            return back()->with('error', 'IP kamu diblokir!');
        }

        /* =========================
         2. BRUTE FORCE SIMPLE
        ========================= */
        $attempts = session('login_attempts', 0) + 1;
        session(['login_attempts' => $attempts]);

        if ($attempts > 5) {
            return back()->with('error', 'Terlalu banyak percobaan login!');
        }

        /* =========================
         3. VALIDASI
        ========================= */
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ]);

        /* =========================
         4. FEATURE ENGINEERING
        ========================= */
        $jam_login = now()->hour;
        $userAgent = $request->header('User-Agent');

        $device_baru = DB::table('login_logs')
            ->where('email', $email)
            ->where('user_agent', $userAgent)
            ->doesntExist() ? 1 : 0;

        $ip_luar = $ip !== '127.0.0.1' ? 1 : 0;

        /* =========================
         5. CALL AI
        ========================= */
        $hasil = null;

        try {
            $response = Http::timeout(5)->post('http://127.0.0.1:8001/cek', [
                'jam_login'   => $jam_login,
                'ip_luar'     => $ip_luar,
                'device_baru' => $device_baru,
            ]);

            $hasil = $response->json();
        } catch (\Exception $e) {
            Log::error('AI ERROR: ' . $e->getMessage());
        }

        $level = $hasil['level'] ?? null;

        /* =========================
         6. AI SECURITY CHECK (SEBELUM LOGIN)
        ========================= */

        //  HIGH = BLOCK PERMANENT
        if ($level === 'HIGH') {

            DB::table('blocked_ips')->updateOrInsert(
                ['ip' => $ip],
                ['created_at' => now(), 'updated_at' => now()]
            );

            Log::warning('HIGH RISK LOGIN BLOCKED', [
                'ip' => $ip,
                'email' => $email
            ]);

            return back()->with('error', 'Akses diblokir karena aktivitas berbahaya.');
        }

        //  MEDIUM = COOLDOWN
        if ($level === 'MEDIUM') {

            DB::table('login_cooldowns')->updateOrInsert(
                ['ip' => $ip],
                [
                    'email'        => $email,
                    'blocked_until'=> now()->addMinutes(5),
                    'created_at'   => now(),
                    'updated_at'   => now()
                ]
            );

            return back()->with('warning', 'Login mencurigakan, cooldown 5 menit.');
        }

        /* =========================
         7. LOGIN AUTH
        ========================= */
        if (!Auth::attempt($request->only('email', 'password'))) {

            DB::table('login_logs')->insert([
                'email'       => $email,
                'ip'          => $ip,
                'jam_login'   => $jam_login,
                'ip_luar'     => $ip_luar,
                'device_baru' => $device_baru,
                'user_agent'  => $userAgent,
                'status'      => 'failed',
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);

            return back()->withErrors([
                'email' => 'Email atau password salah.'
            ]);
        }

        /* =========================
         8. LOGIN SUCCESS
        ========================= */
        $request->session()->regenerate();
        session()->forget('login_attempts');

        DB::table('login_logs')->insert([
            'email'       => $email,
            'ip'          => $ip,
            'jam_login'   => $jam_login,
            'ip_luar'     => $ip_luar,
            'device_baru' => $device_baru,
            'user_agent'  => $userAgent,
            'status'      => 'success',
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        /* =========================
         9. LOG AI RESULT (OPTIONAL)
        ========================= */
        if ($hasil) {
            DB::table('security_logs')->insert([
                'email'      => $email,
                'ip'         => $ip,
                'status'     => $hasil['status'] ?? 'safe',
                'level'      => $level ?? 'LOW',
                'risk_score' => $hasil['risk_score'] ?? 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        /* =========================
         10. REDIRECT
        ========================= */
        return $this->redirectBasedOnRole();
    }

    protected function redirectBasedOnRole()
    {
        $user = Auth::user();

        return match ($user->role) {
            'kepala_perpustakaan' => redirect()->route('kepala.home'),
            'petugas'             => redirect()->route('petugas.home'),
            default               => redirect()->route('user.home'),
        };
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}