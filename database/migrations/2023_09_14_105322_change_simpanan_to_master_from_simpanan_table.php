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
        Schema::table('simpanan', function (Blueprint $table) {
            $table->dropConstrainedForeignId('anggota_id');
            $table->dropColumn('bukti_pembayaran');
            $table->dropColumn('status_pencairan');
            $table->dropColumn('status_tagihan');
            $table->dropConstrainedForeignId('metode_pembayaran_id');
            $table->foreignId('periode_id')->nullable()->constrained('periode');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('simpanan', function (Blueprint $table) {
            $table->foreignId('anggota_id')->nullable()->constrained('anggota');
            $table->string('bukti_pembayaran', 100)->nullable();
            $table->boolean('status_pencairan')->default(0);
            $table->integer('status_tagihan')->default(0);
            $table->foreignId('metode_pembayaran_id')->nullable()->constrained('metode_pembayaran');
            $table->dropConstrainedForeignId('periode_id');
        });
    }
};
