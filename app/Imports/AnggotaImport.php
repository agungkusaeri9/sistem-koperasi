<?php

namespace App\Imports;

use App\Models\Agama;
use App\Models\Anggota;
use App\Models\Jabatan;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;

class AnggotaImport implements ToCollection
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function collection(Collection $rows)
    {

        foreach ($rows as $key => $row) {
            if ($key > 0 && $row[0]) {
                try {
                    if ($row[9]) {
                        $agama = Agama::updateOrCreate(
                            ['nama' => $row[9]],
                            ['nama' => $row[9]]
                        )->id;
                    } else {
                        $agama = NULL;
                    }
                    if ($row[8]) {
                        $jabatan = Jabatan::updateOrCreate(
                            ['nama' => $row[8]],
                            ['nama' => $row[8]]
                        )->id;
                    } else {
                        $jabatan = NULL;
                    }

                    if ($row[3] === 'P')
                        $jenis_kelamin = 'Perempuan';
                    elseif ($row[3] === 'L')
                        $jenis_kelamin = 'Laki-laki';
                    else
                        $jenis_kelamin = NULL;

                    if ($row[10]) {
                        $email = $row[10];
                        $password = bcrypt($row[11]);
                    } else {
                        $email = preg_replace("/[^a-zA-Z0-9]/", "", \Str::replace(' ', '', $row[0])) . rand(1, 100) . '@gmail.com';
                        $password = bcrypt($email);
                    }
                    DB::beginTransaction();
                    $user = User::create([
                        'name' => $row[1],
                        'email' => $email,
                        'password' => $password,
                        'role' => 'anggota',
                        'is_active' => 1
                    ]);
                    Anggota::create([
                        'nama' => $row[1],
                        'nip' => $row[2],
                        'jenis_kelamin' => $jenis_kelamin,
                        'tempat_lahir' => $row[4],
                        'tanggal_lahir' => $row[5],
                        'alamat' => $row[6],
                        'nomor_telepon' => $row[7],
                        'jabatan_id' => $jabatan,
                        'agama_id' => $agama,
                        'user_id' => $user->id
                    ]);
                    DB::commit();
                } catch (\Throwable $th) {
                    Log::error($th->getMessage());
                }
            }
        }
    }
}
