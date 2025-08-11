<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Users;
use App\Models\Item;

class SettingsController extends Controller{

    
    public function index()
    {
        // Obtener el usuario autenticado
        $user = Auth::user();
        
        // Generar iniciales para el avatar
        $initials = $this->generateInitials($user->user_name . ' ' . $user->user_last_name);
        
        return view('dashboard', [
            'user' => $user,
            'initials' => $initials,
            'lastAccess' => $user->user_last_access ? $user->user_last_access->format('d/m/Y H:i') : 'Nunca'
        ]);
    }
    
    

    
}