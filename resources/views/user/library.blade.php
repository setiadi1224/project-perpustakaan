@extends('user.layouts.app')

@section('content')
    <style>
        body {
            background: #0f172a;
            color: #e5e7eb;
        }

        .container {
            padding: 20px;
        }

        /* TOP BAR */
        .top-bar {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        .search-input {
            flex: 1;
            padding: 12px 16px;
            border-radius: 12px;
            border: 1px solid #334155;
            background: #1e293b;
            color: #e5e7eb;
            outline: none;
            transition: 0.2s;
        }

        .search-input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
        }

        /* KATEGORI */
        .kategori-bar {
            display: flex;
            gap: 10px;
            overflow-x: auto;
            margin-bottom: 20px;
            padding-bottom: 5px;
        }

        .kategori-item {
            padding: 8px 16px;
            background: #1e293b;
            border-radius: 999px;
            border: 1px solid #334155;
            white-space: nowrap;
            text-decoration: none;
            color: #cbd5e1;
            transition: 0.2s;
            font-size: 13px;
        }

        .kategori-item.active,
        .kategori-item:hover {
            background: #3b82f6;
            color: white;
            border-color: #3b82f6;
            transform: translateY(-1px);
        }

        /* GRID */
        .book-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        /* CARD */
        .book-item {
            background: #1e293b;
            padding: 12px;
            border-radius: 14px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.35);
            text-align: center;
            text-decoration: none;
            color: #e5e7eb;
            transition: 0.25s;
            position: relative;
            border: 1px solid #334155;
        }

        .book-item:hover {
            transform: translateY(-6px);
            box-shadow: 0 15px 35px rgba(59, 130, 246, 0.15);
            border-color: #3b82f6;
        }

        /* COVER */
        .book-item img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 10px;
        }

        /* BADGE */
        .badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: rgba(59, 130, 246, 0.9);
            color: white;
            font-size: 10px;
            padding: 4px 10px;
            border-radius: 999px;
            backdrop-filter: blur(6px);
        }

        /* TEXT */
        .book-item h4 {
            font-size: 14px;
            margin-top: 10px;
            font-weight: 600;
            color: #f1f5f9;
        }

        .book-item p {
            font-size: 12px;
            color: #94a3b8;
        }
    </style>

    <div class="container">

        {{-- SEARCH --}}
        <form method="GET" class="top-bar">
            <input type="text" name="search" class="search-input" placeholder="Cari buku..." value="{{ request('search') }}">
        </form>

        {{-- KATEGORI --}}
        <div class="kategori-bar">
            <a href="{{ url()->current() }}" class="kategori-item {{ !request('kategori') ? 'active' : '' }}">
                Semua
            </a>

            @foreach ($kategoris as $k)
                <a href="{{ url()->current() }}?kategori={{ $k->id }}&search={{ request('search') }}"
                    class="kategori-item {{ request('kategori') == $k->id ? 'active' : '' }}">
                    {{ $k->nama }}
                </a>
            @endforeach
        </div>

        {{-- GRID BOOK --}}
        <div class="book-list">
            @forelse($books as $book)
                <a href="{{ route('user.buku.detail', $book->id) }}" class="book-item">

                    @if ($book->kategori)
                        <div class="badge">{{ $book->kategori->nama }}</div>
                    @endif

                    <img src="{{ $book->cover ? asset('storage/' . $book->cover) : 'https://via.placeholder.com/150' }}">

                    <h4>{{ \Illuminate\Support\Str::limit($book->judul, 25) }}</h4>
                    <p>{{ $book->penulis }}</p>
                </a>
            @empty
                <p style="text-align:center; grid-column:1/-1; color:#94a3b8;">
                    Tidak ada buku ditemukan
                </p>
            @endforelse
        </div>

        {{-- PAGINATION --}}
        <div class="pagination-wrapper">
            {{ $books->links() }}
        </div>

    </div>
@endsection
