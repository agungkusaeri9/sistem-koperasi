<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PinjamanAngsuran extends Model
{
    use HasFactory;
    protected $table = 'pinjaman_angsuran';
    protected $guarded = ['id'];
    public function metode_pembayaran()
    {
        return $this->belongsTo(MetodePembayaran::class);
    }

    public function status()
    {
        // 0 = Belum Bayar, 1 Menunggu Verifikasi, 2 = Lunas
        if ($this->status == 0) {
            return '<span class="badge badge-danger">Belum Bayar</span>';
        } elseif ($this->status == 1) {
            return '<span class="badge badge-warning">Menunggu Verifikasi</span>';
        } else {
            return '<span class="badge badge-success">Lunas</span>';
        }
    }
}
