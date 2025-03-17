<?php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use App\Http\Controllers\Api\Tenant\TenantUserController;

Route::middleware([
    'api', 
    InitializeTenancyByDomain::class, 
    PreventAccessFromCentralDomains::class
])->prefix('api')->group(function () {
    // Route::get('/tenant-test-route', function () {
    //     return response()->json(['message' => 'Tenant API is working!']);
    // });

     // Tenant-specific authentication
    Route::post('/login', [TenantUserController::class, 'login']);
    Route::post('/register', [TenantUserController::class, 'register']);
    Route::post('/logout', [TenantUserController::class, 'logout'])->middleware('auth:sanctum');

    // Protected routes for tenants
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/users', [TenantUserController::class, 'index']);
        Route::post('/users', [TenantUserController::class, 'store']);
        Route::get('/users/{id}', [TenantUserController::class, 'show']);
        Route::put('/users/{id}', [TenantUserController::class, 'update']);
        Route::delete('/users/{ids}', [TenantUserController::class, 'destroy']);
        Route::put('/profile/{id}', [TenantUserController::class, 'profileUpdate'])->name('profile.update');
        Route::post('/change-password', [TenantUserController::class, 'changePassword']);
        // Add more tenant-specific routes
    });
});




    // Route::middleware([
    //     'api',
    //     InitializeTenancyByDomain::class,
    //     PreventAccessFromCentralDomains::class,
    // ])->group(function () {
    //     Route::post('/login', [TenantUserController::class, 'login']);
    //     Route::post('/register', [TenantUserController::class, 'register']);
    //     Route::post('/logout', [TenantUserController::class, 'logout'])->middleware('auth:sanctum');
    // });