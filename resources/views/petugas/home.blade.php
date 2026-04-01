@extends('petugas.layouts.app')

@section('content')

<div class="header">
    <h1>Dashboard Overview</h1>
    <span>Selamat datang, {{ auth()->user()->name }}</span>
</div>

<div class="cards">
    <div class="card">
        <p>Total Anggota</p>
        <h2>{{ $totalAnggota }}</h2>
    </div>

    <div class="card">
        <p>Total Buku</p>
        <h2>{{ $totalBuku }}</h2>
    </div>

    <div class="card">
        <p>Peminjaman Aktif</p>
        <h2>{{ $peminjamanAktif }}</h2>
    </div>

    <div class="card">
        <p>Total Denda</p>
        <h2>Rp {{ number_format($totalDenda) }}</h2>
    </div>
</div>

@endsection