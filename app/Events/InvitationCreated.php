<?php

namespace App\Events;

use App\Models\AgencyInvitation;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class InvitationCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public AgencyInvitation $invitation;

    public function __construct(AgencyInvitation $invitation)
    {
        $this->invitation = $invitation;
    }
}
