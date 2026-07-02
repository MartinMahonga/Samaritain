<?php

use App\Http\Controllers\Admin\ArtisanController as AdminArtisanController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\InvitationController;
use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Admin\ParcelleController;
use App\Http\Controllers\Admin\PropertyController as AdminPropertyController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\ArtisanContactController;
use App\Http\Controllers\ArtisanController;
use App\Http\Controllers\ArtisanProjectController;
use App\Http\Controllers\ArtisanReviewController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ParcelleWebController;
use App\Http\Controllers\PassController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\ScanController;
use App\Http\Controllers\Socialite\ProviderCallbackController;
use App\Http\Controllers\Socialite\ProviderRedirectController;
use App\Http\Controllers\VisitRequestController;
use App\Http\Middleware\StaffMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AvisController;


Route::get('/test', function () {
    return view('layouts.user-dashboard');
});

Route::get('/', [HomeController::class, 'index'])->name('index');

// Routes publiques pour les biens (utilisateur)
Route::get('properties', [PropertyController::class, 'index'])->name('property.index');
Route::get('properties/search', [PropertyController::class, 'search'])->name('property.search');
Route::get('properties/city/{city}', [PropertyController::class, 'byCity'])->name('property.byCity');
Route::get('properties/category/{category}', [PropertyController::class, 'byCategory'])->name('property.byCategory');

// Routes protégées pour les biens (CRUD utilisateur)
Route::middleware(['auth', 'verified'])->group(function () {
    // Profil
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile/info', [ProfileController::class, 'updateInfo'])->name('profile.update-info');
    Route::put('/profile/photo', [ProfileController::class, 'updatePhoto'])->name('profile.update-photo');
    Route::delete('/profile/photo', [ProfileController::class, 'deletePhoto'])->name('profile.delete-photo');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('my-properties/dashboard', [PropertyController::class, 'dashboard'])->name('property.dashboard');
    Route::post('property/{property}/duplicate', [PropertyController::class, 'duplicate'])->name('property.duplicate');

    // CRUD utilisateur
    Route::get('property/create', [PropertyController::class, 'create'])->name('property.create');
    Route::post('property', [PropertyController::class, 'store'])->name('property.store');
    Route::get('property/{property}/edit', [PropertyController::class, 'edit'])->name('property.edit');
    Route::put('property/{property}', [PropertyController::class, 'update'])->name('property.update');
    Route::delete('property/{property}', [PropertyController::class, 'destroy'])->name('property.destroy');
});

Route::get('property/{property}', [PropertyController::class, 'show'])->name('property.show');

// Favorite system
Route::post('/properties/{property}/favorite', [FavoriteController::class, 'toggle'])
    ->middleware(['auth', 'verified'])
    ->name('property.favorite');

Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorite')->middleware(['auth', 'verified']);

// Admin routes
Route::prefix('/admin/dashboard')->middleware(['auth', 'verified', StaffMiddleware::class])->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('index');
    Route::resource('property', AdminPropertyController::class);

    // Routes pour la gestion des statuts
    Route::patch('property/{property}/verify', [AdminPropertyController::class, 'verify'])->name('property.verify');
    Route::patch('property/{property}/unverify', [AdminPropertyController::class, 'unverify'])->name('property.unverify');
    Route::patch('property/{property}/enable', [AdminPropertyController::class, 'enable'])->name('property.enable');
    Route::patch('property/{property}/disable', [AdminPropertyController::class, 'disable'])->name('property.disable');
    Route::patch('property/{property}/toggle-active', [AdminPropertyController::class, 'toggleActive'])->name('property.toggle-active');
    Route::patch('property/{property}/toggle-verify', [AdminPropertyController::class, 'toggleVerify'])->name('property.toggle-verify');

    Route::resource('parcelle', ParcelleController::class)->except('show');

    // Artisans
    Route::get('/artisans', [AdminArtisanController::class, 'index'])->name('artisans.index');
    Route::get('/artisans/pending', [AdminArtisanController::class, 'pending'])->name('artisans.pending');
    Route::get('/artisans/{artisan}', [AdminArtisanController::class, 'show'])->name('artisans.show');
    Route::post('/artisans/{artisan}/verify', [AdminArtisanController::class, 'verify'])->name('artisans.verify');
    Route::post('/artisans/{artisan}/suspend', [AdminArtisanController::class, 'suspend'])->name('artisans.suspend');
    Route::delete('/artisans/{artisan}', [AdminArtisanController::class, 'destroy'])->name('artisans.destroy');
});

// Socialite
Route::get('/auth/{provider}/redirect', ProviderRedirectController::class)->name('auth.redirect');
Route::get('/auth/{provider}/callback', ProviderCallbackController::class)->name('auth.callback');

// Parcelles
Route::get('/parcelles', [ParcelleWebController::class, 'index'])->name('parcelles.index');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/parcelles/create', [ParcelleWebController::class, 'create'])->name('parcelles.create');
    Route::post('/parcelles', [ParcelleWebController::class, 'store'])->name('parcelles.store');
    Route::get('/parcelles/{id}/edit', [ParcelleWebController::class, 'edit'])->name('parcelles.edit');
});

Route::get('/parcelles/{id}', [ParcelleWebController::class, 'show'])->name('parcelles.show');

// Artisans (public)
Route::get('/artisans', [ArtisanController::class, 'index'])->name('artisans.index');
Route::get('/artisans/{artisan:slug}', [ArtisanController::class, 'show'])->name('artisans.show');

// Contact
Route::post('/artisans/{artisan:slug}/contact', [ArtisanContactController::class, 'store'])->middleware('throttle:5,1')->name('artisans.contact.store');

// Routes authentifiées pour artisans
Route::middleware(['auth', 'verified'])->group(function () {
    // Devenir artisan
    Route::get('/devenir-artisan', [ArtisanController::class, 'create'])->name('artisan.create');
    Route::post('/devenir-artisan', [ArtisanController::class, 'store'])->name('artisan.store');

    // Dashboard artisan
    Route::get('/artisan/dashboard', [ArtisanController::class, 'dashboard'])->name('artisan.dashboard');

    // Édition profil artisan
    Route::get('/artisan/{artisan}/edit', [ArtisanController::class, 'edit'])->name('artisan.edit');
    Route::put('/artisan/{artisan}', [ArtisanController::class, 'update'])->name('artisan.update');

    // Gestion des réalisations
    Route::get('/artisan/{artisan}/projects', [ArtisanProjectController::class, 'index'])->name('artisan.projects.index');
    Route::get('/artisan/{artisan}/projects/create', [ArtisanProjectController::class, 'create'])->name('artisan.projects.create');
    Route::post('/artisan/{artisan}/projects', [ArtisanProjectController::class, 'store'])->name('artisan.projects.store');
    Route::get('/artisan/{artisan}/projects/{project}/edit', [ArtisanProjectController::class, 'edit'])->name('artisan.projects.edit');
    Route::put('/artisan/{artisan}/projects/{project}', [ArtisanProjectController::class, 'update'])->name('artisan.projects.update');
    Route::delete('/artisan/{artisan}/projects/{project}', [ArtisanProjectController::class, 'destroy'])->name('artisan.projects.destroy');

    // Gestion des avis
    Route::post('/artisans/{artisan:slug}/reviews', [ArtisanReviewController::class, 'store'])->middleware('throttle:10,1')->name('artisans.reviews.store');
    Route::put('/artisans/{artisan:slug}/reviews/{review}', [ArtisanReviewController::class, 'update'])->name('artisans.reviews.update');
    Route::delete('/artisans/{artisan:slug}/reviews/{review}', [ArtisanReviewController::class, 'destroy'])->name('artisans.reviews.destroy');
});

// Routes d'authentification email
Route::middleware(['auth', 'verified'])->group(function () {
    // Pass management
    Route::resource('passes', PassController::class);
    Route::get('passes/{pass}/export', [PassController::class, 'export'])->name('passes.export');

    // Scan management
    Route::get('/scan', [ScanController::class, 'index'])->name('scan.index');
    Route::get('/scan/{uuid}', [ScanController::class, 'show'])->name('scan.show');
    Route::post('/scan/process', [ScanController::class, 'process'])->name('scan.process');

    // Statistics
    Route::get('/statistics', [PassController::class, 'statistics'])->name('statistics');
});

// Admin routes with staff middleware
Route::middleware(['auth', 'verified', StaffMiddleware::class])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // Membres
        Route::resource('members', MemberController::class)->except(['create', 'store']);
        Route::patch('members/{member}/deactivate', [MemberController::class, 'deactivate'])->name('members.deactivate');
        Route::patch('members/{member}/activate', [MemberController::class, 'activate'])->name('members.activate');

        // Invitations
        Route::resource('invitations', InvitationController::class)->except(['show', 'edit', 'update']);
        Route::post('invitations/{invitation}/resend', [InvitationController::class, 'resend'])->name('invitations.resend');
        Route::get('invitations/accept', [InvitationController::class, 'acceptForm'])->name('invitations.accept.form');
        Route::post('invitations/{invitation}/accept', [InvitationController::class, 'accept'])
            ->middleware('throttle:10,60')
            ->name('invitations.accept');
        Route::post('invitations/{invitation}/decline', [InvitationController::class, 'decline'])->name('invitations.decline');

        // Rôles et permissions
        Route::resource('roles', RoleController::class);
    });

Route::post('/visit-requests', [VisitRequestController::class, 'store'])->middleware('throttle:5,1')->name('visit-requests.store');

Route::middleware(['auth', 'verified'])->group(function () {
    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.api');
    Route::patch('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
    Route::patch('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::delete('/notifications/destroy-all', [NotificationController::class, 'destroyAll'])->name('notifications.destroy-all');
    Route::delete('/notifications/destroy-read', [NotificationController::class, 'destroyRead'])->name('notifications.destroy-read');
    Route::get('/notifications/all', [NotificationController::class, 'showAll'])->name('notifications.all');
});


Route::middleware('auth')->group(function () {
    Route::post('/avis', [AvisController::class, 'store'])->name('avis.store');
    Route::get('/avis', [AvisController::class, 'index'])->name('avis.index');
    Route::delete('/avis/{avis}', [AvisController::class, 'destroy'])->name('avis.destroy');
});
// Route::get('/debug-signature', function (Request $request) {
//     return response()->json([
//         'full_url' => $request->fullUrl(),
//         'has_valid_signature' => $request->hasValidSignature(),
//         'scheme' => $request->getScheme(),
//         'host' => $request->getHost(),
//         'forwarded_proto' => $request->header('X-Forwarded-Proto'),
//         'forwarded_host' => $request->header('X-Forwarded-Host'),
//     ]);
// });
