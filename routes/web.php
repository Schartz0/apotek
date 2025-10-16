<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MedController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Models\Transaction;

/*
|--------------------------------------------------------------------------
| Route: Halaman Login dan Autentikasi
|--------------------------------------------------------------------------
*/

// Halaman login (hanya untuk guest)
Route::view('/', 'pages.login')->name('login')->middleware('guest');

// Proses login
Route::post('/login', [AuthController::class, 'login'])
    ->name('login.process')
    ->middleware('guest');



/*
|--------------------------------------------------------------------------
| Route: Setelah Login (auth required)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    /*
    --------------------------------------------------------------------------
    | Pencarian Produk (Obat dan Service)
    |--------------------------------------------------------------------------
    */   

    Route::get('/products/search', [ProductController::class, 'search'])->name('products.search');

    /*
    |--------------------------------------------------------------------------
    | Logout
    |--------------------------------------------------------------------------
    */
    
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    /*
    |--------------------------------------------------------------------------
    | Ganti Password
    |--------------------------------------------------------------------------
    */
    Route::post('/password/update', [UserController::class, 'updatePassword'])->name('password.update');

    /*
    |--------------------------------------------------------------------------
    | Dashboard Utama
    |--------------------------------------------------------------------------
    */
    Route::get('/dashboard', function () {
        $user = auth()->user();
        return view('pages.draft', compact('user'));
    })->name('dashboard');


    /*
    |--------------------------------------------------------------------------
    | CRUD Obat (Meds)
    |--------------------------------------------------------------------------
    |
    | Controller: MedController
    | Resource: index, create, store, show, edit, update, destroy
    |
    */
    Route::resource('med', MedController::class);



    /*
    |--------------------------------------------------------------------------
    | CRUD Service
    |--------------------------------------------------------------------------
    |
    | Controller: ServiceController
    |
    */
    Route::resource('service', ServiceController::class);


    /*
    |--------------------------------------------------------------------------
    | CRUD Transaksi
    |--------------------------------------------------------------------------
    |
    | Controller: TransactionController
    | Digunakan untuk gabungan antara produk (obat / service)
    |
    */
    Route::resource('transaction', TransactionController::class);
    Route::post('/transactions/store', [TransactionController::class, 'store'])->name('transactions.store');

    /*
    |--------------------------------------------------------------------------
    | CRUD Staff dan User (hanya admin)
    |--------------------------------------------------------------------------
    */
    Route::middleware('isAdmin')->group(function () {
        Route::resource('staff', StaffController::class);
        Route::resource('user', UserController::class); // pastikan ada controller UserController
    });


    Route::get('/list', [\App\Http\Controllers\TransactionController::class, 'listPage'])
  ->name('list.page');
  Route::delete('/transactions/ref/{ref_no}', [TransactionController::class, 'destroyByRef'])
  ->name('transactions.destroyByRef');
  Route::get('/detail/{ref_no}', [\App\Http\Controllers\TransactionController::class, 'detailByRef'])
->name('transactions.detail');



    /*
    |--------------------------------------------------------------------------
    | Static Pages (Halaman Tampilan)
    |--------------------------------------------------------------------------
    */
    Route::view('/draft', 'pages.draft')->name('draft.page');
    Route::view('/buat', 'pages.buat')->name('buat.page');
    // Route::view('/produk/service', 'pages.produk_service')->name('produk.service');
    // Route::view('/produk/obat', 'pages.produk_obat')->name('produk.obat');


});
