@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/reports.css') }}">
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            @if (session('success'))
                $('#ModalSuccess').modal('show');
            @endif

            @if (session()->has('error'))
                $('#ModalErrorPassword').modal('show');
            @endif
        });
    </script>
@endpush

@section('body')
    @if (session()->has('success'))
    <div class="modal fade modais-confirmacao" id="ModalSuccess" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
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
                        <button class="cancel btns-restaurar" data-bs-dismiss="modal" type="button">Cancelar</button>
                        <button class="btn success btns-restaurar" data-bs-dismiss="modal" type="submit">Ok</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="modal fade modais-confirmacao" id="ModalConfirmacaoExclusaoCurso" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <form action="{{ route('courses.destroy', $report->course->slug) }}" method="POST" class="delete-form">
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
                            <p class="message">{{ trans('ifUdeleteThisCourse') }} ({{$report->course->title}}), {{ trans('allHisModulesAndLessonsAlsoDeleted') }}</p>
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

    @if (session()->has('error'))
    <div class="modal fade modais-confirmacao" id="ModalErrorPassword" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
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
                        <span class="title">Ação não concluída!</span>
                        <p class="message">{{ session('error') }}</p>
                    </div>
                    <div class="actions">
                        <button class="btns-restaurar desactivate" data-bs-dismiss="modal" type="button">Fechar</button>
                        <button class="btn cancel btns-restaurar" data-bs-dismiss="modal" type="button">Ok</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if($report->lesson)
    <div class="modal fade modais-confirmacao" id="ModalConfirmacaoExclusaoAula" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <form action="{{ route('lessons.destroy', ['moduleSlug' => $report->lesson->module->slug, 'lessonSlug' => $report->lesson->slug]) }}" method="POST" class="delete-form">
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
    @endif

    <a href="{{ route("admin.reports")}}" class="back-btn"><i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar</a>
    <div class="slider">
        <div class="slide card shadow-sm">
            <div class="card-header">
                <h1>Denuncia: {{ $report->course->title }}</h1>
                @if($report->lesson)
                    <h3>{{ $report->lesson->title }}</h3>
                @endif
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush mb-4">
                    <li class="list-group-item list-group-item-action"><b>Curso:</b> {{ $report->course->title }}</li>
                    <li class="list-group-item list-group-item-action"><b>Autor do curso:</b> {{ $report->course->creator->name }}</li>
                    @if($report->lesson)
                        <li class="list-group-item list-group-item-action"><b>Aula denunciada:</b> {{ $report->lesson->title }}</li>
                    @endif
                    <li class="list-group-item list-group-item-action"><b>Denunciado por:</b> {{ $report->user->name }}</li>
                    <li class="list-group-item list-group-item-action"><b>Descrição da denuncia:</b> {{ $report->description }}</li>
                </ul>
                <div class="d-flex justify-content-center">
                    <a href="{{ route('courses.show.deleted', $report->course->slug) }}" class="btn btn-outline-primary">Ver curso</a>
                    @if($report->lesson)
                        <a href="{{ route("lessons.watch.deleted", ['courseSlug' => $report->course->slug, 'lessonSlug' => $report->lesson->slug]) }}" class="btn btn-outline-primary">Ver aula denunciada</a>
                    @endif
                </div>
                <h4 class="acoes">Ações:</h4>
                <div class="d-flex justify-content-center gap-3">
                    <a href="#" class="btn btn-outline-primary">Chat com o autor</a>
                    <button class="btn btn-outline-primary" data-bs-target="#ModalConfirmacaoExclusaoCurso" data-bs-toggle="modal">Excluir curso denunciado</a>
                    @if($report->lesson)
                        <button class="btn btn-outline-primary" data-bs-target="#ModalConfirmacaoExclusaoAula" data-bs-toggle="modal">Excluir aula denunciada</a>
                    @endif
                </div>
            </div>
            <div class="card-footer d-flex justify-content-center align-items-center gap-5">
                <a class="d-flex justify-content-center align-items-center confirm-btn" href="{{ route('admin.reports.accept', $report->id) }}">
                    <img class="confirm" src="{{ asset('images/correto.png') }}" alt="icone de confirmar denuncia">
                    <p>Confirmar</p>
                </a>
                <a class="d-flex justify-content-center align-items-center cancel-btn" href="{{ route('admin.reports.decline', $report->id) }}">
                    <img class="cancel" src="{{ asset('images/cancelar.png') }}" alt="icone de confirmar denuncia">
                    <p>Recusar</p>
                </a>
            </div>
        </div>
    </div>

@endsection