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
        Schema::create('anggota', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 30);
            $table->string('nip', 20)->nullable()->unique();
            $table->string('jenis_kelamin', 20);
            $table->string('tempat_lahir', 30);
            $table->date('tanggal_lahir');
            $table->text('alamat');
            $table->string('nomor_telepon', 20);
            $table->foreignId('jabatan_id')->constrained('jabatan');
            $table->foreignId('agama_id')->constrained('agama');
            $table->foreignId('user_id')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anggota');
    }
};
