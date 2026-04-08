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
        $table->enum('metode_pembayaran', ['offline','online'])->nullable();
    });
}

public function down()
{
    Schema::table('peminjamans', function (Blueprint $table) {
        $table->dropColumn('metode_pembayaran');
    });
}
};
