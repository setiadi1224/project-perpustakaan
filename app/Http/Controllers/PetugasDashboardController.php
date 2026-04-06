<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class PetugasDashboardController extends Controller
{
    // ================= DASHBOARD =================
 public function home()
{
    $totalAnggota = User::where('role', 'user')->count();
    $totalBuku = Buku::count();
    $peminjamanAktif = Peminjaman::where('status', 'dipinjam')->count();
    $totalDenda = Peminjaman::sum('denda');
    $menunggu = Peminjaman::where('status', 'menunggu')->count();

    // 🔥 aktivitas terbaru
    $recentPeminjaman = Peminjaman::with(['user', 'buku'])
        ->latest()
        ->take(5)
        ->get();

    // 🔥 data chart (7 hari terakhir)
    $chartData = [];
    $labels = [];

    for ($i = 6; $i >= 0; $i--) {
        $date = Carbon::now()->subDays($i);

        $labels[] = $date->format('d M');

        $chartData[] = Peminjaman::whereDate('created_at', $date)->count();
    }

    // 🔥 buku terlambat
    $terlambat = Peminjaman::where('status', 'dipinjam')
        ->whereDate('tanggal_kembali', '<', now())
        ->count();

    return view('petugas.home', compact(
        'totalAnggota',
        'totalBuku',
        'peminjamanAktif',
        'totalDenda',
        'menunggu',
        'recentPeminjaman',
        'chartData',
        'labels',
        'terlambat'
    ));
}
    // ================= ANGGOTA =================
    public function anggota(Request $request)
    {
        $anggotas = User::where('role', 'user')
            ->when($request->search, function ($q) use ($request) {
                $q->where(function ($qq) use ($request) {
                    $qq->where('name', 'like', "%{$request->search}%")
                       ->orWhere('email', 'like', "%{$request->search}%");
                });
            })
            ->latest()
            ->paginate(5)
            ->withQueryString();

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
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
        ]);

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
                $q->where(function ($qq) use ($request) {
                    $qq->where('judul', 'like', "%{$request->search}%")
                       ->orWhere('penulis', 'like', "%{$request->search}%");
                });
            })
            ->latest()
            ->paginate(5)
            ->withQueryString();

        $kategoris = Kategori::all();

        return view('petugas.buku', compact('bukus', 'kategoris'));
    }

    public function storeBuku(Request $request)
    {
        $request->validate([
            'judul' => 'required',
            'penulis' => 'required',
            'stok' => 'required|integer',
            'kategori_id' => 'required|exists:kategoris,id',
        ]);

        $cover = $request->file('cover')?->store('buku', 'public');

        Buku::create([
            'judul' => $request->judul,
            'penulis' => $request->penulis,
            'penerbit' => $request->penerbit,
            'tahun_terbit' => $request->tahun_terbit,
            'kategori_id' => $request->kategori_id,
            'deskripsi' => $request->deskripsi,
            'stok' => $request->stok,
            'cover' => $cover,
        ]);

        return back()->with('success', 'Buku berhasil ditambahkan');
    }

    public function updateBuku(Request $request, $id)
    {
        $buku = Buku::findOrFail($id);

        if ($request->hasFile('cover')) {
            if ($buku->cover) {
                Storage::disk('public')->delete($buku->cover);
            }
            $buku->cover = $request->file('cover')->store('buku', 'public');
        }

        $buku->update($request->except('cover'));

        return back()->with('success', 'Buku berhasil diupdate');
    }

    public function deleteBuku($id)
    {
        $buku = Buku::findOrFail($id);

        if ($buku->cover) {
            Storage::disk('public')->delete($buku->cover);
        }

        $buku->delete();

        return back()->with('success', 'Buku berhasil dihapus');
    }

    // ================= PEMINJAMAN (🔥 FIX DI SINI) =================
    public function peminjaman(Request $request)
    {
        $data = Peminjaman::with(['user', 'buku'])

            ->when($request->search, function ($q) use ($request) {
                $q->where(function ($query) use ($request) {
                    $query->whereHas('user', function ($qq) use ($request) {
                        $qq->where('name', 'like', '%' . $request->search . '%');
                    })
                    ->orWhereHas('buku', function ($qq) use ($request) {
                        $qq->where('judul', 'like', '%' . $request->search . '%');
                    });
                });
            })

            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view('petugas.peminjaman', compact('data'));
    }

    // ================= APPROVE =================
    public function approve($id)
    {
        $p = Peminjaman::with('buku')->findOrFail($id);

        if ($p->status != 'menunggu') {
            return back()->with('error', 'Sudah diproses');
        }

        if ($p->buku->stok <= 0) {
            return back()->with('error', 'Stok habis');
        }

        $p->update([
            'status' => 'dipinjam',
            'tanggal_pinjam' => now(),
            'tanggal_kembali' => now()->addDays(1),
        ]);

        $p->buku->decrement('stok');

        return back()->with('success', 'Disetujui');
    }

    // ================= RETURN =================
    public function returnBuku($id)
    {
        $p = Peminjaman::with('buku')->findOrFail($id);

        if (!in_array($p->status, ['dipinjam', 'terlambat'])) {
            return back()->with('error', 'Tidak valid');
        }

        $today = now();
        $batas = Carbon::parse($p->tanggal_kembali);

        $denda = 0;

        if ($today->gt($batas)) {
            $telat = $today->diffInDays($batas);
            $denda = 20000 + ($telat * 5000);
        }

        $p->update([
            'status' => 'dikembalikan',
            'tanggal_dikembalikan' => $today,
            'denda' => $denda,
        ]);

        $p->buku->increment('stok');

        return back()->with('success', 'Buku dikembalikan');
    }

    // ================= DENDA =================
   public function denda(Request $request)
{
    $data = Peminjaman::with(['user', 'buku'])
        ->latest()
        ->paginate(5)
        ->withQueryString();

    $totalDenda = 0;

    foreach ($data as $item) {
        $terlambat = 0;
        $denda = $item->denda;

        if ($item->status == 'dipinjam') {
            $batas = \Carbon\Carbon::parse($item->tanggal_kembali);

            if (now()->gt($batas)) {
                $terlambat = now()->diffInDays($batas);
                $denda = 20000 + ($terlambat * 5000);
            }
        }

        $item->terlambat = $terlambat;
        $item->total_denda = $denda;

        $totalDenda += $denda;
    }

    return view('petugas.denda', compact('data', 'totalDenda'));
}
}