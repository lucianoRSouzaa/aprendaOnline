@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/cursos-page.css') }}">
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
            <ul class="nav-itens">
                <!-- itens -->
                @auth
                <li><a href="{{ route('courses.favorited') }}">Favoritos</a></li>
                @endauth
                <li><a href="{{ route('courses.completed') }}">Cursos concluídos</a></li>
                @auth
                <li><a href="{{ route('logout') }}">{{$nameUser}} <i class="fa fa-angle-down" aria-hidden="true"></i></a></li>
                @endauth
            </ul>
        </div>
    </nav>
@endsection

@section('main')
    <div class="container">
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
@endsection