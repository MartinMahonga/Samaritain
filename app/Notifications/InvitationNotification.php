<?php

namespace App\Notifications;

use App\Models\AgencyInvitation;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InvitationNotification extends Notification
{
    use Queueable;

    public function __construct(public string $acceptUrl, public AgencyInvitation $invitation) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Invitation à rejoindre l'équipe de l'agence")
            ->markdown('emails.invitation-notification', [
                'acceptUrl' => $this->acceptUrl,
                'invitation' => $this->invitation,
            ]);
    }
}
