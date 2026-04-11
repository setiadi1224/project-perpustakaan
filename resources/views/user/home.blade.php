@extends('user.layouts.app')

@section('title', 'Home — Perpustakaan Digital')

@push('styles')
    <style>
        /* GLOBAL */
        body {
            background: #0f172a;
            color: #e5e7eb;
        }

        /* ================= HERO ================= */
        .hero {
            position: relative;
            height: 360px;
            border-radius: 18px;
            overflow: hidden;
            margin-bottom: 30px;
        }

        .hero-slide {
            position: absolute;
            width: 100%;
            height: 100%;
            opacity: 0;
            transition: opacity 0.8s ease;
        }

        .hero-slide.active {
            opacity: 1;
        }

        .hero img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            filter: brightness(0.6);
        }

        .hero-content {
            position: absolute;
            bottom: 40px;
            left: 40px;
            z-index: 2;
        }

        .hero-title {
            font-size: 32px;
            font-weight: 800;
        }

        .hero-desc {
            font-size: 14px;
            color: #cbd5f5;
            margin: 10px 0;
        }

        .hero-btn {
            background: #3b82f6;
            padding: 10px 18px;
            border-radius: 10px;
            color: white;
            text-decoration: none;
        }

        /* ================= SECTION ================= */
        .sec-title {
            font-size: 18px;
            font-weight: 700;
            margin: 25px 0 12px;
        }

        /* ================= SCROLL ROW ================= */
        .buku-row {
            display: flex;
            gap: 14px;

            overflow-x: auto !important;
            overflow-y: hidden;

            white-space: nowrap;
            scroll-behavior: smooth;

            padding-bottom: 10px;
        }

        /* scrollbar */
        .buku-row::-webkit-scrollbar {
            height: 6px;
        }

        .buku-row::-webkit-scrollbar-thumb {
            background: #334155;
            border-radius: 10px;
        }

        /* ================= CARD ================= */
        .buku-card {
            flex: 0 0 auto;
            width: 150px;
            height: 220px;

            border-radius: 14px;
            overflow: hidden;
            position: relative;
            background: #1e293b;

            transition: 0.3s;
        }

        .buku-card:hover {
            transform: scale(1.08);
        }

        .buku-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* HOVER */
        .buku-hover {
            position: absolute;
            bottom: 0;
            width: 100%;
            padding: 10px;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.9), transparent);
            opacity: 0;
            transition: 0.3s;
        }

        .buku-card:hover .buku-hover {
            opacity: 1;
        }

        .buku-title {
            font-size: 13px;
            font-weight: 600;
        }

        .buku-author {
            font-size: 11px;
            color: #cbd5f5;
        }

        .badge {
            background: #22c55e;
            font-size: 10px;
            padding: 2px 6px;
            border-radius: 4px;
        }
    </style>
@endpush

@section('content')

    {{-- HERO --}}
    <div class="hero">
        @foreach ($trending as $i => $buku)
            <div class="hero-slide {{ $i == 0 ? 'active' : '' }}">
                <img src="{{ asset('storage/' . $buku->cover) }}">
                <div class="hero-content">
                    <div class="hero-title">{{ $buku->judul }}</div>
                    <div class="hero-desc">{{ $buku->penulis }}</div>
                    <a href="{{ route('user.buku.detail', $buku->id) }}" class="hero-btn">
                        Lihat Detail
                    </a>
                </div>
            </div>
        @endforeach
    </div>

    {{-- TRENDING --}}
    <div class="sec-title">Trending</div>
    <div class="buku-row">
        @foreach ($trending as $buku)
            <a href="{{ route('user.buku.detail', $buku->id) }}" class="buku-card">
                <img src="{{ asset('storage/' . $buku->cover) }}">
                <div class="buku-hover">
                    <div class="badge">Populer</div>
                    <div class="buku-title">{{ $buku->judul }}</div>
                    <div class="buku-author">{{ $buku->penulis }}</div>
                </div>
            </a>
        @endforeach
    </div>

    {{-- BARU --}}
    <div class="sec-title">Baru Ditambahkan</div>
    <div class="buku-row">
        @foreach ($baru as $buku)
            <a href="{{ route('user.buku.detail', $buku->id) }}" class="buku-card">
                <img src="{{ asset('storage/' . $buku->cover) }}">
                <div class="buku-hover">
                    <div class="badge" style="background:#3b82f6">Baru</div>
                    <div class="buku-title">{{ $buku->judul }}</div>
                    <div class="buku-author">{{ $buku->penulis }}</div>
                </div>
            </a>
        @endforeach
    </div>

    {{-- HERO SLIDER --}}
    <script>
        let slides = document.querySelectorAll('.hero-slide');
        let index = 0;

        setInterval(() => {
            slides[index].classList.remove('active');
            index = (index + 1) % slides.length;
            slides[index].classList.add('active');
        }, 4000);
    </script>

@endsection
