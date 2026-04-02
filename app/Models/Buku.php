<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Kategori;
use App\Models\Peminjaman;

class Buku extends Model
{
    use HasFactory;

    protected $table = 'bukus'; // opsional, tapi aman kalau nama tabel custom

    protected $fillable = [
        'judul',
        'penulis',
        'penerbit',
        'tahun_terbit',
        'kategori_id',
        'deskripsi',
        'stok',
        'cover'
    ];

    protected $casts = [
        'tahun_terbit' => 'integer',
        'stok' => 'integer',
    ];

    /**
     * Relasi ke kategori
     */
    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    /**
     * Relasi ke peminjaman
     */
    public function peminjamans()
    {
        return $this->hasMany(Peminjaman::class);
    }
}