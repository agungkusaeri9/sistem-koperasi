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
            $table->increments('id');
            $table->uuid('uuid')->unique();
            $table->string('jenis', 30)->nullable();
            $table->integer('bulan');
            $table->integer('tahun');
            $table->integer('nominal');
            $table->unsignedInteger('anggota_id');
            $table->unsignedInteger('metode_pembayaran_id')->nullable();
            $table->unsignedInteger('periode_id')->nullable();
            $table->boolean('status_pencairan')->default(0);
            $table->boolean('status')->default(0);

            $table->foreign('anggota_id')->references('id')->on('anggota')->onDelete('cascade');
            $table->foreign('metode_pembayaran_id')->references('id')->on('metode_pembayaran');
            $table->foreign('periode_id')->references('id')->on('periode');
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
