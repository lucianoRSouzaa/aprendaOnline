@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/modules.css') }}">
@endpush

@section('header')
    <div class="container">
        <div class="d-flex justify-content-between">
            <a href="{{ route('modules.index', $course->slug) }}" class="logo">
                @if (Cookie::get('theme_preference', 'light') === 'dark')
                    <img class="logo2" src="{{ asset('images/logoMenu.png') }}" alt="">
                @else
                    <img class="logo2" src="{{ asset('images/logoMenu2.png') }}" alt="">
                @endif
            </a>       
            <div class="search-container d-flex justify-content-end align-items-center">
                <a href="{{ route('modules.index', $course->slug) }}">{{ trans('modulesAndLessons') }}</a>
                <a class="active" href="{{ route('course.data.index', $course->slug) }}">{{ trans('viewCourseData') }}</a>
                <a href="{{ route('course.config', $course->slug) }}">{{ trans('settings') }}</a>
            </div>    
        </div>
    </div>
@endsection

@section('main')
    <div class="container overview">
        <h3 class="text-center mt-3">{{ $course->title }}</h3>
        <h5>Classificação:</h5>
        <ul>
            <li>Total de avaliações recebidas: {{ $totalRatings }}</li>
            <li>Média de avaliação do curso: {{ $averageRating }}</li>
        </ul>

        <h5>Aulas:</h5>
        <ul>
            <li>Total de aulas do curso: {{ $totalLessons }} aulas</li>
        </ul>

        <h5>Média da conclusão de aulas de cada módulo do curso:</h5>
        <ul>
            @forelse ($moduleData as $data)
                <li>{{ $data['moduleName'] }}: {{ $data['completedPercentage'] }}% </li>
            @empty
                
            @endforelse
        </ul>

        <h5>Registros de usuários:</h5>
        <ul>
            <li>Favoritado por: {{ $totalFavorites }} usuários</li>
            <li>Quantidade de usuários que se inscreveram no curso: {{ $totalSubscriptions }} </li>
            <li>Quantidade de usuários que completaram o curso: {{ $totalCompletions }} </li>
            <li>Taxa de conclusão: {{ $completionRate }}% </li>
        </ul>
    </div>
@endsection
