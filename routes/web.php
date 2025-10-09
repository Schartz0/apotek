<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'pages.login');
Route::view('/draft', 'pages.draft'); 
Route::view('/buat', 'pages.buat'); 
Route::view('/list', 'pages.list');
Route::view('/detail', 'pages.detail');
Route::view('/produk/service', 'pages.produk_service');
Route::view('/produk/obat',    'pages.produk_obat');
