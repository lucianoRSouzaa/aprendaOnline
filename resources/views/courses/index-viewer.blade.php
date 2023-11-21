@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/cursos-page.css') }}">

    <style>
        .card .card-overlay{
            width: 50px;
            height: 50px;

            top: 10px;
            left: 12px;
        }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('js/script.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('.coracao').click(function() {
                $(this).closest('.favoriteForm').submit();
            });

            @if (session('creator'))
                $('#ModalCreatorErro').modal('show');
            @endif

            @if (session()->has('success'))
                $('#ModalSucess').modal('show');
            @endif

            @if (session()->has('error'))
                $('#ModalError').modal('show');
            @endif
        });
    </script>
@endpush


@section('header')
    @if (session()->has('creator'))
        <div class="modal fade" id="ModalCreatorErro" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
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
                            <span class="title">{{ trans('accessDeniedPermissionRequired') }}</span>
                            <p class="message">{{ session('creator') }}</p>
                        </div>
                        <div class="actions">
                            <button class="desactivate" data-bs-dismiss="modal" type="button">{{ trans('cancel') }}</button>
                            <a href="{{ route('user.edit', $user->email) }}" class="cancel text-center">{{ trans('becomeCreator') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <nav class="navbar-dark container">
        <!-- logo -->
        <div class="logo">
            <a href="{{ route('courses.viewer') }}">
                @if (Cookie::get('theme_preference', 'light') === 'dark')
                    <img class="logo2" src="{{ asset('images/logoMenu.png') }}" alt="">
                @else
                    <img class="logo2" src="{{ asset('images/logoMenu2.png') }}" alt="">
                @endif
            </a>
        </div>
        <div class="links">
            <ul class="nav-itens d-flex align-items-center">
                <!-- itens -->
                @auth
                    <li class="ball-li"><a href="{{ route('chat.index') }}">Chat @if($totalUnreadMessages)<span class="ball">{{ $totalUnreadMessages }}</span>@endif</a></li>
                    <li><a href="{{ route('courses.favorited') }}">Favoritos</a></li>
                    <li><a href="{{ route('courses.completed') }}">Cursos concluídos</a></li>
                    <li class="d-flex align-items-center profile" id="config"><img src="{{ asset($user->image) }}" class="rounded-circle avatar" alt=""><p class="nameUser">{{$user->name}} <i class="fa fa-angle-down" aria-hidden="true"></i></p></li>
                    <div class="notification-div">
                        <p class="text-center">Menu</p>
                        <hr>
                        @if (session('user_role') === 'viewer')
                            <a href="{{ route('courses.toggleMode') }}"><i class="fa-solid fa-user-tie fa-lg"></i>{{ trans('toggleModeCreator') }}</a>
                        @endif
                        <a href="{{ route('user.show', auth()->user()->email) }}"><i class="fa fa-user fa-lg" aria-hidden="true"></i>{{ trans('profile') }}</a>
                        <a href="{{ route('configs') }}"><i class="fa fa-cog fa-lg" aria-hidden="true"></i>{{ trans('settings') }}</a>
                        <div class="themes d-flex">
                            <a href="{{ route('theme', 'light') }}" class="theme w-50 d-flex justify-content-center align-items-center">
                                <i class="fa fa-sun-o fa-xl" aria-hidden="true"></i>
                            </a>
                            <span class="line"></span>
                            <a href="{{ route('theme', 'dark') }}" class="theme w-50 d-flex justify-content-center align-items-center">
                                <i class="fa-solid fa-moon fa-xl" aria-hidden="true"></i>
                            </a>                        
                        </div>
                        <a href="{{ route('logout') }}"><i class="fa-solid fa-arrow-right-from-bracket fa-lg"></i>{{ trans('logout') }}</a>
                    </div>
                    <li id="notifications" class="notifications"><i class="fa-solid fa-bell fa-lg"></i>@if(auth()->user()->unreadNotifications->count() > 0)<span class="ball"></span>@endif</li>
                    {{-- sidebar de notificações não lidas --}}
                    <div id="sidebar" class="sidebar">
                        <div class="d-flex justify-content-between title-notifications">
                            <p class="title-h">Notificações</p>
                            <i id="fechar-notifications" class="fa-solid fa-xmark fa-xl"></i>
                        </div>
                        <div id="notifications-container">
                            @foreach (auth()->user()->unreadNotifications as $notification)
                                <div class="notification @if($notification->type == 'App\Notifications\ReportRejected') recusada @elseif($notification->type == 'App\Notifications\ReportAccepted') aceita @endif">
                                    <p>{{ $notification->data['data'] }}</p>
                                </div>
                            @endforeach
                            @if(auth()->user()->unreadNotifications->count() == 0)
                                <p class="p-no-notiticatiosn text-center">Nenhuma notificação recente</p>
                            @endif
                        </div>
                    </div>
                @endauth
                @guest
                    <li><a href="{{ route('home') }}"><i class="fa fa-chevron-left" aria-hidden="true"></i> {{ trans('back') }}</a></li>
                @endguest
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
                        <span class="title">Sucesso!</span>
                        <p class="message">{{ session('success') }}</p>
                    </div>
                    <div class="actions">
                        <button class="cancel btns-restaurar" data-bs-dismiss="modal" type="button">Fechar</button>
                        <button class="btn success btns-restaurar" data-bs-dismiss="modal" type="button">Ok</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

@if (session()->has('error'))
    <div class="modal fade" id="ModalError" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="d-flex">
                        <div class="image">
                            <img src="{{ asset('images/warning.png') }}" alt="Icone de perigo" srcset="">
                        </div>
                        <div class="close">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                    </div>
                    <div class="content">
                        <span class="title">Ação não permitida!</span>
                        <p class="message">{{ session('error') }}</p>
                    </div>
                    <div class="actions">
                        <button class="cancel" data-bs-dismiss="modal" aria-label="Close" type="button">Fechar</button>
                        <button class="desactivate" data-bs-dismiss="modal" aria-label="Close" type="button">Ok</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

    <div class="container">
        <div class="search-container">
            <form action="{{ route('courses.search') }}" method="GET" class="search-bar">
                <input type="text" name="title" id="search-input" placeholder="{{ trans('searchPlaceholder') }}">
                <button type="submit" id="search-button"><i class="fa fa-search"></i></button>
            </form>
        </div> 

        <div class="d-flex justify-content-center search-link-page">
            <a href="{{ route('courses.search') }}">Ir para página de pesquisa personalizada</a>
        </div>

        <div class="row justify-content-center">
            @if($popularCourses)
            <h2>Cursos mais populares:</h2>
            <div class="wrapper">
                <i id="left" class="fa-solid fa-angle-left"></i>
                    <ul class="carousel">
                        @foreach($popularCourses as $popularCourse)
                            <div class="card shadow-sm">
                                <img class="img-card" src="{{ asset('storage/' . $popularCourse->image) }}" alt="Imagem do Card">
                                <div class="card-content d-flex flex-column justify-content-center">
                                    <p class="title">{{ $popularCourse->title }}</p>
                                    <p>{{ trans('madeBy') }} {{ $popularCourse->creator->name }}</p>
                                    <div class="d-flex align-items-center">
                                        <p>{{ trans('ratings') }} {{ number_format($popularCourse->average_rating, 1) }}</p>
                                        <img class="star" src="{{ asset('images/star/star-on.png') }}" alt="estrela da classificação">
                                    </div>
                                    
                                    <a href="{{ route('courses.show', $popularCourse->slug) }}" class="stretched-link"></a>
                                </div>
                            </div>
                        @endforeach
                    </ul>
                <i id="right" class="fa-solid fa-angle-right"></i>
            </div>
            @endif
            
            @auth
                @if ($subscribedCourses->count() > 0)
                <h2 class="subs">{{ trans('coursesImEnrolledIn') }} </h2>
                <div class="group-cards">
                @foreach($subscribedCourses as $subscribedCourse)
                    <div class="card">
                        <img class="img-card" src="{{ asset('storage/' . $subscribedCourse->image) }}" alt="Imagem do Card">
                        <div class="card-content">
                            <p class="title">{{ $subscribedCourse->title }}</p>
                            <p>{{ trans('madeBy') }} {{ $subscribedCourse->creator->name }}</p>
                            <div class="d-flex align-items-center">
                                <p>{{ trans('ratings') }} {{ number_format($subscribedCourse->average_rating, 1) }}</p>
                                <img class="star" src="{{ asset('images/star/star-on.png') }}" alt="estrela da classificação">
                            </div>
                            
                            <a href="{{ route('lessons.index', $subscribedCourse->slug) }}" class="stretched-link"></a>
                        </div>
                    </div>
                @endforeach
                </div>
                @endif

                @if ($favoriteCourses->count() > 0) 
                <h2 class="favs">{{ trans('myFavoriteCourses') }} </h2>
                <div class="group-cards">
                @foreach($favoriteCourses as $favoriteCourse)
                    <div class="card">
                        <div class="card-overlay">
                            <form class="favoriteForm" action="{{ route('courses.favorite.toggle', $favoriteCourse->slug) }}" method="POST">
                                @csrf
                                <img class="coracao coracaoPreenchido" src="{{ asset('images/coracaoPreenchido.png') }}" alt="favoritar">
                            </form> 
                        </div>
                        <img class="img-card" src="{{ asset('storage/' . $favoriteCourse->image) }}" alt="Imagem do Card">
                        <div class="card-content">
                            <p class="title">{{ $favoriteCourse->title }}</p>
                            <p>{{ trans('madeBy') }} {{ $favoriteCourse->creator->name }}</p>
                            <div class="d-flex align-items-center">
                                <p>{{ trans('ratings') }} {{ number_format($favoriteCourse->average_rating, 1) }}</p>
                                <img class="star" src="{{ asset('images/star/star-on.png') }}" alt="estrela da classificação">
                            </div>
                            
                            <a href="{{ route('courses.show', $favoriteCourse->slug) }}" class="stretched-link"></a>
                        </div>
                    </div>
                @endforeach
                </div>
                @endif
            @endauth
            
            <h2 class="discover">{{ trans('discoverSomeCourses') }} </h2>
            <div class="group-cards mb-5">
            @foreach($courses as $course)
                <div class="card">
                    <div class="card-overlay">
                        <form class="favoriteForm" action="{{ route('courses.favorite.toggle', $course->slug) }}" method="POST">
                            @csrf

                            @auth
                                @if (in_array($course->id, $favoriteCourseIds))
                                    <img class="coracao coracaoPreenchido" src="{{ asset('images/coracaoPreenchido.png') }}" alt="favoritar">
                                @else
                                    <img class="coracao" src="{{ asset('images/coracaoVazio.png') }}" alt="favoritar">
                                @endif
                            @endauth
                        </form> 
                    </div>
                    <img class="img-card" src="{{ asset('storage/' . $course->image) }}" alt="Imagem do Card">
                    <div class="card-content">
                        <p class="title">{{ $course->title }}</p>
                        <p>{{ trans('madeBy') }} {{ $course->creator->name }}</p>
                        <div class="d-flex align-items-center">
                            <p>{{ trans('ratings') }} {{ number_format($course->average_rating, 1) }}</p>
                            <img class="star" src="{{ asset('images/star/star-on.png') }}" alt="estrela da classificação">
                        </div>
                        
                        <a href="{{ route('courses.show', $course->slug) }}" class="stretched-link"></a>
                    </div>
                </div>
            @endforeach
            </div>
        </div>
    </div>
@endsection
