<?php

namespace App\Models;

use MongoDB\Laravel\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use MongoDB\BSON\UTCDateTime;
use Carbon\Carbon;

class Usuarios extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $connection = 'mongodb';
    protected $collection = 'usuarios';

    protected $fillable = [
        'nombre',
        'email',
        'password',
        'direccion',
        'telefono',
        'user_tipo',
        'reset_token',
        'reset_token_expires_at'
    ];

    protected $hidden = [
        'password',
        'reset_token'
    ];

    public $timestamps = false;
    
    // Convertir UTCDateTime a Carbon automáticamente
    protected $dates = ['reset_token_expires_at'];
    
    // Mutador para asegurar que las fechas se guarden correctamente
    public function setResetTokenExpiresAtAttribute($value)
    {
        $this->attributes['reset_token_expires_at'] = $value instanceof Carbon 
            ? new UTCDateTime($value->getTimestamp() * 1000)
            : new UTCDateTime(Carbon::parse($value)->getTimestamp() * 1000);
    }
    
    // Accesor para convertir UTCDateTime a Carbon
    public function getResetTokenExpiresAtAttribute($value)
    {
        if ($value instanceof UTCDateTime) {
            return Carbon::createFromTimestamp($value->toDateTime()->getTimestamp());
        }
        
        return $value;
    }
    
    public function routeNotificationForMail()
    {
        return $this->email;
    }
}