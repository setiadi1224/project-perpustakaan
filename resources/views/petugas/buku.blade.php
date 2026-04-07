@extends('petugas.layouts.app')

@section('title', 'Kelola Buku')

@section('content')

<style>
.header {
    display: flex;
    justify-content: space-between;
    margin-bottom: 20px;
}

.actions {
    display: flex;
    gap: 10px;
}

.actions input {
    padding: 8px 12px;
    border-radius: 8px;
    border: 1px solid #ddd;
}

.table-box {
    background: #fff;
    padding: 20px;
    border-radius: 14px;
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
    background: #f8fafc;
    color: #64748b;
    text-align: left;
}

tr {
    border-bottom: 1px solid #eee;
}

.action-group {
    display: flex;
    gap: 8px;
}

.edit {
    background: #fef9c3;
    color: #ca8a04;
    border: none;
    padding: 5px 10px;
    border-radius: 999px;
    cursor: pointer;
}

.hapus {
    background: #ff2828;
    color: #ffffff;
    border: none;
    padding: 5px 10px;
    border-radius: 999px;
    cursor: pointer;
}

.btn {
    background: #2563eb;
    color: white;
    border: none;
    padding: 8px 14px;
    border-radius: 8px;
    cursor: pointer;
}

/* PAGINATION */
.pagination-wrapper {
    display: flex;
    justify-content: center;
    margin-top: 25px;
}

.pagination {
    display: flex;
    gap: 8px;
    align-items: center;
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
    font-size: 13px;
    font-weight: 500;
    text-decoration: none;
    border: 1px solid #e2e8f0;
    background: #ffffff;
    color: #334155;
    transition: all 0.2s ease;
}

/* hover effect */
.pagination a:hover {
    background: #2563eb;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 6px 14px rgba(37, 99, 235, 0.25);
}

/* active */
.pagination .active span {
    background: linear-gradient(135deg, #2563eb, #1e40af);
    color: white;
    border: none;
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.4);
}

/* disabled */
.pagination .disabled span {
    background: #f1f5f9;
    color: #94a3b8;
    cursor: not-allowed;
}

/* prev next icon */
.pagination li:first-child a,
.pagination li:last-child a {
    font-weight: bold;
}
/* MODAL */
.modal {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.5);
    justify-content: center;
    align-items: center;
}

.modal form {
    background: white;
    padding: 20px;
    border-radius: 12px;
    width: 400px;
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.modal input,
.modal select,
.modal textarea {
    padding: 10px;
    border-radius: 8px;
    border: 1px solid #ddd;
}
</style>

<div class="header">
    <h1>Kelola Buku</h1>

    <div class="actions">
        {{-- 🔥 FIX SEARCH --}}
        <form method="GET" action="{{ url()->current() }}">
            <input 
                type="text" 
                name="search" 
                value="{{ request('search') }}"
                placeholder="Search...">
        </form>

        <button type="button" class="btn" onclick="openModal()">+ Tambah</button>
    </div>
</div>

@if (session('success'))
    <div style="background:#dcfce7;padding:10px;border-radius:8px;margin-bottom:10px;">
        {{ session('success') }}
    </div>
@endif

<div class="table-box">
    <table>
        <thead>
            <tr>
                <th>Cover</th>
                <th>Judul</th>
                <th>Penulis</th>
                <th>Kategori</th>
                <th>Stok</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($bukus as $b)
                <tr>
                    <td>
                        @if ($b->cover)
                            <img src="{{ asset('storage/' . $b->cover) }}"
                                style="width:40px;height:55px;border-radius:6px;object-fit:cover;">
                        @else
                            -
                        @endif
                    </td>

                    <td>{{ $b->judul }}</td>
                    <td>{{ $b->penulis }}</td>
                    <td>{{ $b->kategori->nama ?? '-' }}</td>
                    <td>{{ $b->stok }}</td>

                    <td>
                        <div class="action-group">
                            <button type="button" class="edit" onclick='editData(@json($b))'>
                                 Edit
                            </button>

                            <form action="{{ route('petugas.buku.delete', $b->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="hapus" onclick="return confirm('Hapus buku ini?')">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align:center;">Tidak ada data buku</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- INFO --}}
    <div style="margin-top:10px; font-size:13px; color:#64748b;">
        Menampilkan {{ $bukus->firstItem() ?? 0 }} - {{ $bukus->lastItem() ?? 0 }} 
        dari {{ $bukus->total() }} data
    </div>
</div>

{{-- 🔥 PAGINATION (FIX UTAMA) --}}
<div class="pagination-wrapper">
    {{ $bukus->links() }}
</div>

{{-- MODAL --}}
<div id="modal" class="modal">
    <form method="POST" id="form" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="_method" id="method">

        <h3 id="title">Tambah Buku</h3>

        <input type="text" name="judul" id="judul" placeholder="Judul" required>
        <input type="text" name="penulis" id="penulis" placeholder="Penulis" required>
        <input type="text" name="penerbit" id="penerbit" placeholder="Penerbit">
        <input type="number" name="tahun_terbit" id="tahun_terbit" placeholder="Tahun Terbit">

        <select name="kategori_id" id="kategori_id" required>
            <option value="">-- Pilih Kategori --</option>
            @foreach ($kategoris as $k)
                <option value="{{ $k->id }}">{{ $k->nama }}</option>
            @endforeach
        </select>

        <textarea name="deskripsi" id="deskripsi" placeholder="Deskripsi"></textarea>

        <input type="number" name="stok" id="stok" placeholder="Stok" required>

        <input type="file" name="cover">

        <div style="display:flex; gap:10px;">
            <button type="submit" class="btn">Simpan</button>
            <button type="button" onclick="closeModal()">Batal</button>
        </div>
    </form>
</div>

@endsection

@section('script')
<script>
function openModal() {
    document.getElementById('modal').style.display = 'flex';
    document.getElementById('form').action = "{{ route('petugas.buku.store') }}";
    document.getElementById('method').value = '';
    document.getElementById('title').innerText = 'Tambah Buku';
    clearForm();
}

function editData(data) {
    document.getElementById('modal').style.display = 'flex';
    document.getElementById('form').action = "/dashboard/petugas/buku/update/" + data.id;
    document.getElementById('method').value = 'PUT';
    document.getElementById('title').innerText = 'Edit Buku';

    document.getElementById('judul').value = data.judul;
    document.getElementById('penulis').value = data.penulis;
    document.getElementById('penerbit').value = data.penerbit ?? '';
    document.getElementById('tahun_terbit').value = data.tahun_terbit ?? '';
    document.getElementById('kategori_id').value = data.kategori_id;
    document.getElementById('deskripsi').value = data.deskripsi ?? '';
    document.getElementById('stok').value = data.stok;
}

function clearForm() {
    document.getElementById('judul').value = '';
    document.getElementById('penulis').value = '';
    document.getElementById('penerbit').value = '';
    document.getElementById('tahun_terbit').value = '';
    document.getElementById('kategori_id').value = '';
    document.getElementById('deskripsi').value = '';
    document.getElementById('stok').value = '';
}

function closeModal() {
    document.getElementById('modal').style.display = 'none';
}

window.onclick = function(e) {
    let modal = document.getElementById('modal');
    if (e.target === modal) {
        modal.style.display = 'none';
    }
}
</script>
@endsection