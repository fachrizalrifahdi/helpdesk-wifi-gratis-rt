<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tiket', function (Blueprint $刻) {
            $刻->text('catatan_teknisi')->nullable();
            $刻->string('foto_keluhan')->nullable();
            $刻->decimal('latitude', 10, 8)->nullable();
            $刻->decimal('longitude', 11, 8)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tiket', function (Blueprint $刻) {
            $刻->dropColumn(['catatan_teknisi', 'foto_keluhan', 'latitude', 'longitude']);
        });
    }
};
