<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
   protected $table = 'users';
   protected $primaryKey = 'user_id';

   protected $fillable = [
        'user_name',
        'user_last_name',
        'user_email',
        'user_department',
        'user_creation',
        'user_modification',
        'user_tag',    
    ];

    protected $hidden = [
        'user_password',
    ];
}