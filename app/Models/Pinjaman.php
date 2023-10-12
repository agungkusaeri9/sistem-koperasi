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

    public function tagihanBelumTerbayar()
    {
        $jml = $this->hasMany(PinjamanAngsuran::class, 'pinjaman_id', 'id')->whereIn('status', [0, 1])->count();
        $hasil = $jml * $this->total_jumlah_angsuran_bulan;
        if ($this->status_potongan_awal == 0)
            $hasil = $hasil + $this->potongan_awal;
        return $hasil;
    }

    public function tagihanSudahTerbayar()
    {
        $jml = $this->hasMany(PinjamanAngsuran::class, 'pinjaman_id', 'id')->where('status', 2)->count();
        $hasil = $jml * $this->total_jumlah_angsuran_bulan;
        if ($this->status_potongan_awal == 1)
            $hasil = $hasil + $this->potongan_awal;
        return $hasil;
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
    public function statusTagihanPotonganAwal()
    {
        // 0 = Belum Bayar, 1 Menunggu Verifikasi, 2 = Lunas
        if ($this->status_potongan_awal == 0) {
            return '<span class="badge badge-danger">Belum Bayar</span>';
        } else {
            return '<span class="badge badge-success">Lunas</span>';
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
        $pinjaman_terakhir = Pinjaman::latest()->first();
        if ($pinjaman_terakhir) {
            $kode_nomor = \Str::after($pinjaman_terakhir->kode, 'P');
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

    public static function kalkukasiPinjaman($besar_pinjaman, $lama_angsuran_id)
    {
        $lama_angsuran = LamaAngsuran::findOrFail($lama_angsuran_id);
        $potongan_awal = $besar_pinjaman * ($lama_angsuran->potongan_awal_persen / 100);
        $jumlah_diterima = $besar_pinjaman - $potongan_awal;
        $jasa_pinjaman_bulan = $besar_pinjaman * ($lama_angsuran->jasa_pinjaman_bulan_persen / 100);
        $angsuran_pokok_bulan = $besar_pinjaman / $lama_angsuran->durasi;
        $total_jumlah_angsuran_bulan = ($besar_pinjaman + ($jasa_pinjaman_bulan * $lama_angsuran->durasi)) / $lama_angsuran->durasi;

        $hasil = [
            'besar_pinjaman' => $besar_pinjaman,
            'potongan_awal' => $potongan_awal,
            'jumlah_diterima' => $jumlah_diterima,
            'angsuran_pokok_bulan' => $angsuran_pokok_bulan,
            'jasa_pinjaman_bulan' => $jasa_pinjaman_bulan,
            'total_jumlah_angsuran_bulan' => $total_jumlah_angsuran_bulan,
            'total_jasa_pinjaman' => $jasa_pinjaman_bulan * $lama_angsuran->durasi,
            'total_bayar' => $besar_pinjaman + ($jasa_pinjaman_bulan * $lama_angsuran->durasi) + $potongan_awal
        ];

        return $hasil;
    }
}
