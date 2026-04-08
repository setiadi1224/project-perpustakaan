@extends('user.layouts.app')

@section('content')

<style>
.main-content { padding: 25px; }

/* CARD */
.card-denda {
    background: linear-gradient(135deg, #3b82f6, #1e40af);
    color: white;
    padding: 25px;
    border-radius: 16px;
    margin-bottom: 25px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}

.card-denda h2 {
    font-size: 28px;
    margin-top: 10px;
}

/* TABLE */
.card-table {
    background: #ffffff;
    padding: 20px;
    border-radius: 16px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
}

table { width: 100%; border-collapse: collapse; }

thead {
    background: #f3f4f6;
    font-size: 13px;
    text-transform: uppercase;
}

th, td { padding: 14px; }

tbody tr {
    border-top: 1px solid #e5e7eb;
    transition: 0.2s;
}

tbody tr:hover {
    background: #f9fafb;
}

/* BADGE */
.badge {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    color: white;
}
.merah { background: #ef4444; }
.hijau { background: #10b981; }
.orange { background: #f59e0b; }

/* BUTTON */
.btn-bayar {
    background: linear-gradient(to right, #3b82f6, #2563eb);
    color: white;
    border: none;
    padding: 7px 14px;
    border-radius: 10px;
    cursor: pointer;
    font-size: 13px;
    transition: 0.2s;
    box-shadow: 0 4px 10px rgba(59,130,246,0.3);
}

.btn-bayar:hover {
    transform: translateY(-2px);
}

/* MODAL */
.modal {
    display: none;
    position: fixed;
    z-index: 999;
    inset: 0;
    background: rgba(0,0,0,0.5);
    backdrop-filter: blur(6px);
}

.modal-content {
    background: white;
    width: 420px;
    max-width: 90%;
    margin: 6% auto;
    padding: 25px;
    border-radius: 18px;
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from { transform: translateY(20px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}

.modal-close {
    float: right;
    cursor: pointer;
    font-size: 18px;
}

/* INPUT */
select, input[type="file"] {
    width: 100%;
    padding: 10px;
    border-radius: 10px;
    border: 1px solid #e5e7eb;
    margin-top: 5px;
}

/* BUTTON MODAL */
.modal-actions {
    display: flex;
    gap: 10px;
    margin-top: 20px;
}

.btn-primary {
    flex: 1;
    background: linear-gradient(to right, #3b82f6, #2563eb);
    color: white;
    padding: 10px;
    border: none;
    border-radius: 10px;
}

.btn-secondary {
    flex: 1;
    background: #e5e7eb;
    padding: 10px;
    border: none;
    border-radius: 10px;
}
</style>

<div class="main-content">

    @if(session('success'))
        <div style="background:#d1fae5; padding:10px; border-radius:8px; margin-bottom:10px;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="background:#fee2e2; padding:10px; border-radius:8px; margin-bottom:10px;">
            {{ session('error') }}
        </div>
    @endif

    <div class="card-denda">
        <h5>Total Denda Aktif</h5>
        <h2>Rp {{ number_format($totalDenda, 0, ',', '.') }}</h2>
    </div>

    <div class="card-table">
        <table>
            <thead>
                <tr>
                    <th>Judul Buku</th>
                    <th>Terlambat</th>
                    <th>Total Denda</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($denda as $item)
                <tr>
                    <td>{{ $item->buku->judul ?? '-' }}</td>
                    <td>{{ $item->terlambat ?? 0 }} Hari</td>
                    <td>Rp {{ number_format($item->denda, 0, ',', '.') }}</td>

                    <td>
                        @if($item->status_pembayaran == 'belum')
                            <span class="badge merah">Belum</span>
                        @elseif($item->status_pembayaran == 'menunggu')
                            <span class="badge orange"> Menunggu</span>
                        @else
                            <span class="badge hijau"> Lunas</span>
                        @endif
                    </td>

                    <td>
                        @if($item->denda > 0 && $item->status_pembayaran == 'belum')
                            <button class="btn-bayar"
                                onclick="openModal({{ $item->id }}, {{ $item->denda }})">
                                Bayar
                            </button>
                        @elseif($item->status_pembayaran == 'menunggu')
                            <span style="color:orange;">Menunggu verifikasi</span>
                        @else
                            <span style="color:green;">Lunas</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align:center;">Tidak ada denda</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

<!-- MODAL -->
<div id="modalBayar" class="modal" onclick="outsideClick(event)">
    <div class="modal-content">

        <span class="modal-close" onclick="closeModal()">✖</span>

        <h3>Bayar Denda</h3>
        <p style="font-size:13px; color:#6b7280;">
            Pilih metode pembayaran. Jika online, upload bukti pembayaran.
        </p>

        <h2 id="totalBayar">Rp 0</h2>

        <form id="formBayar" method="POST" enctype="multipart/form-data">
            @csrf

            <label>Metode Pembayaran</label>
            <select name="metode" id="metode" onchange="toggleBukti()" required>
                <option value="">-- Pilih Metode --</option>
                <option value="offline">Bayar Offline</option>
                <option value="online">Bayar Online</option>
            </select>

            <div id="buktiField" style="display:none; margin-top:10px;">
                <label>Upload Bukti</label>
                <input type="file" name="bukti" id="bukti">
            </div>

            <div class="modal-actions">
                <button type="submit" class="btn-primary">Kirim</button>
                <button type="button" class="btn-secondary" onclick="closeModal()">Batal</button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal(id, denda) {
    document.getElementById('modalBayar').style.display = 'block';

    document.getElementById('totalBayar').innerText =
        'Rp ' + denda.toLocaleString('id-ID');

    let url = "{{ route('user.bayar', ':id') }}";
    url = url.replace(':id', id);

    document.getElementById('formBayar').action = url;

    document.getElementById('metode').value = "";
    document.getElementById('bukti').value = "";
    document.getElementById('buktiField').style.display = 'none';
}

function closeModal() {
    document.getElementById('modalBayar').style.display = 'none';
}

function outsideClick(event) {
    if (event.target.id === 'modalBayar') {
        closeModal();
    }
}

function toggleBukti() {
    let metode = document.getElementById('metode').value;
    let buktiField = document.getElementById('buktiField');
    let buktiInput = document.getElementById('bukti');

    if (metode === 'online') {
        buktiField.style.display = 'block';
        buktiInput.required = true;
    } else {
        buktiField.style.display = 'none';
        buktiInput.required = false;
        buktiInput.value = "";
    }
}
</script>

@endsection