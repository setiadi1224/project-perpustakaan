<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Barryvdh\DomPDF\Facade\Pdf;

class KepalaDashboardController extends Controller
{
    // ================= DASHBOARD =================
    public function home()
    {
        $totalBuku = Buku::count();
        $totalAnggota = User::where('role', 'user')->count();
        $totalPeminjaman = Peminjaman::count();
        $totalDenda = Peminjaman::sum('denda');

        $petugas = User::where('role', 'petugas')->latest()->get();
        $bukuPopuler = Buku::latest()->take(5)->get();

        return view('kepala.home', compact(
            'totalBuku',
            'totalAnggota',
            'totalPeminjaman',
            'totalDenda',
            'petugas',
            'bukuPopuler'
        ));
    }

    // ================= KELOLA PETUGAS =================
    public function petugas(Request $request)
    {
        $petugas = User::where('role', 'petugas')
            ->when($request->search, function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                    ->orWhere('email', 'like', "%{$request->search}%");
            })
            ->latest()
            ->get();

        return view('kepala.petugas', compact('petugas'));
    }

    // ================= TAMBAH PETUGAS =================
    public function storePetugas(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'no_telepon' => 'required',
            'alamat' => 'required',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'petugas',
            'no_telepon' => $request->no_telepon,
            'alamat' => $request->alamat,
        ]);

        return back()->with('success', 'Petugas berhasil ditambahkan');
    }

    // ================= DELETE PETUGAS =================
    public function deletePetugas($id)
    {
        $petugas = User::where('role', 'petugas')->findOrFail($id);
        $petugas->delete();

        return back()->with('success', 'Petugas berhasil dihapus');
    }

    // ================= UPDATE PETUGAS =================
    public function updatePetugas(Request $request, $id)
    {
        $petugas = User::where('role', 'petugas')->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $id,
            'no_telepon' => 'required',
            'alamat' => 'required',
            'password' => 'nullable|min:6',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'no_telepon' => $request->no_telepon,
            'alamat' => $request->alamat,
        ];
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }
        $petugas->update($data);
        return back()->with('success', 'Petugas berhasil diupdate');
    }
    public function laporanPeminjaman()
{
    $data = Peminjaman::with(['user','buku'])->latest()->paginate(10);
    return view('kepala.laporan.peminjaman', compact('data'));
}

public function laporanDenda()
{
    $data = Peminjaman::with(['user','buku'])
        ->where('denda','>',0)
        ->latest()
        ->paginate(10);

    return view('kepala.laporan.denda', compact('data'));
}

public function laporanAnggota()
{
    $data = User::where('role','user')->latest()->paginate(10);
    return view('kepala.laporan.anggota', compact('data'));
}
public function cetakPeminjaman()
{
    $data = Peminjaman::with(['user','buku'])->latest()->get();

    $pdf = Pdf::loadView('kepala.laporan.cetak_peminjaman', compact('data'))
        ->setPaper('A4', 'portrait');

    return $pdf->stream('laporan_peminjaman.pdf');
}
}
