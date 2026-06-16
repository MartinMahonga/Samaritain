<?php

namespace App\Providers;

use App\Models\AgencyInvitation;
use App\Models\Property;
use App\Models\User;
use App\Policies\InvitationPolicy;
use App\Policies\MemberPolicy;
use App\Policies\PropertyPolicy;
use App\Policies\RolePolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Spatie\Permission\Models\Role;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Spécifiue à Railway
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        Gate::policy(Property::class, PropertyPolicy::class);
        Gate::policy(User::class, MemberPolicy::class);
        Gate::policy(AgencyInvitation::class, InvitationPolicy::class);
        Gate::policy(Role::class, RolePolicy::class);
    }
}
