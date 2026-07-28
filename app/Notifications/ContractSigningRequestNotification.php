<?php

namespace App\Notifications;

use App\Models\Contract;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContractSigningRequestNotification extends Notification
{
    use Queueable;

    public Contract $contract;

    public string $role;

    public function __construct(Contract $contract, string $role)
    {
        $this->contract = $contract;
        $this->role = $role;
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $roleLabel = $this->role === 'owner' ? 'propriétaire' : 'locataire';
        $actionUrl = route($this->role === 'owner' ? 'owner.contracts.show' : 'tenant.contracts.show', $this->contract);

        return (new MailMessage)
            ->subject('Contrat à signer - '.$this->contract->tenant_name)
            ->greeting('Bonjour,')
            ->line("Un contrat de bail vous demande une signature en tant que $roleLabel.")
            ->line('Propriété : '.$this->contract->property->title)
            ->line('Locataire : '.$this->contract->tenant_name)
            ->action('Signer le contrat', $actionUrl)
            ->line('Merci de signer ce contrat dans les meilleurs délais.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'contract_id' => $this->contract->id,
            'role' => $this->role,
            'tenant_name' => $this->contract->tenant_name,
            'property_title' => $this->contract->property->title,
        ];
    }
}
