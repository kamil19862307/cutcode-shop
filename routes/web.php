<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->group(function(){
    Route::get('/login', 'index')->name('login');
    Route::post('/login', 'signIn')
        ->middleware('throttle:auth')
        ->name('signIn');

    Route::get('/sign-up', 'signUp')->name('signUp');

    Route::post('/sign-up', 'store')
        ->middleware('throttle:auth')
        ->name('store');

    Route::delete('/logout', 'logOut')->name('logOut');

//    Route::get('/forgot-password', 'forgotPassword')->name('forgotPassword');

    Route::get('/forgot-password', 'forgot')
        ->middleware('guest')
        ->name('password.request');

    Route::post('/forgot-password', 'forgotPassword')
        ->middleware('guest')
        ->name('password.email');

    Route::get('/reset-password/{token}', 'reset')
        ->middleware('guest')
        ->name('password.reset');

    Route::post('/reset-password', 'resetPassword')
        ->middleware('guest')
        ->name('password.update');

});

Route::get('/', HomeController::class)->name('home');


