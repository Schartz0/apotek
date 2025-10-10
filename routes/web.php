<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ObatController;
use App\Http\Controllers\ServiceController;

/*
|--------------------------------------------------------------------------
| Route untuk Autentikasi
|--------------------------------------------------------------------------
*/

// Halaman login
Route::view('/', 'pages.login')->name('login');

// Proses login
Route::post('/login', [AuthController::class, 'login'])->name('login.process');

// Logout (POST biar aman)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


/*
|--------------------------------------------------------------------------
| Route yang butuh login (auth)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        $user = auth()->user();
        return view('pages.draft', compact('user'));
    })->name('draft');

    /*
    |--------------------------------------------------------------------------
    | CRUD Obat
    |--------------------------------------------------------------------------
    */
    Route::get('/obat', [ObatController::class, 'index']);
    Route::post('/obat', [ObatController::class, 'store']);
    Route::get('/obat/{obat}', [ObatController::class, 'show']);
    Route::put('/obat/{obat}', [ObatController::class, 'update']);
    Route::delete('/obat/{obat}', [ObatController::class, 'destroy']);

    /*
    |--------------------------------------------------------------------------
    | CRUD Service
    |--------------------------------------------------------------------------
    */
    Route::get('/service', [ServiceController::class, 'index']);
    Route::post('/service', [ServiceController::class, 'store']);
    Route::get('/service/{service}', [ServiceController::class, 'show']);
    Route::put('/service/{service}', [ServiceController::class, 'update']);
    Route::delete('/service/{service}', [ServiceController::class, 'destroy']);

    /*
    |--------------------------------------------------------------------------
    | Static Pages
    |--------------------------------------------------------------------------
    */
    Route::view('/draft', 'pages.draft');
    Route::view('/buat', 'pages.buat');
    Route::view('/list', 'pages.list');
    Route::view('/detail', 'pages.detail');
    Route::view('/produk/service', 'pages.produk_service');
    Route::view('/produk/obat', 'pages.produk_obat');
});
