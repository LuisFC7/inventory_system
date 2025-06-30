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

        // Caracteres permitidos: @ $ ! % * ? &
        $customMessages = [
            'name.required' => 'El nombre es obligatorio',
            'name.max' => 'El nombre no debe exceder 255 caracteres',
            'lastname.required' => 'El apellido es obligatorio',
            'email.required' => 'El correo electrónico es requerido',
            'email.email' => 'Debe ingresar un correo electrónico válido',
            'email.unique' => 'Este correo ya está registrado',
            'password.required' => 'La contraseña es obligatoria',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres',
            'password.regex' => 'La contraseña debe contener al menos: una mayúscula, una minúscula, un número y un carácter especial',
            'password.confirmed' => 'Las contraseñas no coinciden',
            'department.required' => 'El departamento es requerido',
            'username.required' => 'El nombre de usuario es obligatorio',
            'username.unique' => 'Este nombre de usuario ya está en uso'
        ];

        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'lastname' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,user_email',
                'password' => [
                    'required',
                    'string',
                    'min:8',
                    'confirmed',
                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/'
                ],
                'department' => 'required|string|max:255',
                'username' => 'required|string|max:255|unique:users,user_tag'
            ], $customMessages);

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
