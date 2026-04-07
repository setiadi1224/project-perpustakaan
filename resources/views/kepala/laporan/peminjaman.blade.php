@extends('kepala.layouts.app')

@section('title', 'Laporan Peminjaman')

@section('content')

<div class="card">
<div style="margin-bottom:15px;">
    <a href="{{ route('kepala.laporan.peminjaman.cetak') }}" target="_blank"
        style="background:#2563eb;color:white;padding:8px 15px;border-radius:8px;text-decoration:none;">
         Cetak PDF
    </a>
</div>
    <table class="table-modern">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Buku</th>
                <th>Tanggal</th>
                <th>Status</th>
            </tr>
        </thead>

        <tbody>
            @forelse($data as $item)
            <tr>
                <td>{{ $item->user->name }}</td>
                <td>{{ $item->buku->judul }}</td>
                <td>{{ $item->tanggal_pinjam }}</td>
                <td>
                    @if($item->status == 'dipinjam')
                        <span class="badge orange">Dipinjam</span>
                    @elseif($item->status == 'dikembalikan')
                        <span class="badge green">Selesai</span>
                    @else
                        <span class="badge red">Menunggu</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" style="text-align:center;">Tidak ada data</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div>
{{ $data->links() }}
    </div>
</div>

@endsection