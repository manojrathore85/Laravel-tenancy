<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TenantController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/debug', function () {
    return dd(Route::getRoutes());
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('tenants', TenantController::class);
    // Route::get('/tenants', [TenantController::class, 'index'])->name('tenants.index');
    // Route::get('/tenants/create', [TenantController::class, 'create'])->name('tenants.create');
    // Route::get('/tenants/{tenant}/delete', [TenantController::class, 'destroy'])->name('tenants.destroy');
});

require __DIR__.'/auth.php';
