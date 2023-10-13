@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/lessons.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('js/jquery.raty.js') }}"></script>
    <script src="{{ asset('js/lessons.js') }}"></script>
@endpush

@section('main')

<div class="modal fade" id="RateModal" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
    <form method="post" action="{{ route('courses.rate', $course->slug) }}">
        @csrf
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="d-flex">
                        <div class="close">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                    </div>
                    <div class="content">
                        <span class="title">{{ trans('rateUsWithStarsYourOpinionIsImportant') }}</span>
                        <p class="message">{{ trans('selectOption') }}</p>

                        <div id="star"></div>

                        <p id="text-comment" onclick="adicionarCampo()" class="mt-3 text-primary comment">{{ trans('WouldULikeToSendCommentToo') }}</p>

                        <div id="comment">

                        </div>
                    </div>
                    <div class="actions">
                        <button class="cancel" type="submit">{{ trans('rate') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<div class="modal fade" id="ModalDenuncia" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="d-flex">
                    <div class="close">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                </div>
                <form action="{{ route('report.store', $course->slug) }}" method="POST">
                    @csrf
                    <div class="content">
                        <span class="title">{{ trans('report') }}: {{$course->title}}?</span>
                        <p class="mt-2">{{ trans('whatUWantToReport') }}</p>
                        <select class="form-control" name="denuncia" onchange="verificarSelecao()" id="denuncia-option">
                            <option selected disabled value="">--{{ trans('select') }}--</option>
                            <option value="curso">{{ trans('entireCourse') }}</option>
                            <option value="aula">{{ trans('lesson') }}</option>
                        </select>

                        <div id="denuncia-aula-option" class="selecao-aula">
                            <p class="mt-3">{{ trans('selectLessonUWantReport') }}</p>
                            <select class="form-control" name="selecao-aula" id="select-aula-option">
                                <option selected disabled value="">--{{ trans('select') }}--</option>
                                @foreach ($course->modules as $module)
                                    <optgroup label="{{$module->title}}">
                                        @foreach ($module->lessons as $lesson)
                                            <option value="{{ $lesson->id }}">{{ $lesson->title }}</option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                        </div>

                        <p class="mt-3">{{ trans('descriptionOfReport') }}</p>
                        <textarea name="desc" class="desc-txtarea"></textarea>
                    </div>
                    <div class="actions">
                        <button class="desactivate" type="submit">{{ trans('report') }}</button>
                        <button class="cancel" type="reset" data-bs-dismiss="modal">{{ trans('cancel') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

    <div class="row">
        <aside class="col-3 barra">
            @if (auth()->user()->isCreator() && !session()->has('user_role'))
            <a href="{{ route('courses.creator') }}" class="d-flex align-items-center header">
                <i class="fa fa-chevron-left" aria-hidden="true"></i><p class="ml-2">{{ trans('back') }}</p>
            </a>
            @else
            <a href="{{ route('courses.viewer') }}" class="d-flex align-items-center header">
                <i class="fa fa-chevron-left" aria-hidden="true"></i><p class="ml-2">{{ trans('back') }}</p>
            </a>
            @endif
            <div class="curso-nome mt-2 pb-3">
                <p>{{ $course->title }}</p>
                <p class="num-aulas">{{ $qtdLessons }} {{ trans('lessons') }}</p>
            </div>

            @php
                $lessonCount = 1;
            @endphp
            @foreach ($course->modules as $module)
                <div class="d-flex align-items-center justify-content-between mt-3 pb-3 modulos" onclick="toggleLessons({{ $module->id }})">
                    <div class="ml-3 text-module">
                        <p>{{ $module->title }}</p>
                    </div>
                    <i id="icon-module-{{ $module->id }}" class="fa fa-angle-down" aria-hidden="true"></i>
                </div>
                <div id="aula-{{ $module->id }}" class="group-aulas">
                    @foreach ($module->lessons as $lesson)
                        <div class="d-flex div-aula align-items-center mt-3 pb-3" data-video="{{ $lesson->video->id_google_drive }}" data-title="{{ $lesson->title }}">
                            <div class="ball shadow-lg">
                                <span class="number">{{ $lessonCount }}</span>
                            </div>
                            <div class="ml-3 text-aula">
                                <p>{{ $lesson->title }}</p>
                            </div>
                            <form action="@if ($user->completedLessons->contains($lesson->id)) {{ route('lesson.unmark') }} @else {{ route('lesson.done') }} @endif" method="post">
                            @csrf
                                <input class="ckx" type="checkbox" name="lesson_completed" value="{{ $lesson->id }}" @if ($user->completedLessons->contains($lesson->id)) checked @endif aria-label="marcar aula como concluída">
                                @if ($user->completedLessons->contains($lesson->id))
                                    <input type="hidden" name="lesson_uncompleted" value="{{ $lesson->id }}">
                                @endif
                            </form>
                            <a class="stretched-link" href="{{route("lessons.watch", ['courseSlug' => $course->slug, 'lessonSlug' => $lesson->slug]) }}" aria-label="Assistir aula"></a>
                        </div>
                        @php
                            $lessonCount++; 
                        @endphp
                    @endforeach
                </div>
            @endforeach
        </aside>
        <div class="col col-video">
            <div class="d-flex align-items-center justify-content-between header">
                <h2 class="">AprendaOnline</h2>
                <div class="btns-marg">
                    <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#RateModal">{{ trans('rateCourse') }}</button>
                    <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#ModalDenuncia">{{ trans('report') }}</button>
                    <button id="progress" class="btn btn-outline-primary">{{ trans('viewProgress') }}</button>
                    <div class="notification-div">
                        <p class="text-center">{{ trans('progress') }}</p>
                        <hr>
                        <p>{{ $perCent }}% ({{ $completedLessonsCount }} de {{ $qtdLessons }}) {{ trans('OfTheLessonsWereCompleted') }}</p>
                    </div>
                </div>
            </div>
            <div id="video-player">
                <video id="lesson-video" controls class="video pd mt-2">
                    <source type='video/mp4'>
                </video>
            </div>
            <h2 id="lesson-title" class="pd"></h2>
        </div>
    </div>
@endsection