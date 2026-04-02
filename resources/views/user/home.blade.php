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
    margin-bottom: 3px;
    line-height: 1;
}

.stat-num.red   { color: #EF4444; }
.stat-num.green { color: #10B981; }
.stat-lbl { font-size: 12.5px; color: #6B7280; }

.sec-title {
    font-size: 14.5px;
    font-weight: 700;
    color: #111827;
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
    font-weight: 500;
    color: #374151;
    cursor: pointer;
    text-decoration: none;
    transition: all 0.15s;
    display: inline-block;
}

.pill:hover, .pill.active {
    background: #2563EB;
    border-color: #2563EB;
    color: #fff;
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
    cursor: pointer;
    text-decoration: none;
    display: block;
    transition: transform 0.18s, box-shadow 0.18s, border-color 0.18s;
}

.buku-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.09);
    border-color: #2563EB;
}

.buku-cover {
    height: 120px;
    background: #1E3A5F;
    display: flex;
    align-items: center;
    justify-content: center;
}

.buku-cover.bg2 { background: #1E3350; }
.buku-cover.bg3 { background: #1A3248; }
.buku-cover.bg4 { background: #172D40; }

.buku-cover img { width: 100%; height: 100%; object-fit: cover; }

.cover-placeholder {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 6px;
}

.cover-placeholder span {
    font-size: 10px;
    color: rgba(255,255,255,0.35);
    font-weight: 500;
}

.buku-info { padding: 10px 12px 12px; }

.buku-judul {
    font-size: 12.5px;
    font-weight: 600;
    color: #111827;
    margin-bottom: 2px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.buku-penulis { font-size: 11.5px; color: #9CA3AF; }

.empty-state {
    grid-column: 1 / -1;
    text-align: center;
    padding: 40px;
    color: #9CA3AF;
    font-size: 14px;
}

@media (max-width: 900px) {
    .buku-grid { grid-template-columns: repeat(2, 1fr); }
    .stat-grid  { grid-template-columns: 1fr 1fr; }
}
</style>
@endpush
@section('content')

    @php
        $bukuDipinjam    = $bukuDipinjam    ?? 2;
        $totalPeminjaman = $totalPeminjaman ?? 10;
        $dendaAktif      = $dendaAktif      ?? 0;
        $kategoris = $kategoris ?? collect([
            (object)['id'=>1,'nama'=>'Teknologi','icon'=>''],
            (object)['id'=>2,'nama'=>'Bisnis',   'icon'=>''],
            (object)['id'=>3,'nama'=>'Desain',   'icon'=>''],
            (object)['id'=>4,'nama'=>'Sains',    'icon'=>''],
        ]);
        $bukuPopuler = $bukuPopuler ?? collect([
            (object)['judul'=>'Belajar Laravel',  'penulis'=>'John Doe',    'kategori'=>(object)['nama'=>'Teknologi']],
            (object)['judul'=>'Mastering MySQL',  'penulis'=>'Jane Smith',  'kategori'=>(object)['nama'=>'Teknologi']],
            (object)['judul'=>'UI UX Expert',     'penulis'=>'Michael Lee', 'kategori'=>(object)['nama'=>'Desain']],
            (object)['judul'=>'Pemrograman Web',  'penulis'=>'David Kim',   'kategori'=>(object)['nama'=>'Teknologi']],
        ]);
        $search = $search ?? null;
        $coverColors = ['#2C5F8A','#3A6B8A','#1E4D6B','#2A5578'];
    @endphp

    {{-- Stat Cards --}}
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
            <div class="stat-num red">{{ $dendaAktif > 0 ? 'Rp '.number_format($dendaAktif,0,',','.') : 0 }}</div>
            <div class="stat-lbl">Denda Aktif</div>
        </div>
    </div>

    {{-- Kategori --}}
    <div class="sec-title">Kategori</div>
    <div class="pills">
        <a href="{{ route('user.home') }}"
           class="pill {{ !request('kategori') ? 'active' : '' }}">Semua</a>
        @foreach($kategoris as $kat)
            <a href="{{ route('user.home', ['kategori' => $kat->id]) }}"
               class="pill {{ request('kategori') == $kat->id ? 'active' : '' }}">
                {{ $kat->nama }}
            </a>
        @endforeach
    </div>

    {{-- Buku Populer --}}
    <div class="sec-title">Buku Populer</div>
    <div class="buku-grid">
    @foreach($bukuPopuler as $buku)
        <a href="#" class="buku-card">
            
            {{-- COVER --}}
            <div class="buku-cover">
                @if($buku->cover)
                    <img src="{{ asset('storage/'.$buku->cover) }}" alt="cover">
                @else
                    <div class="cover-placeholder">
                        <span>Tidak ada cover</span>
                    </div>
                @endif
            </div>

            {{-- INFO --}}
            <div class="buku-info">
                <div class="buku-judul">{{ $buku->judul }}</div>
                <div class="buku-penulis">{{ $buku->penulis }}</div>
            </div>

        </a>
    @endforeach
</div>

@endsection