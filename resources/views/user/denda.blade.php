@extends('user.layouts.app')

@section('content')
<style>
.main-content { padding: 20px; }

.card-denda {
    background: linear-gradient(to right, #3b82f6, #1d4ed8);
    color: white;
    padding: 20px;
    border-radius: 12px;
    margin-bottom: 20px;
}

.card-table {
    background: #f9fafb;
    padding: 20px;
    border-radius: 12px;
}

/* TABLE */
table { width: 100%; border-collapse: collapse; }
thead { color: #6b7280; font-size: 14px; }
th, td { padding: 12px; }
tbody tr { border-top: 1px solid #e5e7eb; }

/* BADGE */
.badge {
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 12px;
    color: white;
}
.merah { background: #ef4444; }
.hijau { background: #10b981; }
.orange { background: #f59e0b; }

/* BUTTON */
.btn-bayar {
    background: #3b82f6;
    color: white;
    border: none;
    padding: 6px 12px;
    border-radius: 8px;
    cursor: pointer;
}

/* MODAL */
.modal {
    display: none;
    position: fixed;
    z-index: 999;
    inset: 0;
    background: rgba(0,0,0,0.6);
    backdrop-filter: blur(5px);
}

.modal-content {
    background: white;
    width: 420px;
    max-width: 90%;
    margin: 8% auto;
    padding: 25px;
    border-radius: 16px;
    position: relative;
}

.modal-close {
    position: absolute;
    right: 15px;
    top: 15px;
    cursor: pointer;
}

/* BUTTON MODAL */
.modal-actions {
    display: flex;
    gap: 10px;
    margin-top: 20px;
}

.btn-primary {
    flex: 1;
    background: #3b82f6;
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
                            <span class="badge merah">Belum Bayar</span>
                        @elseif($item->status_pembayaran == 'menunggu')
                            <span class="badge orange">Menunggu</span>
                        @else
                            <span class="badge hijau">Lunas</span>
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
        <h2 id="totalBayar">Rp 0</h2>

        <form id="formBayar" method="POST" enctype="multipart/form-data">
            @csrf

            <input type="file" name="bukti" required>

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
}

function closeModal() {
    document.getElementById('modalBayar').style.display = 'none';
}

function outsideClick(event) {
    if (event.target.id === 'modalBayar') {
        closeModal();
    }
}
</script>

@endsection