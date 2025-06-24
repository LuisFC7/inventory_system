<?php

namespace App\Http\Services;

use App\Models\Users;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService
{
    public function loginUser(array $data): bool
    {
        $user = Users::where('user_email', $data['email'])
                ->where('user_status', 1)
                ->first();

        if (!$user || !Hash::check($data['password'], $user->user_password)) {
            return false;
        }

        // Especifica explícitamente el guard y los campos
        Auth::guard('web')->attempt([
            'user_email' => $data['email'],
            'password' => $data['password']
        ], $data['remember'] ?? false);

        return Auth::check();
    }

    public function logoutUser(): void
    {
        Auth::logout();
    }

    public function hasTooManyAttempts(string $email): bool
    {
        return RateLimiter::tooManyAttempts(
            $this->throttleKey($email),
            5 // Intentos máximos
        );
    }

    protected function incrementAttempts(string $email): void
    {
        RateLimiter::hit(
            $this->throttleKey($email),
            60 * 60 // Bloqueo por 1 hora
        );
    }

    protected function clearAttempts(string $email): void
    {
        RateLimiter::clear($this->throttleKey($email));
    }

    protected function throttleKey(string $email): string
    {
        return Str::lower($email).'|'.request()->ip();
    }
}