<?php
use App\Http\Controllers\Page\DashboardController;
use App\Http\Controllers\Page\AuthenticationController;
use App\Http\Controllers\Page\UserController;
use App\Http\Controllers\Page\ForgotPasswordController;
use App\Http\Controllers\Page\ResetPasswordController;
use Illuminate\Support\Facades\Route;



Route::get('/', [ AuthenticationController::class, 'login_page' ])->name('page.login');
Route::post('/login', [ AuthenticationController::class, 'login' ])->name('action.login');
Route::post('/logout', [ AuthenticationController::class, 'logout' ])->name('action.logout');


Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('page.dashboard');
    Route::get('/users', [ UserController::class, 'index' ])->name('page.users');
    Route::post('/users/delete', [ UserController::class, 'delete' ])->name('page.users.delete');
    Route::post('/users/update', [ UserController::class, 'update' ])->name('page.users.update');
    Route::post('/users/store', [ UserController::class, 'store' ])->name('page.users.store');
});


Route::middleware('guest')->group(function () {
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

    Route::get('/password-reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
});