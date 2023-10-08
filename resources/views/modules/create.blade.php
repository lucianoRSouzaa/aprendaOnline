@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/cads.css') }}">

    <style>
        .fundo{
            overflow: hidden;
        }
    </style>
@endpush

@section('main')
    <div class="container">
        <div class="card shadow-sm m-5">
            <div class="card-header bg-azul text-white d-flex justify-content-center">
                <h1>Cadastro de Módulo</h1>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('modules.store', $course->slug) }}" method="POST">
                    @csrf
                    <label class="form-label" for="titulo">Título do Módulo:</label>
                    <input class="form-control" type="text" name="title" id="titulo" value="{{ old('title') }}">
                    <br>
                    <input type="hidden" name="course_slug" value="{{ $course->slug }}">
                    <div class="d-flex justify-content-center">
                        <button class="btn btn-hover flex-fill linha m-2" type="submit">Salvar</button>
                        <button class="btn btn-hover flex-fill linha m-2" type="reset">Limpar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
