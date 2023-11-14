<div
    x-data="{
        height:0,
        conversationElement:document.getElementById('conversation'),
        markAsRead:null
    }"
    
    x-init="
        height= conversationElement.scrollHeight;
        $nextTick(()=>conversationElement.scrollTop= height);
    "

    @scroll-bottom.window="
        $nextTick(()=>
            conversationElement.scrollTop= conversationElement.scrollHeight
        );
    "

    class="col-8"
>
    <div class="container-all border-bottom d-flex flex-column flex-grow-1 h-100">
        {{-- header --}}
        <div class="configs w-100">
            <div class="d-flex align-items-center h-100"> 
                <img class="photo" src="{{ asset($selectedConversation->getReceiver()->image) }}"></img>
                <p class="name">{{ $selectedConversation->getReceiver()->name }}</p>
            </div>
        </div>

        {{-- body --}}
        <div id="conversation"  class="d-flex flex-column flex-grow-1 conversation-div gap-3 overflow-y-auto w-100 mb-auto">

            @if ($loadedMessages)

                @php
                    $previousMessage= null;
                @endphp

                @foreach ($loadedMessages as $key => $message)
                
                @if ($key>0)
                    @php
                        $previousMessage= $loadedMessages->get($key-1)
                    @endphp 
                @endif
                
                    <div 
                        wire:key="{{time().$key}}"
                        @class([
                                'message-div-position d-flex w-auto gap-2 position-relative mt-2',
                                'ms-auto'=> $message->sender_id === auth()->id(),
                            ]) >

                        {{-- avatar --}}
                        <div @class([
                                    'flex-shrink-0',
                                    'invisible'=> $previousMessage?->sender_id == $message->sender_id,
                                    'visually-hidden'=> $message->sender_id === auth()->id()
                                ])>
                            <x-avatar src="{{ asset($selectedConversation->getReceiver()->image) }}"  />
                        </div>

                        {{-- messsage body --}}
                        <div @class(['message-div d-flex flex-column flex-wrap text-black',
                                    'message-div-other'=> !($message->sender_id=== auth()->id()),
                                    'message-div-me text-white'=> $message->sender_id=== auth()->id(),
                        ])>

                            <p class="text-message">{{ $message->body }}</p>
                            
                            <div class="ms-auto d-flex align-items-center gap-2">
                                <p @class([
                                        'hour-message',
                                        'hour-other'=> !($message->sender_id=== auth()->id()),
                                        'text-white'=> $message->sender_id=== auth()->id(),
                                    ]) >

                                    {{$message->created_at->format('g:i a')}}
                                </p>

                                {{-- message status , only show if message belongs auth --}}
                                @if ($message->sender_id === auth()->id())
                                    <div
                                        x-data="{
                                            markAsRead:@json($message->isRead())
                                        }"
                                    > 
                                        {{-- double ticks --}}
                                        <span x-cloak x-show="markAsRead" @class('text-gray-200')>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check2-all" viewBox="0 0 16 16">
                                                <path d="M12.354 4.354a.5.5 0 0 0-.708-.708L5 10.293 1.854 7.146a.5.5 0 1 0-.708.708l3.5 3.5a.5.5 0 0 0 .708 0l7-7zm-4.208 7-.896-.897.707-.707.543.543 6.646-6.647a.5.5 0 0 1 .708.708l-7 7a.5.5 0 0 1-.708 0z"/>
                                                <path d="m5.354 7.146.896.897-.707.707-.897-.896a.5.5 0 1 1 .708-.708z"/>
                                            </svg>
                                        </span>

                                        {{-- single ticks --}}
                                        <span x-show="!markAsRead" @class('text-gray-200')>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check2" viewBox="0 0 16 16">
                                                <path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z"/>
                                            </svg>
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        {{-- send message  --}}
        <div class="flex-shrink-0 bg-white">
            <div class="border-top p-3">
                <form
                    x-data="{
                        body: @entangle('body').defer
                    }"
                    @submit.prevent="$wire.sendMessage"
                    method="POST" autocapitalize="off"
                >
                @csrf

                    <input type="hidden" autocomplete="false" style="display:none">
                    <div class="row px-3">
                        <input 
                                x-model="body"
                                type="text"
                                autocomplete="off"
                                autofocus
                                placeholder="Escreva sua mensagem aqui"
                                maxlength="1700"
                                class="col-11 input-send-message border-0 outline-0 focus:border-0 focus:ring-0 hover:ring-0 rounded-lg  focus:outline-none"
                        >
                        <button x-bind:disabled="!body.trim()" type='submit' class="send col-1">
                            <i class="icon fa fa-paper-plane-o" aria-hidden="true"></i>
                        </button>
                    </div>
                </form>

                @error('body')
                    <p> {{$message}} </p> 
                @enderror
            </div>
        </div>
    </div>
</div>