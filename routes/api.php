<?php

use App\Http\Controllers\Folder\FolderCreateController;
use App\Http\Controllers\Folder\FolderGetController;
use App\Http\Controllers\Note\NoteCreateController;
use App\Http\Controllers\Note\NoteGetController;
use App\Http\Controllers\Note\NoteShowController;
use App\Http\Controllers\Note\NoteUpdateController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/folders', FolderCreateController::class);
Route::get('/folders', FolderGetController::class);
Route::post('/notes', NoteCreateController::class);
Route::get('/notes', NoteGetController::class);
Route::get('/notes/{note}', NoteShowController::class);
Route::put('/notes/{note}', NoteUpdateController::class);
