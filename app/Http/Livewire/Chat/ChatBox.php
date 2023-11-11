<?php

namespace App\Http\Livewire\Chat;

use Livewire\Component;

class ChatBox extends Component
{
    public $selectedConversation;
    
    public function render()
    {
        return view('livewire.chat.chat-box');
    }
}
