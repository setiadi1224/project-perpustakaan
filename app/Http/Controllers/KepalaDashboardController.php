<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\User;
use Barryvdh\DomPDF\Facade\pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class KepalaDashboardController extends Controller
{
    //dashboard
    public function home()
    {
        $totalBuku = Buku::count();
        $totalAnggota = User::where('role', 'user')->count();
        $totalPeminjaman = Peminjaman::count();
        $totalDenda = Peminjaman::sum('denda');

        $petugas = User::where('role', 'petugas')->latest()->take(5)->get();
        $bukuPopuler = Buku::latest()->take(5)->get();

        return view('kepala.home', compact('totalBuku', 'totalAnggota', 'totalPeminjaman', 'totalDenda', 'petugas', 'bukuPopuler'));
    }

    //kelola petugas
    public function petugas(Request $request)
    {
        $petugas = User::where('role', 'petugas')
            ->when($request->search, function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")->orWhere('email', 'like', "%{$request->search}%");
            })
            ->latest()
            ->paginate(10);

        return view('kepala.petugas', compact('petugas'));
    }

    //tambah petugas
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

    //update petugass
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

    //delete petugas
    public function deletePetugas($id)
    {
        $petugas = User::where('role', 'petugas')->findOrFail($id);
        $petugas->delete();

        return back()->with('success', 'Petugas berhasil dihapus');
    }

    //laporan peminjaman
    public function laporanPeminjaman(Request $request)
    {
        $bulan = $request->bulan ?? date('m');
        $tahun = $request->tahun ?? date('Y');

        $data = Peminjaman::with(['user', 'buku'])

            // filter bulan & tahun
            ->whereMonth('tanggal_pinjam', $bulan)
            ->whereYear('tanggal_pinjam', $tahun)

            // filter nama
            ->when($request->nama, function ($q) use ($request) {
                $q->whereHas('user', function ($qq) use ($request) {
                    $qq->where('name', 'like', '%' . $request->nama . '%');
                });
            })

            // filter buku
            ->when($request->buku, function ($q) use ($request) {
                $q->whereHas('buku', function ($qq) use ($request) {
                    $qq->where('judul', 'like', '%' . $request->buku . '%');
                });
            })

            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view('kepala.laporan.peminjaman', compact('data', 'bulan', 'tahun'));
    }
    //cetak laporan peminjaman
    public function cetaklaporanPeminjaman(Request $request)
    {
        $bulan = $request->bulan ?? date('m');
        $tahun = $request->tahun ?? date('Y');
        $nama = $request->nama;
        $buku = $request->buku;

        $query = Peminjaman::with(['user', 'buku'])
            ->whereMonth('tanggal_pinjam', $bulan)
            ->whereYear('tanggal_pinjam', $tahun);

        if ($nama) {
            $query->whereHas('user', function ($q) use ($nama) {
                $q->where('name', 'like', '%' . $nama . '%');
            });
        }

        if ($buku) {
            $query->whereHas('buku', function ($q) use ($buku) {
                $q->where('judul', 'like', '%' . $buku . '%');
            });
        }

        $data = $query->get();

        $pdf = Pdf::loadView('kepala.laporan.cetak_peminjaman', compact('data', 'bulan', 'tahun', 'nama', 'buku'));

        return $pdf->stream('laporan-peminjaman-' . $bulan . '-' . $tahun . '.pdf');
    }
    //laporan denda
    public function laporanDenda(Request $request)
    {
        $nama = $request->nama;
        $buku = $request->buku;
        $status = $request->status;

        $data = Peminjaman::with(['user', 'buku'])
            ->where('denda', '>', 0)

            ->when($nama, function ($q) use ($nama) {
                $q->whereHas('user', function ($qq) use ($nama) {
                    $qq->where('name', 'like', '%' . $nama . '%');
                });
            })

            ->when($buku, function ($q) use ($buku) {
                $q->whereHas('buku', function ($qq) use ($buku) {
                    $qq->where('judul', 'like', '%' . $buku . '%');
                });
            })

            ->when($status, function ($q) use ($status) {
                $q->where('status_pembayaran', $status);
            })

            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view('kepala.laporan.denda', compact('data'));
    }

    //laporan anggota
    public function laporanAnggota()
    {
        $data = User::where('role', 'user')->latest()->paginate(10);
        return view('kepala.laporan.anggota', compact('data'));
    }

    public function cetakLaporanDenda(Request $request)
    {
        $nama = $request->nama;
        $buku = $request->buku;
        $status = $request->status;

        $query = Peminjaman::with(['user', 'buku'])
            ->where('denda', '>', 0);

        if ($nama) {
            $query->whereHas('user', function ($q) use ($nama) {
                $q->where('name', 'like', '%' . $nama . '%');
            });
        }

        if ($buku) {
            $query->whereHas('buku', function ($q) use ($buku) {
                $q->where('judul', 'like', '%' . $buku . '%');
            });
        }

        if ($status) {
            $query->where('status_pembayaran', $status);
        }

        $data = $query->get();

        $pdf = Pdf::loadView('kepala.laporan.cetak_denda', compact('data', 'nama', 'buku', 'status'));

        return $pdf->stream('laporan-denda.pdf');
    }
}
