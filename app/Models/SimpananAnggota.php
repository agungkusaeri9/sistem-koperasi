<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SimpananAnggota extends Model
{
    use HasFactory;
    protected $table = 'simpanan_anggota';
    protected $guarded = ['id'];

    public function simpanan()
    {
        return $this->belongsTo(Simpanan::class);
    }

    public function scopeByAnggota($query)
    {
        return $query->where('anggota_id', auth()->user()->anggota->id);
    }

    public function status_tagihan()
    {
        // 0 = Belum Bayar, 1 Menunggu Verifikasi, 2 = Lunas
        if ($this->status_tagihan == 0) {
            return '<span class="badge badge-danger">Belum Bayar</span>';
        } elseif ($this->status_tagihan == 1) {
            return '<span class="badge badge-warning">Menunggu Verifikasi</span>';
        } else {
            return '<span class="badge badge-success">Lunas</span>';
        }
    }

    public function scopeJenisWajib($query)
    {
        $query->whereHas('simpanan', function ($simpanan) {
            $simpanan->where('jenis', 'wajib');
        });
    }
    public function scopeJenisShr($query)
    {
        $query->whereHas('simpanan', function ($simpanan) {
            $simpanan->where('jenis', 'shr');
        });
    }
}
