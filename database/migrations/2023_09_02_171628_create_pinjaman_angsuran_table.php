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
            $table->increments('id');
            $table->uuid('uuid')->unique();
            $table->unsignedInteger('pinjaman_id');
            $table->integer('bulan');
            $table->integer('tahun');
            $table->date('tanggal_verifikasi')->nullable();
            $table->unsignedInteger('metode_pembayaran_id')->nullable();
            $table->integer('status')->default(0);

            $table->foreign('pinjaman_id')->references('id')->on('pinjaman');
            $table->foreign('metode_pembayaran_id')->references('id')->on('metode_pembayaran');
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
