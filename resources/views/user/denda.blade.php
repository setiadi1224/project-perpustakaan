@extends('user.layouts.app')

@section('content')
    <style>
        body {
            background: #0f172a;
            color: #e5e7eb;
        }

        .main-content {
            padding: 25px;
        }

        /* ALERT */
        .alert {
            padding: 10px 14px;
            border-radius: 10px;
            margin-bottom: 10px;
            font-size: 13px;
        }

        .alert.success {
            background: rgba(34, 197, 94, 0.15);
            color: #4ade80;
            border: 1px solid rgba(34, 197, 94, 0.3);
        }

        .alert.error {
            background: rgba(239, 68, 68, 0.15);
            color: #f87171;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        /* CARD DENDa */
        .card-denda {
            background: linear-gradient(135deg, #1e3a8a, #2563eb);
            color: white;
            padding: 25px;
            border-radius: 18px;
            margin-bottom: 25px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.35);
        }

        .card-denda h5 {
            opacity: 0.9;
            font-weight: 500;
        }

        .card-denda h2 {
            font-size: 30px;
            margin-top: 10px;
        }

        /* TABLE */
        .card-table {
            background: #1e293b;
            padding: 20px;
            border-radius: 18px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.35);
            border: 1px solid #334155;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 700px;
        }

        thead {
            font-size: 12px;
            text-transform: uppercase;
            color: #94a3b8;
        }

        th,
        td {
            padding: 14px;
            text-align: left;
        }

        th {
            border-bottom: 1px solid #334155;
        }

        td {
            border-bottom: 1px solid #243244;
            font-size: 13px;
            color: #e5e7eb;
        }

        tbody tr:hover {
            background: rgba(59, 130, 246, 0.06);
        }

        /* BADGE */
        .badge {
            padding: 5px 12px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 500;
            display: inline-block;
        }

        .merah {
            background: rgba(239, 68, 68, 0.15);
            color: #f87171;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        .hijau {
            background: rgba(34, 197, 94, 0.15);
            color: #4ade80;
            border: 1px solid rgba(34, 197, 94, 0.3);
        }

        .orange {
            background: rgba(245, 158, 11, 0.15);
            color: #fbbf24;
            border: 1px solid rgba(245, 158, 11, 0.3);
        }

        /* BUTTON */
        .btn-bayar {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: white;
            border: none;
            padding: 8px 14px;
            border-radius: 10px;
            cursor: pointer;
            font-size: 12px;
            transition: 0.2s;
            box-shadow: 0 5px 15px rgba(59, 130, 246, 0.25);
        }

        .btn-bayar:hover {
            transform: translateY(-2px);
        }

        /* MODAL */
        .modal {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(6px);
            z-index: 999;
        }

        .modal-content {
            background: #1e293b;
            width: 420px;
            max-width: 90%;
            margin: 6% auto;
            padding: 25px;
            border-radius: 18px;
            border: 1px solid #334155;
            animation: fadeIn 0.25s ease;
            color: #e5e7eb;
        }

        @keyframes fadeIn {
            from {
                transform: translateY(20px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .modal-close {
            float: right;
            cursor: pointer;
            font-size: 18px;
            color: #94a3b8;
        }

        select,
        input[type="file"] {
            width: 100%;
            padding: 10px;
            border-radius: 10px;
            border: 1px solid #334155;
            background: #0f172a;
            color: #e5e7eb;
            margin-top: 5px;
        }

        /* MODAL BUTTON */
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
            background: #334155;
            color: #e5e7eb;
            border: none;
            border-radius: 10px;
        }
    </style>

    <div class="main-content">

        {{-- ALERT --}}
        @if (session('success'))
            <div class="alert success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert error">{{ session('error') }}</div>
        @endif

        {{-- TOTAL CARD --}}
        <div class="card-denda">
            <h5>Total Denda Aktif</h5>
            <h2>Rp {{ number_format($totalDenda, 0, ',', '.') }}</h2>
        </div>

        {{-- TABLE --}}
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
                                @if ($item->status_pembayaran == 'belum')
                                    <span class="badge merah">Belum</span>
                                @elseif($item->status_pembayaran == 'menunggu')
                                    <span class="badge orange">Menunggu</span>
                                @else
                                    <span class="badge hijau">Lunas</span>
                                @endif
                            </td>

                            <td>
                                @if ($item->denda > 0 && $item->status_pembayaran == 'belum')
                                    <button class="btn-bayar" onclick="openModal({{ $item->id }}, {{ $item->denda }})">
                                        Bayar
                                    </button>
                                @elseif($item->status_pembayaran == 'menunggu')
                                    <span style="color:#fbbf24;">Menunggu verifikasi</span>
                                @else
                                    <span style="color:#4ade80;">Lunas</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align:center; color:#94a3b8;">
                                Tidak ada denda
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- MODAL --}}
    <div id="modalBayar" class="modal" onclick="outsideClick(event)">
        <div class="modal-content">
            <span class="modal-close" onclick="closeModal()">✖</span>
            <h3>Bayar Denda</h3>

            <h2 id="totalBayar">Rp 0</h2>

            <form id="formBayar" method="POST" enctype="multipart/form-data">
                @csrf

                <label>Metode</label>
                <select name="metode" id="metode" onchange="toggleBukti()" required>
                    <option value="">-- Pilih --</option>
                    <option value="offline">Offline</option>
                    <option value="online">Online</option>
                </select>

                <div id="buktiField" style="display:none; margin-top:10px;">
                    <label>Bukti</label>
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
            document.getElementById('formBayar').action = url.replace(':id', id);
        }

        function closeModal() {
            document.getElementById('modalBayar').style.display = 'none';
        }

        function outsideClick(e) {
            if (e.target.id === 'modalBayar') closeModal();
        }

        function toggleBukti() {
            let metode = document.getElementById('metode').value;
            let box = document.getElementById('buktiField');
            let input = document.getElementById('bukti');
            if (metode === 'online') {
                box.style.display = 'block';
                input.required = true;
            } else {
                box.style.display = 'none';
                input.required = false;
                input.value = "";
            }
        }
    </script>
@endsection
