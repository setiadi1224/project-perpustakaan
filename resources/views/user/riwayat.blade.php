@extends('user.layouts.app')

@section('title', 'Riwayat Peminjaman')

@section('content')

<style>
    body {
        background: #0f172a;
        color: #e5e7eb;
    }

    .container {
        padding: 20px;
    }

    /* ================= HEADER ================= */
    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        gap: 10px;
        flex-wrap: wrap;
    }

    .header h2 {
        color: #f1f5f9;
        font-size: 20px;
    }

    /* SEARCH */
    .search-box {
        width: 250px;
    }

    .search-box input {
        width: 100%;
        padding: 10px 15px;
        border-radius: 12px;
        border: 1px solid #334155;
        background: #1e293b;
        color: #e5e7eb;
        outline: none;
    }

    .search-box input:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
    }

    /* ================= TABLE ================= */
    .table-box {
        background: #1e293b;
        padding: 20px;
        border-radius: 16px;
        border: 1px solid #334155;
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th, td {
        padding: 14px;
        text-align: left;
        font-size: 13px;
    }

    th {
        color: #94a3b8;
        border-bottom: 1px solid #334155;
    }

    td {
        border-bottom: 1px solid #243244;
    }

    tr:hover {
        background: rgba(59, 130, 246, 0.05);
    }

    /* ================= STATUS ================= */
    .status {
        padding: 5px 12px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 500;
        display: inline-block;
    }

    .status.menunggu { background: rgba(245,158,11,.15); color:#fbbf24; }
    .status.dipinjam { background: rgba(59,130,246,.15); color:#60a5fa; }
    .status.selesai { background: rgba(34,197,94,.15); color:#4ade80; }
    .status.terlambat { background: rgba(239,68,68,.15); color:#f87171; }

    /* DENDA */
    .denda {
        color: #f87171;
        font-weight: 600;
    }

    /* BUTTON */
    .btn-return {
        background: #10b981;
        color: white;
        border: none;
        padding: 7px 12px;
        border-radius: 10px;
        cursor: pointer;
        font-size: 12px;
        width: fit-content;
    }

    .btn-return:hover {
        background: #059669;
    }

    /* ================= CARD (MOBILE) ================= */
    .card-list {
        display: none;
    }

    /* ================= RESPONSIVE ================= */
    @media (max-width: 768px) {

        .container {
            padding: 15px;
        }

        .header {
            flex-direction: column;
            align-items: stretch;
        }

        .search-box {
            width: 100%;
        }

        /* HIDE TABLE */
        table {
            display: none;
        }

        /* SHOW CARD */
        .card-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .card {
            background: #1e293b;
            padding: 15px;
            border-radius: 14px;
            border: 1px solid #334155;
        }

        .card h4 {
            color: #f1f5f9;
            margin-bottom: 10px;
            font-size: 16px;
        }

        .card-item {
            font-size: 13px;
            margin-bottom: 6px;
            color: #cbd5e1;
        }

        .card .btn-return {
            width: 100%;
            margin-top: 10px;
        }
    }
</style>

<div class="container">

    {{-- HEADER --}}
    <div class="header">
        <h2>Riwayat Peminjaman</h2>

        <div class="search-box">
            <form method="GET">
                <input type="text" name="search" placeholder="Cari Buku..." value="{{ request('search') }}">
            </form>
        </div>
    </div>

    {{-- TABLE (DESKTOP) --}}
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
                        <td colspan="6" style="text-align:center; color:#94a3b8;">
                            Belum ada riwayat peminjaman
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- CARD MOBILE --}}
        <div class="card-list">
            @forelse($riwayats as $item)

                @php
                    $hari = $item->tanggal_pinjam
                        ? \Carbon\Carbon::parse($item->tanggal_pinjam)->diffInDays(now())
                        : 0;

                    $terlambat = $item->status == 'dipinjam' && $hari > 1;
                @endphp

                <div class="card">
                    <h4>{{ $item->buku->judul ?? '-' }}</h4>

                    <div class="card-item">📅 Pinjam:
                        {{ $item->tanggal_pinjam ? \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d M Y') : '-' }}
                    </div>

                    <div class="card-item">📅 Kembali:
                        {{ $item->tanggal_kembali ? \Carbon\Carbon::parse($item->tanggal_kembali)->format('d M Y') : '-' }}
                    </div>

                    <div class="card-item">
                        Status:
                        @if ($item->status == 'menunggu')
                            <span class="status menunggu">Menunggu</span>
                        @elseif($item->status == 'dipinjam' && $terlambat)
                            <span class="status terlambat">Terlambat</span>
                        @elseif($item->status == 'dipinjam')
                            <span class="status dipinjam">Dipinjam</span>
                        @elseif($item->status == 'dikembalikan')
                            <span class="status selesai">Selesai</span>
                        @endif
                    </div>

                    <div class="card-item">
                        Denda:
                        @if ($item->denda > 0)
                            <span class="denda">
                                Rp {{ number_format($item->denda, 0, ',', '.') }}
                            </span>
                        @else
                            -
                        @endif
                    </div>

                    @if ($item->status == 'dipinjam')
                        <form action="{{ route('user.return', $item->id) }}" method="POST">
                            @csrf
                            <button class="btn-return"
                                onclick="return confirm('Yakin ingin mengembalikan buku?')">
                                Kembalikan
                            </button>
                        </form>
                    @endif
                </div>

            @empty
                <p style="text-align:center; color:#94a3b8;">
                    Belum ada riwayat peminjaman
                </p>
            @endforelse
        </div>

    </div>

    {{-- PAGINATION --}}
    <div style="margin-top:20px;">
        {{ $riwayats->links() }}
    </div>

</div>

@endsection