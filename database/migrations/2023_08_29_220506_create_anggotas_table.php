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
            $table->increments('id');
            $table->string('nama', 30);
            $table->string('nip', 20)->nullable()->unique();
            $table->string('jenis_kelamin', 20);
            $table->string('tempat_lahir', 30);
            $table->date('tanggal_lahir');
            $table->text('alamat');
            $table->string('nomor_telepon', 20);
            $table->unsignedInteger('jabatan_id');
            $table->unsignedInteger('agama_id');
            $table->unsignedInteger('user_id');

            $table->foreign('jabatan_id')->references('id')->on('jabatan');
            $table->foreign('agama_id')->references('id')->on('agama');
            $table->foreign('user_id')->references('id')->on('users');
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
