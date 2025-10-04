<?php

use App\Http\Controllers\API\TaskController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;



Route::post('register',[AuthController::class,'register']);
Route::post('login',[AuthController::class,'login']);



Route::middleware(['auth:sanctum','throttle:60,1'])->group(function(){
    Route::post('logout',[AuthController::class,'logout']);

    // Tasks
    Route::prefix('tasks')->group(function () {
        Route::get('all', [TaskController::class, 'getAllTasks']);
        Route::get('/', [TaskController::class, 'getTasksOwner']);
        Route::post('/', [TaskController::class, 'create']);
        Route::get('{task}', [TaskController::class, 'getTaskById']);
        Route::put('{task}', [TaskController::class, 'update']);
        Route::delete('{task}', [TaskController::class, 'delete']);
        Route::post('{task}/complete', [TaskController::class, 'complete']);
    });


});

