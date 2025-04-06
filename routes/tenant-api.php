<?php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use App\Http\Controllers\Api\Tenant\TenantUserController;
use App\Http\Controllers\Api\Tenant\MenuController;
use App\Http\Controllers\Api\Tenant\RoleController;
use App\Http\Controllers\Api\Tenant\ProjectController;

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
    Route::post('/register', [TenantUserController::class, 'register'])->name('api.register');
    Route::post('/logout', [TenantUserController::class, 'logout'])->middleware('auth:sanctum');

    // Protected routes for tenants
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/users', [TenantUserController::class, 'index'])->middleware('menuPermission:users,can_view');
        Route::post('/users', [TenantUserController::class, 'store'])->middleware('menuPermission:users,can_add');
        Route::get('/users/{id}', [TenantUserController::class, 'show'])->middleware('menuPermission:users,can_view');
        Route::put('/users/{id}', [TenantUserController::class, 'update'])->middleware('menuPermission:users,can_edit');
        Route::delete('/users/{ids}', [TenantUserController::class, 'destroy'])->middleware('menuPermission:users,can_delete');
        Route::put('/profile/{id}', [TenantUserController::class, 'profileUpdate'])->name('profile.update');
        Route::post('/change-password', [TenantUserController::class, 'changePassword']);
        Route::post('/roles/{roleId}/permissions', [RoleController::class, 'updatePermissions']);

        // Add more tenant-specific routes
        Route::apiResource('menus', MenuController::class);
        Route::get('role-menus/{roleId}', [MenuController::class, 'getMenuByRole']);
        Route::get('menu-permission/{id}', [MenuController::class, 'menuPermission']);
        Route::apiResource('roles', RoleController::class);
        Route::controller(ProjectController::class)->group(function () {
            Route::get('projects', 'index')->middleware('menuPermission:project,can_view');
            Route::post('projects', 'store')->middleware('menuPermission:project,can_add');
            Route::get('projects/{project}', 'show')->middleware('menuPermission:project,can_view');
            Route::put('projects/{project}', 'update')->middleware('menuPermission:project,can_edit');
            Route::delete('projects/{project}', 'destroy')->middleware('menuPermission:project,can_delete');
        });
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