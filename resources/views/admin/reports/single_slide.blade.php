@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/reports.css') }}">
@endpush

@section('body')
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