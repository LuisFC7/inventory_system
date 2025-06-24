<?php

namespace App\Rules;

class UserRules
{
    public static function registerRules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'password' => [
                'required',
                'string',
                'min:8',
                'max:255',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/'
            ],
            'email' => 'required|string|email|max:255',
            'department' => 'required|string|max:255',
        ];
    }

    public static function allowedFields(): array
    {
        return [
            'name',
            'lastname',
            'username',
            'password',
            'email',
            'department'
        ];
    }
}