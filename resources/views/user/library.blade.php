@extends('user.layouts.app')

@section('content')
<style>
.top-bar {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
}

.search-input {
    flex: 1;
    padding: 10px 15px;
    border-radius: 20px;
    border: 1px solid #ccc;
}

/* KATEGORI */
.kategori-bar {
    display: flex;
    gap: 10px;
    overflow-x: auto;
    margin-bottom: 20px;
}

.kategori-item {
    padding: 8px 16px;
    background: #fff;
    border-radius: 20px;
    border: 1px solid #ddd;
    white-space: nowrap;
    text-decoration: none;
    color: #374151;
    transition: 0.2s;
}

.kategori-item.active,
.kategori-item:hover {
    background: #2563eb;
    color: white;
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
    background: #fff;
    padding: 12px;
    border-radius: 14px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    text-align: center;
    text-decoration: none;
    color: inherit;
    transition: 0.25s;

    position: relative; /* 🔥 FIX UTAMA */
}

.book-item:hover {
    transform: translateY(-5px);
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
    background: rgba(239, 68, 68, 0.95);
    color: white;
    font-size: 10px;
    padding: 4px 10px;
    border-radius: 20px;
    z-index: 2;
}

/* TEXT */
.book-item h4 {
    font-size: 14px;
    margin-top: 10px;
    font-weight: 600;
}

.book-item p {
    font-size: 12px;
    color: #6b7280;
}

/* PAGINATION */
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
    background: #2563eb;
    color: white;
    transform: translateY(-2px);
}

.pagination .active span {
    background: #2563eb;
    color: white;
}

.pagination .disabled span {
    opacity: 0.5;
}
</style>

<div class="container">

    {{-- 🔍 SEARCH --}}
    <form method="GET" class="top-bar">
        <input 
            type="text" 
            name="search" 
            class="search-input"
            placeholder="Cari buku..."
            value="{{ request('search') }}"
        >
    </form>

    {{-- 📂 KATEGORI --}}
    <div class="kategori-bar">
        <a href="{{ url()->current() }}" 
           class="kategori-item {{ !request('kategori') ? 'active' : '' }}">
            Semua
        </a>

        @foreach($kategoris as $k)
            <a 
                href="{{ url()->current() }}?kategori={{ $k->id }}&search={{ request('search') }}"
                class="kategori-item {{ request('kategori') == $k->id ? 'active' : '' }}"
            >
                {{ $k->nama }}
            </a>
        @endforeach
    </div>

    {{-- 📚 GRID --}}
    <div class="book-list">
        @forelse($books as $book)
            <a href="{{ route('user.buku.detail', $book->id) }}" class="book-item">

                {{-- BADGE --}}
                @if($book->kategori)
                    <div class="badge">{{ $book->kategori->nama }}</div>
                @endif

                {{-- COVER --}}
                <img src="{{ $book->cover ? asset('storage/'.$book->cover) : 'https://via.placeholder.com/150' }}">

                {{-- INFO --}}
                <h4>{{ \Illuminate\Support\Str::limit($book->judul, 25) }}</h4>
                <p>{{ $book->penulis }}</p>

            </a>
        @empty
            <p style="text-align:center; grid-column:1/-1;">
                Tidak ada buku ditemukan
            </p>
        @endforelse
    </div>

    {{-- 🔥 PAGINATION --}}
    <div class="pagination-wrapper">
        {{ $books->links() }}
    </div>

</div>
@endsection