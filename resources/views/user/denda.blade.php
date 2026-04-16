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

        .alert {
            padding: 10px 14px;
            border-radius: 10px;
            margin-bottom: 10px;
            font-size: 13px;
        }

        .success {
            background: rgba(34, 197, 94, .15);
            color: #4ade80;
        }

        .error {
            background: rgba(239, 68, 68, .15);
            color: #f87171;
        }

        .card-denda {
            background: linear-gradient(135deg, #1e3a8a, #2563eb);
            padding: 25px;
            border-radius: 18px;
            margin-bottom: 25px;
        }

        .card-denda h2 {
            font-size: 28px;
            margin-top: 10px;
        }

        .card-table {
            background: #1e293b;
            padding: 20px;
            border-radius: 18px;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 14px;
            font-size: 13px;
        }

        th {
            color: #94a3b8;
            border-bottom: 1px solid #334155;
        }

        td {
            border-bottom: 1px solid #243244;
        }

        .badge {
            padding: 5px 12px;
            border-radius: 999px;
            font-size: 11px;
        }

        .merah {
            background: rgba(239, 68, 68, .15);
            color: #f87171;
        }

        .hijau {
            background: rgba(34, 197, 94, .15);
            color: #4ade80;
        }

        .orange {
            background: rgba(245, 158, 11, .15);
            color: #fbbf24;
        }

        .btn-bayar {
            background: #3b82f6;
            padding: 8px 14px;
            border-radius: 10px;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 12px;
        }

        .card-list {
            display: none;
        }

        @media (max-width: 768px) {
            .main-content {
                padding: 15px;
            }

            table {
                display: none;
            }

            .card-list {
                display: flex;
                flex-direction: column;
                gap: 15px;
            }

            .card {
                background: #1e293b;
                padding: 15px;
                border-radius: 14px;
                border: 1px solid #334155;
            }

            .card-item {
                font-size: 13px;
                margin-bottom: 6px;
            }

            .card .btn-bayar {
                width: 100%;
                margin-top: 10px;
            }
        }

        /* MODAL */
        .modal {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .6);
            z-index: 999;
        }

        .modal-content {
            background: #1e293b;
            width: 400px;
            max-width: 90%;
            margin: 10% auto;
            padding: 20px;
            border-radius: 15px;
        }

        select,
        input[type=file] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 10px;
            background: #0f172a;
            border: 1px solid #334155;
            color: white;
        }

        .modal-actions {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }

        .btn-primary {
            background: #3b82f6;
            color: white;
            padding: 10px;
            border-radius: 10px;
            border: none;
            flex: 1;
        }

        .btn-secondary {
            background: #334155;
            color: white;
            padding: 10px;
            border-radius: 10px;
            border: none;
            flex: 1;
        }

        .qr-box {
            background: #0f172a;
            border: 1px solid #334155;
            padding: 15px;
            border-radius: 12px;
            text-align: center;
            margin-bottom: 10px;
        }

        .qr {
            width: 120px;
            margin-bottom: 8px;
        }

        .qr-text {
            font-size: 12px;
            color: #94a3b8;
        }

        .action-group {
            display: flex;
            gap: 8px;
        }

        .btn-modern {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 14px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
            transition: 0.25s;
            border: 1px solid transparent;
        }

        /* STRUK */
        .btn-struk {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: white;
        }

        .btn-struk:hover {
            transform: translateY(-2px);
        }

        /* PDF */
        .btn-pdf {
            background: rgba(59, 130, 246, .1);
            color: #93c5fd;
            border: 1px solid rgba(59, 130, 246, .3);
        }

        .btn-pdf:hover {
            background: #3b82f6;
            color: white;
        }
    </style>

    <div class="main-content">

        {{-- ALERT --}}
        @if (session('success'))
            <div class="alert success">{{ session('success') }}</div>
        @endif

        {{-- TOTAL --}}
        <div class="card-denda">
            <h5>Total Denda Aktif</h5>
            <h2>Rp {{ number_format($totalDenda, 0, ',', '.') }}</h2>
        </div>

        {{-- TABLE --}}
        <div class="card-table">
            <table>
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Terlambat</th>
                        <th>Denda</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($denda as $item)
                        <tr>
                            <td>{{ $item->buku->judul }}</td>

                            <td>
                                {{ $item->terlambat ?? 0 }} Hari

                                @if ($item->terlambat > 0)
                                    <div style="font-size:11px; color:#f87171;">
                                        Terlambat
                                    </div>
                                @else
                                    <div style="font-size:11px; color:#4ade80;">
                                        Tepat Waktu
                                    </div>
                                @endif
                            </td>

                            <td>
                                Rp {{ number_format($item->total_denda, 0, ',', '.') }}
                            </td>

                            <td>
                                <span
                                    class="badge 
                        {{ $item->status_pembayaran == 'belum'
                            ? 'merah'
                            : ($item->status_pembayaran == 'menunggu'
                                ? 'orange'
                                : 'hijau') }}">
                                    {{ $item->status_pembayaran }}
                                </span>
                            </td>

                            <td>
                                @if ($item->status_pembayaran == 'belum' && $item->total_denda > 0)
                                    <button class="btn-bayar"
                                        onclick="openModal({{ $item->id }}, {{ $item->total_denda }})">
                                        Bayar
                                    </button>
                                @endif

                                @if ($item->status_pembayaran == 'lunas')
                                    <div class="action-group">
                                        <a href="{{ route('user.struk', $item->id) }}" target="_blank"
                                            class="btn-modern btn-struk">
                                            🧾 Struk
                                        </a>

                                        <a href="{{ route('user.struk.pdf', $item->id) }}" class="btn-modern btn-pdf">
                                            📄 PDF
                                        </a>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- MOBILE --}}
            <div class="card-list">
                @foreach ($denda as $item)
                    <div class="card">

                        <h4>{{ $item->buku->judul }}</h4>

                        <div class="card-item">
                            ⏱ Terlambat:
                            <strong>{{ $item->terlambat ?? 0 }} Hari</strong>

                            @if ($item->terlambat > 0)
                                <div style="font-size:11px; color:#f87171;">
                                    Terlambat
                                </div>
                            @else
                                <div style="font-size:11px; color:#4ade80;">
                                    Tepat Waktu
                                </div>
                            @endif
                        </div>

                        <div class="card-item">
                            💰 Denda:
                            <strong>Rp {{ number_format($item->total_denda, 0, ',', '.') }}</strong>
                        </div>

                        <div class="card-item">
                            Status:
                            <span
                                class="badge 
                    {{ $item->status_pembayaran == 'belum'
                        ? 'merah'
                        : ($item->status_pembayaran == 'menunggu'
                            ? 'orange'
                            : 'hijau') }}">
                                {{ $item->status_pembayaran }}
                            </span>
                        </div>

                        {{-- ACTION --}}
                        <div style="margin-top:10px;">

                            @if ($item->status_pembayaran == 'belum' && $item->total_denda > 0)
                                <button class="btn-bayar"
                                    onclick="openModal({{ $item->id }}, {{ $item->total_denda }})">
                                    Bayar Sekarang
                                </button>
                            @endif

                            @if ($item->status_pembayaran == 'lunas')
                                <div class="action-group" style="margin-top:8px;">
                                    <a href="{{ route('user.struk', $item->id) }}" target="_blank"
                                        class="btn-modern btn-struk">
                                        🧾 Struk
                                    </a>

                                    <a href="{{ route('user.struk.pdf', $item->id) }}" class="btn-modern btn-pdf">
                                        📄 PDF
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    </div>

    {{-- MODAL --}}
    <div id="modalBayar" class="modal" onclick="outsideClick(event)">
        <div class="modal-content">
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

                <div id="buktiField" style="display:none;">
                    <div class="qr-box">
                        <img src="{{ asset('images/Qr.jpeg') }}" class="qr">
                        <div class="qr-text">Scan untuk pembayaran</div>
                    </div>

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

    {{-- PAGINATION --}}
    <div class="pagination-wrapper">
        {{ $denda->links() }}
    </div>

    <script>
        function openModal(id, denda) {
            document.getElementById('modalBayar').style.display = 'block';

            denda = parseInt(denda);
            document.getElementById('totalBayar').innerText = 'Rp ' + denda.toLocaleString('id-ID');

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
            }
        }
    </script>
@endsection
