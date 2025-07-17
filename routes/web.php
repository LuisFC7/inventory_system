<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Auth\UtiitiesController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/csrf-token', function() {
    return response()->json(['token' => csrf_token()]);
});



// Ruta para mostrar el formulario (GET)
Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register.form');
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login.form');

Route::get('/forgot-password', [UtiitiesController::class, 'showFormPasswordRecover'])
    ->middleware('guest')
    ->name('password.request');

// Ruta para procesar el registro (POST)
Route::post('/register', [RegisterController::class, 'registerUser'])->name('register');
Route::post('/login', [LoginController::class, 'loginUser'])->name('login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Rutas protegidas
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    // Route::get('/items/update', [DashboardController::class, 'showEditTable'])->name('dash.update');
    Route::resource('items', ItemController::class);

    Route::get('/items-update', [ItemController::class, 'indexUpdateForm'])->name('items.indexUpdateForm');

    Route::get('/items-report', [ReportController::class, 'showReportForm'])->name('items.reports');
    Route::post('/items-generate-report', [ReportController::class, 'generateReport'])->name('items.generateReport');
    // Route::post('/items-filter-preview', [ReportController::class, 'getFilteredItems'])->name('items.getFilteredItems');

    Route::match(['get', 'post'], '/items-filter-preview', [ReportController::class, 'getFilteredItems'])
    ->name('items.getFilteredItems');

    Route::patch('/items/{item}/deactivate', [ItemController::class, 'deactivate'])->name('items.deactivate');



});

// Recovering password
Route::post('/forgot-password', [UtiitiesController::class, 'store'])
    ->middleware('guest')
    ->name('password.email');

Route::get('/reset-password/{token}', function (string $token) {
    return view('auth.resetPassword', ['token' => $token]);
})->middleware('guest')->name('password.reset');

Route::post('/reset-password', [UtiitiesController::class, 'reset'])
    ->middleware('guest')
    ->name('password.update');


// Rutas de verificación de email
Route::get('/email/verify', function () {
    return view('auth.verifyEmail');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/')->with('verified', true);
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', '¡Enlace de verificación reenviado!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');