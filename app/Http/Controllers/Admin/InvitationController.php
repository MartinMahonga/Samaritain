<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AcceptInvitationRequest;
use App\Http\Requests\Admin\StoreInvitationRequest;
use App\Models\AgencyInvitation;
use App\Services\InvitationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Role;

class InvitationController extends Controller
{
    protected $invitationService;

    public function __construct(InvitationService $invitationService)
    {
        $this->invitationService = $invitationService;
    }

    public function index()
    {
        Gate::authorize('view', AgencyInvitation::class);

        $invitations = AgencyInvitation::with(['role', 'creator'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.team.invitations.index', compact('invitations'));
    }

    public function create()
    {
        Gate::authorize('create', AgencyInvitation::class);

        $roles = Role::where('name', '!=', 'owner')->get();

        return view('admin.team.invitations.create', compact('roles'));
    }

    public function store(StoreInvitationRequest $request)
    {
        Gate::authorize('create', AgencyInvitation::class);

        try {
            $invitation = $this->invitationService->createInvitation(
                $request->email,
                $request->role_id,
                $request->user()
            );

            $this->invitationService->sendInvitationEmail($invitation);

            return redirect()->route('admin.invitations.index')
                ->with('success', 'Invitation envoyée avec succès.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy(AgencyInvitation $invitation)
    {
        Gate::authorize('delete', $invitation);

        $invitation->delete();

        return redirect()->route('admin.invitations.index')
            ->with('success', 'Invitation annulée.');
    }

    public function resend(AgencyInvitation $invitation)
    {
        Gate::authorize('resend', $invitation);

        $this->invitationService->sendInvitationEmail($invitation);

        return redirect()->route('admin.invitations.index')
            ->with('success', 'Invitation renvoyée.');
    }

    public function acceptForm(Request $request)
    {
        $token = $request->query('token');
        $invitation = AgencyInvitation::where('token', $token)->first();

        if (! $invitation || $invitation->isExpired() || $invitation->isAccepted()) {
            abort(404, 'Invitation invalide ou expirée.');
        }

        return view('admin.team.invitations.accept', compact('token'));
    }

    public function accept(AcceptInvitationRequest $request)
    {
        $invitation = AgencyInvitation::where('token', $request->token)->first();

        if (! $invitation || $invitation->isExpired() || $invitation->isAccepted()) {
            return back()->withErrors(['token' => 'Invitation invalide ou expirée.']);
        }

        try {
            $user = $this->invitationService->acceptInvitation(
                $invitation,
                $request->name,
                $request->password
            );

            auth()->login($user);

            return redirect()->route('admin.index')
                ->with('success', 'Bienvenue dans l\'équipe ! Votre compte a été créé.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
