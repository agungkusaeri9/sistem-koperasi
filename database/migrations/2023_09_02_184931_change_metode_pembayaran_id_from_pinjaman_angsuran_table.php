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
        Schema::table('pinjaman_angsuran', function (Blueprint $table) {
            $table->dropForeign(['metode_pembayaran_id']);

            $table->foreignId('metode_pembayaran_id')->nullable()->change();
            $table->foreign('metode_pembayaran_id')->references('id')->on('metode_pembayaran');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pinjaman_angsuran', function (Blueprint $table) {
            $table->dropForeign(['metode_pembayaran_id']);

            $table->foreignId('metode_pembayaran_id')->change();
            $table->foreign('metode_pembayaran_id')->references('id')->on('metode_pembayaran');
        });
    }
};
