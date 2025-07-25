<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
// Inclure les routes d'authentification Breeze
//  require __DIR__.'/auth.php';

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:sanctum'])->prefix('tasks')->group(function () {
    Route::get('/', [App\Http\Controllers\Api\TaskController::class, 'index']);
    Route::post('/', [App\Http\Controllers\Api\TaskController::class, 'store']);
    Route::get('/{task}', [App\Http\Controllers\Api\TaskController::class, 'show']);
    // Route::put('/{task}', [App\Http\Controllers\Api\TaskController::class, 'update']);
});

Route::post('/user/login', [App\Http\Controllers\Api\UserController::class, 'login']);
Route::post('/user/register',[App\Http\Controllers\Api\UserController::class,'register']);
Route::post('/user/logout',[App\Http\Controllers\Api\UserController::class,'logout']);
