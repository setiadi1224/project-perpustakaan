@extends('user.layouts.app')

@section('title', 'Riwayat Peminjaman')

@section('content')

<style>
.container {
    padding: 20px;
}

.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.search-box input {
    padding: 8px 15px;
    border-radius: 20px;
    border: 1px solid #ccc;
}

.table-box {
    background: #fff;
    padding: 20px;
    border-radius: 15px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.05);
}

table {
    width: 100%;
    border-collapse: collapse;
}

th, td {
    padding: 12px;
}

th {
    border-bottom: 1px solid #eee;
    color: #888;
}

tr:not(:last-child) {
    border-bottom: 1px solid #f1f1f1;
}

/* STATUS */
.status {
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 12px;
}

.status.menunggu { background: #fff3cd; color: #856404; }
.status.dipinjam { background: #cce5ff; color: #007bff; }
.status.selesai { background: #d4edda; color: #28a745; }
.status.terlambat { background: #f8d7da; color: #dc3545; }

/* DENDA */
.denda {
    font-weight: 600;
    color: #ef4444;
}

/* BUTTON */
.btn-return {
    background: #10b981;
    color: white;
    border: none;
    padding: 6px 12px;
    border-radius: 8px;
    cursor: pointer;
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
}

.pagination a:hover {
    background: #2563eb;
    color: white;
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

    <div class="header">
        <h2>Riwayat Peminjaman</h2>

        {{-- 🔍 SEARCH --}}
        <div class="search-box">
            <form method="GET">
                <input 
                    type="text" 
                    name="search" 
                    placeholder="Cari Buku..."
                    value="{{ request('search') }}"
                >
            </form>
        </div>
    </div>

    <div class="table-box">
        <table>
            <thead>
                <tr>
                    <th>Judul Buku</th>
                    <th>Tanggal Pinjam</th>
                    <th>Tanggal Kembali</th>
                    <th>Status</th>
                    <th>Denda</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($riwayats as $item)

                    @php
                        $hari = $item->tanggal_pinjam
                            ? \Carbon\Carbon::parse($item->tanggal_pinjam)->diffInDays(now())
                            : 0;

                        $terlambat = $item->status == 'dipinjam' && $hari > 1;
                    @endphp

                    <tr>
                        <td>{{ $item->buku->judul ?? '-' }}</td>

                        <td>
                            {{ $item->tanggal_pinjam ? \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d M Y') : '-' }}
                        </td>

                        <td>
                            {{ $item->tanggal_kembali ? \Carbon\Carbon::parse($item->tanggal_kembali)->format('d M Y') : '-' }}
                        </td>

                        <td>
                            @if ($item->status == 'menunggu')
                                <span class="status menunggu">Menunggu</span>
                            @elseif($item->status == 'dipinjam' && $terlambat)
                                <span class="status terlambat">Terlambat</span>
                            @elseif($item->status == 'dipinjam')
                                <span class="status dipinjam">Dipinjam</span>
                            @elseif($item->status == 'dikembalikan')
                                <span class="status selesai">Selesai</span>
                            @endif
                        </td>

                        <td>
                            @if ($item->denda > 0)
                                <span class="denda">
                                    Rp {{ number_format($item->denda, 0, ',', '.') }}
                                </span>
                            @else
                                -
                            @endif
                        </td>

                        <td>
                            @if ($item->status == 'dipinjam')
                                <form action="{{ route('user.return', $item->id) }}" method="POST">
                                    @csrf
                                    <button class="btn-return"
                                        onclick="return confirm('Yakin ingin mengembalikan buku?')">
                                        Kembalikan
                                    </button>
                                </form>
                            @else
                                -
                            @endif
                        </td>
                    </tr>

                @empty
                    <tr>
                        <td colspan="6" style="text-align:center;">
                            Belum ada riwayat peminjaman
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- 🔥 PAGINATION --}}
    <div class="pagination-wrapper">
        {{ $riwayats->links() }}
    </div>

</div>
@endsection