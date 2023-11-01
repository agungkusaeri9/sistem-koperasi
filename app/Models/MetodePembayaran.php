<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class MetodePembayaran extends Model
{
    use HasFactory;
    protected $table = 'metode_pembayaran';
    protected $guarded = ['id'];


    public function getFull()
    {
        if (!$this->nomor) {
            return $this->nama;
        } else {
            return $this->nomor . ' (' . $this->nama . ')' . ' a.n ' . $this->pemilik;
        }
    }

    public function anggota()
    {
        return $this->belongsTo(Anggota::class);
    }

    // public function getActivitylogOptions(): LogOptions
    // {
    //     return LogOptions::defaults()
    //         ->setDescriptionForEvent(fn (string $eventName) => "The " . \Str::ucfirst(auth()->user()->name) . " {$eventName} Metode Pembayaran")
    //         ->logOnly(['nama'])
    //         ->useLogName('Metode Pembayaran');
    // }

    public function scopeByAnggota($query)
    {
        return $query->where('anggota_id', auth()->user()->anggota->id);
    }


    public function scopeBySistem($query)
    {
        return $query->whereNull('anggota_id');
    }
}
