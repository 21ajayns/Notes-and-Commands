<?php

use App\Http\Controllers\Command\CommandCreateController;
use App\Http\Controllers\Command\CommandDeleteController;
use App\Http\Controllers\Command\CommandGetController;
use App\Http\Controllers\Command\CommandShowController;
use App\Http\Controllers\Command\CommandUpdateController;
use App\Http\Controllers\CommandFolder\CommandFolderCreateController;
use App\Http\Controllers\CommandFolder\CommandFolderDeleteController;
use App\Http\Controllers\CommandFolder\CommandFolderGetController;
use App\Http\Controllers\CommandFolder\CommandFolderUpdateController;
use App\Http\Controllers\Folder\FolderCreateController;
use App\Http\Controllers\Folder\FolderDeleteController;
use App\Http\Controllers\Folder\FolderGetController;
use App\Http\Controllers\Folder\FolderUpdateController;
use App\Http\Controllers\Note\NoteCreateController;
use App\Http\Controllers\Note\NoteDeleteController;
use App\Http\Controllers\Note\NoteGetController;
use App\Http\Controllers\Note\NoteShowController;
use App\Http\Controllers\Note\NoteUpdateController;
use App\Http\Controllers\Task\TaskCreateController;
use App\Http\Controllers\Task\TaskDeleteController;
use App\Http\Controllers\Task\TaskGetController;
use App\Http\Controllers\Task\TaskUpdateController;
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
Route::put('/folders/{folder}', FolderUpdateController::class);
Route::delete('/folders/{folder}', FolderDeleteController::class);
Route::post('/notes', NoteCreateController::class);
Route::get('/notes', NoteGetController::class);
Route::get('/notes/{note}', NoteShowController::class);
Route::put('/notes/{note}', NoteUpdateController::class);
Route::delete('/notes/{note}', NoteDeleteController::class);
Route::post('/tasks', TaskCreateController::class);
Route::get('/tasks', TaskGetController::class);
Route::put('/tasks/{task}', TaskUpdateController::class);
Route::delete('/tasks/{task}', TaskDeleteController::class);
Route::post('/command-folders', CommandFolderCreateController::class);
Route::get('/command-folders', CommandFolderGetController::class);
Route::put('/command-folders/{commandFolder}', CommandFolderUpdateController::class);
Route::delete('/command-folders/{commandFolder}', CommandFolderDeleteController::class);
Route::post('/commands', CommandCreateController::class);
Route::get('/commands', CommandGetController::class);
Route::get('/commands/{command}', CommandShowController::class);
Route::put('/commands/{command}', CommandUpdateController::class);
Route::delete('/commands/{command}', CommandDeleteController::class);
