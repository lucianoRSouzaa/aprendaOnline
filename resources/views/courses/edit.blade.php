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
                .addClass("input-will-learn");
            $("#camposInput").append(novoCampo);
        }
    </script>
@endpush

@section('main')
<div class="container">
    <header>Edição de curso</header>
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
      <form action="{{ route('courses.update', $course->slug) }}" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PUT')
        <div class="page slide-page">
          <div class="title">Informações básicas:</div>
          <div class="field">
            <label class="label" for="title">Nome do curso</label>
            <input type="text" value="{{ $course->title }}" name="title" id="title" aria-label="Campo para nome do curso">
            @error('title')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
          <div class="field">
            <div class="label">Descrição</div>
            <textarea rows="7" name="description" aria-label="Campo para descrição do curso">{{ $course->description }}</textarea>
            @error('description')
                <span class="text-danger">{{ $message }}</span>
            @enderror
          </div>
            <button type="button" class="next pintado" data-page="0">Próximo</button>
        </div>

        <div class="page" style="display: none;">
            <div class="title">Imagem e categoria:</div>
            <div class="label">Imagem atual:</div>
            <img class="image-atual" id="fotopreview" src="{{ asset('storage/' . $course->image) }}" alt="Imagem atual do curso">
            <div class="d-flex justify-content-center">
                <label class="edit mt-2" for="image"><i class="fa-solid fa-pencil"></i> Alterar foto</label>
                <input type="file" accept="image/*" id="image" name="image" class="sr-only">
            </div>
            @error('image')
                    <span class="text-danger">{{ $message }}</span>
            @enderror
          <div class="field mt-4">
            <div class="label">Categoria</div>
            <select name="category_id" aria-label="Campo para selecionar categoria do curso">
                <option value="" selected disabled>--Selecione--</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @if($course->category_id == $category->id) selected @endif>{{ $category->name }}</option>
                @endforeach
            </select>
            @error('category_id')
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
            <input type="email" name="contact_email" aria-label="Campo para email de contato do criador do curso" value="{{ $course->contact_email }}">
            @error('contact_email')
                <span class="text-danger">{{ $message }}</span>
            @enderror
          </div>
          <div class="field last-field" id="camposInput">
              <label for="campoLearn" class="label">O que seus alunos irão aprender?</label>
                @foreach($whatWillLeran as $learn)
                    <input class="input-will-learn" type="text" name="learn[]" id="campoLearn" value="{{ $learn }}">
                @endforeach
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
            <button class="submit pintado" type="submit">Atualizar</button>
          </div>
        </div>
      </form>
    </div>
</div>
@endsection