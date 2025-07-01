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
    Route::get('/items/update', [DashboardController::class, 'showEditTable'])->name('dash.update');
    Route::resource('items', ItemController::class);

    Route::get('/items/update', [ItemController::class, 'indexUpdateForm'])->name('items.indexUpdateForm');
    Route::get('/items/updateForm', [ItemController::class, 'index2'])->name('items.index2');
    

});