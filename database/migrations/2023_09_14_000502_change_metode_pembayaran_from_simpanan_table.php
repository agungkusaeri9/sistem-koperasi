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
            $table->dropConstrainedForeignId('metode_pembayaran');

            $table->foreignId('metode_pembayaran_id')->nullable()->constrained('metode_pembayaran');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('simpanan', function (Blueprint $table) {
            $table->dropConstrainedForeignId('metode_pembayaran_id');

            $table->foreignId('metode_pembayaran')->nullable()->constrained('metode_pembayaran');
        });
    }
};
