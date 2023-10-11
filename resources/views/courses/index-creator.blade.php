@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/cursos-page.css') }}">

    <style>
        .card .card-overlay{
            background-color: rgba(105, 105, 105, 0.5); 
            border-radius: 15px;
            top: 12px;
            right: 12px;
        }

        .card-content {
            padding: 12px;
            color: #000;
            text-align: center; 
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('js/creator.js') }}"></script>
    <script src="{{ asset('js/script.js') }}"></script>
@endpush


@section('header')
    <nav class="navbar-dark container">
        <!-- logo -->
        <div class="logo">
            <a href="{{ route('courses.creator') }}" aria-label="Ir para o menu da página de criadores de conteúdo">
                <img class="logo2" src="{{ asset('images/logoMenu2.png') }}" alt="">
            </a>
        </div>
        <div class="links">
            <ul class="nav-itens d-flex align-items-center">
                <!-- itens -->
                <li><a href="#">{{ trans('myCourses') }}</a></li>
                <li><a href="#">{{ trans('support') }}</a></li>
                <li class="d-flex align-items-center profile" id="config"><img src="{{ asset($user->image) }}" class="rounded-circle avatar" alt=""><p class="nameUser">{{$user->name}} <i class="fa fa-angle-down" aria-hidden="true"></i></p></li>
                <div class="notification-div">
                    <p class="text-center">Menu</p>
                    <hr>
                    @if (!session()->has('user_role'))
                        <a href="{{ route('courses.toggleMode') }}"><i class="fa-solid fa-user-graduate fa-lg"></i>{{ trans('toggleModeStudent') }}</a>
                    @endif
                    <a href="{{ route('user.show', auth()->user()->id) }}"><i class="fa fa-user fa-lg" aria-hidden="true"></i>{{ trans('profile') }}</a>
                    <a href="{{ route('configs') }}"><i class="fa fa-cog fa-lg" aria-hidden="true"></i>{{ trans('settings') }}</a>
                    <div class="themes d-flex">
                        <div class="theme w-50 d-flex justify-content-center align-items-center">
                            <i class="fa fa-sun-o fa-xl" aria-hidden="true"></i>
                        </div>
                        <span class="line"></span>
                        <div class="theme w-50 d-flex justify-content-center align-items-center">
                            <i class="fa-solid fa-moon fa-xl" aria-hidden="true"></i>
                        </div>                        
                    </div>
                    <a href="{{ route('logout') }}"><i class="fa-solid fa-arrow-right-from-bracket fa-lg"></i>{{ trans('logout') }}</a>
                </div>
            </ul>
        </div>

        <!-- botão do menu responsivo -->
        <div class="toggle_btn">
            <i class="fa fa-bars" aria-hidden="true"></i>
        </div>

        <!-- links dentro do menu responsivo -->
        <div class="dropdown_menu">
            <li><a href="#">Cursos</a></li>
            <li><a href="#">Ferramentas</a></li>
            <li><a href="#">Suporte</a></li>
            <li><a href="#" class="gradient-button" data-bs-target="#Modal1" data-bs-toggle="modal">Entrar</a></li>
        </div>
    </nav>
@endsection

@section('main')
    <div class="container">
        <div class="search-container">
            <form action="{{ route('courses.search') }}" method="GET" class="search-bar">
                <input type="text" name="searchTerm" id="search-input" placeholder="{{ trans('searchPlaceholder') }}">
                <button type="submit" id="search-button" aria-label="Pesquisar"><i class="fa fa-search"></i></button>
            </form>
        </div> 

        <div class="d-flex justify-content-center search-link-page">
            <a href="{{ route('courses.search') }}">Ir para página de pesquisa personalizada</a>
        </div>

        <div class="row justify-content-center">
            @foreach($courses as $course)
                <div class="card">
                    <div class="card-overlay">
                        <a href="{{ route('courses.edit', $course->slug) }}" aria-label="editar curso"><i class="far fa-edit edit-icon"></i></a>
                        <i id="delete-btn" class="far fa-trash-alt delete-icon"></i>                      
                    </div>
                    <img class="img-card" src="{{ asset('storage/' . $course->image) }}" alt="Imagem do Card">
                    <div class="card-content">
                        <p class="title">{{ $course->title }}</p>
                        {{-- precisa tirar esse <a> para funcionar o edit e delete --}}
                        <a href="{{ route('modules.index', $course->slug) }}" class="stretched-link" aria-label="acessar curso"></a>
                    </div>
                </div>

                <div class="modal fade" id="ModalConfirmacaoExclusao" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-body">
                                <div class="d-flex">
                                    <div class="image">
                                        <img src="{{ asset('images/warning.png') }}" alt="" srcset="">
                                    </div>
                                    <div class="close">
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                </div>
                                <div class="content">
                                    <span class="title">{{ trans('areUSureWantDelete') }}</span>
                                    <p class="message">{{ trans('ifUdeleteThisCourse') }} ({{$course->title}}), {{ trans('allHisModulesAndLessonsAlsoDeleted') }}</p>
                                </div>
                                <div class="actions">
                                    <button class="cancel" data-bs-dismiss="modal" type="button">{{ trans('cancel') }}</button>
                                    <form action="{{ route('courses.destroy', $course->slug) }}" method="POST" class="delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button class="desactivate" type="submit">{{ trans('delete') }}</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="floating-container">
            <div class="floating-button"><a href="{{ route('courses.create') }}"><img src="{{ asset('images/plus-svgrepo-com.svg') }}" class="img-add" aria-label="Criar novo curso"></a></div>
        </div>
    </div>
@endsection
