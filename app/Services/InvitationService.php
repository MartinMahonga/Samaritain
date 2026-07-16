<?php

namespace App\Services;

use App\Events\InvitationAccepted;
use App\Events\InvitationCancelled;
use App\Events\InvitationCreated;
use App\Models\AgencyInvitation;
use App\Models\User;
use App\Notifications\InvitationNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class InvitationService
{
    /**
     * Créer une invitation avec protection contre les races conditions.
     */
    public function createInvitation(string $email, int $roleId, User $creator): AgencyInvitation
    {
        $email = strtolower(trim($email));

        // Vérifier si l'utilisateur existe déjà et est membre
        $existingUser = User::where('email', $email)->first();
        if ($existingUser && $existingUser->is_staff) {
            throw new \Exception('Cet utilisateur est déjà membre de l\'entreprise.');
        }

        // Vérifier si l'utilisateur existe mais est inactif / banni
        if ($existingUser && ! $existingUser->is_active) {
            throw new \Exception('Cet utilisateur est inactif et ne peut pas être invité.');
        }

        // Vérifier le rôle owner n'est pas assignable via invitation
        $role = Role::findById($roleId);
        if ($role->name === 'owner') {
            throw new \Exception('Le rôle propriétaire ne peut pas être assigné via une invitation.');
        }

        // Utiliser une transaction avec lock pour éviter les races conditions
        return DB::transaction(function () use ($email, $roleId, $creator) {
            $pendingInvitation = AgencyInvitation::where('email', $email)
                ->whereNull('accepted_at')
                ->whereNull('cancelled_at')
                ->where('expires_at', '>', Carbon::now())
                ->lockForUpdate()
                ->first();

            if ($pendingInvitation) {
                throw new \Exception('Une invitation est déjà en cours pour cet email.');
            }

            $invitation = AgencyInvitation::create([
                'email' => $email,
                'role_id' => $roleId,
                'token' => Str::random(64),
                'expires_at' => Carbon::now()->addDays(7),
                'created_by' => $creator->id,
            ]);

            event(new InvitationCreated($invitation));

            return $invitation;
        });
    }

    /**
     * Envoyer l'email d'invitation via Notification.
     */
    public function sendInvitationEmail(AgencyInvitation $invitation): void
    {
        $acceptUrl = route('admin.invitations.accept.form', ['token' => $invitation->token]);

        // Envoi direct sans queue pour fiabilité
        Notification::route('mail', $invitation->email)
            ->notify(new InvitationNotification($acceptUrl, $invitation));
    }

    /**
     * Accepter une invitation avec protection contre les races conditions.
     */
    public function acceptInvitation(AgencyInvitation $invitation): User
    {
        return DB::transaction(function () use ($invitation) {
            $invitation = AgencyInvitation::where('id', $invitation->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($invitation->isExpired()) {
                throw new \Exception('Cette invitation a expiré.');
            }

            if ($invitation->isAccepted()) {
                throw new \Exception('Cette invitation a déjà été acceptée.');
            }

            if ($invitation->isCancelled()) {
                throw new \Exception('Cette invitation a été annulée.');
            }

            $role = Role::find($invitation->role_id);
            if (! $role) {
                throw new \Exception('Le rôle associé à cette invitation n\'existe plus.');
            }

            $user = User::where('email', $invitation->email)->first();

            if (! $user) {
                throw new \Exception('Aucun compte utilisateur ne correspond à cette invitation.');
            }

            if ($user->is_staff) {
                throw new \Exception('Cet utilisateur est déjà membre de l\'entreprise.');
            }

            if (! $user->is_active) {
                throw new \Exception('Ce compte utilisateur est inactif et ne peut pas être réactivé via une invitation.');
            }

            $user->update([
                'is_staff' => true,
                'is_active' => true,
            ]);

            $user->assignRole($role);

            $invitation->update(['accepted_at' => now()]);

            event(new InvitationAccepted($invitation, $user));

            return $user;
        });
    }

    /**
     * Annuler une invitation.
     */
    public function cancelInvitation(AgencyInvitation $invitation): void
    {
        DB::transaction(function () use ($invitation) {
            $invitation = AgencyInvitation::where('id', $invitation->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($invitation->isAccepted()) {
                throw new \Exception('Impossible d\'annuler une invitation déjà acceptée.');
            }

            if ($invitation->isCancelled()) {
                throw new \Exception('Cette invitation a déjà été annulée.');
            }

            $invitation->update(['cancelled_at' => now()]);

            event(new InvitationCancelled($invitation));
        });
    }

    /**
     * Renvoyer une invitation (prolonge l'expiration).
     */
    public function resendInvitation(AgencyInvitation $invitation): void
    {
        if (! $invitation->isValid()) {
            throw new \Exception('Cette invitation n\'est plus valide.');
        }

        $invitation->update([
            'expires_at' => Carbon::now()->addDays(7),
        ]);

        $this->sendInvitationEmail($invitation);
    }
}
