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
        Schema::create('lama_angsuran', function (Blueprint $table) {
            $table->id();
            $table->integer('durasi');
            $table->integer('potongan_awal_persen');
            $table->integer('jasa_pinjaman_bulan_persen');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lama_angsuran');
    }
};
