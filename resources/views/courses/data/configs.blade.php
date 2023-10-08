@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/modules.css') }}">
@endpush

@section('header')
    <div class="container">
        <div class="d-flex justify-content-between">
            <a href="{{ route('modules.index', $course->slug) }}" class="logo">
                <img src="{{ asset('images/logoMenu2.png') }}" alt="Logo do site">
            </a>       
            <div class="search-container d-flex justify-content-end align-items-center">
                <a href="{{ route('modules.index', $course->slug) }}">{{ trans('modulesAndLessons') }}</a>
                <a href="{{ route('course.data.index', $course->slug) }}">{{ trans('viewCourseData') }}</a>
                <a class="active" href="{{ route('course.config', $course->slug) }}">{{ trans('settings') }}</a>
            </div>    
        </div>
    </div>
@endsection

@section('main')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card mt-5">
                    <div class="card-header w-100 d-flex justify-content-center">
                        <h3 class="course-title">{{ $course->title }}</h3>
                    </div>
                    <div class="card-body d-flex flex-column align-items-center">
                        <h4>Status atual do curso: {{ $status }}</h4>
                        @if ($isCourseCompleted === 0)
                            <form action="{{ route('courses.mark-complete', $course) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-primary">{{ trans('markCourseCompleted') }}</button>
                            </form> 
                        @else
                            <form action="{{ route('courses.unmark-complete', $course) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-danger">{{ trans('unmarkCourseCompleted') }}</button>
                            </form> 
                        @endif

                        <h4 class="mt-4">Edição e exclusão:</h4>
                        <div class="d-flex gap-4">
                            <a href="{{ route('courses.edit', $course->slug) }}" class="config-btns edit">
                                <i class="fa-solid fa-pen fa-xs"></i>
                                Editar curso
                            </a>
                            <a href="{{ route('courses.edit', $course->slug) }}" class="config-btns btn btn-danger">
                                <i class="fa-regular fa-trash-can"></i>
                                Excluir curso
                            </a>
                        </div>

                        <h4 class="mt-4">Ver página de detalhes do curso:</h4>
                        <a href="{{ route('courses.show', $course->slug) }}" class="btn btn-primary">{{ trans('view') }}<i class="fa-solid fa-angles-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection