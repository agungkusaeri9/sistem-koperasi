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
        Schema::create('pinjaman_angsuran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pinjaman_id')->constrained('pinjaman')->cascadeOnDelete()->cascadeOnUpdate();
            $table->integer('bulan');
            $table->integer('tahun');
            $table->date('tanggal_verifikasi')->nullable();
            $table->string('bukti_pembayaran', 100)->nullable();
            $table->foreignId('metode_pembayaran_id')->constrained('metode_pembayaran');
            $table->integer('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pinjaman_angsuran');
    }
};
