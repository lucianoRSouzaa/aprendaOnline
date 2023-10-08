@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/configs.css') }}">
@endpush

@section('main')
    <div class="container d-flex flex-column justify-content-center">
        <div class="card shadow-sm">
            <h2 class="text-center">{{ trans('settings') }}</h2>

            <h5>{{ trans('chooseTheme') }}</h5>
            <div class="themes d-flex">
                <div class="theme w-50 d-flex justify-content-center align-items-center">
                    <i class="fa fa-sun-o fa-xl" aria-hidden="true"></i>
                </div>
                <span class="line"></span>
                <div class="theme w-50 d-flex justify-content-center align-items-center">
                    <i class="fa-solid fa-moon fa-xl" aria-hidden="true"></i>
                </div>                        
            </div>

            <h5 class="mt-4">{{ trans('chooseLang') }}</h5>
            <div class="d-flex gap-4 mb-5 mt-1">
                <a href="{{ route('lang', 'pt') }}" class="btn btn-outline-primary">Português</a>
                <a href="{{ route('lang', 'en') }}" class="btn btn-outline-primary">English</a>
                <a href="{{ route('lang', 'es') }}" class="btn btn-outline-primary">Español</a>
            </div>

            <div class="d-flex justify-content-center gap-4">
                <a href="{{ auth()->user()->isCreator() ? route('courses.creator') : route('courses.viewer') }}" class="btn btn-outline-secondary"><i class="fa-solid fa-circle-arrow-left fa-lg"></i>{{ trans('back') }}</a>
                <a href="{{ route('logout') }}" class="btn btn-danger"><i class="fa-solid fa-arrow-right-from-bracket fa-lg"></i>{{ trans('logout') }}</a>
            </div>
        </div>
    </div>
@endsection
