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
        Schema::table('pencairan_simpanan', function (Blueprint $table) {
            $table->foreignId('periode_id')->nullable()->constrained('periode');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pencairan_simpanan', function (Blueprint $table) {
            $table->dropConstrainedForeignId('periode_id');
        });
    }
};
