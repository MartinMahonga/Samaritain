<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class CustomVerifyEmail extends VerifyEmail
{
    /**
     * Build the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject(__('Confirmez votre adresse email'))
            ->greeting(__('Bonjour').' '.$notifiable->name.',')
            ->line(__('Merci de vous être inscrit.'))
            ->line(__('Veuillez confirmer votre adresse email afin d\'activer votre compte.'))
            ->action(__('Confirmer mon adresse'), $verificationUrl)
            ->line(__('Si vous n\'avez pas créé de compte, vous pouvez ignorer cet email.'));
    }
}
