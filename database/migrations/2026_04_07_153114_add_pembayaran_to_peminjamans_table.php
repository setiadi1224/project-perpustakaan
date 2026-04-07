<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('peminjamans', function (Blueprint $table) {
        $table->string('bukti_pembayaran')->nullable();
        $table->enum('status_pembayaran', ['belum','menunggu','lunas'])->default('belum');
    });
}

public function down()
{
    Schema::table('peminjamans', function (Blueprint $table) {
        $table->dropColumn(['bukti_pembayaran', 'status_pembayaran']);
    });
}
};
