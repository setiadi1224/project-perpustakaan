@extends('user.layouts.app')

@section('title', 'Detail Buku')

@section('content')

    <style>
        body {
            background: #0b1220;
            color: #e5e7eb;
        }

        /* WRAPPER */
        .detail-wrapper {
            max-width: 1100px;
            margin: auto;
            padding: 25px;
        }

        /* MAIN CARD */
        .detail-card {
            display: flex;
            gap: 30px;
            background: linear-gradient(135deg, #111c33, #0f172a);
            border: 1px solid #243244;
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5);
            position: relative;
            overflow: hidden;
        }

        .detail-card::before {
            content: "";
            position: absolute;
            width: 400px;
            height: 400px;
            background: rgba(59, 130, 246, 0.15);
            top: -150px;
            right: -150px;
            filter: blur(80px);
            border-radius: 50%;
        }

        /* COVER */
        .cover {
            width: 260px;
            height: 360px;
            border-radius: 16px;
            overflow: hidden;
            flex-shrink: 0;
            border: 1px solid #334155;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.6);
            transition: 0.3s;
            z-index: 2;
        }

        .cover:hover {
            transform: translateY(-6px) scale(1.02);
        }

        .cover img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* INFO */
        .detail-info {
            flex: 1;
            z-index: 2;
        }

        .detail-info h2 {
            font-size: 30px;
            margin-bottom: 10px;
            color: #f8fafc;
        }

        .meta {
            color: #94a3b8;
            font-size: 14px;
            margin-bottom: 6px;
        }

        /* BADGE */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
            margin: 8px 6px 8px 0;
            border: 1px solid transparent;
        }

        .green {
            background: rgba(34, 197, 94, 0.12);
            color: #4ade80;
            border-color: rgba(34, 197, 94, 0.25);
        }

        .red {
            background: rgba(239, 68, 68, 0.12);
            color: #f87171;
            border-color: rgba(239, 68, 68, 0.25);
        }

        .gray {
            background: rgba(148, 163, 184, 0.12);
            color: #cbd5e1;
            border-color: rgba(148, 163, 184, 0.25);
        }

        /* INPUT */
        .input-jumlah {
            width: 80px;
            padding: 10px;
            border-radius: 10px;
            border: 1px solid #334155;
            background: #0b1220;
            color: #e5e7eb;
            margin-right: 10px;
        }

        .input-jumlah:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
            outline: none;
        }

        /* BUTTONS */
        .btn {
            border: none;
            padding: 10px 16px;
            border-radius: 12px;
            cursor: pointer;
            font-weight: 600;
            transition: 0.25s;
            font-size: 13px;
        }

        .btn-pinjam {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: white;
            box-shadow: 0 10px 25px rgba(59, 130, 246, 0.25);
        }

        .btn-pinjam:hover {
            transform: translateY(-3px);
        }

        .btn-return {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
            box-shadow: 0 10px 25px rgba(245, 158, 11, 0.25);
        }

        .btn-return:hover {
            transform: translateY(-3px);
        }

        /* DESKRIPSI */
        .deskripsi {
            margin-top: 18px;
            padding: 16px;
            border-radius: 14px;
            background: rgba(15, 23, 42, 0.7);
            border: 1px solid #243244;
            color: #cbd5e1;
            line-height: 1.7;
            font-size: 14px;
        }

        /* REKOMENDASI */
        .rekomendasi {
            margin-top: 35px;
        }

        .rekomendasi h4 {
            margin-bottom: 15px;
            color: #f8fafc;
            font-size: 18px;
        }

        .rekomendasi-list {
            display: flex;
            gap: 15px;
            overflow-x: auto;
            padding-bottom: 10px;
        }

        .rekomendasi-item {
            min-width: 160px;
            background: #111c33;
            border: 1px solid #243244;
            padding: 12px;
            border-radius: 14px;
            text-align: center;
            text-decoration: none;
            color: #e5e7eb;
            transition: 0.25s;
        }

        .rekomendasi-item:hover {
            transform: translateY(-6px);
            border-color: #3b82f6;
            box-shadow: 0 15px 35px rgba(59, 130, 246, 0.15);
        }

        .rekomendasi-item img {
            width: 100%;
            height: 120px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 8px;
        }

        .rekomendasi-item p {
            font-size: 13px;
            font-weight: 500;
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .detail-card {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }

            .cover {
                width: 200px;
                height: 300px;
            }
        }
    </style>
    <div class="detail-wrapper">
        <div class="detail-card">
            {{-- COVER --}}
            <div class="cover">
                <img src="{{ $buku->cover ? asset('storage/' . $buku->cover) : 'https://via.placeholder.com/200' }}">
            </div>
            {{-- INFO --}}
            <div class="detail-info">
                <h2>{{ $buku->judul }}</h2>
                <div class="meta"> {{ $buku->penulis }}</div>
                <div class="meta"> {{ $buku->tahun_terbit ?? '-' }}</div>
                <div class="meta"> {{ $buku->kategori->nama ?? '-' }}</div>
                {{-- STATUS --}}
                <div class="badge {{ $buku->stok > 0 ? 'green' : 'red' }}">
                    {{ $buku->stok > 0 ? 'Tersedia' : 'Tidak Tersedia' }}
                </div>
                <div class="badge gray">
                    Stok: <strong>{{ $buku->stok }}</strong> buku
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
                        <input type="number" name="jumlah" class="input-jumlah" min="1"
                            max="{{ min(5, $buku->stok) }}" value="1">
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
                    {{ $buku->deskripsi ?? 'Tidak ada deskripsi tersedia untuk buku ini.' }}
                </div>

            </div>
        </div>
        {{-- REKOMENDASI --}}
        <div class="rekomendasi">
            <h4>Rekomendasi Buku</h4>
            <div class="rekomendasi-list">
                @foreach ($rekomendasi as $item)
                    <a href="{{ route('user.buku.detail', $item->id) }}" class="rekomendasi-item">
                        <img
                            src="{{ $item->cover ? asset('storage/' . $item->cover) : 'https://via.placeholder.com/150' }}">
                        <p>{{ $item->judul }}</p>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
@endsection
