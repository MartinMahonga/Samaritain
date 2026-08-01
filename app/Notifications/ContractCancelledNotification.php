<?php

namespace App\Notifications;

use App\Models\Contract;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContractCancelledNotification extends Notification
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
            ->subject('Contrat annulé - '.$this->contract->tenant_name)
            ->greeting('Bonjour,')
            ->line('Le contrat de bail suivant a été annulé par le propriétaire.')
            ->line('Propriété : '.$this->contract->property->title)
            ->line('Locataire : '.$this->contract->tenant_name)
            ->line('Si vous pensez qu\'il s\'agit d\'une erreur, veuillez contacter le propriétaire.');
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
