@extends('user.layouts.app')

@section('content')

<style>
/* (CSS kamu tetap, tidak usah diubah) */
.main-content {
    padding: 20px;
}

.card-denda {
    background: linear-gradient(to right, #3b82f6, #1d4ed8);
    color: white;
    padding: 20px;
    border-radius: 12px;
    margin-bottom: 20px;
}

.card-table {
    background: #f9fafb;
    padding: 20px;
    border-radius: 12px;
}

table {
    width: 100%;
    border-collapse: collapse;
}

thead {
    color: #6b7280;
    font-size: 14px;
}

th, td {
    padding: 12px;
}

tbody tr {
    border-top: 1px solid #e5e7eb;
}

.badge {
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 12px;
    color: white;
}

.merah { background: #ef4444; }
.hijau { background: #10b981; }

.btn-bayar {
    background: #3b82f6;
    color: white;
    border: none;
    padding: 6px 12px;
    border-radius: 8px;
}
</style>

<div class="main-content">

    <!-- TOTAL DENDA -->
    <div class="card-denda">
        <h5>Total Denda Aktif</h5>
        <h2>Rp {{ number_format($totalDenda, 0, ',', '.') }}</h2>
    </div>

    <!-- TABLE -->
    <div class="card-table">
        <table>
            <thead>
                <tr>
                    <th>Judul Buku</th>
                    <th>Terlambat (Hari)</th>
                    <th>Total Denda</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($denda as $item)
                <tr>
                    <td>{{ $item->buku->judul ?? '-' }}</td>
                    <td>{{ $item->terlambat ?? 0 }}</td>
                    <td>Rp {{ number_format($item->denda, 0, ',', '.') }}</td>

                    <td>
                        @if($item->status == 'belum_bayar')
                            <span class="badge merah">Belum Bayar</span>
                        @else
                            <span class="badge hijau">Lunas</span>
                        @endif
                    </td>

                    <td>
                        @if($item->status == 'belum_bayar')
                            <button class="btn-bayar">Bayar</button>
                        @else
                            -
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align:center;">Tidak ada denda</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

@endsection