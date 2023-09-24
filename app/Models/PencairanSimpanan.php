<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PencairanSimpanan extends Model
{
    use HasFactory;
    protected $table = 'pencairan_simpanan';
    protected $guarded = ['id'];

    public function periode()
    {
        return $this->belongsTo(Periode::class);
    }

    public function anggota()
    {
        return $this->belongsTo(Anggota::class);
    }

    public function metode_pembayaran()
    {
        return $this->belongsTo(MetodePembayaran::class);
    }

    public function status()
    {
        // 0 menunggu verifikasi/validasi, 1 diterima, 2 ditolak, 3 = dibatalkan
        if ($this->status == 0) {
            return '<span class="badge badge-warning">Menunggu Validasi</span>';
        } elseif ($this->status == 1) {
            return '<span class="badge badge-success">Diterima</span>';
        } elseif ($this->status == 2) {
            return '<span class="badge badge-danger">Ditolak</span>';
        } else {
            return '<span class="badge badge-danger">Dibatalkan</span>';
        }
    }

    public function scopeJenisWajib($q)
    {
        return $q->where('jenis', 'wajib');
    }

    public function scopeJenisShr($q)
    {
        return $q->where('jenis', 'shr');
    }
}
