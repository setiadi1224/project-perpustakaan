<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('login_cooldowns', function (Blueprint $table) {
            $table->id();

            $table->string('ip', 45)->index();
            $table->string('email')->nullable()->index();

            $table->dateTime('blocked_until')->index();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('login_cooldowns');
    }
};