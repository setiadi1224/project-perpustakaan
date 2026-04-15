@extends('petugas.layouts.app')
@section('title', 'Kelola Kategori')

@section('content')
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            overflow-x: hidden;
        }

        /* ================= CARD ================= */
        .card {
            background: #fff;
            padding: 20px;
            border-radius: 16px;
            margin-bottom: 20px;
        }

        /* ================= FORM ================= */
        .input-group {
            display: flex;
            gap: 10px;
        }

        .input-group input {
            flex: 1;
            padding: 10px;
            border-radius: 10px;
            border: 1px solid #e5e7eb;
        }

        .input-group button {
            background: #2563eb;
            color: white;
            border: none;
            padding: 10px 18px;
            border-radius: 10px;
            cursor: pointer;
        }

        /* ================= TABLE ================= */
        .table-wrapper {
            width: 100%;
            overflow: hidden;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            text-align: left;
            font-size: 14px;
            color: #6b7280;
            padding: 10px;
        }

        td {
            padding: 12px 10px;
            overflow: hidden;
        }

        tbody tr {
            border-top: 1px solid #e5e7eb;
        }

        /* ================= BUTTON ================= */
        .btn-delete {
            background: #ef4444;
            color: white;
            border: none;
            padding: 8px 14px;
            border-radius: 8px;
            cursor: pointer;
            display: block;
            margin: 8px auto 0 auto;
            width: fit-content;
        }

        .btn-disabled {
            background: #9ca3af;
            cursor: not-allowed;
            opacity: 0.6;
        }

        td form {
            width: 100%;
            text-align: center;
            margin: 0;
        }

        /* ================= EMPTY ================= */
        .empty {
            text-align: center;
            padding: 20px;
            color: #9ca3af;
        }

        /* ================= PAGINATION ================= */
        .pagination-wrapper {
            margin-top: 15px;
            display: flex;
            justify-content: center;
        }

        /* ================= RESPONSIVE ================= */
        @media (max-width: 768px) {

            .card {
                padding: 15px;
            }

            h3 {
                font-size: 18px;
            }

            h5 {
                font-size: 16px;
            }

            .input-group {
                flex-direction: column;
            }

            .input-group button {
                width: 100%;
            }

            table,
            thead,
            tbody,
            tr,
            td,
            th {
                display: block;
                width: 100%;
            }

            thead {
                display: none;
            }

            tr {
                background: #fff;
                margin-bottom: 10px;
                padding: 12px;
                border-radius: 12px;
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
                overflow: hidden;
            }

            td {
                padding: 8px 0;
                border: none;
            }

            td::before {
                content: attr(data-label);
                font-weight: bold;
                display: block;
                color: #6b7280;
                font-size: 12px;
                margin-bottom: 3px;
            }

            .btn-delete {
                width: 100%;
                max-width: 250px;
                margin: 10px 0 0 0;
            }

            td form {
                text-align: left;
            }
        }
    </style>

    <h3 class="page-title">Kelola Kategori</h3>

    {{-- FORM TAMBAH --}}
    <div class="card">
        <h5>Tambah Kategori</h5>
        <form method="POST" action="{{ route('petugas.kategori.store') }}">
            @csrf
            <div class="input-group">
                <input type="text" name="nama" placeholder="Masukkan nama kategori..." required>
                <button type="submit">Tambah</button>
            </div>
        </form>
    </div>

    {{-- DATA --}}
    <div class="card">
        <h5>Data Kategori</h5>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Nama Kategori</th>
                        <th width="120">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($data as $k)
                        <tr>
                            <td data-label="Nama Kategori">
                                {{ $k->nama }}
                                <br>
                                <small style="color: gray;">
                                    {{ $k->bukus_count }} buku
                                </small>
                            </td>

                            <td data-label="Aksi">
                                @if ($k->bukus_count > 0)
                                    <button class="btn-delete btn-disabled" disabled>
                                        Tidak bisa
                                    </button>
                                @else
                                    <form action="{{ route('petugas.kategori.delete', $k->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="btn-delete"
                                            onclick="return confirm('Hapus kategori ini?')">
                                            Hapus
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="empty">
                                Belum ada kategori
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        <div class="pagination-wrapper">
            {{ $data->links() }}
        </div>
    </div>
@endsection
