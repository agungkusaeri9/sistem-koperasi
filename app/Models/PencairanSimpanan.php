<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PencairanSimpanan extends Model
{
    use HasFactory;
    protected $table = 'pencairan_simpanan';
    protected $guarded = ['id'];

    public function status()
    {
        // 0 menunggu verifikasi/validasi, 1 diterima, 2 ditolak
    }
}
