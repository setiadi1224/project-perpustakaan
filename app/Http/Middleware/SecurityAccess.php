<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SecurityAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();

        if ($user->role !== 'kepala_perpustakaan') {

            Log::warning('Percobaan akses security dashboard', [
                'ip' => $request->ip(),
                'user' => $user?->email
            ]);
            \DB::table('blocked_ips')->insert([
                'ip' => request()->ip(),
                'created_at' => now(),
                'updated_at' => now()
            ]);
            abort(403, 'Akses ditolak');
        }

        return $next($request);
    }
}
