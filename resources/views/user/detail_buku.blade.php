@extends('user.layouts.app')

@section('title', 'Detail Buku')

@section('content')

<style>
/* CONTAINER */
.detail-wrapper {
    background: #fff;
    padding: 25px;
    border-radius: 16px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.05);
}

/* FLEX */
.detail-container {
    display: flex;
    gap: 30px;
    flex-wrap: wrap;
}

/* COVER */
.cover {
    width: 220px;
    height: 300px;
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}

.cover img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* INFO */
.detail-info {
    flex: 1;
    min-width: 250px;
}

.detail-info h2 {
    font-size: 24px;
    margin-bottom: 10px;
}

.label {
    color: #6b7280;
    font-size: 14px;
    margin-bottom: 5px;
}

/* BADGE */
.badge {
    display: inline-block;
    padding: 6px 14px;
    border-radius: 999px;
    font-size: 12px;
    margin: 10px 0;
    font-weight: 600;
}

.badge.green { background: #22c55e; color: #fff; }
.badge.red { background: #ef4444; color: #fff; }
.badge.gray { background: #e5e7eb; color: #374151; }

/* BUTTON */
.btn {
    border: none;
    padding: 10px 16px;
    border-radius: 10px;
    cursor: pointer;
    margin-top: 10px;
    font-weight: 600;
    transition: 0.2s;
}

.btn-pinjam {
    background: #2563eb;
    color: white;
}
.btn-pinjam:hover {
    background: #1d4ed8;
}

.btn-return {
    background: #f59e0b;
    color: white;
}
.btn-return:hover {
    background: #d97706;
}

/* INPUT */
.input-jumlah {
    width: 70px;
    padding: 7px;
    border-radius: 8px;
    border: 1px solid #ddd;
    margin-right: 8px;
}

/* DESKRIPSI */
.deskripsi {
    margin-top: 15px;
    line-height: 1.7;
    color: #374151;
    background: #f9fafb;
    padding: 15px;
    border-radius: 10px;
    font-size: 14px;
}

/* REKOMENDASI */
.rekomendasi {
    margin-top: 30px;
}

.rekomendasi h4 {
    margin-bottom: 15px;
}

.rekomendasi-list {
    display: flex;
    gap: 15px;
    overflow-x: auto;
    padding-bottom: 10px;
}

/* CARD */
.rekomendasi-item {
    min-width: 150px;
    background: #fff;
    padding: 10px;
    border-radius: 12px;
    text-align: center;
    text-decoration: none;
    color: #111;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    transition: 0.2s;
}

.rekomendasi-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.12);
}

.rekomendasi-item img {
    width: 100%;
    height: 100px;
    object-fit: cover;
    border-radius: 8px;
    margin-bottom: 8px;
}

.rekomendasi-item p {
    font-size: 13px;
    font-weight: 500;
}

/* RESPONSIVE */
@media (max-width: 768px) {
    .detail-container {
        flex-direction: column;
        align-items: center;
    }

    .cover {
        width: 180px;
        height: 260px;
    }

    .detail-info {
        text-align: center;
    }
}
</style>

<div class="detail-wrapper">

    <div class="detail-container">
        {{-- COVER --}}
        <div class="cover">
            <img src="{{ $buku->cover ? asset('storage/' . $buku->cover) : 'https://via.placeholder.com/200' }}">
        </div>

        {{-- INFO --}}
        <div class="detail-info">
            <h2>{{ $buku->judul }}</h2>

            <p class="label">Pengarang: {{ $buku->penulis }}</p>
            <p class="label">Tahun: {{ $buku->tahun_terbit ?? '-' }}</p>
            <p class="label">Kategori: {{ $buku->kategori->nama ?? '-' }}</p>

            <div class="badge {{ $buku->stok > 0 ? 'green' : 'red' }}">
                {{ $buku->stok > 0 ? 'Tersedia' : 'Tidak Tersedia' }}
            </div>

            {{-- PINJAMAN --}}
            @php
                $pinjaman = \App\Models\Peminjaman::where('user_id', auth()->id())
                    ->where('buku_id', $buku->id)
                    ->whereIn('status', ['menunggu', 'dipinjam'])
                    ->first();
            @endphp

            {{-- PINJAM --}}
            @if (!$pinjaman && $buku->stok > 0)
                <form action="{{ route('user.pinjam', $buku->id) }}" method="POST">
                    @csrf

                    <input type="number" name="jumlah"
                        class="input-jumlah"
                        min="1"
                        max="{{ min(5, $buku->stok) }}"
                        value="1">

                    <button class="btn btn-pinjam">Pinjam Buku</button>
                </form>
            @endif

            {{-- MENUNGGU --}}
            @if ($pinjaman && $pinjaman->status == 'menunggu')
                <div class="badge gray">Menunggu Persetujuan</div>
            @endif

            {{-- RETURN --}}
            @if ($pinjaman && $pinjaman->status == 'dipinjam')
                <form action="{{ route('user.return', $pinjaman->id) }}" method="POST">
                    @csrf
                    <button class="btn btn-return">Return Buku</button>
                </form>
            @endif

            {{-- DESKRIPSI --}}
            <div class="deskripsi">
                {{ $buku->deskripsi ?? 'Tidak ada deskripsi' }}
            </div>
        </div>
    </div>

    {{-- REKOMENDASI --}}
    <div class="rekomendasi">
        <h4>Rekomendasi Buku Lainnya</h4>

        <div class="rekomendasi-list">
            @foreach ($rekomendasi as $item)
                <a href="{{ route('user.buku.detail', $item->id) }}" class="rekomendasi-item">
                    <img src="{{ $item->cover ? asset('storage/' . $item->cover) : 'https://via.placeholder.com/150' }}">
                    <p>{{ $item->judul }}</p>
                </a>
            @endforeach
        </div>
    </div>

</div>

@endsection