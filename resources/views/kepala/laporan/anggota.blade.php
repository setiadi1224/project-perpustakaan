@extends('kepala.layouts.app')

@section('title','Laporan Anggota')

@section('content')


{{-- 🔥 TABLE --}}
<div class="card">
    <table class="table-modern">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Email</th>
                <th>No HP</th>
                <th>Status</th>
            </tr>
        </thead>

        <tbody>
            @forelse($data as $d)
            <tr>
                <td>{{ $d->name }}</td>
                <td>{{ $d->email }}</td>
                <td>{{ $d->no_telepon ?? '-' }}</td>
                <td>
                    <span class="badge green">Aktif</span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" style="text-align:center;">
                    Tidak ada data anggota
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