<?php

namespace app\Models;

use Illuminate\Database\Eloquent\Model;

class UsuarioAuth extends Model
{
    protected $table = 'usuarios';
    public $timestamps = false;
    protected $fillable = [
        'nombre',
        'email',
        'password',
        'rol',
        'token'
    ];
    protected $hidden = [
        'password'
    ];
}