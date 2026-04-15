@extends('petugas.layouts.app')

@section('content')
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            overflow-x: hidden;
        }

        body.modal-open {
            overflow: hidden;
        }

        /* ================= HEADER ================= */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        /* ACTION */
        .actions {
            display: flex;
            gap: 10px;
        }

        .actions input {
            padding: 8px 12px;
            border-radius: 8px;
            border: 1px solid #ddd;
        }

        /* ================= TABLE ================= */
        .table-box {
            background: #fff;
            padding: 20px;
            border-radius: 16px;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 600px;
        }

        th,
        td {
            padding: 12px;
            border-top: 1px solid #e5e7eb;
            text-align: left;
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

        /* BUTTON */
        .btn {
            background: #004cff;
            color: white;
            padding: 10px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
        }

        /* ================= MODAL FIX TOTAL ================= */
        .modal {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 999;
            background: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
            padding: 15px;
            overflow-y: auto;
        }

        .modal form {
            background: white;
            padding: 20px;
            border-radius: 12px;
            width: 100%;
            max-width: 400px;
            max-height: 90vh;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .modal input {
            width: 100%;
            padding: 10px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
        }

        /* ================= PAGINATION ================= */
        .pagination-wrapper {
            margin-top: 15px;
            display: flex;
            justify-content: center;
        }

        /* ================= RESPONSIVE ================= */

        /* Tablet */
        @media (max-width: 1024px) {
            .header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
        }

        /* Mobile */
        @media (max-width: 768px) {

            .header {
                flex-direction: column;
                align-items: stretch;
            }

            .actions {
                flex-direction: column;
                width: 100%;
            }

            .actions input {
                width: 100%;
            }

            .btn {
                width: 100%;
                text-align: center;
            }

            .action-group {
                flex-direction: column;
            }

            .edit,
            .hapus {
                width: 100%;
            }

            table {
                min-width: 100%;
            }

            thead {
                display: none;
                /* sembunyikan header */
            }

            tbody tr {
                display: block;
                background: #fff;
                margin-bottom: 10px;
                border-radius: 10px;
                padding: 10px;
            }

            tbody td {
                display: flex;
                justify-content: space-between;
                padding: 6px 0;
                border: none;
            }

            tbody td::before {
                font-weight: bold;
            }

            tbody td:nth-child(1)::before {
                content: "Nama";
            }

            tbody td:nth-child(2)::before {
                content: "Email";
            }

            tbody td:nth-child(3)::before {
                content: "No HP";
            }

            tbody td:nth-child(4)::before {
                content: "Alamat";
            }

            tbody td:nth-child(5)::before {
                content: "Aksi";
            }

            .action-group {
                flex-direction: row;
                gap: 5px;
            }
        }
    </style>

    <div class="header">
        <h3>Kelola Anggota</h3>

        <div class="actions">
            <form method="GET">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search...">
            </form>

            <button type="button" class="btn" onclick="openModal()">Tambah</button>
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

    <div class="pagination-wrapper">
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
            <input type="password" name="password" id="password" placeholder="Password">

            <button type="submit" class="btn">Simpan</button>
            <button type="button" onclick="closeModal()" class="btn" style="background:#ccc; color:#000;">Batal</button>
        </form>
    </div>
@endsection

@section('script')
    <script>
        const modal = document.getElementById('modal');
        const form = document.getElementById('form');
        const method = document.getElementById('method');
        const title = document.getElementById('title');

        const name = document.getElementById('name');
        const email = document.getElementById('email');
        const no_telepon = document.getElementById('no_telepon');
        const alamat = document.getElementById('alamat');
        const password = document.getElementById('password');

        function openModal() {
            modal.style.display = 'flex';
            document.body.classList.add('modal-open'); // 🔥 kunci body

            form.action = "{{ route('petugas.anggota.store') }}";
            method.value = '';
            title.innerText = 'Tambah Anggota';

            name.value = '';
            email.value = '';
            no_telepon.value = '';
            alamat.value = '';
            password.value = '';
        }

        function editData(data) {
            modal.style.display = 'flex';
            document.body.classList.add('modal-open');

            form.action = "/dashboard/petugas/anggota/update/" + data.id;
            method.value = 'PUT';
            title.innerText = 'Edit Anggota';

            name.value = data.name;
            email.value = data.email;
            no_telepon.value = data.no_telepon ?? '';
            alamat.value = data.alamat ?? '';
            password.value = '';
        }

        function closeModal() {
            modal.style.display = 'none';
            document.body.classList.remove('modal-open'); // 🔥 buka scroll lagi
        }

        window.onclick = function(e) {
            if (e.target === modal) {
                closeModal();
            }
        }
    </script>
@endsection
