<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreRoleRequest;
use App\Http\Requests\Admin\UpdateRoleRequest;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        Gate::authorize('viewAny', Role::class);

        $roles = Role::where('name', '!=', 'owner')->with('permissions')->get();

        return view('admin.team.roles.index', compact('roles'));
    }

    public function create()
    {
        Gate::authorize('create', Role::class);

        $permissions = Permission::all();

        return view('admin.team.roles.create', compact('permissions'));
    }

    public function store(StoreRoleRequest $request)
    {
        Gate::authorize('create', Role::class);

        $role = Role::create(['name' => $request->name, 'guard_name' => 'web']);

        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        return redirect()->route('admin.roles.index')
            ->with('success', 'Rôle créé avec succès.');
    }

    public function edit(Role $role)
    {
        Gate::authorize('update', $role);

        if ($role->name === 'owner') {
            abort(403);
        }

        $permissions = Permission::all();
        $rolePermissions = $role->permissions->pluck('id')->toArray();

        return view('admin.team.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    public function update(UpdateRoleRequest $request, Role $role)
    {
        Gate::authorize('update', $role);

        if ($role->name === 'owner') {
            abort(403);
        }

        $role->update(['name' => $request->name]);

        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        } else {
            $role->syncPermissions([]);
        }

        return redirect()->route('admin.roles.index')
            ->with('success', 'Rôle mis à jour.');
    }

    public function destroy(Role $role)
    {
        Gate::authorize('delete', $role);

        if ($role->name === 'owner') {
            return back()->with('error', 'Le rôle owner ne peut pas être supprimé.');
        }

        if ($role->users()->count() > 0) {
            return back()->with('error', 'Ce rôle ne peut pas être supprimé car il est utilisé par des membres.');
        }

        $role->delete();

        return redirect()->route('admin.roles.index')
            ->with('success', 'Rôle supprimé.');
    }
}
