@extends('petugas.layouts.app')

@section('content')
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            overflow-x: hidden;
        }

        .page-title {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .table-box {
            background: #fff;
            padding: 20px;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
        }

        .header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }

        .header input {
            padding: 8px 14px;
            border-radius: 20px;
            border: 1px solid #ddd;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 12px;
            font-size: 14px;
        }

        th {
            text-align: left;
            border-bottom: 1px solid #eee;
            color: #6B7280;
        }

        .badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            color: #fff;
        }

        .waiting {
            background: orange;
        }

        .btn {
            padding: 6px 10px;
            border-radius: 8px;
            border: none;
            font-size: 12px;
            cursor: pointer;
        }

        .btn.green {
            background: #10b981;
            color: white;
        }

        .btn.red {
            background: #ef4444;
            color: white;
        }

        .action-group {
            display: flex;
            gap: 6px;
        }

        .pagination-wrapper {
            margin-top: 15px;
            display: flex;
            justify-content: center;
        }

        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                gap: 10px;
            }

            .header input {
                width: 100%;
            }

            table,
            thead,
            tbody,
            tr,
            td,
            th {
                display: block;
                width: 100%;
            }

            thead {
                display: none;
            }

            tr {
                background: #fff;
                margin-bottom: 12px;
                padding: 12px;
                border-radius: 12px;
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            }

            td {
                padding: 6px 0;
                border: none;
            }

            td::before {
                content: attr(data-label);
                font-weight: bold;
                font-size: 12px;
                color: #6b7280;
                display: block;
                margin-bottom: 2px;
            }

            .action-group {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }
        }
    </style>

    <h4 class="page-title">Pengembalian Buku</h4>

    <div class="table-box">

        {{-- SEARCH --}}
        <div class="header">
            <form method="GET">
                <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Search...">
            </form>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Buku</th>
                    <th>Tgl Pinjam</th>
                    <th>Tgl Kembali</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($data as $item)
                    <tr>
                        <td data-label="Nama">{{ $item->user->name }}</td>
                        <td data-label="Buku">{{ $item->buku->judul }}</td>

                        <td data-label="Tgl Pinjam">
                            {{ \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d M Y') }}
                        </td>

                        <td data-label="Tgl Kembali">
                            {{ \Carbon\Carbon::parse($item->tanggal_kembali)->format('d M Y') }}
                        </td>

                        <td data-label="Status">
                            <span class="badge waiting">Menunggu Konfirmasi</span>
                        </td>

                        <td data-label="Aksi">
                            <div class="action-group">

                                {{-- APPROVE --}}
                                <form action="{{ route('petugas.pengembalian.approve', $item->id) }}" method="POST">
                                    @csrf
                                    <button class="btn green">Approve</button>
                                </form>

                            </div>
                        </td>
                    </tr>

                @empty
                    <tr>
                        <td colspan="6" style="text-align:center;">Tidak ada data</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div style="margin-top:10px; font-size:13px; color:#64748b;">
            Menampilkan {{ $data->firstItem() ?? 0 }} - {{ $data->lastItem() ?? 0 }}
            dari {{ $data->total() }} data
        </div>

    </div>

    <div class="pagination-wrapper">
        {{ $data->links() }}
    </div>

    {{-- SEARCH AUTO --}}
    <script>
        let timeout = null;

        document.getElementById('search').addEventListener('keyup', function() {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                this.form.submit();
            }, 500);
        });
    </script>
@endsection
