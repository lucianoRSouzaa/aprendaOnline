<?php

namespace App\Http\Livewire\Chat;

use Livewire\Component;

class ChatList extends Component
{
    public $selectedConversation;
    
    public function render()
    {
        $user= auth()->user();
        $conversations = $user->conversations()->latest('updated_at')->get();

        return view('livewire.chat.chat-list', [
            'conversations' => $conversations
        ]);
    }
}
