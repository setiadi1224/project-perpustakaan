@extends('user.layouts.app')

@section('title', 'Home — Perpustakaan Digital')

@push('styles')
<style>
.stat-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 12px;
    margin-bottom: 20px;
}

.stat-card {
    background: #fff;
    border-radius: 14px;
    padding: 16px 18px;
    border: 1px solid #EAECF0;
}

.stat-num {
    font-size: 26px;
    font-weight: 700;
    color: #2563EB;
}

.stat-num.red { color: #EF4444; }
.stat-num.green { color: #10B981; }

.stat-lbl {
    font-size: 12px;
    color: #6B7280;
}

.sec-title {
    font-size: 14px;
    font-weight: 700;
    margin-bottom: 10px;
}

.pills {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 20px;
}

.pill {
    background: #fff;
    border: 1px solid #E5E7EB;
    border-radius: 20px;
    padding: 6px 16px;
    font-size: 13px;
    text-decoration: none;
    color: #374151;
}

.pill.active,
.pill:hover {
    background: #2563EB;
    color: white;
}

.buku-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 14px;
}

.buku-card {
    background: #fff;
    border-radius: 14px;
    overflow: hidden;
    border: 1px solid #EAECF0;
    text-decoration: none;
    transition: 0.2s;
}

.buku-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.08);
}

.buku-cover {
    height: 120px;
    background: #1E3A5F;
}

.buku-cover img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.buku-info {
    padding: 10px;
}

.buku-judul {
    font-size: 13px;
    font-weight: 600;
}

.buku-penulis {
    font-size: 11px;
    color: #9CA3AF;
}

/* 🔥 PAGINATION */
.pagination-wrapper {
    display: flex;
    justify-content: center;
    margin-top: 25px;
}

.pagination {
    display: flex;
    gap: 8px;
}

.pagination li {
    list-style: none;
}

.pagination a,
.pagination span {
    min-width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
    border: 1px solid #e5e7eb;
    background: #fff;
    color: #374151;
    text-decoration: none;
    font-size: 13px;
    transition: 0.2s;
}

.pagination a:hover {
    background: #2563EB;
    color: white;
}

.pagination .active span {
    background: #2563EB;
    color: white;
}

.pagination .disabled span {
    opacity: 0.5;
}
</style>
@endpush

@section('content')

{{-- STAT --}}
<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-num">{{ $bukuDipinjam }}</div>
        <div class="stat-lbl">Buku Dipinjam</div>
    </div>
    <div class="stat-card">
        <div class="stat-num green">{{ $totalPeminjaman }}</div>
        <div class="stat-lbl">Total Peminjaman</div>
    </div>
    <div class="stat-card">
        <div class="stat-num red">
            {{ $dendaAktif > 0 ? 'Rp '.number_format($dendaAktif,0,',','.') : 0 }}
        </div>
        <div class="stat-lbl">Denda Aktif</div>
    </div>
</div>

{{-- KATEGORI --}}
<div class="sec-title">Kategori</div>
<div class="pills">
    <a href="{{ route('user.home') }}"
       class="pill {{ !request('kategori') ? 'active' : '' }}">
        Semua
    </a>

    @foreach ($kategoris as $kat)
        <a href="{{ route('user.home', ['kategori' => $kat->id]) }}"
           class="pill {{ request('kategori') == $kat->id ? 'active' : '' }}">
            {{ $kat->nama }}
        </a>
    @endforeach
</div>

{{-- BUKU --}}
<div class="sec-title">Buku Populer</div>

<div class="buku-grid">
    @forelse ($bukuPopuler as $buku)
        <a href="{{ route('user.buku.detail', $buku->id) }}" class="buku-card">
            <div class="buku-cover">
                @if ($buku->cover)
                    <img src="{{ asset('storage/' . $buku->cover) }}">
                @else
                    <div style="color:white;text-align:center;padding-top:40px;font-size:12px;">
                        No Cover
                    </div>
                @endif
            </div>

            <div class="buku-info">
                <div class="buku-judul">{{ $buku->judul }}</div>
                <div class="buku-penulis">{{ $buku->penulis }}</div>
            </div>
        </a>
    @empty
        <div style="grid-column:1/-1;text-align:center;color:#9CA3AF;">
            Tidak ada buku
        </div>
    @endforelse
</div>

{{-- 🔥 PAGINATION --}}
<div class="pagination-wrapper">
    {{ $bukuPopuler->links() }}
</div>

@endsection