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
        Schema::create('pencairan_simpanan', function (Blueprint $table) {
            $table->id();
            $table->string('jenis');
            $table->foreignId('anggota_id')->constrained('anggota');
            $table->foreignId('metode_pembayaran_id')->constrained('metode_pembayaran');
            $table->integer('nominal');
            $table->string('bukti_pencairan')->nullable();
            $table->integer('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pencairan_simpanan');
    }
};
