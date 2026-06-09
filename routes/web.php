<?php

use App\Http\Controllers\Admin\ParcelleController;
use App\Http\Controllers\Admin\PropertyController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ParcelleWebController;
use App\Http\Controllers\PropertyController as UsersPropertyController;
use App\Http\Controllers\Socialite\ProviderCallbackController;
use App\Http\Controllers\Socialite\ProviderRedirectController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('index');
Route::get('/properties', [UsersPropertyController::class, 'index'])
    ->name('property.index');
Route::get('/properties/{property}', [UsersPropertyController::class, 'show'])
    ->name('property.show');
//Favorite system
Route::post('/properties/{property}/favorite', [FavoriteController::class, 'toggle'])
    ->middleware('auth')
    ->name('property.favorite');

Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorite')->middleware('auth');

Route::prefix('/admin/dashboard')->middleware('auth')->name('admin.')->group(function () {
    Route::get('/', function () {
        return view('pages.admin.index');
    })->name('index');
    Route::resource('property', PropertyController::class)->except('show');
    Route::resource('parcelle', ParcelleController::class)->except('show');
});

Route::get('/auth/{provider}/redirect', ProviderRedirectController::class)->name('auth.redirect');
Route::get('/auth/{provider}/callback', ProviderCallbackController::class)->name('auth.callback');

Route::get('/home', function () {
    return view('pages.home');
})->middleware('auth')->name('home');

Route::get('/parcelles', [ParcelleWebController::class, 'index'])->name('parcelles.index');
Route::get('/parcelles/create', [ParcelleWebController::class, 'create'])->name('parcelles.create');
Route::post('/parcelles', [ParcelleWebController::class, 'store'])->name('parcelles.store');
Route::get('/parcelles/{id}', [ParcelleWebController::class, 'show'])->name('parcelles.show');
Route::get('/parcelles/{id}/edit', [ParcelleWebController::class, 'edit'])->name('parcelles.edit');