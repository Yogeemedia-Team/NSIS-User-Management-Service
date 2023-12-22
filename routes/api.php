<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\UserActivityController;
use App\Http\Controllers\UserAssigningController;
use App\Http\Controllers\UserLevelController;
use App\Http\Controllers\UserRoleController;

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
Route::post('/login', [LoginController::class,'login']);
Route::post('/register', [RegisterController::class,'register']);


Route::middleware('auth:api')->group(function () {
    Route::get('/profile', [ProfileController::class,'show']);
    Route::post('/logout', [ProfileController::class,'logout']);
    Route::get('/permissions', [PermissionController::class,'index']);


    Route::apiResource('/user_levels', UserLevelController::class);

    Route::apiResource('/user_roles', UserRoleController::class);

    Route::apiResource('/user_activities', UserActivityController::class);

    Route::apiResource('/user_assignees', UserAssigningController::class);

    Route::apiResource('/students', StudentController::class);
    
    
    
   
});

