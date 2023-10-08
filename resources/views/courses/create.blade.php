@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/cads.css') }}">
@endpush

@section('main')
    <div class="container">
        <div class="card shadow-sm m-5">
            <div class="card-header bg-azul text-white d-flex justify-content-center">
                <h1>Cadastro de curso</h1>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('courses.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <label class="form-label" for="title">Título:</label>
                    <input class="form-control" type="text" name="title" id="title">
                    @error('title')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                    <br>
                    <label class="form-label" for="description">Descrição:</label>
                    <textarea class="form-control" name="description" id="description"></textarea>
                    @error('description')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                    <br>
                    <label class="form-label" for="title">Categoria:</label>
                    <select class="form-select" name="category">
                        <option selected disabled value="">-- selecione --</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                    <br>
                    <label class="form-label" for="image">Imagem:</label>
                    <input class="form-control" type="file" name="image" id="image">
                    @error('image')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                    <br>
                    <div class="d-flex justify-content-center">
                        <button class="btn btn-hover flex-fill linha m-2" type="submit">Salvar</button>
                        <button class="btn btn-hover flex-fill linha m-2" type="reset">Limpar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
