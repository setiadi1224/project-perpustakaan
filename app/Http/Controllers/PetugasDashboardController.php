<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class PetugasDashboardController extends Controller
{
    // ================= DASHBOARD =================
    public function home()
    {
        return view('petugas.home', [
            'totalAnggota' => User::where('role', 'user')->count(),
            'totalBuku' => Buku::count(),
            'peminjamanAktif' => Peminjaman::where('status', 'dipinjam')->count(),
            'totalDenda' => Peminjaman::sum('denda'),
        ]);
    }

    // ================= ANGGOTA =================
    public function anggota(Request $request)
    {
        $anggotas = User::where('role', 'user')
            ->when($request->search, function ($q) use ($request) {
                $q->where(function ($query) use ($request) {
                    $query->where('name', 'like', "%{$request->search}%")
                          ->orWhere('email', 'like', "%{$request->search}%");
                });
            })
            ->latest()
            ->get();

        return view('petugas.anggota', compact('anggotas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user',
            'no_telepon' => $request->no_telepon,
            'alamat' => $request->alamat,
        ]);

        return back()->with('success', 'Anggota berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        User::findOrFail($id)->update([
            'name' => $request->name,
            'email' => $request->email,
            'no_telepon' => $request->no_telepon,
            'alamat' => $request->alamat,
        ]);

        return back()->with('success', 'Anggota berhasil diupdate');
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return back()->with('success', 'Anggota berhasil dihapus');
    }

    // ================= BUKU =================

    public function buku(Request $request)
    {
        $bukus = Buku::with('kategori')
            ->when($request->search, function ($q) use ($request) {
                $q->where('judul', 'like', "%{$request->search}%")
                  ->orWhere('penulis', 'like', "%{$request->search}%");
            })
            ->latest()
            ->get();

        $kategoris = Kategori::all();

        return view('petugas.buku', compact('bukus', 'kategoris'));
    }

    // ➕ TAMBAH BUKU
    public function storeBuku(Request $request)
    {
        $request->validate([
            'judul' => 'required',
            'penulis' => 'required',
            'stok' => 'required|integer',
            'kategori_id' => 'required|exists:kategoris,id', // 🔥 FIX
            'cover' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $coverPath = null;

        if ($request->hasFile('cover')) {
            $coverPath = $request->file('cover')->store('buku', 'public');
        }

        Buku::create([
            'judul' => $request->judul,
            'penulis' => $request->penulis,
            'penerbit' => $request->penerbit,
            'tahun_terbit' => $request->tahun_terbit,
            'kategori_id' => $request->kategori_id,
            'deskripsi' => $request->deskripsi,
            'stok' => $request->stok,
            'cover' => $coverPath,
        ]);

        return back()->with('success', 'Buku berhasil ditambahkan');
    }

    // ✏️ UPDATE BUKU
    public function updateBuku(Request $request, $id)
    {
        $buku = Buku::findOrFail($id);

        $request->validate([
            'judul' => 'required',
            'penulis' => 'required',
            'stok' => 'required|integer',
            'kategori_id' => 'required|exists:kategoris,id', // 🔥 FIX
            'cover' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('cover')) {

            if ($buku->cover) {
                Storage::disk('public')->delete($buku->cover);
            }

            $buku->cover = $request->file('cover')->store('buku', 'public');
        }

        $buku->update([
            'judul' => $request->judul,
            'penulis' => $request->penulis,
            'penerbit' => $request->penerbit,
            'tahun_terbit' => $request->tahun_terbit,
            'kategori_id' => $request->kategori_id,
            'deskripsi' => $request->deskripsi,
            'stok' => $request->stok,
            'cover' => $buku->cover,
        ]);

        return back()->with('success', 'Buku berhasil diupdate');
    }

    // ❌ DELETE
    public function deleteBuku($id)
    {
        $buku = Buku::findOrFail($id);

        if ($buku->cover) {
            Storage::disk('public')->delete($buku->cover);
        }

        $buku->delete();

        return back()->with('success', 'Buku berhasil dihapus');
    }
}