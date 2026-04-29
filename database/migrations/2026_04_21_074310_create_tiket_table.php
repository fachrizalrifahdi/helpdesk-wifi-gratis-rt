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
        Schema::create('tiket', function (Blueprint $table) {
            $table->id('id_tiket');
            $table->string('nama_pelapor', 100);
            $table->string('no_whatsapp', 20);
            $table->string('rt', 10);
            $table->string('rw', 10)->nullable();
            $table->string('kelurahan', 50)->nullable();
            $table->string('kecamatan', 50)->nullable();
            $table->string('kategori', 50)->nullable();
            $table->text('deskripsi')->nullable();
            $table->enum('status', ['Open', 'Proses', 'Selesai'])->default('Open');
            $table->timestamp('tgl_lapor')->useCurrent();
            $table->unsignedBigInteger('id_petugas')->nullable();
            $table->timestamps();

            $table->foreign('id_petugas')->references('id_petugas')->on('petugas')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tiket');
    }
};
