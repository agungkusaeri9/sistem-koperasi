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
            $table->increments('id');
            $table->string('jenis');
            $table->unsignedInteger('anggota_id');
            $table->unsignedInteger('metode_pembayaran_id')->nullable();
            $table->integer('nominal');
            $table->integer('status')->default(0);
            $table->unsignedInteger('periode_id')->nullable();

            $table->foreign('anggota_id')->references('id')->on('anggota')->cascade('ondelete');
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
        Schema::dropIfExists('pencairan_simpanan');
    }
};
