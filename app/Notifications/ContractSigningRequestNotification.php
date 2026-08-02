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

        $ownerName = $this->contract->creator?->name ?? 'Propriétaire';
        $propertyTitle = $this->contract->property?->title ?? 'Bien immobilier';
        $createdAt = $this->contract->created_at?->format('d/m/Y') ?? now()->format('d/m/Y');

        return (new MailMessage)
            ->subject('Contrat de bail en attente de signature - '.$this->contract->tenant_name)
            ->greeting('Bonjour,')
            ->line("Un contrat de bail vous attend pour signature en tant que $roleLabel.")
            ->line('**Propriétaire :** '.$ownerName)
            ->line('**Bien concerné :** '.$propertyTitle)
            ->line('**Date de création :** '.$createdAt)
            ->line('**Locataire :** '.$this->contract->tenant_name)
            ->line('Ce contrat est en attente de signature. Merci de le signer dans les meilleurs délais.')
            ->action('Ouvrir le portail locataire', $actionUrl)
            ->line('Si vous avez des questions, n\'hésitez pas à contacter le propriétaire.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'contract_id' => $this->contract->id,
            'role' => $this->role,
            'tenant_name' => $this->contract->tenant_name,
            'property_title' => $this->contract->property?->title,
            'owner_name' => $this->contract->creator?->name,
        ];
    }
}
