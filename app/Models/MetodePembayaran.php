<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class MetodePembayaran extends Model
{
    use HasFactory, LogsActivity;
    protected $table = 'metode_pembayaran';
    protected $guarded = ['id'];


    public function getFull()
    {
        if ($this->nomor) {
            return $this->nama;
        } else {
            return $this->nomor . ' (' . $this->nama . ')' . ' a.n ' . $this->pemilik;
        }
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->setDescriptionForEvent(fn (string $eventName) => "The " . \Str::ucfirst(auth()->user()->name) . " {$eventName} Metode Pembayaran")
            ->logOnly(['nama'])
            ->useLogName('Metode Pembayaran');
    }
}
