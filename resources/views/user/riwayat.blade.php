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

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .search-box {
            width: 250px;
        }

        .search-box input {
            width: 100%;
            padding: 10px;
            border-radius: 12px;
            border: 1px solid #334155;
            background: #1e293b;
            color: #e5e7eb;
        }

        .table-box {
            background: #1e293b;
            padding: 20px;
            border-radius: 16px;
            border: 1px solid #334155;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 14px;
            font-size: 13px;
        }

        th {
            color: #94a3b8;
            border-bottom: 1px solid #334155;
        }

        td {
            border-bottom: 1px solid #243244;
        }

        .status {
            padding: 5px 12px;
            border-radius: 999px;
            font-size: 11px;
        }

        .status.menunggu {
            background: rgba(245, 158, 11, .15);
            color: #fbbf24;
        }

        .status.dipinjam {
            background: rgba(59, 130, 246, .15);
            color: #60a5fa;
        }

        .status.selesai {
            background: rgba(34, 197, 94, .15);
            color: #4ade80;
        }

        .status.terlambat {
            background: rgba(239, 68, 68, .15);
            color: #f87171;
        }

        .status.ditolak {
            background: rgba(107, 114, 128, .15);
            color: #9ca3af;
        }

        .status.konfirmasi {
            background: rgba(168, 85, 247, .15);
            color: #c084fc;
        }

        .btn-return {
            background: #10b981;
            color: white;
            border: none;
            padding: 7px 12px;
            border-radius: 10px;
            cursor: pointer;
            font-size: 12px;
        }
    </style>

    <div class="container">

        <div class="header">
            <h2>Riwayat Peminjaman</h2>

            <div class="search-box">
                <form method="GET">
                    <input type="text" name="search" placeholder="Cari Buku..." value="{{ request('search') }}">
                </form>
            </div>
        </div>

        <div class="table-box">
            <table>
                <thead>
                    <tr>
                        <th>Judul</th>
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
                            $terlambat = false;

                            if ($item->status == 'dipinjam' && $item->tanggal_kembali) {
                                $terlambat = now()->gt(\Carbon\Carbon::parse($item->tanggal_kembali));
                            }
                        @endphp

                        <tr>
                            <td>{{ $item->buku->judul ?? '-' }}</td>

                            <td>{{ $item->tanggal_pinjam ? \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d M Y') : '-' }}
                            </td>

                            <td>{{ $item->tanggal_kembali ? \Carbon\Carbon::parse($item->tanggal_kembali)->format('d M Y') : '-' }}
                            </td>

                            <td>
                                {{-- STATUS PENGEMBALIAN PRIORITAS --}}
                                @if ($item->status_pengembalian == 'menunggu')
                                    <span class="status konfirmasi">Menunggu Konfirmasi</span>
                                @elseif($item->status == 'menunggu')
                                    <span class="status menunggu">Menunggu</span>
                                @elseif($item->status == 'dipinjam' && $terlambat)
                                    <span class="status terlambat">Terlambat</span>
                                @elseif($item->status == 'dipinjam')
                                    <span class="status dipinjam">Dipinjam</span>
                                @elseif($item->status == 'dikembalikan')
                                    <span class="status selesai">Selesai</span>
                                @elseif($item->status == 'ditolak')
                                    <span class="status ditolak">Ditolak</span>
                                @endif
                            </td>

                            <td>
                                @if ($item->denda > 0)
                                    <span style="color:#f87171;font-weight:600;">
                                        Rp {{ number_format($item->denda, 0, ',', '.') }}
                                    </span>
                                @else
                                    -
                                @endif
                            </td>

                            <td>
                                {{-- TOMBOL RETURN --}}
                                @if ($item->status == 'dipinjam' && $item->status_pengembalian != 'menunggu')
                                    <form action="{{ route('user.return', $item->id) }}" method="POST">
                                        @csrf
                                        <button class="btn-return"
                                            onclick="return confirm('Ajukan pengembalian buku ini?')">
                                            Kembalikan
                                        </button>
                                    </form>
                                @elseif($item->status_pengembalian == 'menunggu')
                                    <span style="font-size:12px;color:#c084fc;">
                                        Menunggu petugas
                                    </span>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="6" style="text-align:center;">Tidak ada data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination-wrapper">
            {{ $riwayats->links() }}
        </div>

    </div>

@endsection
