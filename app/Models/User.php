<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $table = 'user';          // ✅ nama tabel
    protected $primaryKey = 'iduser';   // ✅ primary key
    public $timestamps = false;         // tabelmu nggak ada created_at/updated_at

    protected $fillable = [
        'username',
        'password',
        'idrole',
    ];

    protected $hidden = [
        'password',
    ];
}
