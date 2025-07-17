<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\RateLimiter;
use App\Models\Users;

class UtiitiesController extends Controller
{
    public function showFormPasswordRecover()
    {
        return view('auth.forgotPassword');
    }

    public function store(Request $request){
        $request->validate([
            'email' => 'required|email|exists:users,user_email',
        ], [
            'email.exists' => 'Este correo no está registrado en el sistema.',
        ]);

        // Verifica el throttling manualmente
        $throttleKey = 'password_reset:'.$request->ip();
        
        if (RateLimiter::tooManyAttempts($throttleKey, 1)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->withErrors([
                'email' => "Por favor espere $seconds segundos antes de intentar nuevamente."
            ]);
        }

        RateLimiter::hit($throttleKey, 60); // 60 segundos = 1 minuto

        $user = Users::where('user_email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'No encontramos un usuario con ese correo electrónico.']);
        }

        $status = Password::broker()->sendResetLink(
            ['user_email' => $request->email]
        );

        if ($status === Password::RESET_LINK_SENT) {
            RateLimiter::clear($throttleKey);
        }

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', 'Hemos enviado un enlace de recuperación a tu correo electrónico.')
            // ? back()->with('status', __($status))
            : back()->withErrors(['email' => __($status)]);
    }


    public function reset(Request $request)
{
    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => [
            'required',
            'confirmed',
            'min:8',
            'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/'
        ],
    ], [
        'password.required' => 'La contraseña es obligatoria',
        'password.min' => 'La contraseña debe tener al menos 8 caracteres',
        'password.regex' => 'La contraseña debe contener al menos: una mayúscula, una minúscula, un número y un carácter especial',
        'password.confirmed' => 'Las contraseñas no coinciden',
    ]);

    $credentials = [
        'user_email' => $request->email,
        'password' => $request->password,
        'password_confirmation' => $request->password_confirmation,
        'token' => $request->token
    ];

    $status = Password::reset(
        $credentials,
        function (Users $user, string $password) {
            $user->user_password = bcrypt($password);
            $user->save();
        }
    );

    if ($status === Password::PASSWORD_RESET) {
        return redirect()->route('login.form')->with('status', 'Contraseña restablecida correctamente');
    }

    return back()
        ->withInput($request->only('email', 'password', 'password_confirmation'))
        ->withErrors(['password' => __($status)]);
}
}