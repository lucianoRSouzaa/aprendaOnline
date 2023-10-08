@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/user.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('js/user.js') }}"></script>

    @if (session()->has('changePassword'))
        <script>
            $(document).ready(function () {
                $('#ModalMudarSenha').modal('show');
            });
        </script>
    @endif

    @if (session()->has('success'))
        <script>
            $(document).ready(function () {
                $('#ModalSucess').modal('show');
            });
        </script>
    @endif
@endpush

@section('main')
    <div class="modal fade" id="ModalConfirmarSenha" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <form action="{{ route('user.update', $user) }}" method="post">
                        @csrf
                        @method("PUT")
                        <div class="d-flex">
                            <div class="image">
                                <img src="{{ asset('images/confirmation.png') }}" alt="" srcset="">
                            </div>
                            <div class="close">
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                        </div>
                        <div class="content">
                            <span class="title">Você tem certeza que deseja modificar sua senha?</span>
                            <p class="message">Insira sua senha para continuar:</p>
                            <input class="input-password" type="password" name="password" placeholder="Senha">
                        </div>
                        <div class="actions">
                            <button class="restore" type="submit">Continuar</button>
                            <button class="cancel" type="button" data-bs-dismiss="modal" aria-label="Close">Canecelar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade modal-termo" id="ModalTermoCriador" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title" id="exampleModalToggleLabel">TERMO DE REPONSABILIDADE</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <p>
                        Os serviços oferecidos pelo site Aprenda Online atende as necessidades de pessoas que tem interesse na realização de determinados cursos e interesse na construção de cursos para determinado público alvo.
                    </p>
                    <p>
                        Em casos de conteúdos impróprios, racismo e outras ações que infringem os direitos humanos o referente conteúdo será denunciado e revisado pelo administrador da plataforma e posteriormente excluído.
                    </p>
                    <p><strong>Caso a parte não aceite as condições do termo de responsabilidade,</strong> pode gerar consequências como: </p>
                    <ul class="termo-lista-consequencias">
                        <li>Impossibilidade de acesso a serviços, projeto ou atividades relacionadas; </li>
                        <li>Impossibilidade de utilização de software, licenças e sistemas;</li>
                        <li>Inviabilidade de participar do projeto objeto do termo de responsabilidade ou do contrato e até mesmo de outros eventuais projetos. </li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button id="concordar-termo" class="btn btn-success">Concordar</button>
                    <button id="recusar-termo" class="btn btn-danger">Recusar</button>
                </div>
            </div>
        </div>
    </div>

@if (session()->has('changePassword'))
    <div class="modal fade" id="ModalMudarSenha" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <form action="{{ route('user.update', $user) }}" method="post">
                    @csrf
                    @method("PUT")
                        <div class="d-flex">
                            <div class="image">
                                <img src="{{ asset('images/confirmation.png') }}" alt="" srcset="">
                            </div>
                            <div class="close">
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                        </div>
                        <div class="content">
                            <span class="title">Você tem certeza que deseja modificar sua senha?</span>
                            
                            <input class="input-password mt-2" type="password" name="newPassword" required placeholder="{{ session('changePassword') }}">

                            <input class="input-password mt-3 mb-1" type="password" name="newPassword_confirmation" required placeholder="Confirme sua senha:">
                        </div>
                        <div class="actions">
                            <button class="restore" type="submit">Continuar</button>
                            <button class="cancel" type="button" data-bs-dismiss="modal" aria-label="Close">Canecelar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endif

@if (session()->has('success'))
    <div class="modal fade" id="ModalSucess" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="d-flex">
                        <div class="image">
                            <img src="{{ asset('images/sucess.png') }}" alt="" srcset="">
                        </div>
                        <div class="close">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                    </div>
                    <div class="content">
                        <span class="title">{{ session('success') }}</span>
                    </div>
                    <div class="actions">
                        <button class="cancel btns-restaurar" data-bs-dismiss="modal" type="button">Cancelar</button>
                        <button class="btn success btns-restaurar" data-bs-dismiss="modal" type="submit">Ok</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

    <a href="{{ route('user.show', $user->id) }}" class="back-btn"><i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar</a>
    <div class="container d-flex flex-column justify-content-center edit-page">
        <div class="card shadow-sm">
            <form action="{{ route('user.update', $user) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method("PUT")
                <div class="row">
                    <div class="col-5 d-flex flex-column align-items-center mt-5">
                        <img src="{{ asset($user->image) }}" alt="" class="photo" id="fotopreview">
                        <label class="edit mt-3" for="uploadfoto"><i class="fa-solid fa-pencil"></i> Alterar foto</label>
                        <input type="file" accept="image/*" id="uploadfoto" name="uploadfoto" class="sr-only">
                        @error('uploadfoto')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-7 mt-5">
                        <h3>Modifique seus dados:</h3>
                        <div class="mb-4 mt-4">
                            <label for="name" class="form-label">Nome de usuário:</label>
                            <input type="text" class="form-control field" name="name" id="name" value="{{ $user->name }}">
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="email" class="form-label">Email:</label>
                            <input type="email" class="form-control field" name="email" id="email" value="{{ $user->email }}">
                            @error('email')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <p class="txt-type-user mt-4"><b>Tipo de usuário:</b> {{ $user->role }}</p>
                        @if ($user->isViewer())
                            <div class="mb-4 form-check">
                                <input type="checkbox" class="form-check-input" id="role" name="role">
                                <label class="form-check-label" for="role">Desejo me tornar criador de conteúdo</label>
                            </div>
                        @endif
                        <div class="mb-3 form-check @if($user->isCreator()) mt-4 @endif" data-bs-toggle="modal" data-bs-target="#ModalConfirmarSenha">
                            <input type="checkbox" class="form-check-input" id="password">
                            <label class="form-check-label" for="password">Desejo modificar minha senha</label>
                        </div>
                    </div>
                    <div class="col-12 align-self-end d-flex align-items-center justify-content-center gap-5">
                        <button type="reset" class="cancel"><i class="fa-solid fa-xmark"></i>Cancelar</button>
                        <button type="submit" class="confirm"><i class="fa-solid fa-check"></i>Salvar alterações</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection