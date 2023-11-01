<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class LamaAngsuran extends Model
{
    use HasFactory;
    protected $table = 'lama_angsuran';
    protected $guarded = ['id'];


    // public function getActivitylogOptions(): LogOptions
    // {
    //     return LogOptions::defaults()
    //         ->setDescriptionForEvent(fn (string $eventName) => "The " . \Str::ucfirst(auth()->user()->name) . " {$eventName} Lama Angsuran")
    //         ->logOnly(['nama'])
    //         ->useLogName('Lama Angsuran');
    // }
}
