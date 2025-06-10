<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use App\Http\Controllers\App\ProfileController;
use App\Http\Controllers\App\UserController;
use App\Http\Controllers\App\DashboardController;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {
    Route::get('/', function () {
		return view('App.welcome');
	});
           Route::get('/dashboard1',function(){
                return view('App.dashboard1');
            });
	
	Route::middleware('auth:tenant')->group(function () {
		Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
 
		Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
		Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
		Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
        Route::middleware('role:admin')->group(function () {
            Route::resource('user', UserController::class);
            // Route::resource('project', UserController::class);        
            // Route::resource('category', UserController::class);        
            // Route::resource('status', UserController::class);        
        });
	});
    require __DIR__.'/tenant-auth.php';
});
