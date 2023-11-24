@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/cads.css') }}">

    <style>
        .fundo{
            overflow: hidden;
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Input file drag and drop
        $(document).ready(function() {
            // Get the file input element, box element, and file name element
            const fileInput = $('#file-upload')[0];
            const box = $('.border-dashed');
            const fileNameElement = $('#file-name');
        
            // Add event listeners for drag and drop events on the box element
            box.on('dragenter', handleDragEnter);
            box.on('dragover', handleDragOver);
            box.on('dragleave', handleDragLeave);
            box.on('drop', handleDrop);
        
            // Add event listener for file selection change
            $(fileInput).on('change', handleFileSelect);
        
            // Handle drag enter event
            function handleDragEnter(event) {
                event.preventDefault();
                event.stopPropagation();
                box.addClass('dragover');
            }
        
            // Handle drag over event
            function handleDragOver(event) {
                event.preventDefault();
                event.stopPropagation();
            }
        
            // Handle drag leave event
            function handleDragLeave(event) {
                event.preventDefault();
                event.stopPropagation();
                box.removeClass('dragover');
            }
        
            // Handle drop event
            function handleDrop(event) {
                event.preventDefault();
                event.stopPropagation();
                box.removeClass('dragover');
                fileInput.files = event.originalEvent.dataTransfer.files;
                handleFileSelect();
            }
        
            // Handle file selection change
            function handleFileSelect() {
                if (fileInput.files.length > 0) {
                    const fileName = fileInput.files[0].name;
                    fileNameElement.text('Arquivo atual: ' + fileName);
                }
            }
        });
    </script>
@endpush


@section('main')
    <div class="container">
        <div class="card shadow-sm m-5">
            <div class="card-header bg-azul text-white d-flex justify-content-center">
                <h1>Cadastro de aulas</h1>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('lessons.update', ['moduleSlug' => $module->slug, 'lessonSlug' => $lesson->slug]) }}" method="POST" enctype="multipart/form-data">
                    @method('PUT')
                    @csrf

                    <label class="form-label" for="titulo">Nome da aula:</label>
                    <input class="form-control" type="text" name="title" id="titulo" value="{{ $lesson->title }}">
                    @error('title')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                    <br>

                    <label for="video">Vídeo da aula:</label>
                    <div class="mt-2 d-flex justify-content-center border-rounded border-dashed">
                        <div class="text-center">
                            <svg class="h-12 w-12 text-gray-300" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M1.5 6a2.25 2.25 0 012.25-2.25h16.5A2.25 2.25 0 0122.5 6v12a2.25 2.25 0 01-2.25 2.25H3.75A2.25 2.25 0 011.5 18V6zM3 16.06V18c0 .414.336.75.75.75h16.5A.75.75 0 0021 18v-1.94l-2.69-2.689a1.5 1.5 0 00-2.12 0l-.88.879.97.97a.75.75 0 11-1.06 1.06l-5.16-5.159a1.5 1.5 0 00-2.12 0L3 16.061zm10.125-7.81a1.125 1.125 0 112.25 0 1.125 1.125 0 01-2.25 0z" clip-rule="evenodd" />
                            </svg>
                            <div class="mt-4 d-flex text-sm text-gray-600">
                                <label for="file-upload" class="cursor-pointer font-semibold text-indigo-600">
                                    <span>Selecione um arquivo</span>
                                    <input id="file-upload" name="video" type="file" class="sr-only" accept="video/*,.mkv">
                                </label>
                                <p class="pl-1">ou arraste e solte aqui</p>
                            </div>
                            <p class="text-xs leading-5 text-gray-600">Tipos de arquivos aceitos: WEBM, MP4, OGV, MKV</p>
                            <p id="file-name" class="text-xs leading-5 text-gray-600">Arquivo atual: {{ $videoName }}</p>
                        </div>
                    </div>
                    @error('video')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                    <br>

                    <label class="mt-2">Arquivos da aula:</label>
                    @if ($fileName)
                        <p>Arquivo atual: {{ $fileName }}</p>
                    @endif
                    <input class="form-control" type="file" name="file-lesson">

                    <br>
                    
                    <label class="form-label" for="texto">Descrição da aula:</label>
                    <textarea class="form-control" name="description" id="texto" rows="3">{{ $lesson->description }}</textarea>
                    @error('description')
                        <span class="text-danger">{{ $message }}</span>
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
