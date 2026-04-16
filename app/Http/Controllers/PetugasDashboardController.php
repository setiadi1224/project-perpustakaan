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
    //dashboard
    public function home()
    {
        $totalAnggota = User::where('role', 'user')->count();
        $totalBuku = Buku::count();
        $peminjamanAktif = Peminjaman::where('status', 'dipinjam')->count();
        $totalDenda = Peminjaman::where('status_pembayaran', '!=', 'lunas')->sum('denda');
        $menunggu = Peminjaman::where('status', 'menunggu')->count();
        $recentPeminjaman = Peminjaman::with(['user', 'buku'])
            ->latest()
            ->take(5)
            ->get();

        $chartData = [];
        $labels = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $labels[] = $date->format('d M');
            $chartData[] = Peminjaman::whereDate('created_at', $date)->count();
        }

        $terlambat = Peminjaman::where('status', 'dipinjam')->whereDate('tanggal_kembali', '<', now())->count();

        return view('petugas.home', compact('totalAnggota', 'totalBuku', 'peminjamanAktif', 'totalDenda', 'menunggu', 'recentPeminjaman', 'chartData', 'labels', 'terlambat'));
    }

    //anggota
    public function anggota(Request $request)
    {
        $anggotas = User::where('role', 'user')
            ->when($request->search, function ($q) use ($request) {
                $q->where(function ($qq) use ($request) {
                    $qq->where('name', 'like', "%{$request->search}%")->orWhere('email', 'like', "%{$request->search}%");
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
            'password' => 'nullable|min:6',
        ]);

        $user = User::findOrFail($id);

        $data = $request->only(['name', 'email', 'no_telepon', 'alamat']);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return back()->with('success', 'Anggota berhasil diupdate');
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return back()->with('success', 'Anggota berhasil dihapus');
    }

    //buku
    public function buku(Request $request)
    {
        $bukus = Buku::with('kategori')
            ->when($request->search, function ($q) use ($request) {
                $q->where(function ($qq) use ($request) {
                    $qq->where('judul', 'like', "%{$request->search}%")->orWhere('penulis', 'like', "%{$request->search}%");
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
            'stok' => 'required|integer|min:0',
            'kategori_id' => 'required|exists:kategoris,id',
            'tahun_terbit' => 'required|integer|min:1000|max:' . date('Y'),
            'cover' => 'nullable|image|mimes:jpg,jpeg,png|max:20000',
        ]);

        $data = $request->all();
        $data['stok'] = max(0, (int) $request->stok);

        if ($request->hasFile('cover')) {
            $data['cover'] = $request->file('cover')->store('buku', 'public');
        }

        Buku::create($data);

        return back()->with('success', 'Buku berhasil ditambahkan');
    }

    public function updateBuku(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required',
            'penulis' => 'required',
            'stok' => 'required|integer|min:0',
            'kategori_id' => 'required|exists:kategoris,id',
            'tahun_terbit' => 'required|integer|min:1000|max:' . date('Y'),
        ]);

        $buku = Buku::findOrFail($id);

        $data = $request->except('cover');
        $data['stok'] = max(0, (int) $request->stok);

        if ($request->hasFile('cover')) {
            if ($buku->cover) {
                Storage::disk('public')->delete($buku->cover);
            }
            $data['cover'] = $request->file('cover')->store('buku', 'public');
        }

        $buku->update($data);

        return back()->with('success', 'Buku berhasil diupdate');
    }
    public function deleteBuku($id)
    {
        $buku = Buku::findOrFail($id);
        Peminjaman::where('buku_id', $id)
            ->where('status', 'menunggu')
            ->update([
                'status' => 'ditolak',
            ]);

        $masihDipinjam = Peminjaman::where('buku_id', $id)->where('status', 'dipinjam')->exists();

        if ($masihDipinjam) {
            return back()->with('error', 'Buku masih dipinjam');
        }

        if ($buku->cover) {
            Storage::disk('public')->delete($buku->cover);
        }

        $buku->delete();

        return back()->with('success', 'Buku berhasil dihapus');
    }
    public function kategori()
    {
        $data = Kategori::withCount('bukus')->latest()->paginate(5);
        return view('petugas.kategori', compact('data'));
    }

    public function storeKategori(Request $request)
    {
        $request->validate([
            'nama' => 'required|unique:kategoris,nama',
        ]);

        Kategori::create([
            'nama' => $request->nama,
        ]);

        return back()->with('success', 'Kategori ditambahkan');
    }

    public function deleteKategori($id)
    {
        $kategori = Kategori::findOrFail($id);
        if ($kategori->bukus()->exists()) {
            return back()->with('error', 'Kategori tidak bisa dihapus karena masih digunakan oleh buku');
        }
        $kategori->delete();

        return back()->with('success', 'Kategori berhasil dihapus');
    }
    //peminjaman
    public function peminjaman(Request $request)
    {
        $data = Peminjaman::with(['user', 'buku'])

            ->when($request->search, function ($q) use ($request) {
                $q->where(function ($query) use ($request) {
                    $query
                        ->whereHas('user', function ($qq) use ($request) {
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
    //approve peminjaman
    public function approve($id)
    {
        $p = Peminjaman::findOrFail($id);

        if (!$p) {
            return back()->with('error', 'Data tidak ditemukan');
        }

        if ($p->status != 'menunggu') {
            return back()->with('error', 'Status bukan menunggu');
        }

        $p->status = 'dipinjam';
        $p->tanggal_kembali = now()->addDays(7);
        $p->save();

        return back()->with('success', 'Berhasil di-approve');
    }

    public function tolakPeminjaman($id)
    {
        $p = Peminjaman::with('buku')->findOrFail($id);

        if ($p->status != 'menunggu') {
            return back()->with('error', 'Tidak bisa ditolak');
        }
        $p->buku->increment('stok', $p->jumlah);

        $p->update([
            'status' => 'ditolak',
        ]);

        return back()->with('success', 'Peminjaman berhasil ditolak');
    }
    //pengembalian
    public function pengembalian(Request $request)
    {
        $data = Peminjaman::with(['user', 'buku'])
            ->where('status_pengembalian', 'menunggu')
            ->when($request->search, function ($q) use ($request) {
                $q->where(function ($query) use ($request) {
                    $query
                        ->whereHas('user', function ($qq) use ($request) {
                            $qq->where('name', 'like', '%' . $request->search . '%');
                        })
                        ->orWhereHas('buku', function ($qq) use ($request) {
                            $qq->where('judul', 'like', '%' . $request->search . '%');
                        });
                });
            })
            ->latest()
            ->paginate(5);

        return view('petugas.pengembalian', compact('data'));
    }
    //approve pengembalian
    public function approvePengembalian($id)
    {
        $p = Peminjaman::with('buku')->findOrFail($id);

        if ($p->status_pengembalian != 'menunggu') {
            return back()->with('error', 'Status tidak valid');
        }

        $today = now();
        $batas = Carbon::parse($p->tanggal_kembali);

        // hitung hari (dibulatkan)
        $terlambat = $today->gt($batas)
            ? (int) floor($batas->diffInHours($today) / 24)
            : 0;

        // hitung denda (integer)
        $denda = $terlambat * 5000 * $p->jumlah;

        $p->update([
            'status' => 'dikembalikan',
            'status_pengembalian' => 'disetujui',
            'tanggal_dikembalikan' => $today,
            'terlambat' => $terlambat,
            'denda' => $denda,
            'status_pembayaran' => $denda > 0 ? 'belum' : 'lunas',
        ]);

        $p->buku->increment('stok', $p->jumlah);

        return back()->with('success', 'Pengembalian disetujui');
    }
    //return buku
    public function returnBuku($id)
    {
        $p = Peminjaman::with('buku')->findOrFail($id);

        $today = now();
        $batas = Carbon::parse($p->tanggal_kembali);

        $terlambat = $today->gt($batas)
            ? (int) floor($batas->diffInHours($today) / 24)
            : 0;

        if ($terlambat > 0) {
            $dendaPerHari = 5000;
            $denda = $terlambat * $dendaPerHari * $p->jumlah;
        }

        $p->update([
            'status' => 'dikembalikan',
            'tanggal_dikembalikan' => $today,
            'denda' => $denda,
            'status_pembayaran' => $denda > 0 ? 'belum' : 'lunas',
        ]);

        $p->buku->increment('stok', $p->jumlah);

        return back()->with('success', 'Buku dikembalikan');
    }

    //denda
    public function denda(Request $request)
    {
        $data = Peminjaman::with(['user', 'buku'])
            ->latest()
            ->paginate(5);

        $totalDenda = 0;

        foreach ($data as $item) {
            $terlambat = 0;
            $denda = $item->denda;

            if ($item->status == 'dipinjam') {
                $batas = Carbon::parse($item->tanggal_kembali);

                if (now()->gt($batas)) {
                    $terlambat = (int) floor($batas->diffInHours(now()) / 24);
                    $denda = $terlambat * 5000 * $item->jumlah;
                }
            }

            if ($item->status_pembayaran == 'lunas') {
                $denda = 0;
            }

            $item->terlambat = $terlambat;
            $item->total_denda = $denda;

            $totalDenda += $denda;
        }

        return view('petugas.denda', compact('data', 'totalDenda'));
    }

    //konfirmasi pembayaran
    public function konfirmasi($id)
    {
        $p = \App\Models\Peminjaman::findOrFail($id);

        $p->update([
            'status_pembayaran' => 'lunas',
        ]);

        return back()->with('success', 'Pembayaran berhasil dikonfirmasi');
    }

    public function tolak($id)
    {
        $p = \App\Models\Peminjaman::findOrFail($id);

        $p->update([
            'status_pembayaran' => 'belum',
            'bukti_pembayaran' => null,
            'metode_pembayaran' => null,
        ]);

        return back()->with('error', 'Pembayaran ditolak');
    }
}
