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
            // hapus jenis simpanan id yang berelasi
            $table->dropConstrainedForeignId('jenis_simpanan_id');

            // buatkan kolom jenis simpanan
            $table->string('jenis', 30)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('simpanan', function (Blueprint $table) {
            $table->foreignId('jenis_simpanan_id')->nullable()->constrained('jenis_simpanan');
            $table->dropColumn('jenis');
        });
    }
};
