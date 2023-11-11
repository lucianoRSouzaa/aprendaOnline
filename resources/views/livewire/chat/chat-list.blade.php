<section
    x-data="{
        modal: null
    }"
    x-init="
        modal = new bootstrap.Modal($refs.modal);
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
                <input type="text" placeholder="Pesquisar conversa">
            </div>
        </div>
        <div class="adicionar d-flex justify-content-center" @click="modal.show()">
            <i class="fa-solid fa-plus"></i>
        </div>
    </div>

    <div class="box-conversa d-flex active">
        <img class="photo" src="{{ asset('images/defaultUser.jpg') }}"></img>
        <div class="desc-contact d-flex align-items-center">
            <p class="name">Luciano</p>
        </div>
        <span class="notification d-flex justify-content-center align-items-center">2</span>
    </div>
    <div class="box-conversa d-flex">
        <img class="photo" src="{{ asset('images/defaultUser.jpg') }}"></img>
        <div class="desc-contact d-flex align-items-center">
            <p class="name">Gustavo</p>
        </div>
        <span class="notification d-flex justify-content-center align-items-center">3</span>
    </div>
    <div class="box-conversa d-flex">
        <img class="photo" src="{{ asset('images/defaultUser.jpg') }}"></img>
        <div class="desc-contact d-flex align-items-center">
            <p class="name">Leandro</p>
        </div>
        <span class="notification d-flex justify-content-center align-items-center">1</span>
    </div>
</section>