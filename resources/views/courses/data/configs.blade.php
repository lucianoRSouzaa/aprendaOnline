@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/modules.css') }}">
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            @if (session('success'))
                $('#ModalSucess').modal('show');
            @endif
            
            @if (session()->has('error'))    
                $('#ModalError').modal('show');
            @endif
        });
    </script>
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
                <a href="{{ route('course.data.index', $course->slug) }}">{{ trans('viewCourseData') }}</a>
                <a class="active" href="{{ route('course.config', $course->slug) }}">{{ trans('settings') }}</a>
            </div>    
        </div>
    </div>
@endsection

@section('main')

@if (session()->has('error'))
    <div class="modal fade" id="ModalError" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
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
                        <span class="title">Ação não concluída</span>
                        <p class="message">{{ session('error') }}</p>
                    </div>
                    <div class="actions">
                        <button class="btn btn-outline-danger" data-bs-dismiss="modal" type="button">{{ trans('cancel') }}</button>
                        <button class="cancel" data-bs-dismiss="modal" type="button">Ok</button>
                    </div>
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
                    <span class="title">Sucesso!</span>
                    <p class="message">{{ session('success') }}</p>
                </div>
                <div class="actions">
                    <button class="cancel btns-restaurar" data-bs-dismiss="modal" type="button">Fechar</button>
                    <button class="btn success success btns-restaurar" data-bs-dismiss="modal" type="button">Ok</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<div class="modal fade" id="ModalConfirmacaoExclusao" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <form action="{{ route('courses.destroy', $course->slug) }}" method="POST" class="delete-form">
                @csrf
                @method('DELETE')
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
                        <input class="input-password mt-1" type="password" name="password" required placeholder="Insira sua senha para confirmar a exclusão">
                    </div>
                    <div class="actions">
                        <button class="cancel" data-bs-dismiss="modal" type="button">{{ trans('cancel') }}</button>
                        <button class="desactivate" type="submit">{{ trans('delete') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

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
                            <a class="config-btns btn btn-danger" data-bs-toggle="modal" data-bs-target="#ModalConfirmacaoExclusao">
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