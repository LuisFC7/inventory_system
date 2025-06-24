<?php

namespace App\Http\Services;

use App\Models\Users;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class UserService{

    public function createUser(array $data): Users{

        return Users::create([
            'user_name' => $data['name'],
            'user_last_name' => $data['lastname'],
            'user_email' => $data['email'],
            'user_department' => $data['department'],
            'user_creation' => Carbon::now(),
            'user_modification' => Carbon::now(),
            'user_tag' => $data['username'],
            'user_status' => 1,
            'user_password' => Hash::make($data['password']),
        ]);

    }

    public function loginUser(array $data): array
    {
        
        if (!Auth::attempt([
            'email' => $data['email'],
            'password' => $data['password']
        ])) {
            throw ValidationException::withMessages([
                'email' => [__('auth.failed')],
            ]);
        }

        $user = Auth::user();
        
        // Limpiar tokens antiguos (opcional)
        $user->tokens()->delete();
        
        // Crear nuevo token de acceso
        $token = $user->createToken('auth-token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token
        ];
    }

}