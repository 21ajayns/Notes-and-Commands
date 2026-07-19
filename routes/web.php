<?php

use App\Http\Controllers\CommandController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\RegisterController;
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

Route::middleware('auth')->group(function () {
    Route::get('/', [ContentController::class, 'getAll'])->name('content.index');

    Route::post('/folders', [FolderController::class, 'create'])->name('folders.store');

    Route::post('/notes', [NoteController::class, 'create'])->name('notes.store');

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.attempt');

    Route::get('/register', [RegisterController::class, 'show'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.store');
});

Route::get('/commands', [CommandController::class, 'index'])->name('commands.index');
