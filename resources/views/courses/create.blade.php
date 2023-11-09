@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/cad-course.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('js/forms.js') }}"></script>
    <script>
        // Adicionar vários campos de o que usuário irá aprender
        function adicionarCampo() {
            var novoCampo = $("<input>")
                .attr("type", "text")
                .attr("name", "learn[]")
                .addClass("mt-2");
            $("#camposInput").append(novoCampo);
        }
    </script>
@endpush

@section('main')
<div class="container">
    <header>Cadastro de curso</header>
    <div class="progress-bar">
      <div class="step">
        <p>Curso</p>
        <div class="bullet">
          <span>1</span>
        </div>
        <div class="check fas fa-check"></div>
      </div>
      <div class="step">
        <p>Estilização</p>
        <div class="bullet">
          <span>2</span>
        </div>
        <div class="check fas fa-check"></div>
      </div>
      <div class="step">
        <p>Configurações</p>
        <div class="bullet">
          <span>3</span>
        </div>
        <div class="check fas fa-check"></div>
      </div>
    </div>
    <div class="form-outer">
      <form action="{{ route('courses.store') }}" method="POST" enctype="multipart/form-data">
      @csrf
        <div class="page slide-page">
          <div class="title">Informações básicas:</div>
          <div class="field">
            <label class="label" for="title">Nome do curso</label>
            <input type="text" value="{{ old('title') }}" name="title" id="title" aria-label="Campo para nome do curso">
            @error('title')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
          <div class="field">
            <div class="label">Descrição</div>
            <textarea rows="7" name="description" aria-label="Campo para descrição do curso">{{ old('description') }}</textarea>
            @error('description')
                <span class="text-danger">{{ $message }}</span>
            @enderror
          </div>
            <button type="button" class="next pintado" data-page="0">Próximo</button>
        </div>

        <div class="page" style="display: none;">
          <div class="title">Imagem e categoria:</div>
          <div class="label">Imagem</div>
          <div class="d-flex justify-content-center border-rounded border-dashed page-create-course">
            <div class="text-center">
                <svg class="h-12 w-12 text-gray-300" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M1.5 6a2.25 2.25 0 012.25-2.25h16.5A2.25 2.25 0 0122.5 6v12a2.25 2.25 0 01-2.25 2.25H3.75A2.25 2.25 0 011.5 18V6zM3 16.06V18c0 .414.336.75.75.75h16.5A.75.75 0 0021 18v-1.94l-2.69-2.689a1.5 1.5 0 00-2.12 0l-.88.879.97.97a.75.75 0 11-1.06 1.06l-5.16-5.159a1.5 1.5 0 00-2.12 0L3 16.061zm10.125-7.81a1.125 1.125 0 112.25 0 1.125 1.125 0 01-2.25 0z" clip-rule="evenodd" />
                </svg>
                <div class="mt-4 d-flex text-sm text-gray-600">
                    <label for="file-upload" class="cursor-pointer font-semibold text-indigo-600">
                        <span>Selecione um arquivo</span>
                        <input id="file-upload" name="image" type="file" class="sr-only" accept="image/*">
                    </label>
                    <p class="pl-1">ou arraste e solte aqui</p>
                </div>
                <p class="text-xs leading-5 text-gray-600">Tipos de arquivos aceitos: JPG, PNG, GIF, JPEG</p>
                <p id="file-name" class="text-xs leading-5 text-gray-600"></p>
            </div>
        </div>
        @error('image')
                <span class="text-danger">{{ $message }}</span>
        @enderror
          <div class="field mt-4">
            <div class="label">Categoria</div>
            <select name="category" aria-label="Campo para selecionar categoria do curso">
                <option value="" selected disabled>--Selecione--</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
            @error('category')
                <span class="text-danger">{{ $message }}</span>
            @enderror
          </div>
          <div class="btns">
            <button type="button" class="prev" data-page="1">Voltar</button>
            <button type="button" class="next pintado" data-page="1">Próximo</button>
          </div>
        </div>

        <div class="page" style="display: none;">
          <div class="title">Seus dados:</div>
          <div class="field">
            <div class="label">Email para contanto</div>
            <input type="email" name="email" aria-label="Campo para email de contato do criador do curso" value="{{ $userEmail }}">
            @error('email')
                <span class="text-danger">{{ $message }}</span>
            @enderror
          </div>
          <div class="field last-field" id="camposInput">
              <label for="campoLearn" class="label">O que seus alunos irão aprender?</label>
              <input placeholder="Ex: Aprender a como programar" type="text" name="learn[]" id="campoLearn">
          </div>
          <span onclick="adicionarCampo()" class="add-response d-flex align-items-start">
              <img src="{{ asset('images/plus.png') }}" class="mt-2" alt="Icone de adicionar campo">
              <p class="txt">Adicionar uma resposta</p>
          </span>
          @error('learn')
                <span class="text-danger">{{ $message }}</span>
          @enderror
          <div class="btns">
            <button type="button" class="prev" data-page="2">Voltar</button>
            <button class="submit pintado" type="submit">Criar</button>
          </div>
        </div>
      </form>
    </div>
</div>
@endsection