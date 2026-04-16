<!DOCTYPE html>
<html>
<head>
    <title>Laporan Denda</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #000;
            padding: 6px;
        }

        th {
            background: #eee;
        }
    </style>
</head>
<body>

    <h3 style="text-align:center;">Laporan Denda</h3>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Buku</th>
                <th>Denda</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $i => $d)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $d->user->name }}</td>
                    <td>{{ $d->buku->judul }}</td>
                    <td>Rp {{ number_format($d->denda, 0, ',', '.') }}</td>
                    <td>{{ ucfirst($d->status_pembayaran) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>