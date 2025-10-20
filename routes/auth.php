<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetPhoneController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyPhoneController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {

    // Регистрация
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    // Сброс пароля
    Route::get('forgot-password', [PasswordResetPhoneController::class, 'create'])
        ->name('forgot-password');
    Route::post('forgot-password', [PasswordResetPhoneController::class, 'store']);

    Route::get('confirm-phone', [PasswordResetPhoneController::class, 'confirmPhone'])->name('confirm-phone');
});

Route::middleware('auth')->group(function () {

    // Подтверждения телефона
    Route::get('phone-verify', [VerifyPhoneController::class, 'index'])->name('phone.verify');
    Route::post('phone-verify', [VerifyPhoneController::class, 'store']);

    // Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
    //     ->name('password.confirm');

    // Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');

    Route::get('new-password', [PasswordResetPhoneController::class, 'newPassword'])->name('new-password');
    Route::post('new-password', [PasswordResetPhoneController::class, 'newPasswordStore'])->name('new-password.store');
});
