<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;

class Users extends Model implements Authenticatable
{
    use HasApiTokens, Notifiable, AuthenticatableTrait;

    protected $table = 'users';
    protected $primaryKey = 'user_id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'user_name',
        'user_last_name',
        'user_email',
        'user_department',
        'user_creation',
        'user_modification',
        'user_tag',    
        'user_status',
        'user_password',
    ];

    protected $hidden = [
        'user_password',
        'remember_token',
    ];

    // Métodos esenciales para autenticación
    public function getAuthIdentifierName()
    {
        return 'user_id';
    }

    public function getAuthIdentifier()
    {
        return $this->user_id;
    }

    public function getAuthPassword()
    {
        return $this->user_password;
    }

    public function getRememberToken()
    {
        return $this->remember_token;
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