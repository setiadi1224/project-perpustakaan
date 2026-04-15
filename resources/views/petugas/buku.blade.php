@extends('petugas.layouts.app')

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

        th,
        td {
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

        .modal {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
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
        <h3>Kelola Buku</h3>
        <div class="actions">
            <form method="GET">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search...">
            </form>
            <button class="btn" onclick="openModal()">Tambah</button>
        </div>
    </div>

    @if (session('success'))
        <div style="background:#dcfce7;padding:10px;border-radius:8px;margin-bottom:10px;">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div style="background:#fee2e2;padding:10px;border-radius:8px;margin-bottom:10px;">
            {{ session('error') }}
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
                                <button class="edit" onclick='editData(@json($b))'>Edit</button>

                                @php
                                    $dipinjam = \App\Models\Peminjaman::where('buku_id', $b->id)
                                        ->where('status', 'dipinjam')
                                        ->count();
                                @endphp

                                @if ($dipinjam > 0)
                                    <button class="hapus" style="background:#9ca3af;cursor:not-allowed;" disabled>
                                        Dipinjam
                                    </button>
                                @else
                                    <form action="{{ route('petugas.buku.delete', $b->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="hapus" onclick="return confirm('Hapus buku ini?')">
                                            Delete
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

        <div style="margin-top:10px;font-size:13px;color:#64748b;">
            Menampilkan {{ $bukus->firstItem() ?? 0 }} - {{ $bukus->lastItem() ?? 0 }}
            dari {{ $bukus->total() }} data
        </div>
    </div>

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
            <input type="number" name="tahun_terbit" id="tahun_terbit" placeholder="Tahun">

            <select name="kategori_id" id="kategori_id" required>
                <option value="">-- Pilih Kategori --</option>
                @foreach ($kategoris as $k)
                    <option value="{{ $k->id }}">{{ $k->nama }}</option>
                @endforeach
            </select>

            <textarea name="deskripsi" id="deskripsi" placeholder="Deskripsi"></textarea>
            <input type="number" name="stok" id="stok" placeholder="Stok" required>
            <input type="file" name="cover">

            <div style="display:flex;gap:10px;">
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

            judul.value = data.judul;
            penulis.value = data.penulis;
            penerbit.value = data.penerbit ?? '';
            tahun_terbit.value = data.tahun_terbit ?? '';
            kategori_id.value = data.kategori_id;
            deskripsi.value = data.deskripsi ?? '';
            stok.value = data.stok;
        }

        function clearForm() {
            judul.value = '';
            penulis.value = '';
            penerbit.value = '';
            tahun_terbit.value = '';
            kategori_id.value = '';
            deskripsi.value = '';
            stok.value = '';
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
