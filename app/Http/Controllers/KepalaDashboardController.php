<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\User;
use Barryvdh\DomPDF\PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class KepalaDashboardController extends Controller
{
    // ================= DASHBOARD =================
    public function home()
    {
        $totalBuku = Buku::count();
        $totalAnggota = User::where('role', 'user')->count();
        $totalPeminjaman = Peminjaman::count();
        $totalDenda = Peminjaman::sum('denda');

        $petugas = User::where('role', 'petugas')->latest()->take(5)->get();
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
            ->paginate(10); // <-- paginate

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

    // ================= DELETE PETUGAS =================
    public function deletePetugas($id)
    {
        $petugas = User::where('role', 'petugas')->findOrFail($id);
        $petugas->delete();

        return back()->with('success', 'Petugas berhasil dihapus');
    }

    // ================= LAPORAN PEMINJAMAN =================
    public function laporanPeminjaman(Request $request)
    {
        $bulan = $request->bulan ?? Carbon::now()->month;
        $tahun = $request->tahun ?? Carbon::now()->year;

        $query = Peminjaman::with(['user', 'buku'])
            ->whereMonth('tanggal_pinjam', $bulan)
            ->whereYear('tanggal_pinjam', $tahun);

        $data = $query->paginate(10); // <-- paginate

        return view('kepala.laporan.peminjaman', compact('data', 'bulan', 'tahun'));
    }

    // ================= CETAK LAPORAN PEMINJAMAN =================
     public function cetaklaporanPeminjaman(Request $request)
    {
        $bulan = $request->bulan ?? date('m');
        $tahun = $request->tahun ?? date('Y');

        $data = Peminjaman::with(['user', 'buku'])
            ->whereMonth('tanggal_pinjam', $bulan)
            ->whereYear('tanggal_pinjam', $tahun)
            ->get();

        $pdf = PDF::loadView('kepala.laporan.cetak_peminjaman', compact('data', 'bulan', 'tahun'));
        return $pdf->stream('laporan-peminjaman-'.$bulan.'-'.$tahun.'.pdf');
    }
    // ================= LAPORAN DENDA =================
    public function laporanDenda()
    {
        $data = Peminjaman::with(['user','buku'])
            ->where('denda','>',0)
            ->latest()
            ->paginate(10);

        return view('kepala.laporan.denda', compact('data'));
    }

    // ================= LAPORAN ANGGOTA =================
    public function laporanAnggota()
    {
        $data = User::where('role','user')->latest()->paginate(10);
        return view('kepala.laporan.anggota', compact('data'));
    }
}