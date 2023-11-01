<?php

namespace Database\Seeders;

use App\Models\Agama;
use App\Models\Jabatan;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AnggotaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data_user1 = ['name' => 'Agung Kusaeri', 'email' => 'agungkusaeri@gmail.com', 'is_active' => 1];
        $data_user1['role'] = 'anggota';
        $data_anggota1 = ['nama' => 'Agung Kusaeri', 'nip' => NULL, 'jenis_kelamin' => 'Laki-laki', 'tempat_lahir' => 'Purwakarta', 'tanggal_lahir' => '2000-02-01', 'alamat' => 'Purwakarta, Jawa Barat', 'nomor_telepon' => '08912312412345', 'jabatan_id' => Jabatan::inRandomOrder()->first()->id, 'agama_id' => Agama::inRandomOrder()->first()->id];
        $data_user1['password'] = bcrypt('password');
        // create akun
        $user = User::create($data_user1);
        // create anggota
        $user->anggota()->create($data_anggota1);

        $data_user2 = ['name' => 'Deni Muhammad Aripin', 'email' => 'denimuhammad@gmail.com', 'is_active' => 1];
        $data_user2['role'] = 'anggota';
        $data_anggota2 = ['nama' => 'Deni Muhammad Aripin', 'nip' => NULL, 'jenis_kelamin' => 'Laki-laki', 'tempat_lahir' => 'Purwakarta', 'tanggal_lahir' => '2000-12-01', 'alamat' => 'Purwakarta, Jawa Barat', 'nomor_telepon' => '08191123421', 'jabatan_id' => Jabatan::inRandomOrder()->first()->id, 'agama_id' => Agama::inRandomOrder()->first()->id];
        $data_user2['password'] = bcrypt('password');
        // create akun
        $user = User::create($data_user2);
        // create anggota
        $user->anggota()->create($data_anggota2);
    }
}
