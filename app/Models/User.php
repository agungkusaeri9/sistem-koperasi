<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'avatar',
        'is_active'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function avatar()
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        } else {
            return asset('assets/images/faces/face28.jpg');
        }
    }
    public function isActive()
    {
        if ($this->is_active) {
            return '<span class="badge badge-success">Aktif</span>';
        } else {
            return '<span class="badge badge-danger">Tidak Aktif</span>';
        }
    }

    public function scopePegawai($query)
    {
        $query->whereNotIn('role', ['anggota']);
    }
    public function scopeAnggota($query)
    {
        $query->whereIn('role', ['anggota']);
    }

    public function anggota()
    {
        return $this->hasOne(Anggota::class);
    }
}
