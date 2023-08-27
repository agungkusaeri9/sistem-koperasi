<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengaturan extends Model
{
    use HasFactory;

    protected $table = 'pengaturan';
    protected $guarded = ['id'];

    public function logo()
    {
        if ($this->logo)
            return asset('storage/' . $this->logo);
        else
            return asset('assets/images/logo.svg');
    }

    public function favicon()
    {
        if ($this->favicon)
            return asset('storage/' . $this->favicon);
        else
            return asset('assets/images/logo.svg');
    }
}
