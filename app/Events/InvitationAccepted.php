<?php

namespace App\Events;

use App\Models\AgencyInvitation;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class InvitationAccepted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public AgencyInvitation $invitation;

    public User $user;

    public function __construct(AgencyInvitation $invitation, User $user)
    {
        $this->invitation = $invitation;
        $this->user = $user;
    }
}
