<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

class RegisterController extends Controller{
    
    public function showRegisterForm(){
        return view('auth.register');
    }

    public function registerUser(Request $request){
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'lastname' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,user_email',
                'password' => 'required|string|min:8|confirmed',
                'department' => 'required|string|max:255',
                'username' => 'required|string|max:255|unique:users,user_tag'
            ]);

            Users::create([
                'user_name' => $validated['name'],
                'user_last_name' => $validated['lastname'],
                'user_email' => $validated['email'],
                'user_department' => $validated['department'],
                'user_creation' => Carbon::now(),
                'user_modification' => Carbon::now(),
                'user_tag' => $validated['username'],
                'user_status' => 1,
                'user_password' => Hash::make($validated['password'])
            ]);

            return back()->with([
                'success' => '¡Registro exitoso! Redirigiendo...',
                'autoRedirect' => true
            ]);

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al registrar: '.$e->getMessage()])
                        ->withInput();
        }
    }
}
