<?php
use App\Http\Controllers\{AppController, AuthController};
use Illuminate\Support\Facades\Route;

Route::get('/', [AppController::class, 'index']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/logout', [AuthController::class, 'logout']);
Route::post('/sync', [AppController::class, 'sync'])->middleware('auth');
