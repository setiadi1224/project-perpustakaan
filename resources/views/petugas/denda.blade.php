@extends('petugas.layouts.app')

@section('content')
<style>
.page-title {
    font-size: 20px;
    font-weight: 700;
    margin-bottom: 20px;
}

.denda-card {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: white;
    padding: 20px;
    border-radius: 16px;
    margin-bottom: 20px;
}

.table-box {
    background: #fff;
    padding: 20px;
    border-radius: 16px;
}

.badge {
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 12px;
    color: white;
}

.red { background: #ef4444; }
.green { background: #10b981; }

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
    transition: 0.2s;
}

.pagination a:hover {
    background: #ef4444;
    color: white;
}

.pagination .active span {
    background: #ef4444;
    color: white;
}

.pagination .disabled span {
    opacity: 0.5;
}
</style>

<h4 class="page-title">Kelola Denda</h4>

<div class="denda-card">
    <h3>Total Denda Aktif</h3>
    <h1>Rp {{ number_format($totalDenda, 0, ',', '.') }}</h1>
</div>

<div class="table-box">
    <table width="100%">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Buku</th>
                <th>Terlambat</th>
                <th>Total</th>
                <th>Status</th>
            </tr>
        </thead>

        <tbody>
            @forelse($data as $item)
                <tr>
                    <td>{{ $item->user->name }}</td>
                    <td>{{ $item->buku->judul }}</td>
                    <td>{{ $item->terlambat }} Hari</td>
                    <td>Rp {{ number_format($item->total_denda, 0, ',', '.') }}</td>
                    <td>
                        @if ($item->total_denda > 0)
                            <span class="badge red">Belum Bayar</span>
                        @else
                            <span class="badge green">Lunas</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align:center;">
                        Tidak ada data denda
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- INFO --}}
    <div style="margin-top:10px; font-size:13px; color:#64748b;">
        Menampilkan {{ $data->firstItem() ?? 0 }} - {{ $data->lastItem() ?? 0 }} 
        dari {{ $data->total() }} data
    </div>
</div>

{{-- 🔥 PAGINATION --}}
<div class="pagination-wrapper">
    {{ $data->links() }}
</div>

@endsection