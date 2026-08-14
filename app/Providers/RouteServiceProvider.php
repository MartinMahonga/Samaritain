<?php

namespace App\Providers;

use App\Models\Parcelle;
use App\Models\Property;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Define your route model bindings, pattern filters, etc.
     */
    public function boot(): void
    {
        $this->bindVisitPassable();

        parent::boot();
    }

    /**
     * Bind the visitPassable parameter to resolve either Property or Parcelle models.
     */
    protected function bindVisitPassable(): void
    {
        Route::bind('visitPassable', function ($value) {
            // Try to find a Parcelle first
            $parcelle = Parcelle::where('slug', $value)->first();
            if ($parcelle) {
                return $parcelle;
            }

            // Then try to find a Property
            $property = Property::where('slug', $value)->first();
            if ($property) {
                return $property;
            }

            // If neither found, abort with 404
            abort(404, 'Resource not found.');
        });
    }
}
