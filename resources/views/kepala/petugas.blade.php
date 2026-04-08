@extends('kepala.layouts.app')

@section('title', 'Kelola Petugas')

@section('content')

    <style>
        .page-title {
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 20px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .search {
            padding: 10px 16px;
            border-radius: 999px;
            border: 1px solid #ddd;
            width: 250px;
        }
        .btn-add {
            background: #3b82f6;
            color: white;
            padding: 10px 16px;
            border-radius: 10px;
            border: none;
            cursor: pointer;
        }

        .btn-edit {
            background: #facc15;
            padding: 6px 10px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
        }

        .btn-delete {
            background: #ef4444;
            color: white;
            padding: 6px 10px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
        }
        .table-box {
            background: #fff;
            padding: 20px;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th {
            text-align: left;
            padding: 12px;
            color: #6B7280;
            border-bottom: 1px solid #eee;
        }
        td {
            padding: 12px;
        }
        tr:hover {
            background: #f9fafb;
        }
        .modal {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }
        .modal-content {
            background: white;
            padding: 25px;
            border-radius: 16px;
            width: 380px;
        }
        .modal input,
        .modal textarea {
            width: 100%;
            margin-bottom: 12px;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #ddd;
        }
    </style>
    <div class="header">
        <form method="GET">
            <input type="text" name="search" class="search" placeholder="Cari petugas..." value="{{ request('search') }}">
        </form>
        <button class="btn-add" onclick="openAddModal()">+ Tambah</button>
    </div>
    <div class="table-box">
        <table>
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>No Telepon</th>
                    <th>Alamat</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($petugas as $p)
                    <tr>
                        <td>{{ $p->name }}</td>
                        <td>{{ $p->email }}</td>
                        <td>{{ $p->no_telepon }}</td>
                        <td>{{ $p->alamat }}</td>
                        <td>
                            <button class="btn-edit"
                                onclick="openEditModal(
                        '{{ $p->id }}',
                        '{{ $p->name }}',
                        '{{ $p->email }}',
                        '{{ $p->no_telepon }}',
                        '{{ $p->alamat }}'
                    )">Edit</button>
                            <form action="{{ route('kepala.petugas.delete', $p->id) }}" method="POST"
                                style="display:inline;" onsubmit="return confirm('Yakin hapus?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn-delete">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" align="center">Belum ada petugas</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{-- MODAL TAMBAH --}}
    <div class="modal" id="addModal">
        <div class="modal-content">
            <h3>Tambah Petugas</h3>
            <form method="POST" action="{{ route('kepala.petugas.store') }}">
                @csrf
                <input type="text" name="name" placeholder="Nama" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <input type="text" name="no_telepon" placeholder="No Telepon" required>
                <textarea name="alamat" placeholder="Alamat" required></textarea>
                <button type="submit" class="btn-add">Simpan</button>
                <button type="button" onclick="closeModal()">Batal</button>
            </form>
        </div>
    </div>

    {{-- MODAL EDIT --}}
    <div class="modal" id="editModal">
        <div class="modal-content">
            <h3>Edit Petugas</h3>
            <form method="POST" id="editForm">
                @csrf
                @method('PUT')
                <input type="text" name="name" id="editName" required>
                <input type="email" name="email" id="editEmail" required>
                <input type="password" name="password" placeholder="Password baru (opsional)">
                <input type="text" name="no_telepon" id="editTelp" required>
                <textarea name="alamat" id="editAlamat" required></textarea>
                <button type="submit" class="btn-add">Update</button>
                <button type="button" onclick="closeModal()">Batal</button>
            </form>
        </div>
    </div>
@endsection
@section('script')
    <script>
        function openAddModal() {
            document.getElementById('addModal').style.display = 'flex';
        }

        function openEditModal(id, name, email, telp, alamat) {
            document.getElementById('editModal').style.display = 'flex';
            document.getElementById('editName').value = name;
            document.getElementById('editEmail').value = email;
            document.getElementById('editTelp').value = telp;
            document.getElementById('editAlamat').value = alamat;
            document.getElementById('editForm').action =
                '/dashboard/kepala/petugas/update/' + id;
        }

        function closeModal() {
            document.getElementById('addModal').style.display = 'none';
            document.getElementById('editModal').style.display = 'none';
        }
        window.onclick = function(e) {
            let add = document.getElementById('addModal');
            let edit = document.getElementById('editModal');
            if (e.target === add) add.style.display = 'none';
            if (e.target === edit) edit.style.display = 'none';
        }
    </script>
@endsection