<?php

namespace App\Http\Livewire\Chat;

use Livewire\Component;

use App\Models\User;
use App\Models\Conversation;


class UserSearch extends Component
{
    public string $query = "";
    public $users = [];

    public function updatedQuery()
    {
        if ($this->query) {
            $this->users = User::where('name', 'like', '%' . $this->query . '%')->where('id', '!=', auth()->id())->get();
        } else{
            $this->users = [];
        }
    }

    public function startConversation($userId)
    {
        $authenticatedUserId = auth()->id();

        // Check if conversation already exists
        $existingConversation = Conversation::where(function ($query) use ($authenticatedUserId, $userId) {
                $query->where('sender_id', $authenticatedUserId)
                    ->where('receiver_id', $userId);
                })
                ->orWhere(function ($query) use ($authenticatedUserId, $userId) {
                    $query->where('sender_id', $userId)
                        ->where('receiver_id', $authenticatedUserId);
                })->first();
            
        if ($existingConversation) {
            // Conversation already exists, redirect to existing conversation
            return redirect()->route('chat', ['query' => $existingConversation->id]);
        }
    
        // Create new conversation
        $createdConversation = Conversation::create([
            'sender_id' => $authenticatedUserId,
            'receiver_id' => $userId,
        ]);
 
        return redirect()->route('chat', ['query' => $createdConversation->id]);
    }

    public function render()
    {
        return view('livewire.chat.user-search');
    }
}
