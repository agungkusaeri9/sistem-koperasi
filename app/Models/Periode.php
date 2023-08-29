<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Periode extends Model
{
    use HasFactory;
    protected $table = 'periode';
    protected $guarded = ['id'];

    public function periode()
    {
        return $this->getNamaBulan($this->bulan_awal) . ' ' . $this->tahun_awal . ' s.d ' . $this->getNamaBulan($this->bulan_akhir) . ' ' . $this->tahun_akhir;
    }

    public function status()
    {
        if ($this->status == 1)
            return '<span class="badge badge-success">Aktif</span>';
        else
            return '<span class="badge badge-danger">Tidak Aktif</span>';
    }

    public static function getTahun()
    {
        $tahun_sekarang = Carbon::now()->format('Y');
        $years = [];

        for ($i = $tahun_sekarang - 2; $i <= $tahun_sekarang + 2; $i++) {
            $years[] = $i;
        }

        return collect($years);
    }

    public static function getBulan()
    {
        $array = collect([
            (object)[
                'no' => 1,
                'nama' => 'Januari'
            ],
            (object)[
                'no' => 2,
                'nama' => 'Februari'
            ],
            (object)[
                'no' => 3,
                'nama' => 'Maret'
            ],
            (object)[
                'no' => 4,
                'nama' => 'April'
            ],
            (object)[
                'no' => 5,
                'nama' => 'Mei'
            ],
            (object)[
                'no' => 6,
                'nama' => 'Juni'
            ],
            (object)[
                'no' => 7,
                'nama' => 'Juli'
            ],
            (object)[
                'no' => 8,
                'nama' => 'Agustus'
            ],
            (object)[
                'no' => 9,
                'nama' => 'September'
            ],

            (object)[
                'no' => 10,
                'nama' => 'Oktober'
            ],
            (object)[
                'no' => 11,
                'nama' => 'November'
            ],
            (object)[
                'no' => 12,
                'nama' => 'Desember'
            ],
        ]);

        return $array;
    }

    function getNamaBulan($bulan_angka)
    {
        $dataBulan = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        return $dataBulan[$bulan_angka] ?? 0;
    }
}
