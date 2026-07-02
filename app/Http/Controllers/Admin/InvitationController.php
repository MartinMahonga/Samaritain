<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreInvitationRequest;
use App\Models\AgencyInvitation;
use App\Services\InvitationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Role;

class InvitationController extends Controller
{
    protected InvitationService $invitationService;

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

        try {
            $this->invitationService->cancelInvitation($invitation);

            return redirect()->route('admin.invitations.index')
                ->with('success', 'Invitation annulée. Un email de notification a été envoyé.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function resend(AgencyInvitation $invitation)
    {
        Gate::authorize('resend', $invitation);

        try {
            $this->invitationService->resendInvitation($invitation);

            return redirect()->route('admin.invitations.index')
                ->with('success', 'Invitation renvoyée avec succès.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function acceptForm(Request $request)
    {
        $token = $request->query('token');
        $invitation = AgencyInvitation::where('token', $token)->first();

        if (! $invitation) {
            return view('admin.team.invitations.accept', [
                'token' => $token,
                'invitation' => null,
                'error_message' => 'Cette invitation est invalide ou n\'existe pas.',
            ]);
        }

        if ($invitation->isExpired()) {
            return view('admin.team.invitations.accept', [
                'token' => $token,
                'invitation' => $invitation,
                'error_message' => 'Cette invitation a expiré le '.$invitation->expires_at->format('d/m/Y à H:i').'. Veuillez contacter l\'équipe pour une nouvelle invitation.',
            ]);
        }

        if ($invitation->isAccepted()) {
            return view('admin.team.invitations.accept', [
                'token' => $token,
                'invitation' => $invitation,
                'error_message' => 'Cette invitation a déjà été acceptée. Vous pouvez vous connecter à votre compte.',
            ]);
        }

        if ($invitation->isCancelled()) {
            return view('admin.team.invitations.accept', [
                'token' => $token,
                'invitation' => $invitation,
                'error_message' => 'Cette invitation a été annulée. Veuillez contacter l\'équipe pour une nouvelle invitation.',
            ]);
        }

        return view('admin.team.invitations.accept', compact('token', 'invitation'));
    }

    public function decline(Request $request, AgencyInvitation $invitation)
    {
        Gate::authorize('delete', $invitation);

        if (! $invitation->isValid()) {
            return back()->with('error', 'Cette invitation n\'est plus valide.');
        }

        try {
            $this->invitationService->cancelInvitation($invitation);

            return redirect()->route('index')
                ->with('success', 'Invitation refusée.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function accept(Request $request, AgencyInvitation $invitation)
    {
        if (! $invitation->isValid()) {
            return back()->with('error', 'Cette invitation est invalide, expirée ou a déjà été utilisée.');
        }

        try {
            $user = $this->invitationService->acceptInvitation($invitation);

            return redirect()->route('admin.index')
                ->with('success', 'Invitation acceptée. Votre compte est maintenant actif.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
