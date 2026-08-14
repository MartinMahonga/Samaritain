<?php

use App\Http\Controllers\Admin\AmenityController;
use App\Http\Controllers\Admin\ArtisanCategoryController;
use App\Http\Controllers\Admin\ArtisanController as AdminArtisanController;
use App\Http\Controllers\Admin\ArtisanProjectController as AdminArtisanProjectController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\InvitationController;
use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Admin\ParcelleCategoryController;
use App\Http\Controllers\Admin\ParcelleController;
use App\Http\Controllers\Admin\PropertyController as AdminPropertyController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\AgencyContactController;
use App\Http\Controllers\ArtisanContactController;
use App\Http\Controllers\ArtisanController;
use App\Http\Controllers\ArtisanProjectController;
use App\Http\Controllers\ArtisanReviewController;
use App\Http\Controllers\AvisController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Owner\ContractController;
use App\Http\Controllers\Owner\DocumentController;
use App\Http\Controllers\Owner\FinancialController;
use App\Http\Controllers\Owner\InspectionController;
use App\Http\Controllers\Owner\InterventionController;
use App\Http\Controllers\Owner\InvoiceController;
use App\Http\Controllers\ParcelleWebController;
use App\Http\Controllers\PassController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\ScanController;
use App\Http\Controllers\Socialite\ProviderCallbackController;
use App\Http\Controllers\Socialite\ProviderRedirectController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserVisitPassController;
use App\Http\Controllers\VisitRequestController;
use App\Http\Middleware\EnsureUserCanSignContract;
use App\Http\Middleware\StaffMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Agency contact
Route::get('property/{property}/contact', [AgencyContactController::class, 'propertyCreate'])->name('property.contact.create');
Route::post('property/{property}/contact', [AgencyContactController::class, 'propertyStore'])->middleware('throttle:5,1')->name('property.contact.store');
Route::get('parcelles/{parcelle}/contact', [AgencyContactController::class, 'parcelleCreate'])->name('parcelles.contact.create');
Route::post('parcelles/{parcelle}/contact', [AgencyContactController::class, 'parcelleStore'])->middleware('throttle:5,1')->name('parcelles.contact.store');

// Favorite system
Route::post('/properties/{property}/favorite', [FavoriteController::class, 'toggle'])
    ->middleware(['auth', 'verified'])
    ->name('property.favorite');

Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorite')->middleware(['auth', 'verified']);

Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/favorites/property/{property}', [FavoriteController::class, 'toggleProperty'])->name('property.favorite.toggle');
    Route::post('/favorites/parcel/{parcel}', [FavoriteController::class, 'toggleParcel'])->name('parcel.favorite');
    Route::delete('/favorites/property/{property}', [FavoriteController::class, 'destroyProperty'])->name('property.favorite.destroy');
    Route::delete('/favorites/parcel/{parcel}', [FavoriteController::class, 'destroyParcel'])->name('parcel.favorite.destroy');
});

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

    Route::resource('parcelle', ParcelleController::class);

    // Artisans
    Route::get('/artisans', [AdminArtisanController::class, 'index'])->name('artisans.index');
    Route::get('/artisans/create', [AdminArtisanController::class, 'create'])->name('artisans.create');
    Route::post('/artisans', [AdminArtisanController::class, 'store'])->name('artisans.store');
    Route::get('/artisans/pending', [AdminArtisanController::class, 'pending'])->name('artisans.pending');
    Route::get('/artisans/{artisan}', [AdminArtisanController::class, 'show'])->name('artisans.show');
    Route::get('/artisans/{artisan}/edit', [AdminArtisanController::class, 'edit'])->name('artisans.edit');
    Route::put('/artisans/{artisan}', [AdminArtisanController::class, 'update'])->name('artisans.update');
    Route::post('/artisans/{artisan}/verify', [AdminArtisanController::class, 'verify'])->name('artisans.verify');
    Route::post('/artisans/{artisan}/suspend', [AdminArtisanController::class, 'suspend'])->name('artisans.suspend');
    Route::delete('/artisans/{artisan}', [AdminArtisanController::class, 'destroy'])->name('artisans.destroy');

    // Réalisations (admin)
    Route::get('/artisans/{artisan}/projects', [AdminArtisanProjectController::class, 'index'])->name('artisans.projects.index');
    Route::get('/artisans/{artisan}/projects/create', [AdminArtisanProjectController::class, 'create'])->name('artisans.projects.create');
    Route::post('/artisans/{artisan}/projects', [AdminArtisanProjectController::class, 'store'])->name('artisans.projects.store');
    Route::get('/artisans/{artisan}/projects/{project}/edit', [AdminArtisanProjectController::class, 'edit'])->name('artisans.projects.edit');
    Route::put('/artisans/{artisan}/projects/{project}', [AdminArtisanProjectController::class, 'update'])->name('artisans.projects.update');
    Route::delete('/artisans/{artisan}/projects/{project}', [AdminArtisanProjectController::class, 'destroy'])->name('artisans.projects.destroy');
    Route::delete('/artisans/{artisan}/projects/image/{image}', [AdminArtisanProjectController::class, 'destroyImage'])->name('artisans.projects.image.destroy');

    // Configuration — nested group ensures "configuration." appears in route names.
    // Without it, Route::resource('configuration/category', ...) produces
    // "admin.category.index" instead of "admin.configuration.category.index".
    Route::prefix('configuration')->name('configuration.')->group(function () {
        // Configuration — Catégories de maisons
        Route::resource('category', CategoryController::class);
        Route::patch('category/{category}/toggle-active', [CategoryController::class, 'toggleActive'])->name('category.toggle-active');
        Route::post('category/update-sort', [CategoryController::class, 'updateSort'])->name('category.update-sort');

        // Configuration — Équipements
        Route::resource('amenity', AmenityController::class);
        Route::patch('amenity/{amenity}/toggle-active', [AmenityController::class, 'toggleActive'])->name('amenity.toggle-active');
        Route::post('amenity/update-sort', [AmenityController::class, 'updateSort'])->name('amenity.update-sort');

        // Configuration — Catégories d'artisans
        Route::resource('artisan-category', ArtisanCategoryController::class);
        Route::patch('artisan-category/{category}/toggle-active', [ArtisanCategoryController::class, 'toggleActive'])->name('artisan-category.toggle-active');
        Route::post('artisan-category/update-sort', [ArtisanCategoryController::class, 'updateSort'])->name('artisan-category.update-sort');

        // Configuration — Catégories de parcelles
        Route::resource('parcelle-category', ParcelleCategoryController::class);
        Route::patch('parcelle-category/{category}/toggle-active', [ParcelleCategoryController::class, 'toggleActive'])->name('parcelle-category.toggle-active');
        Route::post('parcelle-category/update-sort', [ParcelleCategoryController::class, 'updateSort'])->name('parcelle-category.update-sort');
    });
});

// Socialite
Route::get('/auth/{provider}/redirect', ProviderRedirectController::class)->name('auth.redirect');
Route::get('/auth/{provider}/callback', ProviderCallbackController::class)->name('auth.callback');

// Parcelles
Route::get('/parcelles', [ParcelleWebController::class, 'index'])->name('parcelles.index');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/parcelles/create', [ParcelleWebController::class, 'create'])->name('parcelles.create');
    Route::post('/parcelles', [ParcelleWebController::class, 'store'])->name('parcelles.store');
    Route::get('/parcelles/{parcelle}/edit', [ParcelleWebController::class, 'edit'])->name('parcelles.edit');
    Route::put('/parcelles/{parcelle}', [ParcelleWebController::class, 'update'])->name('parcelles.update');
    Route::delete('/parcelles/{parcelle}', [ParcelleWebController::class, 'destroy'])->name('parcelles.destroy');
    Route::delete('/parcelles/images/{image}', [ParcelleWebController::class, 'deleteImage'])->name('parcelles.images.destroy');
    Route::get('mes-parcelles/dashboard', [ParcelleWebController::class, 'dashboard'])->name('parcelles.dashboard');
});

Route::get('/parcelles/{parcelle}', [ParcelleWebController::class, 'show'])->name('parcelles.show');

// Artisans (public)
Route::get('/artisans', [ArtisanController::class, 'index'])->name('artisans.index');
Route::get('/artisans/{artisan:slug}', [ArtisanController::class, 'show'])->name('artisans.show');
Route::get('/artisans/{artisan:slug}/projects/{project}', [ArtisanProjectController::class, 'show'])->name('artisans.projects.show');

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
    Route::delete('/artisan/{artisan}/projects/image/{image}', [ArtisanProjectController::class, 'destroyImage'])->name('artisan.projects.image.destroy');

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

Route::get('/pay', [TransactionController::class, 'paymentPage'])
    ->middleware('auth')
    ->name('transactions.pay');

// pawaPay server-to-server callback (callbackUrl) — POST, CSRF-exempt via bootstrap/app.php
Route::post('/transactions/{transaction}/webhook', [TransactionController::class, 'handleCallback'])
    ->name('transactions.webhook');

// pawaPay browser redirect (returnUrl) — GET, user sees the result
Route::get('/transactions/{transaction}/callback', [TransactionController::class, 'callback'])
    ->middleware('auth')
    ->name('transactions.callback');

// Manual status check endpoint
Route::get('/transactions/{transaction}/status', [TransactionController::class, 'status'])
    ->middleware('auth')
    ->name('transactions.status');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/messenger', function () {
        $user = auth()->user();

        if ($user->hasRole('owner') || $user->isAdmin()) {
            return redirect()->route('owner.messenger');
        }

        if ($user->hasRole('tenant')) {
            return redirect()->route('tenant.messenger');
        }

        return view('pages.messenger.index');
    })->name('messenger');
});

// User visit passes routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/visit-pass/create/{visitPassable}', [UserVisitPassController::class, 'create'])
        ->name('my-visit-passes.create');
    Route::get('/visit-pass/create/parcelle/{visitPassable}', [UserVisitPassController::class, 'create'])
        ->name('my-visit-passes.create.parcelle');
    Route::post('/visit-pass', [UserVisitPassController::class, 'store'])
        ->name('my-visit-passes.store');
    Route::get('/my-visit-passes', [UserVisitPassController::class, 'index'])
        ->name('my-visit-passes.index');
    Route::get('/my-visit-passes/{visitPass}', [UserVisitPassController::class, 'show'])
        ->name('my-visit-passes.show');
    Route::get('/my-visit-passes/{visitPass}/download', [UserVisitPassController::class, 'download'])
        ->name('my-visit-passes.download');
    Route::post('/my-visit-passes/{visitPass}/retry-payment', [UserVisitPassController::class, 'retryPayment'])
        ->name('my-visit-passes.retry-payment');
    Route::delete('/my-visit-passes/{visitPass}', [UserVisitPassController::class, 'destroy'])
        ->name('my-visit-passes.destroy');
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

Route::get('/propos', function () {
    return view('apropos');
})->name('propos');

Route::get('/politique', function () {
    return view('politique');
})->name('politique');

Route::get('/conditions', function () {
    return view('conditions');
})->name('conditions');

Route::get('/apropo_S', function () {
    return view('apropo_S');
})->name('apropos_S');

// Owner Portal & Management Routes
Route::middleware(['auth', 'verified', 'owner'])->prefix('owner')->name('owner.')->group(function () {
    // Dashboard / Portal Overview
    Route::get('/dashboard', [App\Http\Controllers\Owner\DashboardController::class, 'index'])->name('dashboard');

    // Financial Dashboard & Stats
    Route::get('/financial', [FinancialController::class, 'index'])->name('financial');
    Route::get('/financial/export', [FinancialController::class, 'export'])->name('financial.export');

    // Contracts (Contrats)
    Route::get('/contracts', [ContractController::class, 'index'])->name('contracts.index');
    Route::get('/contracts/create', [ContractController::class, 'create'])->name('contracts.create');
    Route::post('/contracts', [ContractController::class, 'store'])->name('contracts.store');
    Route::get('/contracts/{contract}', [ContractController::class, 'show'])->name('contracts.show');
    Route::get('/contracts/{contract}/pdf', [ContractController::class, 'downloadPdf'])->name('contracts.pdf');
    Route::post('/contracts/{contract}/generate-rents', [ContractController::class, 'generateRents'])->name('contracts.generate-rents');
    Route::post('/contracts/{contract}/sign', [ContractController::class, 'sign'])->middleware(EnsureUserCanSignContract::class)->name('contracts.sign');
    Route::post('/contracts/{contract}/cancel', [ContractController::class, 'cancel'])->name('contracts.cancel');
    Route::delete('/contracts/{contract}', [ContractController::class, 'destroy'])->name('contracts.destroy');
    Route::post('/rent-payments/{rentPayment}/toggle-paid', [ContractController::class, 'togglePaid'])->name('rent-payments.toggle-paid');

    // Invoices (Factures)
    Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('/invoices/create', [InvoiceController::class, 'create'])->name('invoices.create');
    Route::post('/invoices', [InvoiceController::class, 'store'])->name('invoices.store');
    Route::get('/invoices/{invoice}/pdf', [InvoiceController::class, 'downloadPdf'])->name('invoices.pdf');
    Route::post('/invoices/{invoice}/toggle-paid', [InvoiceController::class, 'togglePaid'])->name('invoices.toggle-paid');
    Route::delete('/invoices/{invoice}', [InvoiceController::class, 'destroy'])->name('invoices.destroy');

    // Interventions / Maintenance
    Route::get('/interventions', [InterventionController::class, 'index'])->name('interventions.index');
    Route::get('/interventions/create', [InterventionController::class, 'create'])->name('interventions.create');
    Route::post('/interventions', [InterventionController::class, 'store'])->name('interventions.store');
    Route::get('/interventions/{intervention}', [InterventionController::class, 'show'])->name('interventions.show');
    Route::post('/interventions/{intervention}/status', [InterventionController::class, 'updateStatus'])->name('interventions.update-status');

    // Inspections (États des lieux)
    Route::get('/inspections', [InspectionController::class, 'index'])->name('inspections.index');
    Route::get('/inspections/create', [InspectionController::class, 'create'])->name('inspections.create');
    Route::post('/inspections', [InspectionController::class, 'store'])->name('inspections.store');
    Route::get('/inspections/compare', [InspectionController::class, 'compare'])->name('inspections.compare');
    Route::get('/inspections/{inspection}', [InspectionController::class, 'show'])->name('inspections.show');
    Route::get('/inspections/{inspection}/pdf', [InspectionController::class, 'downloadPdf'])->name('inspections.pdf');

    // Documents
    Route::get('/documents', [DocumentController::class, 'index'])->name('documents.index');
    Route::post('/documents', [DocumentController::class, 'store'])->name('documents.store');
    Route::get('/documents/{document}/download', [DocumentController::class, 'download'])->name('documents.download');
    Route::delete('/documents/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');

    // Messagerie
    Route::get('/messenger', function () {
        return view('pages.owner.messenger');
    })->name('messenger');
});

// Tenant Portal Routes
Route::middleware(['auth', 'verified', 'tenant'])->prefix('tenant')->name('tenant.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Tenant\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/contracts', [App\Http\Controllers\Tenant\DashboardController::class, 'contracts'])->name('contracts');
    Route::get('/contracts/{contract}', [App\Http\Controllers\Tenant\DashboardController::class, 'show'])->name('contracts.show');
    Route::post('/contracts/{contract}/sign', [App\Http\Controllers\Tenant\DashboardController::class, 'sign'])->middleware(EnsureUserCanSignContract::class)->name('contracts.sign');
    Route::get('/contracts/{contract}/pdf', [App\Http\Controllers\Tenant\DashboardController::class, 'downloadPdf'])->name('contracts.pdf');
    Route::get('/payments', [App\Http\Controllers\Tenant\DashboardController::class, 'payments'])->name('payments');
    Route::post('/rent-payments/{rentPayment}/pay', [App\Http\Controllers\Tenant\DashboardController::class, 'payRentPayment'])->name('rent-payments.pay');
    Route::get('/interventions', [App\Http\Controllers\Tenant\DashboardController::class, 'interventions'])->name('interventions');
    Route::get('/documents', [App\Http\Controllers\Tenant\DashboardController::class, 'documents'])->name('documents');
    Route::get('/documents/{document}/download', [App\Http\Controllers\Tenant\DashboardController::class, 'downloadDocument'])->name('documents.download');

    // Messagerie
    Route::get('/messenger', function () {
        return view('pages.tenant.messenger');
    })->name('messenger');
});
