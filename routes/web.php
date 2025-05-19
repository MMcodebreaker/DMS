<?php
use App\Http\Controllers\Page\DashboardController;
use App\Http\Controllers\Page\AuthenticationController;
use App\Http\Controllers\Page\UserController;
use Illuminate\Support\Facades\Route;



Route::get('/', [ AuthenticationController::class, 'login_page' ])->name('page.login');
Route::post('/login', [ AuthenticationController::class, 'login' ])->name('action.login');
Route::post('/logout', [ AuthenticationController::class, 'logout' ])->name('action.logout');


Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('page.dashboard');
    Route::get('/users', [ UserController::class, 'index' ])->name('page.users');
});