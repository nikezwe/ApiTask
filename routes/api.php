<?php

use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\UserTaskController;
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

Route::prefix('user')->group(function () {
    Route::post('/login', [UserController::class, 'login']);
    Route::post('/register',[UserController::class,'register']);
    Route::post('/logout',[UserController::class,'logout']);
});


Route::middleware(['auth:sanctum'])->group(function () {
    Route::get("/user", function(Request $request){
        return $request->user();
    });
    Route::apiResource('tasks', TaskController::class);
    Route::apiResource('user-tasks', UserTaskController::class);
});



