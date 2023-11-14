<?php

namespace App\Http\Livewire\Chat;

use Livewire\Component;

use App\Models\Message;


class ChatBox extends Component
{
    public $selectedConversation;
    public $loadedMessages;
    public $paginate_var = 10;

    public function loadMore(): void
    {
        // increment 
        $this->paginate_var += 10;

        // call loadMessages()
        $this->loadMessages();

        // update the chat height 
        $this->dispatchBrowserEvent('update-chat-height');
    }

    public function loadMessages()
    {
        $userId = auth()->id();
        
        // count messages
        $count = Message::where('conversation_id', $this->selectedConversation->id)->count();

        // get last messages
        $this->loadedMessages = Message::where('conversation_id', $this->selectedConversation->id)
                ->skip($count - $this->paginate_var)
                ->take($this->paginate_var)
                ->get();

        return $this->loadedMessages;
    }

    public function mount()
    {
        $this->loadMessages();
    }
    
    public function render()
    {
        return view('livewire.chat.chat-box');
    }
}
