<?php

namespace App\Notifications;

use App\Models\VisitRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
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
        return ["mail", "database"];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line("The introduction to the notification.")
            ->action("Notification Action", url("/"))
            ->line("Thank you for using our application!");
    }

    public function toDatabase($notifiable)
    {
        return [
            'visit_request_id' => $this->visitRequest->id,
            'full_name'        => $this->visitRequest->full_name,
            'phone'            => $this->visitRequest->phone,
            'city'             => $this->visitRequest->city,
            'property_category'=> $this->visitRequest->property_category,
            'preferred_date'   => $this->visitRequest->preferred_date,
            'message'          => $this->visitRequest->message,
            'property_id'      => $this->visitRequest->property_id,
            'created_at'       => $this->visitRequest->created_at->toDateTimeString(),
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
