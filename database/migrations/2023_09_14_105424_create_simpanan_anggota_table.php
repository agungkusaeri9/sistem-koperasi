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
        Schema::create('simpanan_anggota', function (Blueprint $table) {
            $table->id();
            $table->foreignId('simpanan_id')->constrained('simpanan');
            $table->foreignId('anggota_id')->constrained('anggota');
            $table->string('bukti_pembayaran')->nullable();
            $table->boolean('status_pencairan')->default(0);
            $table->integer('status_tagihan');
            $table->foreignId('metode_pembayaran_id')->nullable()->constrained('metode_pembayaran');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('simpanan_anggota');
    }
};
