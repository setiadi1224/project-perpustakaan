@extends('petugas.layouts.app')

@section('content')
    <div class="header">
        <h1>Kelola Anggota</h1>

        <div class="actions">
            <input type="text" id="search" placeholder="Search...">
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

            <tbody id="tableBody">
                @foreach ($anggotas as $a)
                    <tr>
                        <td>{{ $a->name }}</td>
                        <td>{{ $a->email }}</td>
                        <td>{{ $a->no_telepon }}</td>
                        <td>{{ $a->alamat }}</td>
                        <td>
                            <div class="action-group">
                                <button type="button" class="edit" onclick='editData(@json($a))'>
                                    ✏️ Edit
                                </button>

                                <form action="{{ route('petugas.anggota.delete', $a->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="hapus">
                                        🗑️ Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- MODAL --}}
    <div id="modal" class="modal">
        <form method="POST" id="form">
            @csrf
            <input type="hidden" name="_method" id="method">

            <h3 id="title">Tambah</h3>

            <input type="text" name="name" id="name" placeholder="Nama">
            <input type="email" name="email" id="email" placeholder="Email">
            <input type="text" name="no_telepon" id="no_telepon" placeholder="No HP">
            <input type="text" name="alamat" id="alamat" placeholder="Alamat">
            <input type="password" name="password" id="password" placeholder="Password">

            <button type="submit">Simpan</button>
        </form>
    </div>
@endsection

@section('script')
    <script>
        // TAMBAH
        function openModal() {
            document.getElementById('modal').style.display = 'flex';
            document.getElementById('form').action = "{{ route('petugas.anggota.store') }}";
            document.getElementById('method').value = '';

            document.getElementById('title').innerText = 'Tambah';
            document.getElementById('password').style.display = 'block';

            document.getElementById('name').value = '';
            document.getElementById('email').value = '';
            document.getElementById('no_telepon').value = '';
            document.getElementById('alamat').value = '';
        }

        // EDIT
        function editData(data) {
            document.getElementById('modal').style.display = 'flex';
            document.getElementById('form').action = "/dashboard/petugas/anggota/update/" + data.id;
            document.getElementById('method').value = 'PUT';

            document.getElementById('title').innerText = 'Edit';
            document.getElementById('password').style.display = 'none';

            document.getElementById('name').value = data.name;
            document.getElementById('email').value = data.email;
            document.getElementById('no_telepon').value = data.no_telepon ?? '';
            document.getElementById('alamat').value = data.alamat ?? '';
        }

        // CLOSE MODAL
        window.onclick = function(e) {
            let modal = document.getElementById('modal');
            if (e.target === modal) {
                modal.style.display = 'none';
            }
        }

        // SEARCH REALTIME
        document.getElementById('search').addEventListener('keyup', function() {
            let value = this.value.toLowerCase();
            let rows = document.querySelectorAll('#tableBody tr');

            rows.forEach(row => {
                row.style.display = row.innerText.toLowerCase().includes(value) ? '' : 'none';
            });
        });
    </script>
@endsection
