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
        Schema::create('simpanan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jenis_simpanan_id')->constrained('jenis_simpanan');
            $table->integer('bulan');
            $table->integer('tahun');
            $table->integer('nominal');
            $table->foreignId('anggota_id')->constrained('anggota')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('metode_pembayaran')->nullable()->constrained('metode_pembayaran');
            $table->string('bukti_pembayaran')->nullable();
            $table->boolean('status_pencairan')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('simpanan');
    }
};
