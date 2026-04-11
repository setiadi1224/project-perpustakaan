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

        /* HEADER */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .header h2 {
            color: #f1f5f9;
        }

        /* SEARCH */
        .search-box input {
            padding: 10px 15px;
            border-radius: 12px;
            border: 1px solid #334155;
            background: #1e293b;
            color: #e5e7eb;
            outline: none;
            transition: 0.2s;
        }

        .search-box input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
        }

        /* TABLE BOX */
        .table-box {
            background: #1e293b;
            padding: 20px;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.35);
            border: 1px solid #334155;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 700px;
        }

        th,
        td {
            padding: 14px;
            text-align: left;
        }

        th {
            color: #94a3b8;
            font-weight: 500;
            border-bottom: 1px solid #334155;
            font-size: 13px;
        }

        td {
            color: #e5e7eb;
            border-bottom: 1px solid #243244;
            font-size: 13px;
        }

        tr:hover {
            background: rgba(59, 130, 246, 0.05);
        }

        /* STATUS */
        .status {
            padding: 5px 12px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 500;
            display: inline-block;
        }

        .status.menunggu {
            background: rgba(245, 158, 11, 0.15);
            color: #fbbf24;
            border: 1px solid rgba(245, 158, 11, 0.3);
        }

        .status.dipinjam {
            background: rgba(59, 130, 246, 0.15);
            color: #60a5fa;
            border: 1px solid rgba(59, 130, 246, 0.3);
        }

        .status.selesai {
            background: rgba(34, 197, 94, 0.15);
            color: #4ade80;
            border: 1px solid rgba(34, 197, 94, 0.3);
        }

        .status.terlambat {
            background: rgba(239, 68, 68, 0.15);
            color: #f87171;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        /* DENDA */
        .denda {
            font-weight: 600;
            color: #f87171;
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
            transition: 0.2s;
        }

        .btn-return:hover {
            background: #059669;
            transform: translateY(-1px);
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
            border: 1px solid #334155;
            background: #1e293b;
            color: #cbd5e1;
            text-decoration: none;
            font-size: 13px;
            transition: 0.2s;
        }

        .pagination a:hover {
            background: #3b82f6;
            color: white;
            border-color: #3b82f6;
        }

        .pagination .active span {
            background: #3b82f6;
            color: white;
            border-color: #3b82f6;
        }

        .pagination .disabled span {
            opacity: 0.4;
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

        {{-- TABLE --}}
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
        </div>

        {{-- PAGINATION --}}
        <div class="pagination-wrapper">
            {{ $riwayats->links() }}
        </div>

    </div>

@endsection
