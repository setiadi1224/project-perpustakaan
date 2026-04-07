@extends('petugas.layouts.app')

@section('content')

<style>
.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.actions {
    display: flex;
    gap: 10px;
}

.table-box {
    background: #fff;
    padding: 20px;
    border-radius: 16px;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th, td {
    padding: 12px;
    border-top: 1px solid #e5e7eb;
}

.action-group {
    display: flex;
    gap: 5px;
}

.edit {
    background: #7aabfb;
    color: white;
    border: none;
    padding: 5px 10px;
    border-radius: 10px;
    cursor: pointer;
}

.hapus {
    background: #ef4444;
    color: white;
    border: none;
    padding: 5px 10px;
    border-radius: 10px;
    cursor: pointer;
}

.btn {
    background: #10b981;
    color: white;
    padding: 8px 12px;
    border-radius: 8px;
    border: none;
    cursor: pointer;
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
    width: 350px;
}

.modal input {
    width: 100%;
    padding: 10px;
    margin-top: 10px;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
}
</style>

<div class="header">
    <h1>Kelola Anggota</h1>

    <div class="actions">
        <form method="GET">
            <input 
                type="text" 
                name="search" 
                value="{{ request('search') }}"
                placeholder="Search...">
        </form>

        <button type="button" class="btn" onclick="openModal()">+ Tambah</button>
    </div>
</div>

<div class="table-box">
    <table>
        <thead>
            <tr>
                <th>Nama</th>
                <th>Email</th>
                <th>No HP</th>
                <th>Alamat</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($anggotas as $a)
                <tr>
                    <td>{{ $a->name }}</td>
                    <td>{{ $a->email }}</td>
                    <td>{{ $a->no_telepon }}</td>
                    <td>{{ $a->alamat }}</td>
                    <td>
                        <div class="action-group">
                            <button type="button" class="edit" onclick='editData(@json($a))'>
                                Edit
                            </button>

                            <form action="{{ route('petugas.anggota.delete', $a->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="hapus">
                                     Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align:center;">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top:10px; font-size:13px;">
        Menampilkan {{ $anggotas->firstItem() ?? 0 }} - {{ $anggotas->lastItem() ?? 0 }} 
        dari {{ $anggotas->total() }} data
    </div>
</div>

<div style="margin-top:20px;">
    {{ $anggotas->links() }}
</div>

{{-- MODAL --}}
<div id="modal" class="modal">
    <form method="POST" id="form">
        @csrf
        <input type="hidden" name="_method" id="method">

        <h3 id="title">Tambah Anggota</h3>

        <input type="text" name="name" id="name" placeholder="Nama" required>
        <input type="email" name="email" id="email" placeholder="Email" required>
        <input type="text" name="no_telepon" id="no_telepon" placeholder="No HP">
        <input type="text" name="alamat" id="alamat" placeholder="Alamat">

        {{-- 🔥 PASSWORD --}}
        <input type="password" name="password" id="password" placeholder="Password (kosongkan jika tidak diubah)">

        <br>

        <button type="submit" class="btn">Simpan</button>
        <button type="button" onclick="closeModal()">Batal</button>
    </form>
</div>

@endsection

@section('script')
<script>
function openModal() {
    document.getElementById('modal').style.display = 'flex';

    document.getElementById('form').action = "{{ route('petugas.anggota.store') }}";
    document.getElementById('method').value = '';
    document.getElementById('title').innerText = 'Tambah Anggota';

    document.getElementById('password').style.display = 'block';

    document.getElementById('name').value = '';
    document.getElementById('email').value = '';
    document.getElementById('no_telepon').value = '';
    document.getElementById('alamat').value = '';
    document.getElementById('password').value = '';
}

function editData(data) {
    document.getElementById('modal').style.display = 'flex';

    document.getElementById('form').action = "/dashboard/petugas/anggota/update/" + data.id;
    document.getElementById('method').value = 'PUT';
    document.getElementById('title').innerText = 'Edit Anggota';

    // 🔥 PASSWORD TETAP MUNCUL
    document.getElementById('password').style.display = 'block';
    document.getElementById('password').value = '';

    document.getElementById('name').value = data.name;
    document.getElementById('email').value = data.email;
    document.getElementById('no_telepon').value = data.no_telepon ?? '';
    document.getElementById('alamat').value = data.alamat ?? '';
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