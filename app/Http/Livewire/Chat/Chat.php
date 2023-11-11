<?php

namespace App\Http\Livewire\Chat;

use Livewire\Component;

use App\Models\Conversation;
use App\Models\Message;


class Chat extends Component
{
    public $query;
    public $selectedConversation;

    public function mount()
    {
        $this->selectedConversation = Conversation::findOrFail($this->query);
    }

    public function render()
    {
        return view('livewire.chat.chat');
    }
}
