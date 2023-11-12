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
            $table->increments('id');
            $table->uuid('uuid')->unique();
            $table->string('kode', 20)->unique();
            $table->unsignedInteger('anggota_id');
            $table->integer('besar_pinjaman');
            $table->string('keperluan', 100);
            $table->unsignedInteger('lama_angsuran_id');
            $table->integer('bulan_mulai');
            $table->integer('tahun_mulai');
            $table->integer('bulan_sampai');
            $table->integer('tahun_sampai');
            $table->integer('potongan_awal');
            $table->integer('jumlah_diterima');
            $table->integer('angsuran_pokok_bulan');
            $table->integer('jasa_pinjaman_bulan');
            $table->integer('total_jumlah_angsuran_bulan');
            $table->boolean('status_potongan_awal')->default(0);
            $table->integer('total_bayar');
            $table->date('tanggal_diterima')->nullable();
            $table->unsignedInteger('diterima_oleh')->nullable();
            $table->integer('status')->default(0);

            $table->foreign('anggota_id')->references('id')->on('anggota');
            $table->foreign('lama_angsuran_id')->references('id')->on('lama_angsuran');
            $table->foreign('diterima_oleh')->references('id')->on('users');
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
