@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/cursos-page.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('js/script.js') }}"></script>
@endpush

@section('header')
    <nav class="navbar-dark container">
        <!-- logo -->
        <div class="logo">
            <a href="{{ route('courses.viewer') }}">
                <img class="logo2" src="{{ asset('images/logoMenu2.png') }}" alt="">
            </a>
        </div>
        <div class="links">
            <ul class="nav-itens">
                <!-- itens -->
                @auth
                <li><a href="#">Favoritos</a></li>
                @endauth
                <li><a href="{{ route('courses.completed') }}">Cursos concluídos</a></li>
                @auth
                <li><a href="{{ route('logout') }}">{{$nameUser}} <i class="fa fa-angle-down" aria-hidden="true"></i></a></li>
                @endauth
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
        @foreach($completedCourses as $completedCourse)
            <div class="card">
                <img class="img-card" src="{{ asset('storage/' . $completedCourse->image) }}" alt="Imagem do Card">
                <div class="card-content">
                    <p class="title">{{ $completedCourse->title }}</p>
                    <p>Feito por: {{ $completedCourse->creator->name }}</p>
                    <div class="d-flex align-items-center">
                        <p>Avaliação: {{ number_format($completedCourse->average_rating, 1) }}</p>
                        <img class="star" src="{{ asset('images/star/star-on.png') }}" alt="estrela da classificação">
                    </div>
                    
                    <a href="{{ route('lessons.index', $completedCourse->slug) }}" class="stretched-link"></a>
                </div>
            </div>
        @endforeach
    </div>
@endsection