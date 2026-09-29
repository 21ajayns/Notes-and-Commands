<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LoginFormController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\RegisterFormController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', LoginFormController::class)->name('login');
    Route::post('/login', LoginController::class)->middleware('throttle:10,1');
    Route::get('/register', RegisterFormController::class)->name('register');
    Route::post('/register', RegisterController::class)->middleware('throttle:10,1');
});

Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return view('notes.index');
    })->name('home');

    Route::post('/logout', LogoutController::class)->name('logout');
});
