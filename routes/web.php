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
Route::view('/', 'pages.login')->name('login')->middleware('guest');

// Proses login
Route::post('/login', [AuthController::class, 'login'])->name('login.process')->middleware('guest');

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
    Route::resource('obat', ObatController::class);


    /*
    |--------------------------------------------------------------------------
    | CRUD Service
    |--------------------------------------------------------------------------
    */
    Route::resource('service', ServiceController::class);

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
