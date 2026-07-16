<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Créer les permissions
        $permissions = [
            'manage-properties',
            'verify-properties',
            'manage-artisans',
            'manage-users',
            'manage-members',
            'manage-roles',
            'manage-settings',
            'manage-transactions',
            'view-analytics',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Créer les rôles
        $owner = Role::firstOrCreate(['name' => 'owner', 'guard_name' => 'web']);
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $marketing = Role::firstOrCreate(['name' => 'marketing', 'guard_name' => 'web']);
        $technical = Role::firstOrCreate(['name' => 'technical', 'guard_name' => 'web']);
        $commercial = Role::firstOrCreate(['name' => 'commercial', 'guard_name' => 'web']);

        // Assigner les permissions
        $owner->givePermissionTo(Permission::all());

        $admin->givePermissionTo([
            'manage-properties',
            'verify-properties',
            'manage-artisans',
            'manage-users',
        ]);

        $marketing->givePermissionTo([
            'manage-properties',
        ]);

        $technical->givePermissionTo([
            'manage-settings',
        ]);

        $commercial->givePermissionTo([
            'manage-properties',
            'manage-users',
        ]);

        // Créer un utilisateur owner par défaut (optionnel)
        if (! User::where('email', 'owner@entreprise.com')->exists()) {
            $ownerUser = User::create([
                'name' => 'Owner',
                'email' => 'owner@entreprise.com',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
                'is_staff' => true,
                'is_active' => true,
            ]);
            $ownerUser->assignRole('owner');
        }
    }
}
