<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PetugasDashboardController extends Controller
{
    public function home()
    {
        $totalAnggota = User::where('role', 'user')->count();
        $totalBuku = Buku::count();
        $peminjamanAktif = Peminjaman::where('status', 'dipinjam')->count();
        $totalDenda = Peminjaman::sum('denda');

        return view('petugas.home', compact(
            'totalAnggota',
            'totalBuku',
            'peminjamanAktif',
            'totalDenda'
        ));
    }

    public function anggota(Request $request)
    {
        $search = $request->search;

        $anggotas = User::where('role', 'user')
            ->when($search, function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%");
            })
            ->latest()
            ->get();

        return view('petugas.anggota', compact('anggotas'));
    }

    public function store(Request $request)
    {
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user',
            'no_telepon' => $request->no_telepon,
            'alamat' => $request->alamat,
        ]);

        return back();
    }

    public function update(Request $request, $id)
    {
        User::findOrFail($id)->update([
            'name' => $request->name,
            'email' => $request->email,
            'no_telepon' => $request->no_telepon,
            'alamat' => $request->alamat,
        ]);

        return back();
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return back();
    }
}