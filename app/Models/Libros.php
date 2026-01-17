<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Libros extends Model
{
    protected $table = 'libros';
    protected $fillable = ['nombre', 'descripcion', 'autor'];

    public $timestamps = false;
}
