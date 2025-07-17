<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Users;
use App\Models\Item;

class DashboardController extends Controller{

    
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
    
    protected function generateInitials($name)
    {
        $words = explode(' ', $name);
        $initials = '';
        
        foreach ($words as $word) {
            $initials .= strtoupper(substr($word, 0, 1));
            if (strlen($initials) >= 2) break;
        }
        
        return $initials;
    }

    //funcion para mostrar vista de update
    public function showEditTable(){
        return view('items.updateInventory');
    }
}