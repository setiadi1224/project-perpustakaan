@extends('petugas.layouts.app')

@section('content')

<style>
.page-title {
    font-size: 20px;
    font-weight: 700;
    margin-bottom: 20px;
}

/* CARD */
.card {
    background: #fff;
    padding: 20px;
    border-radius: 16px;
    margin-bottom: 20px;
}

/* INPUT */
.input-group {
    display: flex;
    gap: 10px;
}

.input-group input {
    flex: 1;
    padding: 10px;
    border-radius: 10px;
    border: 1px solid #e5e7eb;
}

.input-group button {
    background: #2563eb;
    color: white;
    border: none;
    padding: 10px 18px;
    border-radius: 10px;
    cursor: pointer;
}

/* TABLE */
table {
    width: 100%;
    border-collapse: collapse;
}

th {
    text-align: left;
    font-size: 14px;
    color: #6b7280;
    padding: 10px;
}

td {
    padding: 12px 10px;
}

tbody tr {
    border-top: 1px solid #e5e7eb;
}

/* BUTTON */
.btn-delete {
    background: #ef4444;
    color: white;
    border: none;
    padding: 6px 12px;
    border-radius: 8px;
    cursor: pointer;
}

/* EMPTY */
.empty {
    text-align: center;
    padding: 20px;
    color: #9ca3af;
}
</style>

<h4 class="page-title">📂 Kelola Kategori</h4>

{{-- FORM TAMBAH --}}
<div class="card">
    <h5>Tambah Kategori</h5>

    <form method="POST" action="{{ route('petugas.kategori.store') }}">
        @csrf
        <div class="input-group">
            <input type="text" name="nama" placeholder="Masukkan nama kategori..." required>
            <button type="submit">Tambah</button>
        </div>
    </form>
</div>

{{-- DATA --}}
<div class="card">
    <h5>Data Kategori</h5>

    <table>
        <thead>
            <tr>
                <th>Nama Kategori</th>
                <th width="120">Aksi</th>
            </tr>
        </thead>

        <tbody>
            @forelse($data as $k)
                <tr>
                    <td>📁 {{ $k->nama }}</td>
                    <td>
                        <form action="{{ route('petugas.kategori.delete',$k->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="btn-delete"
                                onclick="return confirm('Hapus kategori ini?')">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="2" class="empty">
                        Belum ada kategori
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection