<!DOCTYPE html>
<html>
<head>
    <title>Struk Pembayaran</title>
    <style>
        body {
            font-family: monospace;
            background: #f3f4f6;
            padding: 20px;
        }

        .struk {
            width: 320px;
            margin: auto;
            border: 1px dashed #000;
            padding: 15px;
            background: #fff;
            color: #000;
        }

        h3 {
            text-align: center;
            margin-bottom: 5px;
        }

        .alamat {
            text-align: center;
            font-size: 11px;
            margin-bottom: 10px;
        }

        .line {
            border-top: 1px dashed #000;
            margin: 10px 0;
        }

        .row {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            font-size: 12px;
            margin: 4px 0;
        }

        .label {
            min-width: 80px;
        }

        .value {
            text-align: right;
            max-width: 180px;
            word-break: break-word;
        }

        .total {
            font-weight: bold;
            font-size: 13px;
        }

        .center {
            text-align: center;
            font-size: 12px;
        }

        .btn-print {
            display: block;
            margin: 15px auto;
            padding: 8px 12px;
            cursor: pointer;
        }

        @media print {
            body {
                background: white;
            }

            .btn-print {
                display: none;
            }
        }
    </style>
</head>
<body>

<div class="struk">
    <h3>PERPUSTAKAAN</h3>
    <div class="alamat">Sistem Perpustakaan Digital</div>

    <div class="line"></div>

    <div class="row">
        <span class="label">No</span>
        <span class="value">{{ $noTransaksi }}</span>
    </div>

    <div class="row">
        <span class="label">Nama</span>
        <span class="value">{{ $p->user->name }}</span>
    </div>

    <div class="row">
        <span class="label">Tanggal</span>
        <span class="value">{{ now()->format('d-m-Y') }}</span>
    </div>

    <div class="line"></div>

    <div class="row">
        <span class="label">Buku</span>
        <span class="value">{{ $p->buku->judul }}</span>
    </div>

    <div class="row">
        <span class="label">Terlambat</span>
        <span class="value">{{ $p->terlambat ?? 0 }} Hari</span>
    </div>

    <div class="line"></div>

    <div class="row total">
        <span class="label">Total</span>
        <span class="value">Rp {{ number_format($p->denda, 0, ',', '.') }}</span>
    </div>

    <div class="row">
        <span class="label">Status</span>
        <span class="value">LUNAS</span>
    </div>

    <div class="line"></div>

    {{-- QR CODE --}}
    <div class="center">
        {!! QrCode::size(90)->generate($noTransaksi) !!}
        <div style="font-size:10px; margin-top:5px;">
            Scan untuk verifikasi
        </div>
    </div>

    <div class="line"></div>

    <p class="center">Terima Kasih 🙏</p>
</div>

<button onclick="window.print()" class="btn-print">Print</button>

</body>
</html>