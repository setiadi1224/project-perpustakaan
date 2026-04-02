<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Peminjaman;
use App\Models\User; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserDashboardController extends Controller
{
    // DASHBOARD USER
    public function home(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $bukuDipinjam = Peminjaman::where('user_id', $user->id)
            ->where('status', 'dipinjam')
            ->count();

        $totalPeminjaman = Peminjaman::where('user_id', $user->id)->count();

        $dendaAktif = Peminjaman::where('user_id', $user->id)
            ->where('denda', '>', 0)
            ->sum('denda');

        $kategoris = Kategori::all();
        $search = $request->get('search');

        $bukuPopuler = Buku::with('kategori')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('judul', 'like', "%$search%")
                      ->orWhere('penulis', 'like', "%$search%");
                });
            })
            ->when($request->kategori, function ($query) use ($request) {
                $query->where('kategori_id', $request->kategori);
            })
            ->latest()
            ->take(8)
            ->get();

        return view('user.home', compact(
            'bukuDipinjam',
            'totalPeminjaman',
            'dendaAktif',
            'kategoris',
            'bukuPopuler',
            'search'
        ));
    }

    // HALAMAN KATEGORI
    public function kategori()
    {
        $kategoris = Kategori::withCount('bukus')->get();
        return view('user.kategori', compact('kategoris'));
    }

    // HALAMAN LIBRARY
    public function library(Request $request)
    {
        $query = Buku::query();

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('judul', 'like', '%' . $request->search . '%')
                  ->orWhere('penulis', 'like', '%' . $request->search . '%');
            });
        }

        $books = $query->paginate(6);
        return view('user.library', compact('books'));
    }
    public function detailBuku($id)
{
    $buku = Buku::with('kategori')->findOrFail($id);

    $rekomendasi = Buku::where('id', '!=', $id)
        ->take(4)
        ->get();

    return view('user.detail_buku', compact('buku', 'rekomendasi'));
}

    // RIWAYAT PEMINJAMAN
    public function riwayat()
    {
        /** @var User $user */
        $user = Auth::user();

        $riwayats = Peminjaman::with('buku')
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        return view('user.riwayat', compact('riwayats'));
    }

    // HALAMAN DENDA
    public function denda()
    {
        /** @var User $user */
        $user = Auth::user();

        $denda = Peminjaman::with('buku')
            ->where('user_id', $user->id)
            ->where('denda', '>', 0)
            ->latest()
            ->get();

        $totalDenda = $denda->sum('denda');

        return view('user.denda', compact('denda', 'totalDenda'));
    }

    // HALAMAN PROFILE
    public function profile()
    {
        /** @var User $user */
        $user = Auth::user();

        return view('user.profile', compact('user'));
    }

    // UPDATE PROFILE + FOTO
    public function updateProfile(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        // VALIDASI
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'no_telepon' => 'nullable|string|max:15',
            'alamat' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        // UPLOAD FOTO
        if ($request->hasFile('foto')) {

            // hapus foto lama
            if ($user->foto && Storage::exists('public/foto/' . $user->foto)) {
                Storage::delete('public/foto/' . $user->foto);
            }

            // simpan foto baru
            $path = $request->file('foto')->store('public/foto');

            $user->foto = basename($path);
        }

        // UPDATE DATA
        $user->name = $request->name;
        $user->email = $request->email;
        $user->no_telepon = $request->no_telepon;
        $user->alamat = $request->alamat;

        // SIMPAN
        $user->save();

        return back()->with('success', 'Profile berhasil diperbarui!');
    }
}