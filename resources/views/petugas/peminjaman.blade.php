@extends('petugas.layouts.app')

@section('content')
<style>
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

th, td {
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

.waiting { background: orange; }
.borrowed { background: #3b82f6; }
.late { background: #ef4444; }
.done { background: #10b981; }

.action-group {
    display: flex;
    gap: 6px;
}

.btn {
    padding: 6px 10px;
    border-radius: 8px;
    border: none;
    font-size: 12px;
    cursor: pointer;
}

.btn.green { background: #10b981; color: white; }
.btn.blue { background: #3b82f6; color: white; }
.btn.red { background: #ef4444; color: white; }

/* PAGINATION */
.pagination-wrapper {
    display: flex;
    justify-content: center;
    margin-top: 25px;
}

.pagination {
    display: flex;
    gap: 8px;
}

.pagination li {
    list-style: none;
}

.pagination a,
.pagination span {
    min-width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
    border: 1px solid #e5e7eb;
    background: #fff;
    color: #374151;
    text-decoration: none;
    font-size: 13px;
    transition: 0.2s;
}

.pagination a:hover {
    background: #3b82f6;
    color: white;
    transform: translateY(-2px);
}

.pagination .active span {
    background: #3b82f6;
    color: white;
}

.pagination .disabled span {
    opacity: 0.5;
}
</style>

<h4 class="page-title">Kelola Peminjaman</h4>

<div class="table-box">

    {{-- 🔥 SEARCH REALTIME --}}
    <div class="header">
        <form method="GET" action="{{ url()->current() }}">
            <input 
                type="text" 
                name="search" 
                id="search"
                value="{{ request('search') }}"
                placeholder="Search...">
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
                @php
                    $hari = $item->tanggal_pinjam
                        ? \Carbon\Carbon::parse($item->tanggal_pinjam)->diffInDays(now())
                        : 0;
                    $terlambat = $item->status == 'dipinjam' && $hari > 7;
                @endphp

                <tr>
                    <td>{{ $item->user->name }}</td>
                    <td>{{ $item->buku->judul }}</td>

                    <td>
                        {{ $item->tanggal_pinjam ? \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d M Y') : '-' }}
                    </td>

                    <td>
                        {{ $item->tanggal_kembali ? \Carbon\Carbon::parse($item->tanggal_kembali)->format('d M Y') : '-' }}
                    </td>

                    <td>
                        @if ($item->status == 'menunggu')
                            <span class="badge waiting">Menunggu</span>
                        @elseif($item->status == 'dipinjam' && $terlambat)
                            <span class="badge late">Terlambat</span>
                        @elseif($item->status == 'dipinjam')
                            <span class="badge borrowed">Dipinjam</span>
                        @elseif($item->status == 'dikembalikan')
                            <span class="badge done">Selesai</span>
                        @endif
                    </td>

                    <td>
                        <div class="action-group">
                            @if ($item->status == 'menunggu')
                                <form action="{{ route('petugas.peminjaman.approve', $item->id) }}" method="POST">
                                    @csrf
                                    <button class="btn green" onclick="return confirm('Setujui?')">
                                        Approve
                                    </button>
                                </form>
                            @endif

                            @if ($item->status == 'dipinjam')
                                <form action="{{ route('petugas.peminjaman.return', $item->id) }}" method="POST">
                                    @csrf
                                    <button class="btn blue" onclick="return confirm('Sudah dikembalikan?')">
                                        Return
                                    </button>
                                </form>
                            @endif
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

    {{-- INFO --}}
    <div style="margin-top:10px; font-size:13px; color:#64748b;">
        Menampilkan {{ $data->firstItem() ?? 0 }} - {{ $data->lastItem() ?? 0 }} 
        dari {{ $data->total() }} data
    </div>

</div>

{{-- PAGINATION --}}
<div class="pagination-wrapper">
    {{ $data->links() }}
</div>

{{-- 🔥 SCRIPT SEARCH REALTIME --}}
<script>
let timeout = null;

document.getElementById('search').addEventListener('keyup', function () {
    clearTimeout(timeout);

    timeout = setTimeout(() => {
        this.form.submit();
    }, 500); // delay biar ga reload terus
});
</script>

@endsection