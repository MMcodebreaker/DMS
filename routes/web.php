<?php
use App\Http\Controllers\Page\DashboardController;
use App\Http\Controllers\Page\AuthenticationController;
use App\Http\Controllers\Page\UserController;
use App\Http\Controllers\Page\PhysicianController;
use App\Http\Controllers\Page\PatientController;
use App\Http\Controllers\Page\SettingController;
use App\Http\Controllers\Page\DocumentController;
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

    Route::get('/physicians', [ PhysicianController::class, 'index' ])->name('page.physicians');
    Route::post('/physicians/delete', [ PhysicianController::class, 'delete' ])->name('page.physicians.delete');
    Route::post('/physicians/update', [ PhysicianController::class, 'update' ])->name('page.physicians.update');
    Route::post('/physicians/store', [ PhysicianController::class, 'store' ])->name('page.physicians.store');

    Route::get('/patients', [ PatientController::class, 'index' ])->name('page.patients');
    Route::post('/patients/delete', [ PatientController::class, 'delete' ])->name('page.patients.delete');
    Route::post('/patients/update', [ PatientController::class, 'update' ])->name('page.patients.update');
    Route::post('/patients/store', [ PatientController::class, 'store' ])->name('page.patients.store');

    Route::get('/settings', [ SettingController::class, 'index' ])->name('page.settings');
    Route::post('/settings/delete', [ SettingController::class, 'delete' ])->name('page.settings.delete');
    Route::post('/settings/update', [ SettingController::class, 'update' ])->name('page.settings.update');
    Route::post('/settings/store', [ SettingController::class, 'store' ])->name('page.settings.store');

    Route::get('/document', [ DocumentController::class, 'index' ])->name('page.document');
    Route::post('/document/storeDocumentV1', [ DocumentController::class, 'storeDocumentV1' ])->name('page.settings.storeDocumentV1');
});


Route::middleware('guest')->group(function () {
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

    Route::get('/password-reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
});