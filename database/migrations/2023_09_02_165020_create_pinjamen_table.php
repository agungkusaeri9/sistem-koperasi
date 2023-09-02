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
        Schema::create('pinjaman', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique();
            $table->foreignId('anggota_id')->constrained('anggota');
            $table->integer('besar_pinjaman');
            $table->string('keperluan');
            $table->foreignId('lama_angsuran_id')->constrained('lama_angsuran');
            $table->integer('bulan_mulai');
            $table->integer('tahun_mulai');
            $table->integer('bulan_sampai');
            $table->integer('tahun_sampai');
            $table->integer('potongan_awal');
            $table->integer('jumlah_diterima');
            $table->integer('angsuran_pokok_bulan');
            $table->integer('jasa_pinjaman_bulan');
            $table->integer('total_jumlah_angsuran_bulan');
            $table->date('tanggal_diterima')->nullable();
            $table->foreignId('diterima_oleh')->nullable()->constrained('users');
            $table->integer('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pinjaman');
    }
};
