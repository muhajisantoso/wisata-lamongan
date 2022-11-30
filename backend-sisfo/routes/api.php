<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Master\AcaraController;
use App\Http\Controllers\Master\DestinasiController;
use App\Http\Controllers\Master\KategoriController;
use App\Http\Controllers\Master\KulinerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'auth', 'as' => 'auth.'], function () {
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum')->name('logout');
});

Route::group(['prefix' => 'master', 'as' => 'master.', 'middleware' => 'auth:sanctum'], function () {
    Route::get('kategori/json', [KategoriController::class, 'json'])->name('kategori.json');
    Route::get('destinasi/json', [DestinasiController::class, 'json'])->name('destinasi.dt');
    Route::get('kuliner/json', [KulinerController::class, 'json'])->name('kuliner.dt');

    Route::apiResources([
        'kategori' => KategoriController::class,
        'destinasi' => DestinasiController::class,
        'kuliner' => KulinerController::class,
    ]);
});
