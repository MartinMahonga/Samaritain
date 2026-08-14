<?php

use Illuminate\Support\Facades\Route;

/**
 * Verify that all admin configuration route names include the "configuration."
 * segment. This is a regression test for a bug where Laravel's prefixedResource()
 * mechanism split "configuration/category" into a URI prefix + "category" resource
 * name, dropping "configuration." from the route names. The blade views and
 * controllers all reference admin.configuration.* route names.
 */
it('registers admin configuration route names with the configuration segment', function () {
    $expectedRoutes = [
        // Category (Catégories de maisons)
        'admin.configuration.category.index',
        'admin.configuration.category.create',
        'admin.configuration.category.store',
        'admin.configuration.category.show',
        'admin.configuration.category.edit',
        'admin.configuration.category.update',
        'admin.configuration.category.destroy',
        'admin.configuration.category.toggle-active',
        'admin.configuration.category.update-sort',

        // Amenity (Équipements)
        'admin.configuration.amenity.index',
        'admin.configuration.amenity.create',
        'admin.configuration.amenity.store',
        'admin.configuration.amenity.show',
        'admin.configuration.amenity.edit',
        'admin.configuration.amenity.update',
        'admin.configuration.amenity.destroy',
        'admin.configuration.amenity.toggle-active',
        'admin.configuration.amenity.update-sort',

        // ArtisanCategory (Catégories d'artisans)
        'admin.configuration.artisan-category.index',
        'admin.configuration.artisan-category.create',
        'admin.configuration.artisan-category.store',
        'admin.configuration.artisan-category.show',
        'admin.configuration.artisan-category.edit',
        'admin.configuration.artisan-category.update',
        'admin.configuration.artisan-category.destroy',
        'admin.configuration.artisan-category.toggle-active',
        'admin.configuration.artisan-category.update-sort',

        // ParcelleCategory (Catégories de parcelles)
        'admin.configuration.parcelle-category.index',
        'admin.configuration.parcelle-category.create',
        'admin.configuration.parcelle-category.store',
        'admin.configuration.parcelle-category.show',
        'admin.configuration.parcelle-category.edit',
        'admin.configuration.parcelle-category.update',
        'admin.configuration.parcelle-category.destroy',
        'admin.configuration.parcelle-category.toggle-active',
        'admin.configuration.parcelle-category.update-sort',
    ];

    foreach ($expectedRoutes as $name) {
        $route = Route::getRoutes()->getByName($name);
        expect($route)->not->toBeNull(
            "Route [{$name}] should be registered but was not found."
        );
    }
});

/**
 * Verify that the old (incorrect) route names without the "configuration." segment
 * are NOT registered, so we catch any regression where the nested group is removed.
 */
it('does not register admin configuration routes without the configuration segment', function () {
    $oldNames = [
        'admin.category.index',
        'admin.amenity.index',
        'admin.artisan-category.index',
        'admin.parcelle-category.index',
    ];

    foreach ($oldNames as $name) {
        $route = Route::getRoutes()->getByName($name);
        expect($route)->toBeNull(
            "Old route [{$name}] should not exist — it would indicate the 'configuration.' segment is missing from the route name."
        );
    }
});

/**
 * Verify that the route() helper can generate URLs for the key configuration
 * routes without throwing RouteNotFoundException. This is the exact failure that
 * was reported when accessing /admin/dashboard.
 */
it('can generate URLs for admin configuration category routes', function () {
    expect(route('admin.configuration.category.index'))->toContain('admin/dashboard/configuration/category')
        ->and(route('admin.configuration.category.create'))->toContain('admin/dashboard/configuration/category/create')
        ->and(route('admin.configuration.category.store'))->toContain('admin/dashboard/configuration/category')
        ->and(route('admin.configuration.amenity.index'))->toContain('admin/dashboard/configuration/amenity')
        ->and(route('admin.configuration.artisan-category.index'))->toContain('admin/dashboard/configuration/artisan-category')
        ->and(route('admin.configuration.parcelle-category.index'))->toContain('admin/dashboard/configuration/parcelle-category')
        ->and(route('admin.configuration.category.toggle-active', ['category' => 1]))->toContain('admin/dashboard/configuration/category')
        ->and(route('admin.configuration.category.update-sort'))->toContain('admin/dashboard/configuration/category/update-sort');
});
