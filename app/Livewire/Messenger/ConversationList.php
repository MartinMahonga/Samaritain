<?php

namespace App\Livewire\Messenger;

use App\Models\Conversation;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class ConversationList extends Component
{
    public string $search = '';

    public function getConversationsProperty()
    {
        return Conversation::forUser(auth()->user())
            ->with('owner', 'tenant', 'contract.property')
            ->orderBy('last_message_at', 'desc')
            ->get()
            ->map(function ($conversation) {
                $otherUser = $conversation->theOtherUser(auth()->user());
                $unread = $conversation->unreadCountFor(auth()->id());
                $lastMessage = $conversation->messages()->first();

                return (object) [
                    'id' => $conversation->id,
                    'other_user' => $otherUser,
                    'property' => $conversation->contract->property,
                    'last_message' => $lastMessage?->body,
                    'last_message_at' => $lastMessage?->created_at,
                    'unread_count' => $unread,
                ];
            })
            ->filter(function ($item) {
                if (empty($this->search)) {
                    return true;
                }

                return str_contains(strtolower($item->other_user->name), strtolower($this->search))
                    || str_contains(strtolower($item->property->title ?? ''), strtolower($this->search));
            });
    }

    public function selectConversation(int $conversationId): void
    {
        $this->dispatch('conversation-selected', conversationId: $conversationId);
    }

    public function render(): View
    {
        return view('livewire.messenger.conversation-list', [
            'conversations' => $this->conversations,
        ]);
    }
}
