<!DOCTYPE html>
<html>
<head>
    <title>Laporan Peminjaman - {{ $bulan }}/{{ $tahun }}</title>
    <style>
        body { font-family:sans-serif; font-size:12px; }
        table { width:100%; border-collapse:collapse; }
        th, td { border:1px solid #000; padding:5px; }
        th { background:#eee; }
        h2 { text-align:center; }
    </style>
</head>
<body>
<h2>Laporan Peminjaman Buku</h2>
<p>Bulan: {{ DateTime::createFromFormat('!m',$bulan)->format('F') }} {{ $tahun }}</p>

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
            <td>{{ $i+1 }}</td>
            <td>{{ $d->user->name }}</td>
            <td>{{ $d->buku->judul }}</td>
            <td>{{ \Carbon\Carbon::parse($d->tanggal_pinjam)->format('d-m-Y') }}</td>
            <td>{{ ucfirst($d->status) }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="5" style="text-align:center;">Tidak ada data</td>
        </tr>
        @endforelse
    </tbody>
</table>
</body>
</html>