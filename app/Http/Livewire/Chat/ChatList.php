<?php

namespace App\Http\Livewire\Chat;

use Livewire\Component;

class ChatList extends Component
{
    public $selectedConversation;
    public $query;
    public string $filter = "";
    
    public function render()
    {
        $user= auth()->user();
        $conversations = $user->conversations()->latest('updated_at')->get();

        if ($this->filter) {
            $conversations = $conversations->filter(function ($conversation) {
                $receiver = $conversation->getReceiver();
                return $receiver && str_contains($receiver->name, $this->filter);
            });
        }

        return view('livewire.chat.chat-list', [
            'conversations' => $conversations
        ]);
    }
}
