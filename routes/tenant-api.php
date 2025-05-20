<?php
declare(strict_types=1);

use App\Http\Controllers\Api\Tenant\CommentController;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use App\Http\Controllers\Api\Tenant\TenantUserController;
use App\Http\Controllers\Api\Tenant\MenuController;
use App\Http\Controllers\Api\Tenant\RoleController;
use App\Http\Controllers\Api\Tenant\ProjectController;
use App\Http\Controllers\Api\Tenant\IssueController;
use App\Http\Controllers\Api\TenantController;
use App\Http\Middleware\FileUrlMiddleware;
use App\Models\Tenant;
Route::middleware([
    'api', 
    InitializeTenancyByDomain::class, 
    PreventAccessFromCentralDomains::class,
    FileUrlMiddleware::class
])->prefix('api')->group(function () {
    // Route::get('/tenant-test-route', function () {
    //     return response()->json(['message' => 'Tenant API is working!']);
    // });
    // Route::get('/test-notify', function () {
    //     $issue = \App\Models\Tenant\Issue::first();
    //     $user = \App\Models\Tenant\User::first();
    //     $user->notify(new \App\Notifications\IssueUpdatedNotification($issue));
    
    //     return 'Notification sent';
    // });

     // Tenant-specific authentication
    Route::post('/login', [TenantUserController::class, 'login']);
    Route::post('/register', [TenantUserController::class, 'register'])->name('api.register');
    Route::post('/logout', [TenantUserController::class, 'logout'])->middleware('auth:sanctum');
    Route::get('/tenant-info', function () {
        $tenant = tenant();     
        if ($tenant) {
            return response()->json([
                'uuid' => $tenant->id,
                'name' => $tenant->name,
            ]);
        }    
        return response()->json(['error' => 'Tenant not identified'], 404);
    });
    // Protected routes for tenants
    Route::middleware(['auth:sanctum', 'projectContext'])->group(function () {
        //Route for dropdown data 
        Route::prefix('dropdowns')->group(function () {
            Route::get('/users/{project}', [TenantUserController::class, 'getUsersByProject']);
            Route::get('/projects', [ProjectController::class, 'index']);          
            Route::get('/timezones', [TenantController::class, 'getTimeZone']);          
        });
        Route::get('/users', [TenantUserController::class, 'index'])->middleware('menuPermission:users,can_view');
        Route::post('/users', [TenantUserController::class, 'store'])->middleware('menuPermission:users,can_add');
        Route::get('/users/{id}', [TenantUserController::class, 'show'])->middleware('menuPermission:users,can_view');
        Route::put('/users/{id}', [TenantUserController::class, 'update'])->middleware('menuPermission:users,can_edit');
        Route::delete('/users/{ids}', [TenantUserController::class, 'destroy'])->middleware('menuPermission:users,can_delete');
        Route::get('/profile/{id}', [TenantUserController::class, 'show']);
        Route::put('/profile/{id}', [TenantUserController::class, 'profileUpdate'])->name('profile.update');
        Route::post('/profile/{id}/profileImage', [TenantUserController::class, 'uppdateProfileImage']);
        Route::post('/change-password', [TenantUserController::class, 'changePassword']);
        Route::get('/roles', [RoleController::class, 'index']);
        Route::post('/roles/{roleId}/permissions', [RoleController::class, 'updatePermissions']);   
        Route::post('/roles/{roleId}/bulk-permissions', [RoleController::class, 'updateBulkPermissions']);   


        // Add more tenant-specific routes
        Route::apiResource('menus', MenuController::class);
        Route::get('role-menus', [MenuController::class, 'getMenuByRole']);
        Route::get('menu-permission/{id}', [MenuController::class, 'menuPermission']);
       
       
        Route::controller(ProjectController::class)->group(function () {
            Route::get('projects', 'index')->middleware('menuPermission:project,can_view');
            Route::post('projects', 'store')->middleware('menuPermission:project,can_add');
            Route::get('projects/{project}', 'show')->middleware('menuPermission:project,can_view');
            Route::put('projects/{project}', 'update')->middleware('menuPermission:project,can_edit');
            Route::delete('projects/{project}', 'destroy')->middleware('menuPermission:project,can_delete');
            Route::get('projects/{project}/assigned-users', 'getAssignedUsers')->middleware('menuPermission:project,can_view');
            Route::post('assign-users', 'assignUsers')->middleware('menuPermission:project,can_add');
        });
        Route::controller(IssueController::class)->group(function () {
            Route::get('issues', 'index')->middleware('menuPermission:issues,can_view');
            Route::post('issues', 'store')->middleware('menuPermission:issues,can_add');
            Route::get('issues/{issues}', 'show')->middleware('menuPermission:issues,can_view');
            Route::put('issues/{issues}', 'update')->middleware('menuPermission:issues,can_edit');
            Route::delete('issues/{issues}', 'destroy')->middleware('menuPermission:issues,can_delete');
            Route::post('issues/{issues}/removeAttachment', 'removeAttachment')->middleware('menuPermission:issues,can_edit');
            Route::post('issues/{issue}/subscribe', 'subscribe');
            Route::post('issues/{issue}/unsubscribe', 'unsubscribe');
        });
        Route::controller(CommentController::class)->group(function () {
            Route::get('comments', 'index')->middleware('menuPermission:comments,can_view');
            Route::post('comments', 'store')->middleware('menuPermission:comments,can_add');
            Route::get('comments/{comments}', 'show')->middleware('menuPermission:comments,can_view');
            Route::put('comments/{comments}', 'update')->middleware('menuPermission:comments,can_edit');
            Route::delete('comments/{comments}', 'destroy')->middleware('menuPermission:comments,can_delete');
            Route::post('comments/{comments}/removeAttachment', 'removeAttachment')->middleware('menuPermission:comments,can_edit');
            Route::get('issues/{issue}/getComments', 'getIssueComments')->middleware('menuPermission:issues,can_view');
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