<?php

namespace App\Listeners;

use App\Events\ContractFullySigned;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use App\Notifications\MessageSentNotification;

class CreateConversationOnContractSigned
{
    /**
     * Handle the event.
     */
    public function handle(ContractFullySigned $event): void
    {
        $contract = $event->contract;

        $tenant = User::where('email', $contract->tenant_email)->first();

        if (! $tenant) {
            return;
        }

        $conversation = Conversation::firstOrCreate(
            ['contract_id' => $contract->id],
            [
                'owner_id' => $contract->created_by,
                'tenant_id' => $tenant->id,
                'last_message_at' => now(),
            ]
        );

        // Only send greeting if this is a new conversation
        if (! $conversation->wasRecentlyCreated) {
            return;
        }

        $greeting = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $contract->created_by,
            'body' => "Bonjour {$tenant->name}, votre contrat pour « {$contract->property->title} » est maintenant actif. Vous pouvez communiquer ici.",
        ]);

        $tenant->notify(new MessageSentNotification($greeting, $conversation));
    }
}
