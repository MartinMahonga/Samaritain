<?php

namespace App\Notifications;

use App\Models\AgencyContact;
use App\Models\Property;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AgencyContactNotification extends Notification
{
    use Queueable;

    public function __construct(public AgencyContact $contact) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $contactable = $this->contact->contactable;
        $type = $contactable instanceof Property ? 'Bien immobilier' : 'Parcelle';
        $title = $contactable->title ?? $contactable->titre;
        $location = $contactable->address ?? $contactable->localisation ?? ($contactable->quartier.', '.$contactable->ville);

        $url = $contactable instanceof Property
            ? route('property.show', $contactable)
            : route('parcelles.show', $contactable);

        $mailMessage = (new MailMessage)
            ->subject("Nouveau message à propos de {$title}")
            ->greeting('Nouveau message de contact')
            ->line('Un visiteur vous a envoyé un message via le formulaire de contact.')
            ->line('---')
            ->line("**De :** {$this->contact->name}")
            ->line("**Email :** {$this->contact->email}")
            ->line('**Téléphone :** '.($this->contact->phone ?: 'Non renseigné'))
            ->line('---')
            ->line("**Sujet :** {$this->contact->subject}")
            ->line('**Message :**')
            ->line($this->contact->message)
            ->line('---')
            ->line("**{$type} :** {$title}")
            ->line("**Référence :** {$contactable->reference}")
            ->line("**Localisation :** {$location}");

        if (isset($contactable->price) || isset($contactable->prix)) {
            $price = $contactable->price ?? $contactable->prix;
            $mailMessage->line('**Prix :** '.number_format($price, 0, ',', ' ').' FCFA');
        }

        return $mailMessage
            ->action('Voir la fiche', url($url));
    }

    public function toDatabase(object $notifiable): array
    {
        $contactable = $this->contact->contactable;

        return [
            'agency_contact_id' => $this->contact->id,
            'name' => $this->contact->name,
            'email' => $this->contact->email,
            'phone' => $this->contact->phone,
            'subject' => $this->contact->subject,
            'message' => $this->contact->message,
            'contactable_type' => $contactable instanceof Property ? 'property' : 'parcelle',
            'contactable_id' => $contactable->id,
            'contactable_title' => $contactable->title ?? $contactable->titre,
            'created_at' => $this->contact->created_at->toDateTimeString(),
        ];
    }

    public function toArray(object $notifiable): array
    {
        return [];
    }
}
