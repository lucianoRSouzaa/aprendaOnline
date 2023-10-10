@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/lessons.css') }}">

    <style>
        .box .div-principal span{
            background-image: url("{{ asset('images/indiceModulo.png') }}");
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>
    <script>
        $(document).ready(function() {
            var sortable = new Sortable(document.getElementById('aulas-list'));

            $('#salvar-button').click(function() {
                var novaOrdem = sortable.toArray();
                $('#novaOrdem-input').val(JSON.stringify(novaOrdem));
            });
        });
    </script>

    @if (session('success'))
    <script>
        $(document).ready(function () {
            $('#ModalSucess').modal('show');
        });
    </script>
    @endif
@endpush

@section('header')
    <div class="container">
        <div class="d-flex justify-content-between">
            <a href="{{ auth()->user()->isCreator() ? route('courses.creator') : route('courses.viewer') }}" class="logo">
                <img src="{{ asset('images/logoMenu2.png') }}" alt="Logo do site">
            </a> 
            <div class="search-container d-flex justify-content-end align-items-center">
                <a class="active" href="{{ route('modules.index', $course->slug) }}">{{ trans('modulesAndLessons') }}</a>
                <a href="{{ route('course.data.index', $course->slug) }}">{{ trans('viewCourseData') }}</a>
                <a href="{{ route('course.config', $course->slug) }}">{{ trans('settings') }}</a>
            </div>          
        </div>
    </div>
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
                    <button class="btn success success btns-restaurar" data-bs-dismiss="modal" type="submit">Ok</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

    <div class="container page-order">
        <h2 class="text-center mt-4 mb-2">Alterar ordem das aulas do módulo: {{ $moduleTitle }}</h2>
        <div class="box" id="aulas-list">
            @foreach ($lessons as $lesson)
                <div class="div-principal" data-id="{{ $lesson->id }}" onclick="toggleDivs(this)">
                    <div class="info-modulo">
                        <span>{{ $lesson->order }}</span>
                        <p class="texto-principal">{{ $lesson->title }}</p>
                    </div>
                </div>
            @endforeach
        </div>
        <form action="{{ route('lesson.reorder') }}" method="POST" id="reordenar-form">
        @csrf
            <div class="d-flex w-100 justify-content-center gap-4">
                <input type="hidden" name="novaOrdem" id="novaOrdem-input">
                <a class="btn btn-outline-dark w-100" href="{{ route('modules.index', $course->slug) }}"><i class="fa-solid fa-circle-arrow-left fa-lg"></i>{{ trans('back') }}</a>
                <button class="btn btn-primary w-100" type="submit" id="salvar-button">Salvar</button>
            </div>
        </form>            
    </div>
@endsection
