<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Peminjaman;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserDashboardController extends Controller
{
    // dashboard
    public function home(Request $request)
    {
        $user = Auth::user();

        $bukuDipinjam = Peminjaman::where('user_id', $user->id)
            ->where('status', 'dipinjam')
            ->sum('jumlah');

        $totalPeminjaman = Peminjaman::where('user_id', $user->id)->count();

        $dendaAktif = Peminjaman::where('user_id', $user->id)
            ->where('status_pembayaran', '!=', 'lunas')
            ->sum('denda');

        $kategoris = Kategori::all();

        $trending = Buku::with('kategori')
            ->withCount('peminjamans')
            ->when($request->kategori, fn($q) => $q->where('kategori_id', $request->kategori))
            ->orderBy('peminjamans_count', 'desc')
            ->take(10)
            ->get();

        $baru = Buku::with('kategori')
            ->when($request->kategori, fn($q) => $q->where('kategori_id', $request->kategori))
            ->latest()
            ->take(10)
            ->get();

        return view('user.home', compact(
            'bukuDipinjam',
            'totalPeminjaman',
            'dendaAktif',
            'kategoris',
            'trending',
            'baru'
        ));
    }

    //  HALAMAN PENGEMBALIAN USER
    public function pengembalian()
    {
        $data = Peminjaman::with('buku')
            ->where('user_id', Auth::id())
            ->where('status', 'dipinjam')
            ->latest()
            ->paginate(5);

        return view('user.pengembalian', compact('data'));
    }

    //  AJUKAN PENGEMBALIAN

    public function returnBuku($id)
    {
        $p = Peminjaman::findOrFail($id);

        if ($p->user_id != Auth::id()) {
            return back()->with('error', 'Akses ditolak');
        }

        if ($p->status != 'dipinjam') {
            return back()->with('error', 'Tidak valid');
        }

        if ($p->status_pengembalian && $p->status_pengembalian === 'menunggu') {
            return back()->with('error', 'Sudah diajukan sebelumnya');
        }

        $p->update([
            'status_pengembalian' => 'menunggu'
        ]);

        return back()->with('success', 'Menunggu konfirmasi petugas');
    }

    // =========================
    // library
    // =========================
    public function library(Request $request)
    {
        $books = Buku::with('kategori')
            ->when($request->search, function ($q) use ($request) {
                $q->where(function ($qq) use ($request) {
                    $qq->where('judul', 'like', "%{$request->search}%")
                        ->orWhere('penulis', 'like', "%{$request->search}%");
                });
            })
            ->when($request->kategori, fn($q) => $q->where('kategori_id', $request->kategori))
            ->paginate(20)
            ->withQueryString();

        $kategoris = Kategori::all();

        return view('user.library', compact('books', 'kategoris'));
    }
    public function pinjam(Request $request, $id)
    {

        $punyaDenda = Peminjaman::where('user_id', Auth::id())
            ->whereIn('status_pembayaran', ['belum', 'menunggu'])
            ->where('denda', '>', 0)
            ->exists();

        if ($punyaDenda) {
            return back()->with('error', 'Anda masih memiliki denda, harap lunasi terlebih dahulu');
        }
        $request->validate([
            'jumlah' => 'required|integer|min:1|max:5',
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
            return back()->with('error', 'Sudah meminjam buku ini');
        }

        $totalDipinjam = Peminjaman::where('user_id', $userId)
            ->whereIn('status', ['dipinjam', 'menunggu'])
            ->sum('jumlah');

        if ($totalDipinjam + $jumlah > 5) {
            return back()->with('error', 'Maksimal 5 buku Yang Di pinjam');
        }

        $buku->decrement('stok', $jumlah);

        Peminjaman::create([
            'user_id' => $userId,
            'buku_id' => $id,
            'jumlah' => $jumlah,
            'status' => 'menunggu',
            'status_pengembalian' => 'belum',
            'tanggal_pinjam' => now(),
        ]);

        return back()->with('success', 'Menunggu persetujuan');
    }
    public function detailBuku($id)
    {
        $buku = Buku::with('kategori')->findOrFail($id);

        $rekomendasi = Buku::where('kategori_id', $buku->kategori_id)
            ->where('id', '!=', $id)
            ->latest()
            ->take(6)
            ->get();

        if ($rekomendasi->count() < 6) {
            $tambahan = Buku::where('id', '!=', $id)
                ->whereNotIn('id', $rekomendasi->pluck('id'))
                ->inRandomOrder()
                ->take(6 - $rekomendasi->count())
                ->get();

            $rekomendasi = $rekomendasi->merge($tambahan);
        }

        $pinjaman = Peminjaman::where('user_id', Auth::id())
            ->where('buku_id', $id)
            ->whereIn('status', ['menunggu', 'dipinjam'])
            ->first();

        return view('user.detail_buku', compact('buku', 'rekomendasi', 'pinjaman'));
    }
    // riwayat
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

    // denda
    public function denda()
    {
        $denda = Peminjaman::with('buku')
            ->where('user_id', Auth::id())
            ->where('denda', '>', 0)
            ->latest()
            ->paginate(5);

        $totalDenda = 0;

        foreach ($denda as $item) {

            $terlambat = $item->terlambat;
            $dendaFix = $item->denda;

            if ($item->status == 'dipinjam') {
                $batas = Carbon::parse($item->tanggal_kembali);

                if (now()->gt($batas)) {
                    $terlambat = $batas->diffInDays(now());
                    $dendaFix = $terlambat * 5000 * $item->jumlah;
                }
            }
            if ($item->status == 'dikembalikan') {
                $batas = Carbon::parse($item->tanggal_kembali);

                if ($item->updated_at > $batas) {
                   $terlambat = floor($batas->diffInDays(now()));
                } else {
                    $terlambat = 0;
                }
            }
            $item->terlambat = $terlambat;
            $item->total_denda = $dendaFix;
        }
        return view('user.denda', compact('denda', 'totalDenda'));
    }

    // bayar denda
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
            'metode' => 'required|in:offline,online',
        ]);

        if ($request->metode == 'online') {
            $request->validate([
                'bukti' => 'required|image|max:2048',
            ]);

            $file = $request->file('bukti')->store('bukti', 'public');

            $p->update([
                'bukti_pembayaran' => $file,
                'status_pembayaran' => 'menunggu',
                'metode_pembayaran' => 'online',
            ]);

            return back()->with('success', 'Bukti dikirim');
        }

        if ($request->metode == 'offline') {
            $p->update([
                'status_pembayaran' => 'menunggu',
                'metode_pembayaran' => 'offline',
            ]);

            return back()->with('success', 'Silakan bayar ke petugas');
        }
    }
    public function struk($id)
    {
        $p = Peminjaman::with('buku', 'user')->findOrFail($id);

        if ($p->user_id != Auth::id()) {
            abort(403);
        }

        $noTransaksi = 'TRX-' . $p->id . '-' . date('Ymd');

        return view('user.struk', compact('p', 'noTransaksi'));
    }

    public function strukPdf($id)
    {
        $p = Peminjaman::with('buku', 'user')->findOrFail($id);

        if ($p->user_id != Auth::id()) {
            abort(403);
        }

        $noTransaksi = 'TRX-' . $p->id . '-' . date('Ymd');

        $pdf = Pdf::loadView('user.struk', compact('p', 'noTransaksi'));

        return $pdf->download('struk.pdf');
    }
    // profile
    public function profile()
    {
        return view('user.profile', [
            'user' => Auth::user(),
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
            'foto' => 'nullable|image|max:20000',
        ]);

        $user->update($request->only('name', 'email', 'no_telepon', 'alamat'));

        if ($request->hasFile('foto')) {
            if ($user->foto && Storage::exists('public/foto/' . $user->foto)) {
                Storage::delete('public/foto/' . $user->foto);
            }

            $fotoName = time() . '_' . $request->file('foto')->getClientOriginalName();
            $request->file('foto')->storeAs('public/foto', $fotoName);

            $user->foto = $fotoName;
            $user->save();
        }

        return back()->with('success', 'Profile updated!');
    }
}
