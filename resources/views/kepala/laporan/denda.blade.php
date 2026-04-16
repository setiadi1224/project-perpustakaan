@extends('kepala.layouts.app')

@section('title', 'Laporan Denda')

@section('content')

 <style>
        .container {
            max-width: 900px;
            margin: 20px auto;
            font-family: Arial, sans-serif;
        }

        .judul {
            text-align: center;
            margin-bottom: 20px;
        }

        form.filter-form {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
            align-items: flex-end;
        }

        form.filter-form label {
            font-weight: bold;
        }

        form.filter-form select {
            padding: 5px 10px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }

        form.filter-form button,
        form.filter-form a {
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            text-decoration: none;
            cursor: pointer;
        }

        form.filter-form button {
            background-color: #007bff;
            color: white;
        }

        form.filter-form a {
            background-color: #ffc107;
            color: black;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        table th,
        table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        table th {
            background-color: #f4f4f4;
        }

        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .badge {
            padding: 3px 8px;
            border-radius: 4px;
            color: white;
            font-size: 0.85em;
        }

        .badge-warning {
            background-color: orange;
        }

        .badge-success {
            background-color: green;
        }

        .badge-Ditolak {
            background-color: rgb(255, 0, 0);
        }

        .badge-secondary {
            background-color: gray;
        }

        .pagination {
            display: flex;
            list-style: none;
            gap: 5px;
            justify-content: center;
            padding: 0;
        }

        .pagination li {
            display: inline;
        }

        .pagination li a,
        .pagination li span {
            padding: 5px 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            text-decoration: none;
            color: black;
        }

        .pagination li.active span {
            background-color: #007bff;
            color: white;
            border-color: #007bff;
        }
    </style>

    <form method="GET" action="{{ route('kepala.laporan.denda') }}" class="filter-form"">
        <input type="text" name="nama" value="{{ request('nama') }}" placeholder="Cari nama">

        <input type="text" name="buku" value="{{ request('buku') }}" placeholder="Cari buku">

        <select name="status">
            <option value="">Semua Status</option>
            <option value="belum" {{ request('status') == 'belum' ? 'selected' : '' }}>Belum</option>
            <option value="lunas" {{ request('status') == 'lunas' ? 'selected' : '' }}>Lunas</option>
        </select>

        <button type="submit">Filter</button>

        <a href="{{ route('kepala.laporan.cetak_denda', request()->all()) }}" target="_blank">
            Cetak PDF
        </a>
    </form>
    <div class="card">
        <table class="table-modern">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Buku</th>
                    <th>Total Denda</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>
                @forelse($data as $d)
                    <tr>
                        <td>{{ $d->user->name }}</td>
                        <td>{{ $d->buku->judul }}</td>
                        <td>
                            <strong style="color:#ef4444;">
                                Rp {{ number_format($d->denda, 0, ',', '.') }}
                            </strong>
                        </td>
                        <td>
                            @if ($d->status_pembayaran == 'belum')
                                <span class="badge red">Belum Bayar</span>
                            @elseif($d->status_pembayaran == 'menunggu')
                                <span class="badge orange">Menunggu</span>
                            @else
                                <span class="badge green">Lunas</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align:center;">
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

        {{-- PAGINATION --}}
        <div class="pagination">
            {{ $data->links() }}
        </div>

    </div>

@endsection
