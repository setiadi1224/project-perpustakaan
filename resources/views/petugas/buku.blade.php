@extends('petugas.layouts.app')

@section('title', 'Kelola Buku')

@section('content')

<div class="header">
    <h1>Kelola Buku</h1>

    <div class="actions">
        <input type="text" id="search" placeholder="Search...">
        <button type="button" class="btn" onclick="openModal()">+ Tambah</button>
    </div>
</div>

{{-- ALERT --}}
@if(session('success'))
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

        <tbody id="tableBody">
            @foreach ($bukus as $b)
            <tr>
                <td>
                    @if($b->cover)
                        <img src="{{ asset('storage/'.$b->cover) }}" 
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

                        <button type="button" class="edit"
                            onclick='editData(@json($b))'>
                            ✏️ Edit
                        </button>

                        <form action="{{ route('petugas.buku.delete', $b->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="hapus" onclick="return confirm('Hapus buku ini?')">
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

{{-- 🔥 MODAL (1 SAJA) --}}
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
            @foreach($kategoris as $k)
                <option value="{{ $k->id }}">{{ $k->nama }}</option>
            @endforeach
        </select>

        <textarea name="deskripsi" id="deskripsi" placeholder="Deskripsi"></textarea>

        <input type="number" name="stok" id="stok" placeholder="Stok" required>

        <input type="file" name="cover">

        <div class="form-actions">
            <button type="submit" class="btn">Simpan</button>
            <button type="button" onclick="closeModal()">Batal</button>
        </div>
    </form>
</div>

@endsection
@section('script')
<script>

// ================= TAMBAH =================
function openModal() {
    document.getElementById('modal').style.display = 'flex';

    document.getElementById('form').action = "{{ route('petugas.buku.store') }}";
    document.getElementById('method').value = '';

    document.getElementById('title').innerText = 'Tambah Buku';

    clearForm();
}

// ================= EDIT =================
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

// ================= CLEAR =================
function clearForm() {
    document.getElementById('judul').value = '';
    document.getElementById('penulis').value = '';
    document.getElementById('penerbit').value = '';
    document.getElementById('tahun_terbit').value = '';
    document.getElementById('kategori_id').value = '';
    document.getElementById('deskripsi').value = '';
    document.getElementById('stok').value = '';
}

// ================= CLOSE =================
function closeModal() {
    document.getElementById('modal').style.display = 'none';
}

// klik luar modal
window.onclick = function(e) {
    let modal = document.getElementById('modal');
    if (e.target === modal) {
        modal.style.display = 'none';
    }
}

// ================= SEARCH =================
document.getElementById('search').addEventListener('keyup', function() {
    let value = this.value.toLowerCase();
    let rows = document.querySelectorAll('#tableBody tr');

    rows.forEach(row => {
        row.style.display = row.innerText.toLowerCase().includes(value) ? '' : 'none';
    });
});

</script>
@endsection