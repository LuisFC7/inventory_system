<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ItemController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/csrf-token', function() {
    return response()->json(['token' => csrf_token()]);
});

// Route::post('/register', [UserController::class, 'registerUser']);

// Rutas de autenticación
// Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
// Route::post('/login', [AuthController::class, 'login']);

// Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rutas protegidas
// Route::middleware('auth')->group(function () {
//     Route::get('/dashboard', 'DashboardController@index');
// });

// Route::get('/dashboard', [DashboardController::class, 'getUserInfo'])
//      ->middleware('auth')
//      ->name('dashboard');


// Ruta para mostrar el formulario (GET)
Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register.form');
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login.form');

// Ruta para procesar el registro (POST)
Route::post('/register', [RegisterController::class, 'registerUser'])->name('register');
Route::post('/login', [LoginController::class, 'loginUser'])->name('login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Rutas protegidas
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('items', ItemController::class);
    
});


