<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up()
    {
        DB::statement("
            ALTER TABLE peminjamans
            MODIFY status ENUM(
                'menunggu',
                'dipinjam',
                'dikembalikan',
                'terlambat',
                'ditolak'
            ) NOT NULL DEFAULT 'menunggu'
        ");
    }

    public function down()
    {
        DB::statement("
            ALTER TABLE peminjamans
            MODIFY status ENUM(
                'menunggu',
                'dipinjam',
                'dikembalikan',
                'terlambat'
            ) NOT NULL DEFAULT 'menunggu'
        ");
    }
};
