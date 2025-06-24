<?php

namespace App\Http\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardService
{
    /**
     * Obtiene información del usuario autenticado
     * 
     * @return \App\Models\User
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function getUserInfo()
    {
        $user = Auth::user();
        
        if (!$user) {
            throw new \RuntimeException('Usuario no autenticado');
        }

        return User::where('id', $user->id)
            ->where('user_status', 1)
            ->firstOrFail();
    }
}