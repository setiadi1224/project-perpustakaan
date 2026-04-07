@extends('kepala.layouts.app')

@section('title','Laporan Denda')

@section('content')

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
                        Rp {{ number_format($d->denda,0,',','.') }}
                    </strong>
                </td>
                <td>
                    @if($d->status_pembayaran == 'belum')
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