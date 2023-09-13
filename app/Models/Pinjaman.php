<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Pinjaman extends Model
{
    use HasFactory, LogsActivity;
    protected $table = 'pinjaman';
    protected $guarded = ['id'];

    public $casts = [
        'tanggal_diterima' => 'datetime'
    ];

    public function anggota()
    {
        return $this->belongsTo(Anggota::class);
    }

    public function lama_angsuran()
    {
        return $this->belongsTo(LamaAngsuran::class);
    }

    public function met_pencairan()
    {
        return $this->belongsTo(MetodePembayaran::class, 'metode_pencairan', 'id');
    }

    public function angsuran()
    {
        return $this->hasMany(PinjamanAngsuran::class, 'pinjaman_id', 'id');
    }

    public function cekVerifikasiStatusAngsuran()
    {
        $data_angsuran = $this->angsuran;
        $angsuran_lunas = 0;
        $angsuran_belum_lunas = 0;
        $total = $this->angsuran->count();
        foreach ($data_angsuran as $angsuran) {
            // cek angsuran lunas
            if ($angsuran->status == 2) {
                $angsuran_lunas = $angsuran_lunas + 1;
            }

            // cek angsuran belum bayar/lunas
            if ($angsuran->status == 0) {
                $angsuran_belum_lunas = $angsuran_belum_lunas + 1;
            }
        }

        if ($angsuran_lunas == $total) {
            return true;
        } else {
            return false;
        }
    }


    public function status()
    {
        // 0 = menunggu persetujuan, 1 disetujui, 2 selesai, 3 = ditolak
        if ($this->status == 0) {
            return '<span class="badge badge-warning">Menunggu Persetujuan</span>';
        } elseif ($this->status == 1) {
            return '<span class="badge badge-info">Disetujui</span>';
        } elseif ($this->status == 2) {
            return '<span class="badge badge-success">Selesai</span>';
        } else {
            return '<span class="badge badge-danger">Ditolak</span>';
        }
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->setDescriptionForEvent(fn (string $eventName) => "The " . \Str::ucfirst(auth()->user()->name) . " {$eventName} pinjaman")
            ->logOnly(['kode'])
            ->useLogName('Pinjaman');
    }

    public static function buatKodeBaru()
    {
        $kode_terakhir = Pinjaman::latest()->first()->kode;
        if ($kode_terakhir) {
            $kode_nomor = \Str::after($kode_terakhir, 'P');
            $kode_baru = "P" . str_pad($kode_nomor + 1, 5, 0, STR_PAD_LEFT);
        } else {
            $kode_baru = "P00001";
        }
        return $kode_baru;
    }

    public function scopeByAnggota($query)
    {
        return $query->where('anggota_id', auth()->user()->anggota->id);
    }
}
