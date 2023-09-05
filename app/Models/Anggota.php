<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;

class Anggota extends Model
{
    use HasFactory;
    protected $table = 'anggota';
    protected $guarded = ['id'];

    public $casts = [
        'tanggal_lahir' => 'datetime'
    ];

    // public function getActivitylogOptions(): LogOptions
    // {
    //     // return LogOptions::defaults()
    //     //     ->setDescriptionForEvent(fn (string $eventName) => "The " . \Str::ucfirst(auth()->user()->name) . " {$eventName} anggota")
    //     //     ->logOnly(['nama'])
    //     //     ->useLogName('Anggota');
    // }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class);
    }
    public function agama()
    {
        return $this->belongsTo(Agama::class);
    }
}
