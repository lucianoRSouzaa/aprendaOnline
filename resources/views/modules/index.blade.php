@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/modules.css') }}">

    <style>
        .box .div-principal span{
            background-image: url("{{ asset('images/indiceModulo.png') }}");
        }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('js/modules.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.delete-module-icon').click(function() {
                var moduleSlug = $(this).data('module-slug');

                var form = $('#ModalConfirmacaoExclusaoModulo .modal-content').find('form.delete-form');

                var action = form.attr('action');
                action = action.replace(':moduleSlug', moduleSlug);
                form.attr('action', action);
            });

            $('#ModalConfirmacaoExclusaoModulo').on('hidden.bs.modal', function () {
                var form = $('#ModalConfirmacaoExclusaoModulo .modal-content').find('form.delete-form');
                var originalAction = "{{ route('modules.destroy', ['courseSlug' => $course->slug, 'moduleSlug' => ':moduleSlug']) }}";
                form.attr('action', originalAction);
            });

            $('.delete-lesson-icon').click(function() {
                var moduleSlug = $(this).data('module-slug');
                var lessonSlug = $(this).data('lesson-slug');
                
                var form = $('#ModalConfirmacaoExclusaoAula .modal-content').find('form.delete-form');

                var action = form.attr('action');
                action = action.replace(':lessonSlug', lessonSlug);
                action = action.replace(':moduleSlug', moduleSlug);
                form.attr('action', action);
            });

            $('#ModalConfirmacaoExclusaoAula').on('hidden.bs.modal', function () {
                var form = $('#ModalConfirmacaoExclusaoAula .modal-content').find('form.delete-form');
                var originalAction = "{{ route('lessons.destroy', ['moduleSlug' => ':moduleSlug', 'lessonSlug' => ':lessonSlug']) }}";
                form.attr('action', originalAction);
            });

            @if (session()->has('success'))
                $('#ModalSucess').modal('show');
            @endif

            @if (session()->has('error'))    
                $('#ModalError').modal('show');
            @endif

            @if ($errors->has('title'))
                $('#ModalCriarModulo').modal('show');
            @endif

            @if ($module)
                $('#ModalAlterarModulo').modal('show');
            @endif
        });

    </script>

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

    <div class="modal fade" id="ModalConfirmacaoExclusaoModulo" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <form action="{{ route('modules.destroy', ['courseSlug' => $course->slug, 'moduleSlug' => ':moduleSlug']) }}" method="POST" class="delete-form">
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
                            <p class="message">{{ trans('ifUDeleteModuleLessonsAlsoDeleted') }}</p>
                            <input class="input-password mt-1" type="password" name="password" required placeholder="Insira sua senha para confirmar a exclusão">
                        </div>
                        <div class="actions">
                            <button class="cancel" data-bs-dismiss="modal" type="button">{{ trans('cancel') }}</button>
                            <button class="desactivate" type="submit">{{trans('delete')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if ($module)
        <div class="modal fade modal-modulo" id="ModalAlterarModulo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('modules.update', ['courseSlug' => $course->slug, 'moduleSlug' => $module->slug]) }}" method="POST">
                    @csrf
                    @method('PUT')
                        <div class="modal-header justify-content-center">
                            <h1 class="modal-title fs-3 text-light" id="exampleModalLabel">Cadastro de módulos</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <label for="titulo">Título:</label><br>
                            <input class="form-control" type="text" name="title" id="title" value="{{ $module->title }}">
                            @error('title')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="modal-footer">
                            <a href="{{ route('modules.index', $course->slug) }}" class="btn btn-outline-primary">Cancelar</a>
                            <button type="submit" class="btn gradient">Atualizar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @else
        <div class="modal fade modal-modulo" id="ModalCriarModulo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('modules.store', $course->slug) }}" method="POST">
                    @csrf
                        <div class="modal-header justify-content-center">
                            <h1 class="modal-title fs-3 text-light" id="exampleModalLabel">Cadastro de módulos</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <label for="title">Título:</label><br>
                            <input class="form-control" type="text" name="title" id="title" value="{{ old('title') }}">
                            @error('title')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn gradient">Adicionar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <div class="modal fade" id="ModalConfirmacaoExclusaoAula" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <form action="{{ route('lessons.destroy', ['moduleSlug' => ':moduleSlug', 'lessonSlug' => ':lessonSlug']) }}" method="POST" class="delete-form">
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
                            <p class="message">{{ trans('ifUDeleteLessonVideoAlsoDeleted') }}</p>
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
        <h2 class="text-center mt-4 mb-2">{{ trans('modulesAndLessons') }}</h2>
        <div class="box">
            @foreach ($course->modules as $module)
                <div class="linha"></div>
                <div class="div-principal" onclick="toggleDivs(this)">
                    <div class="botoes">
                        <a href="{{ route('modules.edit', ['courseSlug' => $course->slug, 'moduleSlug' => $module->slug]) }}" aria-label="Editar módulo"><i class="far fa-edit edit-icon"></i></a>
                        <i class="far fa-trash-alt delete-module-icon" data-bs-target="#ModalConfirmacaoExclusaoModulo" data-bs-toggle="modal" data-module-slug="{{ $module->slug }}"></i>
                        <i class="fa fa-angle-down" aria-hidden="true"></i>
                    </div>
                    <div class="info-modulo">
                        <span>{{ $module->order }}</span>
                        <p class="texto-principal">{{ $module->title }}</p>
                    </div>
                </div>
                <div class="div-filhas">
                    <ul class="subitems">
                        @foreach ($module->lessons as $lesson)
                        <li class="titulo_modulo d-flex justify-content-between">
                            <div>
                                <div class="video-player d-flex align-items-center justify-content-center">
                                    <img src="{{ asset('images/botao-play.png') }}" alt="">
                                    <p>{{ trans('clickViewLesson') }}</p>
                                    <a class="stretched-link" href="{{route("lessons.watch", ['courseSlug' => $course->slug, 'lessonSlug' => $lesson->slug])}}" aria-label="Assistir aula"></a>
                                </div>
                                <p class="mb-3">{{ $lesson->title }}</p>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <a href="{{ route('lessons.edit', ['moduleSlug' => $module->slug, 'lessonSlug' => $lesson->slug]) }}" aria-label="Editar aula"><i class="far fa-edit edit-icon"></i></a>
                                <i class="far fa-trash-alt delete-lesson-icon" data-bs-target="#ModalConfirmacaoExclusaoAula" data-bs-toggle="modal" data-module-slug="{{ $module->slug }}" data-lesson-slug="{{ $lesson->slug }}"></i>
                            </div>
                        </li>
                        @endforeach
                        <li>
                            <div class="d-flex justify-content-end">
                                <a href="{{ route('lessons.create', $module->slug) }}" class="btn btn-add-aula btn-outline-primary">{{ trans('createLesson') }}</a>
                                <a href="{{ route('lesson.order', $module->slug) }}" class="btn btn-alt-ordem-aula btn-outline-primary">{{ trans('changeOrderLessons') }}</a>
                            </div>
                        </li>
                    </ul>
                </div>
            @endforeach
            
            <div class="d-grid">
                <a data-bs-target="#ModalCriarModulo" data-bs-toggle="modal" class="btn btn-add-mod btn-outline-primary">{{ trans('createModule') }}</a>
            </div>
        </div>
    </div>
@endsection
