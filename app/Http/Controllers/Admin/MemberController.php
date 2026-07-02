<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateMemberRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Role;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('viewAny', User::class);

        $members = User::where('is_staff', true)
            ->with('roles')
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.team.index', compact('members'));
    }

    public function show(User $member)
    {
        Gate::authorize('view', $member);

        if (! $member->is_staff) {
            abort(404);
        }

        return view('admin.team.show', compact('member'));
    }

    public function edit(User $member)
    {
        Gate::authorize('update', $member);

        if (! $member->is_staff) {
            abort(404);
        }

        $roles = Role::where('name', '!=', 'owner')->get();

        return view('admin.team.edit', compact('member', 'roles'));
    }

    public function update(UpdateMemberRequest $request, User $member)
    {
        Gate::authorize('update', $member);

        $member->update([
            'name' => $request->name,
            'email' => $request->email,
            'is_active' => $request->boolean('is_active'),
        ]);

        $member->syncRoles([$request->role_id]);

        return redirect()->route('admin.members.index')
            ->with('success', 'Membre mis à jour avec succès.');
    }

    public function destroy(User $member)
    {
        Gate::authorize('delete', $member);

        $member->delete();

        return redirect()->route('admin.members.index')
            ->with('success', 'Membre supprimé.');
    }

    public function deactivate(User $member)
    {
        Gate::authorize('deactivate', $member);

        $member->update(['is_active' => false]);

        return redirect()->route('admin.members.index')
            ->with('success', 'Membre désactivé.');
    }

    public function activate(User $member)
    {
        Gate::authorize('activate', $member);

        $member->update(['is_active' => true]);

        return redirect()->route('admin.members.index')
            ->with('success', 'Membre réactivé.');
    }
}
