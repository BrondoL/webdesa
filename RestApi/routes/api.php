<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DusunController;
use App\Http\Controllers\WargaController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/auth/login', [AuthController::class, 'login'])->name('auth.login');
Route::get('/dusun/total', [DusunController::class, 'getDusun'])->name('dusun.getDusun');
Route::get('/warga/total', [WargaController::class, 'getWarga'])->name('warga.getWarga');
Route::resource('auth', AuthController::class);
Route::resource('dusun', DusunController::class);
Route::resource('warga', WargaController::class);
