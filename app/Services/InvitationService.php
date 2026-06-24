<?php

namespace App\Services;

use App\Models\AgencyInvitation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class InvitationService
{
    public function createInvitation(string $email, int $roleId, User $creator): AgencyInvitation
    {
        // Vérifier si l'utilisateur existe déjà
        $existingUser = User::where('email', $email)->first();
        if ($existingUser && $existingUser->is_staff) {
            throw new \Exception('Cet utilisateur est déjà membre de l\'agence.');
        }

        // Vérifier si une invitation est déjà en cours pour cet email
        $pendingInvitation = AgencyInvitation::where('email', $email)
            ->whereNull('accepted_at')
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if ($pendingInvitation) {
            throw new \Exception('Une invitation est déjà en cours pour cet email.');
        }

        return AgencyInvitation::create([
            'email' => $email,
            'role_id' => $roleId,
            'token' => Str::random(64),
            'expires_at' => Carbon::now()->addDays(7),
            'created_by' => $creator->id,
        ]);
    }

    public function sendInvitationEmail(AgencyInvitation $invitation): void
    {
        $acceptUrl = route('admin.invitations.accept.form', ['token' => $invitation->token]);

        Mail::send('emails.invitation', ['url' => $acceptUrl, 'invitation' => $invitation], function ($message) use ($invitation) {
            $message->to($invitation->email)
                ->subject('Invitation à rejoindre l\'équipe de l\'agence');
        });
    }

    public function acceptInvitation(AgencyInvitation $invitation, string $name, string $password): User
    {
        // Vérifier si l'invitation est valide
        if ($invitation->isExpired()) {
            throw new \Exception('Cette invitation a expiré.');
        }

        if ($invitation->isAccepted()) {
            throw new \Exception('Cette invitation a déjà été acceptée.');
        }

        // Vérifier si un utilisateur avec cet email existe déjà
        $existingUser = User::where('email', $invitation->email)->first();

        if ($existingUser) {
            // Si l'utilisateur existe déjà, le convertir en membre
            if ($existingUser->is_staff) {
                throw new \Exception('Cet utilisateur est déjà membre de l\'agence.');
            }

            // Convertir le client en membre
            $existingUser->update([
                'name' => $name,
                'password' => Hash::make($password),
                'is_staff' => true,
                'is_active' => true,
            ]);

            // Nettoyer les sessions existantes de cet utilisateur
            DB::table('sessions')->where('user_id', $existingUser->id)->delete();

            $user = $existingUser;
        } else {
            // Créer un nouvel utilisateur membre
            $user = User::create([
                'name' => $name,
                'email' => $invitation->email,
                'password' => Hash::make($password),
                'email_verified_at' => now(),
                'is_staff' => true,
                'is_active' => true,
            ]);
        }

        // Assigner le rôle
        $role = Role::findById($invitation->role_id);
        $user->assignRole($role);

        // Marquer l'invitation comme acceptée
        $invitation->update(['accepted_at' => now()]);

        return $user;
    }
}
