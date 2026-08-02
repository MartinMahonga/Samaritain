<?php

namespace App\Notifications;

use App\Models\Contract;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContractCompletedNotification extends Notification
{
    use Queueable;

    public Contract $contract;

    public function __construct(Contract $contract)
    {
        $this->contract = $contract;
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Contrat actif - '.$this->contract->tenant_name)
            ->greeting('Bonjour,')
            ->line('Le contrat de bail est maintenant pleinement signé et actif.')
            ->line('Propriété : '.$this->contract->property->title)
            ->line('Locataire : '.$this->contract->tenant_name)
            ->line('Début : '.$this->contract->start_date->format('d/m/Y'))
            ->action('Consulter le contrat', route('owner.contracts.show', $this->contract));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'contract_id' => $this->contract->id,
            'tenant_name' => $this->contract->tenant_name,
            'property_title' => $this->contract->property->title,
        ];
    }
}
