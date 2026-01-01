<?php

// tafeld/routes/web.auth.php
// Öffentliche Auth-Routen (Login, Registrierung, Passwort, Verifikation)

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use App\Support\RouteAudit;

use App\Livewire\Pages\Auth\Login;
use App\Livewire\Pages\Auth\Register;
use App\Livewire\Pages\Auth\ForgotPassword;
use App\Livewire\Pages\Auth\ResetPassword;
use App\Livewire\Pages\Auth\VerifyEmail;
use App\Livewire\Pages\Auth\ConfirmPassword;

route_if_exists(Login::class, function () {
    Route::get('/login', Login::class)->name('login');
});

route_if_exists(Register::class, function () {
    Route::get('/register', Register::class)->name('register');
});

route_if_exists(ForgotPassword::class, function () {
    Route::get('/forgot-password', ForgotPassword::class)->name('password.request');
});

route_if_exists(ResetPassword::class, function () {
    Route::get('/reset-password/{token}', ResetPassword::class)->name('password.reset');
});

route_if_exists(VerifyEmail::class, function () {
    Route::get('/verify-email', VerifyEmail::class)->name('verification.notice');
});

route_if_exists(ConfirmPassword::class, function () {
    Route::get('/confirm-password', ConfirmPassword::class)->name('password.confirm');
});
