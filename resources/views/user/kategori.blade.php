@extends('user.layouts.app')

@section('title', 'Kategori — Perpustakaan Digital')

@push('styles')
<style>
.page-title {
    font-size: 18px;
    font-weight: 700;
    color: #111827;
    margin-bottom: 20px;
}

.kategori-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
}

.kategori-card {
    background: #fff;
    border-radius: 14px;
    border: 1px solid #EAECF0;
    padding: 28px 20px;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 14px;
    cursor: pointer;
    text-decoration: none;
    transition: transform 0.18s, box-shadow 0.18s, border-color 0.18s;
}

.kategori-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.09);
    border-color: #2563EB;
}

.kategori-icon {
    width: 60px;
    height: 60px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
}

.kategori-icon.blue   { background: #EFF6FF; }
.kategori-icon.green  { background: #F0FDF4; }
.kategori-icon.amber  { background: #FFFBEB; }
.kategori-icon.purple { background: #F5F3FF; }
.kategori-icon.pink   { background: #FDF2F8; }
.kategori-icon.red    { background: #FEF2F2; }

.kategori-nama {
    font-size: 13.5px;
    font-weight: 600;
    color: #111827;
    text-align: center;
}

.kategori-jumlah {
    font-size: 12px;
    color: #9CA3AF;
}

@media (max-width: 900px) {
    .kategori-grid { grid-template-columns: repeat(2, 1fr); }
}
</style>
@endpush

@section('content')

    @php
        $kategoris = $kategoris ?? collect([
            (object)['id'=>1,'nama'=>'Teknologi', 'icon'=>'💻', 'color'=>'blue',   'jumlah'=>12],
            (object)['id'=>2,'nama'=>'Bisnis',    'icon'=>'📈', 'color'=>'green',  'jumlah'=>8],
            (object)['id'=>3,'nama'=>'Desain',    'icon'=>'🎨', 'color'=>'amber',  'jumlah'=>6],
            (object)['id'=>4,'nama'=>'Sains',     'icon'=>'🔬', 'color'=>'purple', 'jumlah'=>9],
            (object)['id'=>5,'nama'=>'Sastra',    'icon'=>'📖', 'color'=>'pink',   'jumlah'=>5],
            (object)['id'=>6,'nama'=>'Sejarah',   'icon'=>'🏛️', 'color'=>'red',    'jumlah'=>7],
        ]);

        $colors = ['blue','green','amber','purple','pink','red'];
        $icons  = ['💻','📈','🎨','🔬','📖','🏛️'];
    @endphp

    <div class="page-title">Semua Kategori</div>

    <div class="kategori-grid">
        @foreach($kategoris as $i => $kat)
            @php
                $color = $colors[$i % count($colors)];
                $icon  = $icons[$i % count($icons)];
            @endphp
            <a href="{{ route('user.home', ['kategori' => $kat->id]) }}" class="kategori-card">
                <div class="kategori-icon {{ $color }}">
                    {{ $kat->icon ?? $icon }}
                </div>
                <div>
                    <div class="kategori-nama">{{ $kat->nama }}</div>
                    @if(isset($kat->jumlah))
                        <div class="kategori-jumlah" style="text-align:center;">{{ $kat->jumlah }} buku</div>
                    @endif
                </div>
            </a>
        @endforeach
    </div>

@endsection
