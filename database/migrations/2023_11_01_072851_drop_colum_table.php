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
        Schema::table('metode_pembayaran', function (Blueprint $table) {
            $table->dropConstrainedForeignId('anggota_id');
        });
        Schema::table('pencairan_simpanan', function (Blueprint $table) {
            $table->dropColumn('bukti_pencairan');
        });
        Schema::table('pinjaman', function (Blueprint $table) {
            $table->dropConstrainedForeignId('metode_pencairan');
        });

        Schema::table('pinjaman_angsuran', function (Blueprint $table) {
            $table->dropColumn('bukti_pembayaran');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('phone_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        Schema::table('metode_pembayaran', function (Blueprint $table) {
            $table->foreignId('anggota_id')->nullable()->constrained('anggota');
        });
        Schema::table('pencairan_simpanan', function (Blueprint $table) {
            $table->string('bukti_pencairan')->nullable();
        });
        Schema::table('pinjaman', function (Blueprint $table) {
            $table->foreignId('metode_pencairan')->nullable()->constrained('metode_pembayaran');
        });
        Schema::table('pinjaman_angsuran', function (Blueprint $table) {
            $table->string('bukti_pembayaran')->nullable();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('phone_number')->nullable();
        });
    }
};
