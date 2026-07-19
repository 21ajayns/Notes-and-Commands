<?php

use App\Http\Controllers\CommandController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\NoteController;
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
});

Route::get('/commands', [CommandController::class, 'index'])->name('commands.index');

Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
