@extends('user.layouts.app')

@section('title', 'Detail Buku')

@section('content')

<style>
.detail-container {
    display: flex;
    gap: 30px;
    margin-bottom: 30px;
}

.cover {
    width: 220px;
    height: 300px;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}

.cover img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.detail-info {
    flex: 1;
}

.detail-info h2 {
    margin-bottom: 10px;
}

.label {
    color: #6b7280;
    font-size: 14px;
}

.badge {
    display: inline-block;
    background: #22c55e;
    color: white;
    padding: 5px 12px;
    border-radius: 999px;
    font-size: 12px;
    margin: 10px 0;
}

.btn-pinjam {
    background: #2563eb;
    color: white;
    border: none;
    padding: 10px 18px;
    border-radius: 8px;
    cursor: pointer;
    margin: 10px 0;
}

.btn-pinjam:hover {
    background: #1d4ed8;
}

.rekomendasi {
    margin-top: 20px;
}

.rekomendasi h4 {
    margin-bottom: 15px;
}

.rekomendasi-list {
    display: flex;
    gap: 15px;
    text-decoration: none;
}

.rekomendasi-item {
    width: 150px;
    background: #fff;
    padding: 10px;
    border-radius: 10px;
    text-align: center;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    text-decoration: none;
}

.rekomendasi-item img {
    width: 100%;
    height: 90px;
    object-fit: cover;
    border-radius: 8px;
}
</style>

<div class="detail-container">

    {{-- COVER --}}
    <div class="cover">
        <img src="{{ $buku->cover ? asset('storage/'.$buku->cover) : 'https://via.placeholder.com/200' }}">
    </div>

    {{-- INFO --}}
    <div class="detail-info">
        <h2>{{ $buku->judul }}</h2>

        <p class="label">Pengarang: {{ $buku->penulis }}</p>
        <p class="label">Tahun: {{ $buku->tahun_terbit ?? '-' }}</p>
        <p class="label">Kategori: {{ $buku->kategori->nama ?? '-' }}</p>

        <div class="badge">
            {{ $buku->stok > 0 ? 'Tersedia' : 'Tidak Tersedia' }}
        </div>

        <br>

        <button class="btn-pinjam">
            Pinjam Buku
        </button>

        <p style="margin-top:10px;">
            {{ $buku->deskripsi ?? 'Tidak ada deskripsi' }}
        </p>
    </div>

</div>

{{-- REKOMENDASI --}}
<div class="rekomendasi">
    <h4>Rekomendasi Buku Lainnya</h4>

    <div class="rekomendasi-list">
        @foreach($rekomendasi as $item)
            <a href="{{ route('user.buku.detail', $item->id) }}" class="rekomendasi-item">
                <img src="{{ $item->cover ? asset('storage/'.$item->cover) : 'https://via.placeholder.com/150' }}">
                <p>{{ $item->judul }}</p>
            </a>
        @endforeach
    </div>
</div>

@endsection