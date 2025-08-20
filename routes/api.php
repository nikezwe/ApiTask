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
| Ici on déclare toutes les routes API protégées par Sanctum.
|
*/

Route::prefix('user')->group(function () {
    // Authentification publique
    Route::post('/login', [UserController::class, 'login']);
    Route::post('/register', [UserController::class, 'register']);

    // Logout protégé (token nécessaire)
    Route::middleware('auth:sanctum')->post('/logout', [UserController::class, 'logout']);
});

Route::middleware(['auth:sanctum'])->group(function () {
    // Récupération des infos utilisateur connecté
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Gestion des tâches
    Route::apiResource('tasks', TaskController::class);
    Route::apiResource('user-tasks', UserTaskController::class);
});
