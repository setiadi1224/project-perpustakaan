<!DOCTYPE html>
<html>

<head>
    <title>Laporan Peminjaman - {{ $bulan }}/{{ $tahun }}</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        h2 {
            text-align: center;
            margin-bottom: 5px;
        }

        .info {
            margin-bottom: 10px;
        }

        .info span {
            display: inline-block;
            margin-right: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
        }

        th {
            background: #eee;
            text-align: center;
        }

        td {
            vertical-align: middle;
        }

        .text-center {
            text-align: center;
        }
    </style>
</head>

<body>

    <h2>Laporan Peminjaman Buku</h2>

    <div class="info">
        <span><strong>Bulan:</strong> {{ DateTime::createFromFormat('!m', $bulan)->format('F') }} {{ $tahun }}</span>

        @if(request('nama'))
            <span><strong>Nama:</strong> {{ request('nama') }}</span>
        @endif

        @if(request('buku'))
            <span><strong>Buku:</strong> {{ request('buku') }}</span>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Nama</th>
                <th>Buku</th>
                <th width="20%">Tanggal Pinjam</th>
                <th width="15%">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $i => $d)
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td>{{ $d->user->name ?? '-' }}</td>
                    <td>{{ $d->buku->judul ?? '-' }}</td>
                    <td class="text-center">
                        {{ \Carbon\Carbon::parse($d->tanggal_pinjam)->format('d-m-Y') }}
                    </td>
                    <td class="text-center">
                        @if ($d->status == 'dipinjam')
                            Dipinjam
                        @elseif($d->status == 'dikembalikan')
                            Selesai
                        @elseif($d->status == 'ditolak')
                            Ditolak
                        @else
                            Menunggu
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>

</html>