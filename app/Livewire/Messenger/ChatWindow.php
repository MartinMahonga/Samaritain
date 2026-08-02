<?php

namespace App\Livewire\Messenger;

use App\Models\Conversation;
use App\Models\Message;
use App\Notifications\MessageSentNotification;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class ChatWindow extends Component
{
    public ?int $conversationId = null;

    public ?string $body = null;

    #[On('conversation-selected')]
    public function openConversation(int $conversationId): void
    {
        $this->conversationId = $conversationId;
        $this->reset('body');
    }

    public function getConversationProperty()
    {
        if (! $this->conversationId) {
            return null;
        }

        return Conversation::forUser(auth()->user())
            ->with(['owner', 'tenant', 'contract.property'])
            ->findOrFail($this->conversationId);
    }

    public function getMessagesProperty()
    {
        if (! $this->conversationId) {
            return collect();
        }

        return Message::where('conversation_id', $this->conversationId)
            ->with('sender')
            ->orderByDesc('created_at')
            ->take(50)
            ->get()
            ->reverse()
            ->values();
    }

    public function getOtherUserProperty()
    {
        if (! $this->conversation) {
            return null;
        }

        return $this->conversation->theOtherUser(auth()->user());
    }

    public function getUnreadCountProperty()
    {
        if (! $this->conversationId || ! $this->conversation) {
            return 0;
        }

        return $this->conversation->unreadCountFor(auth()->id());
    }

    public function sendMessage(): void
    {
        $this->validate([
            'body' => ['required', 'string', 'max:2000'],
        ]);

        if (! $this->conversation) {
            return;
        }

        $message = Message::create([
            'conversation_id' => $this->conversation->id,
            'sender_id' => auth()->id(),
            'body' => $this->body,
        ]);

        Message::where('conversation_id', $this->conversation->id)
            ->where('sender_id', '!=', auth()->id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $this->conversation->update(['last_message_at' => $message->created_at]);

        $otherUser = $this->conversation->theOtherUser(auth()->user());
        $otherUser->notify(new MessageSentNotification($message, $this->conversation));

        $this->dispatch('message-sent');
        $this->reset('body');
        $this->dispatch('$refresh');
    }

    public function render(): View
    {
        return view('livewire.messenger.chat-window', [
            'conversation' => $this->conversation,
            'messages' => $this->messages,
            'otherUser' => $this->otherUser,
            'unreadCount' => $this->unreadCount,
        ]);
    }
}
