<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Producto extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'tb_productos';

    protected $fillable = [
        'nombre',
        'precio',
        'descripcion',
        'imagen'
    ];

    public $timestamps = false;
}
