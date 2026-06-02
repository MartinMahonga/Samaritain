<?php

use App\Http\Controllers\Socialite\ProviderCallbackController;
use App\Http\Controllers\Socialite\ProviderRedirectController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
})->name('index');

Route::get('/auth/{provider}/redirect', ProviderRedirectController::class)->name('auth.redirect');
Route::get('/auth/{provider}/callback', ProviderCallbackController::class)->name('auth.callback');

Route::get('/home', function () {
    return view('pages.home');
})->middleware('auth')->name('home');

Route::get('/dashboard', function () {
    return view('pages.dashboard.index');
})->middleware('auth')->name('dashboard');