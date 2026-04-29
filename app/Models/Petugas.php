<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Petugas extends Authenticatable
{
    use Notifiable;
    protected $table = 'petugas';
    protected $primaryKey = 'id_petugas';

    protected $fillable = [
        'nama',
        'role',
        'username',
        'password',
    ];

    protected $hidden = [
        'password',
    ];

    public function tikets()
    {
        return $this->hasMany(Tiket::class, 'id_petugas', 'id_petugas');
    }
}
