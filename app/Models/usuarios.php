<?php


namespace App\Models;

use MongoDB\Laravel\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Usuarios extends Authenticatable
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'usuarios';

    protected $fillable = [
        'nombre',
        'email',
        'password',
        'direccion',
        'telefono',
        'user_tipo',
    ];

    protected $hidden = [
        'password',
    ];

    public $timestamps = false;
}
