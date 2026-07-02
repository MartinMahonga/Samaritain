<?php

namespace App\Notifications;

use App\Models\VisitRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewVisitRequestNotification extends Notification
{
    use Queueable;

    protected VisitRequest $visitRequest;

    /**
     * Create a new notification instance.
     */
    public function __construct(VisitRequest $visitRequest)
    {
        $this->visitRequest = $visitRequest;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Nouvelle demande de visite')
            ->greeting('Nouvelle demande de visite')
            ->line('Une nouvelle demande de visite a été soumise.')
            ->line('Nom : '.$this->visitRequest->full_name)
            ->line('Téléphone : '.$this->visitRequest->phone)
            ->line('Ville : '.$this->visitRequest->city)
            ->action('Voir les demandes', url('/admin/visits'));
    }

    public function toDatabase($notifiable)
    {
        return [
            'visit_request_id' => $this->visitRequest->id,
            'full_name' => $this->visitRequest->full_name,
            'phone' => $this->visitRequest->phone,
            'city' => $this->visitRequest->city,
            'property_category' => $this->visitRequest->property_category,
            'preferred_date' => $this->visitRequest->preferred_date,
            'message' => $this->visitRequest->message,
            'property_id' => $this->visitRequest->property_id,
            'created_at' => $this->visitRequest->created_at->toDateTimeString(),
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
