<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Auth\Notifications\VerifyEmail;

class Users extends Model implements Authenticatable, CanResetPasswordContract, MustVerifyEmail
{
    use HasApiTokens, Notifiable, AuthenticatableTrait, CanResetPassword;

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
        'user_last_access',
        'email_verified_at' // Añadir este campo
    ];

    protected $hidden = [
        'user_password',
        'remember_token',
    ];

    protected $casts = [
        'user_last_access' => 'datetime',
        'user_creation' => 'datetime',
        'user_modification' => 'datetime',
        'email_verified_at' => 'datetime' // Añadir este cast
    ];

    // Métodos de autenticación
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

    // Métodos para el reseteo de contraseña
    public function getEmailForPasswordReset()
    {
        return $this->user_email;
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new \App\Notifications\CustomResetPasswordNotification($token));
    }

    // Métodos para la verificación de email (MustVerifyEmail)
    public function hasVerifiedEmail()
    {
        return !is_null($this->email_verified_at);
    }

    public function markEmailAsVerified()
    {
        return $this->forceFill([
            'email_verified_at' => $this->freshTimestamp(),
            'user_status' => 1 // Cambiar estado a activo al verificar
        ])->save();
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail);
    }

    public function getEmailForVerification()
    {
        return $this->user_email;
    }

    // Accessor para compatibilidad
    public function getEmailAttribute()
    {
        return $this->user_email;
    }
}