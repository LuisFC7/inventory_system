<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Carbon;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('welcome'); // Asegúrate que esta vista existe
    }

    public function loginUser(Request $request)
    {
        // Verificar si es una solicitud AJAX/JSON
        $wantsJson = $request->wantsJson() || $request->ajax();
        
        $this->checkTooManyFailedAttempts($request);

        try {
            $credentials = $this->validateLogin($request);
            
            if ($this->attemptLogin($credentials, $request->filled('remember'))) {
                if ($wantsJson) {
                    return response()->json([
                        'redirect' => route('dashboard'),
                        'message' => 'Login exitoso'
                    ]);
                }
                return $this->sendLoginResponse($request);
            }

            RateLimiter::hit($this->throttleKey($request));

            if ($wantsJson) {
                return response()->json([
                    'error' => 'Credenciales inválidas'
                ], 401);
            }

            return $this->sendFailedLoginResponse($request);

        } catch (ValidationException $e) {
            if ($wantsJson) {
                return response()->json([
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        }
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            Auth::user()->update([
                'user_last_access' => Carbon::now('America/Mexico_City')
            ]);
        }
        
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('status', 'Has cerrado sesión correctamente.');
    }

    protected function validateLogin(Request $request)
    {
        return $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
            'company' =>'required|string'
        ]);
    }

    protected function attemptLogin(array $credentials, $remember = false)
    {

        if ($credentials['company'] !== '023989ewd') {
            throw ValidationException::withMessages([
                'company' => 'Acceso restringido: código de empresa inválido',
            ]);
        }
        
        $user = Users::where('user_tag', $credentials['username'])
                ->orWhere('user_email', $credentials['username'])
                ->first();

        if (!$user || !Hash::check($credentials['password'], $user->user_password)) {
            return false;
        }

        if ($user->user_status != 1) {
            throw ValidationException::withMessages([
                'username' => 'Tu cuenta está desactivada.',
            ]);
        }

        // Cambia esta línea para no usar remember token
        
        // $user->update([
        //     'user_last_access' => Carbon::now()
        // ]);
        
        Auth::login($user); // Elimina el segundo parámetro $remember
        return true;
    }

    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();
        RateLimiter::clear($this->throttleKey($request));
        
        return redirect()->intended('/dashboard')
               ->with('success', '¡Bienvenido de nuevo!');
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            'username' => ['Las credenciales proporcionadas no son correctas.'],
        ]);
    }

    protected function checkTooManyFailedAttempts(Request $request)
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey($request), 5)) {
            return;
        }

        $seconds = RateLimiter::availableIn($this->throttleKey($request));

        throw ValidationException::withMessages([
            'username' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    protected function throttleKey(Request $request)
    {
        return Str::transliterate(Str::lower($request->input('username')).'|'.$request->ip());
    }
}