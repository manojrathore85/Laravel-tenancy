<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthenticatedSessionController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\RegisteredUserController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\TenantController;
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
Route::group(['middeleware'=> 'api'],function (){
    Route::post('/register', [RegisteredUserController::class, 'store']);
    Route::post('/login',[AuthenticatedSessionController::class,'store'] ); 
    Route::middleware('auth:sanctum')->group(function () {
        Route::apiResource('users', UserController::class);
        Route::get('/profile/{id}', [ProfileController::class, 'edit']);
        Route::patch('/profile/{id}', [ProfileController::class, 'update']);
        Route::delete('/profile/{id}', [ProfileController::class, 'destroy']);
        Route::apiResource('tenants', TenantController::class);

    
    });
   
});
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
