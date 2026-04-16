@extends('petugas.layouts.app')

@section('content')
    <style>
        .page-title {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .denda-card {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
            padding: 20px;
            border-radius: 16px;
            margin-bottom: 20px;
        }

        .table-box {
            background: #fff;
            padding: 20px;
            border-radius: 16px;
        }

        .badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            color: white;
            border: none;
        }

        .btn {
            padding: 6px 10px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            font-size: 12px;
        }

        .red {
            background: #ef4444;
            color: white;
        }

        .green {
            background: #10b981;
            color: white;
        }

        .orange {
            background: #f59e0b;
            color: white;
        }

        /* IMAGE */
        .img-preview {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.2s;
        }

        .img-preview:hover {
            transform: scale(1.1);
        }

        /* MODAL IMAGE */
        .modal-img {
            display: none;
            position: fixed;
            z-index: 999;
            inset: 0;
            background: rgba(0, 0, 0, 0.7);
        }

        .modal-img img {
            display: block;
            max-width: 90%;
            max-height: 80%;
            margin: 5% auto;
            border-radius: 10px;
        }
    </style>

    <h4 class="page-title">Kelola Denda</h4>

    <div class="denda-card">
        <h3>Total Denda Aktif</h3>
        <h1>Rp {{ number_format($totalDenda) }}</h1>
    </div>
    <div class="table-box">
        <table width="100%">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Buku</th>
                    <th>Terlambat</th>
                    <th>Total</th>
                    <th>Metode</th>
                    <th>Bukti</th>
                    <th>Status</th>
                    <th>Aksi</th> 
                </tr>
            </thead>

            <tbody>
                @forelse($data as $item)
                    <tr>
                        <td>{{ $item->user->name }}</td>
                        <td>{{ $item->buku->judul }}</td>
                        <td>{{ (int) $item->terlambat }} Hari</td>
                        <td>Rp {{ number_format($item->denda, 0, ',', '.') }}</td>

                        {{-- METODE --}}
                        <td>
                            @if ($item->metode_pembayaran == 'online')
                                <span class="badge orange">Online</span>
                            @elseif($item->metode_pembayaran == 'offline')
                                <span class="badge green">Offline</span>
                            @else
                                <span style="color:#9ca3af;">-</span>
                            @endif
                        </td>

                        {{-- BUKTI --}}
                        <td>
                            @if ($item->bukti_pembayaran)
                                <img src="{{ asset('storage/' . $item->bukti_pembayaran) }}" class="img-preview"
                                    onclick="showImage(this.src)">
                            @else
                                <span style="color:#9ca3af;">Tidak ada</span>
                            @endif
                        </td>

                        {{-- STATUS --}}
                        <td>
                            @if ($item->status_pembayaran == 'menunggu')
                                <span class="badge orange">Menunggu</span>
                            @elseif($item->status_pembayaran == 'lunas')
                                <span class="badge green">Lunas</span>
                            @else
                                <span class="badge red">Belum</span>
                            @endif
                        </td>

                        {{-- AKSI --}}
                        <td>
                            @if ($item->status_pembayaran == 'menunggu')
                                {{-- KONFIRMASI --}}
                                <form action="{{ route('petugas.konfirmasi', $item->id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    <button class="btn green" onclick="return confirm('Konfirmasi pembayaran ini?')">
                                        ✔️
                                    </button>
                                </form>

                                {{-- TOLAK --}}
                                <form action="{{ route('petugas.tolak', $item->id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    <button class="btn red" onclick="return confirm('Tolak pembayaran ini?')">
                                        ✖
                                    </button>
                                </form>
                            @else
                                <span style="color:#9ca3af;">-</span>
                            @endif
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align:center;">
                            Tidak ada data denda
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div style="margin-top:10px; font-size:13px; color:#64748b;">
            Menampilkan {{ $data->firstItem() ?? 0 }} - {{ $data->lastItem() ?? 0 }}
            dari {{ $data->total() }} data
        </div>
    </div>

    <div class="pagination-wrapper">
        {{ $data->links() }}
    </div>

    {{-- MODAL GAMBAR --}}
    <div id="modalImg" class="modal-img" onclick="this.style.display='none'">
        <img id="imgPreview">
    </div>

    <script>
        function showImage(src) {
            document.getElementById('modalImg').style.display = 'block';
            document.getElementById('imgPreview').src = src;
        }
    </script>
@endsection
