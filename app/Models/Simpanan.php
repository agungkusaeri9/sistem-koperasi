<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Simpanan extends Model
{
    use HasFactory;
    protected $table = 'simpanan';
    protected $guarded = ['id'];

    public function simpanan_anggota()
    {
        return $this->hasOne(SimpananAnggota::class);
    }

    public static function checkSimpananAnggota($id)
    {
        // $simpanan = Simpanan::where('id', $id)->first();
        $simpanan_anggota = SimpananAnggota::where([
            'simpanan_id' => $id,
            'anggota_id' => auth()->user()->anggota->id
        ])->first();


        if ($simpanan_anggota) {
            if ($simpanan_anggota->status_tagihan == 0) {
                return '<span class="badge badge-danger">Belum Bayar</span>';
            } elseif ($simpanan_anggota->status_tagihan == 1) {
                return '<span class="badge badge-warning">Menunggu Verifikasi</span>';
            } else {
                return '<span class="badge badge-success">Lunas</span>';
            }
        } else {
            return '<span class="badge badge-danger">Belum Bayar</span>';
        }
    }
    public static function checkSimpananAnggotaNumber($id)
    {
        // $simpanan = Simpanan::where('id', $id)->first();
        $simpanan_anggota = SimpananAnggota::where([
            'simpanan_id' => $id,
            'anggota_id' => auth()->user()->anggota->id
        ])->first();


        if ($simpanan_anggota) {
            return $simpanan_anggota->status_tagihan;
        } else {
            return NULL;
        }
    }
}
