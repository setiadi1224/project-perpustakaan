<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Peminjaman;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserDashboardController extends Controller
{
    // ===============================
    // DASHBOARD
    // ===============================
    public function home(Request $request)
    {
        $user = Auth::user();

        $bukuDipinjam = Peminjaman::where('user_id', $user->id)
            ->where('status', 'dipinjam')
            ->sum('jumlah');

        $totalPeminjaman = Peminjaman::where('user_id', $user->id)->count();

        // 🔥 hanya hitung yang belum lunas
        $dendaAktif = Peminjaman::where('user_id', $user->id)
            ->where('status_pembayaran', '!=', 'lunas')
            ->sum('denda');

        $kategoris = Kategori::all();

        $bukuPopuler = Buku::with('kategori')
            ->when($request->kategori, function ($q) use ($request) {
                $q->where('kategori_id', $request->kategori);
            })
            ->latest()
            ->paginate(8)
            ->appends($request->only('kategori'));

        return view('user.home', compact(
            'bukuDipinjam',
            'totalPeminjaman',
            'dendaAktif',
            'kategoris',
            'bukuPopuler'
        ));
    }

    // ===============================
    // DETAIL BUKU
    // ===============================
    public function detailBuku($id)
    {
        $buku = Buku::with('kategori')->findOrFail($id);

        $rekomendasi = Buku::where('id', '!=', $id)
            ->latest()
            ->take(4)
            ->get();

        $pinjaman = Peminjaman::where('user_id', Auth::id())
            ->where('buku_id', $id)
            ->whereIn('status', ['menunggu', 'dipinjam'])
            ->first();

        return view('user.detail_buku', compact(
            'buku',
            'rekomendasi',
            'pinjaman'
        ));
    }

    // ===============================
    // PINJAM
    // ===============================
    public function pinjam(Request $request, $id)
    {
        $request->validate([
            'jumlah' => 'required|integer|min:1|max:5'
        ]);

        $userId = Auth::id();
        $buku = Buku::findOrFail($id);
        $jumlah = (int) $request->jumlah;

        if ($jumlah > $buku->stok) {
            return back()->with('error', 'Stok tidak cukup');
        }

        $cek = Peminjaman::where('user_id', $userId)
            ->where('buku_id', $id)
            ->whereIn('status', ['menunggu', 'dipinjam'])
            ->first();

        if ($cek) {
            return back()->with('error', 'Kamu sudah meminjam / menunggu buku ini');
        }

        $totalDipinjam = Peminjaman::where('user_id', $userId)
            ->whereIn('status', ['dipinjam', 'menunggu'])
            ->sum('jumlah');

        if ($totalDipinjam + $jumlah > 5) {
            return back()->with('error', 'Maksimal 5 buku');
        }

        Peminjaman::create([
            'user_id' => $userId,
            'buku_id' => $id,
            'jumlah' => $jumlah,
            'status' => 'menunggu',
            'tanggal_pinjam' => now(),
        ]);

        return back()->with('success', 'Menunggu persetujuan petugas');
    }

    // ===============================
    // RETURN (🔥 FIX DENDA)
    // ===============================
    public function returnBuku($id)
    {
        $p = Peminjaman::with('buku')->findOrFail($id);

        if ($p->user_id != Auth::id()) {
            return back()->with('error', 'Akses ditolak');
        }

        if ($p->status != 'dipinjam') {
            return back()->with('error', 'Tidak valid');
        }

        $today = now();
        $batas = Carbon::parse($p->tanggal_kembali);

        $terlambat = $today->gt($batas)
            ? $batas->diffInDays($today)
            : 0;

        $denda = 0;

        if ($terlambat > 0) {
            $dendaPerHari = 5000;
            $denda = $terlambat * $dendaPerHari * $p->jumlah;
        }

        $p->update([
            'status' => 'dikembalikan',
            'tanggal_dikembalikan' => $today,
            'denda' => $denda,
            'status_pembayaran' => $denda > 0 ? 'belum' : 'lunas'
        ]);

        $p->buku->increment('stok', $p->jumlah);

        return back()->with('success', 'Buku dikembalikan');
    }

    // ===============================
    // LIBRARY
    // ===============================
    public function library(Request $request)
    {
        $books = Buku::with('kategori')
            ->when($request->search, function ($q) use ($request) {
                $q->where(function ($qq) use ($request) {
                    $qq->where('judul', 'like', "%{$request->search}%")
                        ->orWhere('penulis', 'like', "%{$request->search}%");
                });
            })
            ->when($request->kategori, function ($q) use ($request) {
                $q->where('kategori_id', $request->kategori);
            })
            ->paginate(10)
            ->withQueryString();

        $kategoris = Kategori::all();

        return view('user.library', compact('books', 'kategoris'));
    }

    // ===============================
    // RIWAYAT
    // ===============================
    public function riwayat(Request $request)
    {
        $query = Peminjaman::with('buku')
            ->where('user_id', Auth::id());

        if ($request->search) {
            $query->whereHas('buku', function ($q) use ($request) {
                $q->where('judul', 'like', '%' . $request->search . '%');
            });
        }

        $riwayats = $query->latest()
            ->paginate(5)
            ->withQueryString();

        return view('user.riwayat', compact('riwayats'));
    }

    // ===============================
    // DENDA (🔥 FIX TOTAL)
    // ===============================
    public function denda()
    {
        $denda = Peminjaman::with('buku')
            ->where('user_id', Auth::id())
            ->where('denda', '>', 0)
            ->latest()
            ->paginate(5);

        $totalDenda = 0;

        foreach ($denda as $item) {

            $batas = Carbon::parse($item->tanggal_kembali);
            $terlambat = 0;
            $dendaFix = $item->denda;

            if ($item->status == 'dipinjam' && now()->gt($batas)) {
                $terlambat = $batas->diffInDays(now());
                $dendaFix = $terlambat * 5000 * $item->jumlah;
            }

            // 🔥 kalau lunas nol
            if ($item->status_pembayaran == 'lunas') {
                $dendaFix = 0;
            }

            $item->terlambat = $terlambat;
            $item->total_denda = $dendaFix;

            $totalDenda += $dendaFix;
        }

        return view('user.denda', compact('denda', 'totalDenda'));
    }

    // ===============================
    // BAYAR (🔥 UNTUK MODAL)
    // ===============================
    public function bayar(Request $request, $id)
    {
        $p = Peminjaman::findOrFail($id);

        if ($p->user_id != Auth::id()) {
            return back()->with('error', 'Akses ditolak');
        }

        if ($p->status_pembayaran == 'lunas') {
            return back()->with('error', 'Sudah dibayar');
        }

        $request->validate([
            'bukti' => 'required|image|max:2048'
        ]);

        $file = $request->file('bukti')->store('bukti', 'public');

        $p->update([
            'bukti_pembayaran' => $file,
            'status_pembayaran' => 'menunggu'
        ]);

        return back()->with('success', 'Bukti dikirim, tunggu verifikasi');
    }

    // ===============================
    // PROFILE
    // ===============================
    public function profile()
    {
        return view('user.profile', [
            'user' => Auth::user()
        ]);
    }
    public function updateProfile(Request $request)
    {

        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'no_telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string|max:255',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048', // max 2MB
        ]);

        // Update semua field
        $user->name = $request->name;
        $user->email = $request->email;
        $user->no_telepon = $request->no_telepon;
        $user->alamat = $request->alamat;

        // Jika user upload foto baru
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($user->foto && Storage::exists('public/foto/' . $user->foto)) {
                Storage::delete('public/foto/' . $user->foto);
            }

            // Simpan foto baru
            $fotoName = time() . '_' . $request->file('foto')->getClientOriginalName();
            $request->file('foto')->storeAs('public/foto', $fotoName);
            $user->foto = $fotoName;
        }
        /** @var \App\Models\User $user */
        $user = Auth::user();
        // Simpan ke database
        $user->save();

        return back()->with('success', 'Profile updated successfully!');
    }
}
