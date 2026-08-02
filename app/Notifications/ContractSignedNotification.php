<?php

namespace App\Notifications;

use App\Models\Contract;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContractSignedNotification extends Notification
{
    use Queueable;

    public Contract $contract;

    public string $signedByRole;

    public function __construct(Contract $contract, string $signedByRole)
    {
        $this->contract = $contract;
        $this->signedByRole = $signedByRole;
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $signedByLabel = $this->signedByRole === 'owner' ? 'propriétaire' : 'locataire';

        return (new MailMessage)
            ->subject('Contrat signé - '.$this->contract->tenant_name)
            ->greeting('Bonjour,')
            ->line("Le contrat a été signé par le $signedByLabel.")
            ->line('Propriété : '.$this->contract->property->title)
            ->line('Locataire : '.$this->contract->tenant_name)
            ->action('Consulter le contrat', route('owner.contracts.show', $this->contract));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'contract_id' => $this->contract->id,
            'signed_by_role' => $this->signedByRole,
            'tenant_name' => $this->contract->tenant_name,
            'property_title' => $this->contract->property->title,
        ];
    }
}
