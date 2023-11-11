<div class="modal-body text-center">
    <p>Procure por usuários da plataforma:</p>
    <input class="form-control" type="text" placeholder="Ex: Thiago" wire:model="query">
    <div class="div-perfils">
        @forelse ($this->users as $user)
            <div class="d-flex justify-content-between align-items-center py-3 div-perfil">
                <div class="d-flex align-items-center">
                    <img class="photo" src="{{ asset($user->image) }}"></img>
                    <p class="modal-name">{{ $user->name }}</p>
                </div>
                <div class="btns">
                    <a href="{{ route('user.show', $user->email) }}" class="btn btn-dark">Ver perfil</a>
                    <a wire:click="startConversation({{$user->id}})" class="btn btn-primary">Iniciar conversa</a>
                </div>
            </div>
        @empty
            <div class="notFound">
                <p>Nenhum usuário encontrado com o nome: "{{ $query }}"</p>
            </div>
        @endforelse
    </div>
</div>