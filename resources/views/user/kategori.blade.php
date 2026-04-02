@extends('user.layouts.app')

@section('content')
<style>
    /* CONTAINER */
.container {
    padding: 30px;
    font-family: Arial, sans-serif;
}

/* HEADER */
.header h1 {
    font-size: 26px;
    margin-bottom: 5px;
}

.header p {
    color: #777;
    margin-bottom: 20px;
}

/* GRID */
.grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 20px;
}

/* CARD */
.card {
    background: #fff;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    transition: 0.3s;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}

/* ICON */
.icon {
    font-size: 35px;
    margin-bottom: 10px;
}

/* TITLE */
.card h2 {
    font-size: 18px;
    margin-bottom: 5px;
}

/* TEXT */
.card p {
    color: #666;
    font-size: 14px;
    margin-bottom: 15px;
}

/* BUTTON */
.btn {
    display: inline-block;
    padding: 8px 14px;
    background: #3490dc;
    color: white;
    border-radius: 8px;
    text-decoration: none;
    font-size: 14px;
    transition: 0.3s;
}

.btn:hover {
    background: #2779bd;
}

/* EMPTY */
.empty {
    grid-column: 1 / -1;
    text-align: center;
    color: #999;
}
</style>
 <div style="margin-bottom: 20px;">
        <form method="GET">
            <input 
                type="text" 
                name="search" 
                placeholder="Cari buku..." 
                value="{{ request('search') }}"
                style="padding:10px 15px; border-radius:20px; border:1px solid #ccc; width:250px;">
        </form>
    </div>

<div class="container">

    <!-- HEADER -->
    <div class="header">
        <h1>Kategori Buku</h1>
        <p>Jelajahi buku berdasarkan kategori</p>
    </div>

    <!-- GRID -->
    <div class="grid">
        @forelse ($kategoris as $kategori)
            <div class="card">

                <div class="icon">
                    {{ $kategori->icon ?? '📚' }}
                </div>

                <h2>{{ $kategori->nama }}</h2>

                <p>{{ $kategori->bukus_count }} Buku</p>

                <a href="{{ route('user.library', ['kategori' => $kategori->id]) }}" class="btn">
                    Lihat Buku
                </a>

            </div>
        @empty
            <p class="empty">Data kategori belum tersedia</p>
        @endforelse
    </div>

</div>
@endsection