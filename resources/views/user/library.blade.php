@extends('user.layouts.app')

@section('content')

<style>
.book-list {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
    margin-bottom: 30px;
    text-decoration: none;
}

.book-item {
    width: 180px;
    background: #fff;
    padding: 15px;
    border-radius: 12px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    text-align: center;
    transition: 0.3s;
    text-decoration: none;
}

.book-item:hover {
    transform: translateY(-5px);
}

.book-item img {
    width: 100%;
    height: 120px;
    object-fit: cover;
    border-radius: 8px;
}

.table-box {
    background: #fff;
    padding: 15px;
    border-radius: 12px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.05);
}

.table-box table {
    width: 100%;
    border-collapse: collapse;
}

.table-box th, .table-box td {
    padding: 10px;
    text-align: left;
}

.table-box th {
    border-bottom: 1px solid #ddd;
}

.status.available {
    background: #d4edda;
    color: green;
    padding: 5px 10px;
    border-radius: 8px;
}

</style>

<div class="container">

    <!-- 🔍 SEARCH -->
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

    <!-- 📚 CARD BUKU -->
    <div class="book-list">
        @forelse($books as $book)
    <a href="{{ route('user.buku.detail', $book->id) }}" class="book-item">
     <img src="{{ $book->cover ? asset('storage/'.$book->cover) : 'https://via.placeholder.com/150' }}" alt="">
            <h4 class="">{{ $book->judul }}</h4>
            <p>{{ $book->penulis }}</p>
</a>
        @empty
        <p>Tidak ada buku ditemukan</p>
        @endforelse
    </div>

    <!-- 📊 TABLE -->
    <div class="table-box">
        <table>
            <thead>
                <tr>
                    <th>Judul</th>
                    <th>Penulis</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($books as $book)
                <tr>
                    <td>{{ $book->judul }}</td>
                    <td>{{ $book->penulis }}</td>
                    <td>
                        <span class="status available">Tersedia</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3">Tidak ada data</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- 🔢 PAGINATION -->
    <div style="margin-top: 20px;">
        {{ $books->links() }}
    </div>

</div>

@endsection