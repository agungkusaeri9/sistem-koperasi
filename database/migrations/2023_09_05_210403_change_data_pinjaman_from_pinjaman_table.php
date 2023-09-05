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
        Schema::table('pinjaman', function (Blueprint $table) {
            $table->integer('bulan_mulai')->nullable()->change();
            $table->integer('bulan_sampai')->nullable()->change();
            $table->integer('tahun_mulai')->nullable()->change();
            $table->integer('tahun_sampai')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pinjaman', function (Blueprint $table) {
            $table->integer('bulan_mulai')->change();
            $table->integer('bulan_sampai')->change();
            $table->integer('tahun_mulai')->change();
            $table->integer('tahun_sampai')->change();
        });
    }
};
