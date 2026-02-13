<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;  // Para el paquete oficial
// use Jenssegers\Mongodb\Eloquent\Model; // Si usas Jenssegers
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\Authenticatable;
use MongoDB\BSON\UTCDateTime;
use Carbon\Carbon;

class Usuarios extends Model implements Authenticatable
{
    use Notifiable;

    protected $connection = 'mongodb';
    protected $collection = 'usuarios';
    
    protected $primaryKey = '_id';
    public $incrementing = false;
    protected $keyType = 'string';

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
    
    protected $dates = ['reset_token_expires_at'];
    
    public function setResetTokenExpiresAtAttribute($value)
    {
        if ($value instanceof Carbon) {
            $this->attributes['reset_token_expires_at'] = new UTCDateTime($value->getTimestamp() * 1000);
        } elseif (is_string($value)) {
            $this->attributes['reset_token_expires_at'] = new UTCDateTime(Carbon::parse($value)->getTimestamp() * 1000);
        } else {
            $this->attributes['reset_token_expires_at'] = $value;
        }
    }
    
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

    // Métodos requeridos por Authenticatable
    public function getAuthIdentifierName()
    {
        return '_id';
    }

    public function getAuthIdentifier()
    {
        return $this->getKey();
    }

    public function getAuthPassword()
    {
        return $this->password;
    }

    public function getRememberToken()
    {
        return $this->remember_token ?? null;
    }

    public function setRememberToken($value)
    {
        $this->remember_token = $value;
    }

    public function getRememberTokenName()
    {
        return 'remember_token';
    }
}