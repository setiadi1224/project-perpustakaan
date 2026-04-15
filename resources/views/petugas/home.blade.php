@extends('petugas.layouts.app')

@section('content')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            overflow-x: hidden;
        }

        /* ================= DASHBOARD ================= */
        .dashboard {
            display: grid;
            gap: 20px;
            padding: 5px;
        }

        /* ================= CARDS ================= */
        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 15px;
        }

        .card {
            background: #fff;
            padding: 20px;
            border-radius: 14px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
        }

        .card h2 {
            margin-top: 5px;
            font-size: 22px;
        }

        .card.blue {
            border-left: 5px solid #3b82f6;
        }

        .card.green {
            border-left: 5px solid #10b981;
        }

        .card.red {
            border-left: 5px solid #ef4444;
        }

        .card.orange {
            border-left: 5px solid #f59e0b;
        }

        /* ================= GRID ================= */
        .grid-2 {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
        }

        .section {
            background: #fff;
            padding: 20px;
            border-radius: 14px;
            overflow: hidden;
        }

        /* ================= BUTTON ================= */
        .quick-actions {
            display: flex;
            gap: 10px;
        }

        .btn {
            display: block;
            width: 100%;
            max-width: 100%;
            background: #2563eb;
            color: white;
            padding: 10px;
            border-radius: 10px;
            text-decoration: none;
            text-align: center;
            transition: 0.2s;
            white-space: normal;
        }

        .btn:hover {
            background: #1d4ed8;
        }

        /* ================= ALERT ================= */
        .alert {
            background: #fee2e2;
            color: #991b1b;
            padding: 10px;
            border-radius: 10px;
            margin-bottom: 10px;
        }

        /* ================= TABLE ================= */
        .table-wrapper {
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td,
        th {
            padding: 10px;
            text-align: left;
        }

        .badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            color: white;
        }

        .waiting {
            background: orange;
        }

        .borrowed {
            background: #3b82f6;
        }

        .done {
            background: #10b981;
        }

        /* ================= CHART ================= */
        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
        }

        /* ================= RESPONSIVE ================= */

        /* Tablet */
        @media (max-width: 1024px) {
            .grid-2 {
                grid-template-columns: 1fr;
            }
        }

        /* Mobile */
        @media (max-width: 768px) {

            .dashboard {
                gap: 15px;
                padding: 0 5px;
            }

            .cards {
                gap: 10px;
            }

            .card {
                padding: 15px;
                border-radius: 12px;
            }

            .section {
                padding: 15px;
            }

            h2 {
                font-size: 18px;
            }

            h3 {
                font-size: 16px;
            }

            .quick-actions {
                flex-direction: column;
            }

            .chart-container {
                height: 200px;
            }
        }
    </style>

    <div class="dashboard">

        {{-- HEADER --}}
        <div>
            <h2>Dashboard Petugas</h2>
            <small>Selamat datang, {{ auth()->user()->name }}</small>
        </div>

        {{-- CARDS --}}
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

        {{-- GRID --}}
        <div class="grid-2">

            {{-- CHART --}}
            <div class="section">
                <h3>Grafik Peminjaman (7 Hari)</h3>
                <div class="chart-container">
                    <canvas id="chart"></canvas>
                </div>
            </div>

            {{-- ALERT --}}
            <div class="section">
                <h3>Notifikasi</h3>

                @if ($terlambat > 0)
                    <div class="alert">
                        ⚠️ Ada {{ $terlambat }} buku terlambat!
                    </div>
                @else
                    <p>Tidak ada keterlambatan 🎉</p>
                @endif

                <div class="quick-actions">
                    <a href="{{ route('petugas.peminjaman') }}" class="btn">
                        Cek Peminjaman
                    </a>
                </div>
            </div>

        </div>

        {{-- ACTIVITY --}}
        <div class="section">
            <h3>Aktivitas Terbaru</h3>

            <div class="table-wrapper">
                <table>
                    @foreach ($recentPeminjaman as $item)
                        <tr>
                            <td>{{ $item->user->name }}</td>
                            <td>{{ $item->buku->judul }}</td>
                            <td>
                                @if ($item->status == 'menunggu')
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

    </div>

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
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    </script>
@endsection
