<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class CustomResetPassword extends ResetPassword
{

    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     */
    public function toMail($notifiable): MailMessage
    {
        $resetUrl = $this->resetUrl($notifiable);

        return (new MailMessage)
            ->subject(__('Réinitialisation de votre mot de passe'))
            ->greeting(__('Bonjour').' '.$notifiable->name.',')
            ->line(__('Nous avons reçu une demande de réinitialisation de votre mot de passe.'))
            ->action(__('Réinitialiser mon mot de passe'), $resetUrl)
            ->line(__('Ce lien de réinitialisation expirera dans :count minutes.', ['count' => config('auth.passwords.users.expire', 60)]))
            ->line(__('Si vous n\'êtes pas à l\'origine de cette demande, vous pouvez ignorer cet email. Votre mot de passe restera inchangé.'));
    }

    /**
     * Get the reset password URL for the given notifiable.
     */
    protected function resetUrl($notifiable): string
    {
        return url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));
    }
}
