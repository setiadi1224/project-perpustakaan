@extends('kepala.layouts.app')

@section('title', '')

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
    <div class="container">
        <h2 class="judul">Laporan Peminjaman Buku</h2>

        <!-- Filter Bulan & Tahun -->
        <form action="{{ route('kepala.laporan.peminjaman') }}" method="GET" class="filter-form">
            <div>
                <label>Bulan:</label>
                <select name="bulan">
                    @for ($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ $bulan == $m ? 'selected' : '' }}>
                            {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                        </option>
                    @endfor
                </select>
            </div>

            <div>
                <label>Tahun:</label>
                <select name="tahun">
                    @for ($y = date('Y') - 5; $y <= date('Y'); $y++)
                        <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>
                            {{ $y }}
                        </option>
                    @endfor
                </select>
            </div>
            <div>
                <label>Nama:</label>
                <input type="text" name="nama" value="{{ request('nama') }}" placeholder="Cari nama">
            </div>

            <div>
                <label>Buku:</label>
                <input type="text" name="buku" value="{{ request('buku') }}" placeholder="Cari buku">
            </div>

            <div>
                <button type="submit">Filter</button>
                <a href="{{ route('kepala.laporan.peminjaman.cetak', [
                    'bulan' => $bulan,
                    'tahun' => $tahun,
                    'nama' => request('nama'),
                    'buku' => request('buku'),
                ]) }}"
                    target="_blank">
                    Cetak PDF
                </a>
            </div>
        </form>

        <!-- Table -->
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Buku</th>
                    <th>Tanggal Pinjam</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $i => $d)
                    <tr>
                        <td>{{ $data->firstItem() + $i }}</td>
                        <td>{{ $d->user->name }}</td>
                        <td>{{ $d->buku->judul }}</td>
                        <td>{{ \Carbon\Carbon::parse($d->tanggal_pinjam)->format('d-m-Y') }}</td>
                        <td>
                            @if ($d->status == 'dipinjam')
                                <span class="badge badge-warning">Dipinjam</span>
                            @elseif($d->status == 'dikembalikan')
                                <span class="badge badge-success">Selesai</span>
                            @elseif($d->status == 'ditolak')
                                <span class="badge badge-Ditolak">Ditolak</span>
                            @else
                                <span class="badge badge-secondary">Menunggu</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align:center;">Tidak ada data</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        <div>
            {{ $data->links() }}
        </div>
    </div>
@endsection
