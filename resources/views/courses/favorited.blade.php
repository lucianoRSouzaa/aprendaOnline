@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/cursos-page.css') }}">
@endpush

@push('scripts')
    <script>
        $(document).ready(function () {
            $('#config').click(function() {
                $('.notification-div').toggleClass('show');
                $('#sidebar').removeClass("open");
            });
        });
    </script>
@endpush

@section('header')
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
            <ul class="nav-itens nav-itens-other">
                <!-- itens -->
                <li class="d-flex align-items-center"><a href="{{ route('courses.favorited') }}">Favoritos</a></li>
                <li class="d-flex align-items-center"><a href="{{ route('courses.completed') }}">Cursos concluídos</a></li>
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
            </ul>
        </div>
    </nav>
@endsection

@section('main')
    <div class="container">
        <div class="row justify-content-center">
            <div class="group-cards mb-5 mt-4">
                @foreach($favoritedCourses as $favoritedCourse)
                    <div class="card">
                        <img class="img-card" src="{{ asset('storage/' . $favoritedCourse->image) }}" alt="Imagem do Card">
                        <div class="card-content">
                            <p class="title">{{ $favoritedCourse->title }}</p>
                            <p>Feito por: {{ $favoritedCourse->creator->name }}</p>
                            <div class="d-flex align-items-center">
                                <p>Avaliação: {{ number_format($favoritedCourse->average_rating, 1) }}</p>
                                <img class="star" src="{{ asset('images/star/star-on.png') }}" alt="estrela da classificação">
                            </div>
                            
                            <a href="{{ route('lessons.index', $favoritedCourse->slug) }}" class="stretched-link"></a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection