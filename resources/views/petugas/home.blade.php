@extends('petugas.layouts.app')

@section('content')

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
.dashboard {
    display: grid;
    gap: 20px;
}

.cards {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 15px;
}

.card {
    background: #fff;
    padding: 20px;
    border-radius: 14px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.05);
}

.card h2 {
    margin-top: 5px;
}

.card.blue { border-left: 5px solid #3b82f6; }
.card.green { border-left: 5px solid #10b981; }
.card.red { border-left: 5px solid #ef4444; }
.card.orange { border-left: 5px solid #f59e0b; }

.grid-2 {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 20px;
}

.section {
    background: #fff;
    padding: 20px;
    border-radius: 14px;
}

.quick-actions {
    display: flex;
    gap: 10px;
}

.btn {
    background: #2563eb;
    color: white;
    padding: 10px 15px;
    border-radius: 10px;
    text-decoration: none;
}

.alert {
    background: #fee2e2;
    color: #991b1b;
    padding: 10px;
    border-radius: 10px;
    margin-bottom: 10px;
}

table {
    width: 100%;
    border-collapse: collapse;
}

td, th {
    padding: 10px;
}

.badge {
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 12px;
    color: white;
}

.waiting { background: orange; }
.borrowed { background: #3b82f6; }
.done { background: #10b981; }
</style>

<div class="dashboard">

    {{-- HEADER --}}
    <div>
        <h2>Dashboard Petugas</h2>
        <small>Selamat datang, {{ auth()->user()->name }}</small>
    </div>

    {{-- 🔥 CARDS --}}
    <div class="cards">
        <div class="card blue">
            <p>Total Anggota</p>
            <h2>{{ $totalAnggota }}</h2>
        </div>

        <div class="card green">
            <p>Total Buku</p>
            <h2>{{ $totalBuku }}</h2>
        </div>

        <div class="card blue">
            <p>Peminjaman Aktif</p>
            <h2>{{ $peminjamanAktif }}</h2>
        </div>

        <div class="card red">
            <p>Total Denda</p>
            <h2>Rp {{ number_format($totalDenda) }}</h2>
        </div>

        <div class="card orange">
            <p>Menunggu</p>
            <h2>{{ $menunggu }}</h2>
        </div>
    </div>

    {{-- 🔥 GRID --}}
    <div class="grid-2">

        {{-- 📊 CHART --}}
        <div class="section">
            <h3>Grafik Peminjaman (7 Hari)</h3>
            <canvas id="chart"></canvas>
        </div>

        {{-- 🔔 ALERT --}}
        <div class="section">
            <h3>Notifikasi</h3>

            @if($terlambat > 0)
                <div class="alert">
                    ⚠️ Ada {{ $terlambat }} buku terlambat!
                </div>
            @else
                <p>Tidak ada keterlambatan 🎉</p>
            @endif

            <div class="quick-actions">
                <a href="{{ route('petugas.peminjaman') }}" class="btn">Cek Peminjaman</a>
            </div>
        </div>

    </div>

    {{-- 🔥 ACTIVITY --}}
    <div class="section">
        <h3>Aktivitas Terbaru</h3>

        <table>
            @foreach($recentPeminjaman as $item)
            <tr>
                <td>{{ $item->user->name }}</td>
                <td>{{ $item->buku->judul }}</td>
                <td>
                    @if($item->status == 'menunggu')
                        <span class="badge waiting">Menunggu</span>
                    @elseif($item->status == 'dipinjam')
                        <span class="badge borrowed">Dipinjam</span>
                    @else
                        <span class="badge done">Selesai</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </table>
    </div>

</div>

{{-- 🔥 CHART SCRIPT --}}
<script>
const ctx = document.getElementById('chart');

new Chart(ctx, {
    type: 'line',
    data: {
        labels: @json($labels),
        datasets: [{
            label: 'Peminjaman',
            data: @json($chartData),
            borderWidth: 2,
            tension: 0.3
        }]
    }
});
</script>

@endsection