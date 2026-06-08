<?php

use App\Http\Controllers\Admin\PropertyController;
use App\Http\Controllers\Socialite\ProviderCallbackController;
use App\Http\Controllers\Socialite\ProviderRedirectController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ParcelleController;
use App\Http\Controllers\ParcelleWebController;


Route::get('/', function () {
    return view('index');
})->name('index');

Route::prefix('/admin/dashboard')->middleware('auth')->name('admin.')->group(function () {
    Route::resource('property', PropertyController::class)->except('show');
});

Route::get('/auth/{provider}/redirect', ProviderRedirectController::class)->name('auth.redirect');
Route::get('/auth/{provider}/callback', ProviderCallbackController::class)->name('auth.callback');

Route::get('/home', function () {
    return view('pages.home');
})->middleware('auth')->name('home');


Route::get('/dashboard', function () {
    return view('pages.dashboard.index');
})->middleware('auth')->name('dashboard');

Route::get('/parcelles', [ParcelleWebController::class, 'index'])->name('parcelles.index');
Route::get('/parcelles/create', [ParcelleWebController::class, 'create'])->name('parcelles.create');
Route::post('/parcelles', [ParcelleWebController::class, 'store'])->name('parcelles.store');
Route::get('/parcelles/{id}', [ParcelleWebController::class, 'show'])->name('parcelles.show');
Route::get('/parcelles/{id}/edit', [ParcelleWebController::class, 'edit'])->name('parcelles.edit');