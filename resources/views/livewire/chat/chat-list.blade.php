<section
    x-data="{
        type:'all',
        query:@entangle('query'),
        modal: null
    }"
    x-init="
        setTimeout(()=>{
            conversationElement = document.getElementById('conversation-'+query);

            if(conversationElement)
            {
                //scroll to the element
                conversationElement.scrollIntoView({'behavior':'smooth'});
            }
        },200);

        modal = new bootstrap.Modal($refs.modal);

        Echo.private('users.{{Auth()->User()->id}}')
            .notification((notification)=>{
                if(notification['type']== 'App\\Notifications\\MessageRead' || notification['type']== 'App\\Notifications\\MessageSent')
                {
                    window.Livewire.emit('refresh');
                }
            }
        );
    "
    class="col-4 conversas barra"
>

    <div class="modal fade" tabindex="-1" role="dialog" x-ref="modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title py-3">ADICIONAR CONVERSAS</h1>
                    <button type="button" class="btn-close" @click="modal.hide()" aria-label="Close"></button>
                </div>
                <livewire:chat.user-search />
            </div>
        </div>
    </div>

    <div class="pesquisar d-flex align-items-center justify-content-between">
        <div class="pesquisa">
            <div class="searchbar d-flex align-items-center">
                <i class="fa fa-search" aria-hidden="true"></i>
                <input type="text" placeholder="Pesquisar conversa" wire:model="filter">
            </div>
        </div>
        <div class="adicionar d-flex justify-content-center" @click="modal.show()">
            <i class="fa-solid fa-plus"></i>
        </div>
    </div>

    @forelse ($conversations as $conversation)
        <div 
            id="conversation-{{ $conversation->id }}" wire:key="{{ $conversation->id }}"
            class="box-conversa d-flex {{ $conversation->id == $selectedConversation?->id ? 'active' : '' }}"
        >
            <img class="photo" src="{{ asset($conversation->getReceiver()->image) }}"></img>
            <div class="desc-contact">
                <p class="name">{{ $conversation->getReceiver()->name }}</p>
                <div class="d-flex last-message align-items-center">
                    @if ($conversation->messages?->last()?->sender_id==auth()->id())
                        @if ($conversation->isLastMessageReadByUser())
                            {{-- double tick  --}}
                            <span class="text-black">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check2-all" viewBox="0 0 16 16">
                                    <path d="M12.354 4.354a.5.5 0 0 0-.708-.708L5 10.293 1.854 7.146a.5.5 0 1 0-.708.708l3.5 3.5a.5.5 0 0 0 .708 0l7-7zm-4.208 7-.896-.897.707-.707.543.543 6.646-6.647a.5.5 0 0 1 .708.708l-7 7a.5.5 0 0 1-.708 0z"/>
                                    <path d="m5.354 7.146.896.897-.707.707-.897-.896a.5.5 0 1 1 .708-.708z"/>
                                </svg>
                            </span>
                        @else
                            {{-- single tick  --}}
                            <span class="text-black">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check2" viewBox="0 0 16 16">
                                    <path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z"/>
                                </svg>
                            </span>
                        @endif
                    @endif
                    <p class="text-truncate">{{$conversation->messages?->last()?->body??' '}}</p>
                    <a href="{{ route('chat', $conversation->id) }}" class="stretched-link"></a>
                </div>
            </div>
            @if ($conversation->unreadMessagesCount()>0)
                <span class="notification d-flex justify-content-center align-items-center">{{ $conversation->unreadMessagesCount() }}</span>
            @endif
        </div>
    @empty
        <div>
            <p class="text-center mt-4">Nenhuma conversa encontrada</p>
        </div>
    @endforelse
</section>