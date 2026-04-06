<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Peminjaman;
// use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class UserDashboardController extends Controller
{
    // ===============================
    // DASHBOARD USER (🔥 PAGINATION FIX)
    // ===============================
    public function home(Request $request)
    {
        $user = Auth::user();

        $bukuDipinjam = Peminjaman::where('user_id', $user->id)
            ->where('status', 'dipinjam')
            ->count();

        $totalPeminjaman = Peminjaman::where('user_id', $user->id)->count();

        $dendaAktif = Peminjaman::where('user_id', $user->id)
            ->where('denda', '>', 0)
            ->sum('denda');

        $kategoris = Kategori::all();
        $search = $request->search;

        $bukuPopuler = Buku::with('kategori')
            ->when($search, function ($q) use ($search) {
                $q->where(function ($qq) use ($search) {
                    $qq->where('judul', 'like', "%$search%")
                        ->orWhere('penulis', 'like', "%$search%");
                });
            })
            ->when($request->kategori, function ($q) use ($request) {
                $q->where('kategori_id', $request->kategori);
            })
            ->latest()
            ->paginate(16) // 🔥 FIX
            ->withQueryString();

        return view('user.home', compact(
            'bukuDipinjam',
            'totalPeminjaman',
            'dendaAktif',
            'kategoris',
            'bukuPopuler',
            'search'
        ));
    }

    // ===============================
    // KATEGORI
    // ===============================
    public function kategori()
    {
        $kategoris = Kategori::withCount('bukus')->get();
        return view('user.kategori', compact('kategoris'));
    }

    // ===============================
    // LIBRARY (SUDAH BAGUS 👍)
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

        return view('user.library', compact('books','kategoris'));
    }

    // ===============================
    // DETAIL BUKU
    // ===============================
    public function detailBuku($id)
    {
        $buku = Buku::with('kategori')->findOrFail($id);

        $rekomendasi = Buku::where('id', '!=', $id)
            ->take(4)
            ->get();

        return view('user.detail_buku', compact('buku', 'rekomendasi'));
    }

    // ===============================
    // PINJAM BUKU
    // ===============================
    public function pinjam($id)
    {
        $user = Auth::user();
        $buku = Buku::findOrFail($id);

        if ($buku->stok <= 0) {
            return back()->with('error', 'Stok buku habis');
        }

        $cek = Peminjaman::where('user_id', $user->id)
            ->where('buku_id', $id)
            ->whereIn('status', ['menunggu', 'dipinjam'])
            ->exists();

        if ($cek) {
            return back()->with('error', 'Kamu sudah meminjam buku ini');
        }

        Peminjaman::create([
            'user_id' => $user->id,
            'buku_id' => $id,
            'tanggal_pinjam' => now(),
            'status' => 'menunggu',
            'denda' => 0
        ]);

        return back()->with('success', 'Menunggu persetujuan petugas');
    }

    // ===============================
    // RETURN BUKU
    // ===============================
    public function returnBuku($id)
    {
        $peminjaman = Peminjaman::with('buku')->findOrFail($id);
        $user = Auth::user();

        if ($peminjaman->user_id != $user->id) {
            return back()->with('error', 'Akses ditolak');
        }

        if ($peminjaman->status != 'dipinjam') {
            return back()->with('error', 'Buku tidak bisa dikembalikan');
        }

        $hari = Carbon::parse($peminjaman->tanggal_pinjam)->diffInDays(now());

        $denda = 0;
        if ($hari > 1) {
            $denda = 20000 + (($hari - 1) * 5000);
        }

        $peminjaman->update([
            'tanggal_kembali' => now(),
            'status' => 'dikembalikan',
            'denda' => $denda
        ]);

        $peminjaman->buku->increment('stok');

        return back()->with('success', 'Buku berhasil dikembalikan');
    }

    // ===============================
    // RIWAYAT (🔥 FIX PAGINATION)
    // ===============================
    public function riwayat(Request $request)
    {
        $user = Auth::user();

        $query = Peminjaman::with('buku')
            ->where('user_id', $user->id);

        if ($request->search) {
            $query->whereHas('buku', function ($q) use ($request) {
                $q->where('judul', 'like', '%' . $request->search . '%');
            });
        }

        $riwayats = $query->latest()
            ->paginate(5) // 🔥 FIX
            ->withQueryString();

        return view('user.riwayat', compact('riwayats'));
    }

    // ===============================
    // DENDA (🔥 FIX PAGINATION)
    // ===============================
    public function denda()
    {
        $user = Auth::user();

        $denda = Peminjaman::with('buku')
            ->where('user_id', $user->id)
            ->where('denda', '>', 0)
            ->latest()
            ->paginate(5); // 🔥 FIX

        $totalDenda = $denda->sum('denda');

        return view('user.denda', compact('denda', 'totalDenda'));
    }

    // ===============================
    // PROFILE
    // ===============================
    public function profile()
    {
        $user = Auth::user();
        return view('user.profile', compact('user'));
    }

    // ===============================
    // UPDATE PROFILE
    // ===============================
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'no_telepon' => 'nullable|string|max:15',
            'alamat' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        if ($request->hasFile('foto')) {

            if ($user->foto && Storage::exists('public/foto/' . $user->foto)) {
                Storage::delete('public/foto/' . $user->foto);
            }

            $path = $request->file('foto')->store('public/foto');
            $user->foto = basename($path);
        }
/** @var \App\Models\User $user */
$user = Auth::user();
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'no_telepon' => $request->no_telepon,
            'alamat' => $request->alamat
        ]);

        return back()->with('success', 'Profile berhasil diperbarui!');
    }
}