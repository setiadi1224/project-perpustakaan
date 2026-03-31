@extends('user.layouts.app')

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
    text-align: left;
}

th {
    color: #888;
    font-weight: 600;
    border-bottom: 1px solid #eee;
}

tr:not(:last-child) {
    border-bottom: 1px solid #f1f1f1;
}

.status {
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
}

.status.selesai {
    background: #d4edda;
    color: #28a745;
}

.status.dipinjam {
    background: #cce5ff;
    color: #007bff;
}

.status.terlambat {
    background: #f8d7da;
    color: #dc3545;
}
</style>

<div class="container">

    <!-- HEADER -->
    <div class="header">
        <h2>Riwayat Peminjaman</h2>

        <div class="search-box">
            <input type="text" placeholder="Search Buku...">
        </div>
    </div>

    <!-- TABLE -->
    <div class="table-box">
        <table>
            <thead>
                <tr>
                    <th>Judul Buku</th>
                    <th>Tanggal Pinjam</th>
                    <th>Tanggal Kembali</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>

                @forelse($riwayats as $item)
                <tr>
                    <td>{{ $item->buku->judul ?? '-' }}</td>
                    <td>{{ $item->tanggal_pinjam }}</td>
                    <td>{{ $item->tanggal_kembali ?? '-' }}</td>
                    <td>
                        @if($item->status == 'selesai')
                            <span class="status selesai">Selesai</span>
                        @elseif($item->status == 'dipinjam')
                            <span class="status dipinjam">Dipinjam</span>
                        @else
                            <span class="status terlambat">Terlambat</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align:center;">Belum ada riwayat peminjaman</td>
                </tr>
                @endforelse

            </tbody>
        </table>
    </div>

</div>

@endsection