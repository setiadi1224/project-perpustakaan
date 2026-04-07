<!DOCTYPE html>
<html>
<head>
    <title>Laporan Peminjaman</title>
    <style>
        body { font-family: sans-serif; }
        h2 { text-align:center; }

        table {
            width:100%;
            border-collapse: collapse;
            margin-top:20px;
        }

        th, td {
            border:1px solid #000;
            padding:8px;
            font-size:12px;
        }

        th {
            background:#eee;
        }
    </style>
</head>
<body>

<h2>LAPORAN PEMINJAMAN BUKU</h2>

<table>
    <tr>
        <th>No</th>
        <th>Nama</th>
        <th>Buku</th>
        <th>Tanggal Pinjam</th>
        <th>Status</th>
    </tr>

    @foreach($data as $i => $d)
    <tr>
        <td>{{ $i+1 }}</td>
        <td>{{ $d->user->name }}</td>
        <td>{{ $d->buku->judul }}</td>
        <td>{{ $d->tanggal_pinjam }}</td>
        <td>{{ $d->status }}</td>
    </tr>
    @endforeach
</table>

</body>
</html>