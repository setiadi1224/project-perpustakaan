<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE peminjamans 
            MODIFY status ENUM(
                'menunggu',
                'dipinjam',
                'menunggu_konfirmasi',
                'dikembalikan',
                'ditolak'
            ) DEFAULT 'menunggu'
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE peminjamans 
            MODIFY status ENUM(
                'menunggu',
                'dipinjam',
                'dikembalikan',
                'ditolak'
            ) DEFAULT 'menunggu'
        ");
    }
};