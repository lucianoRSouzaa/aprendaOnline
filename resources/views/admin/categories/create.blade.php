@extends('layouts.app')

@push('styles')
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/reports.css') }}">
    <link rel="stylesheet" href="{{ asset('css/category.css') }}"> 
@endpush

@push('scripts')
  <script>
      $(document).ready(function () {
        $(".add-category").click(function () {
            $("#ModalAddCategory").modal("show");
        });
    });
  </script>

  @if ($errors->has('name'))
    <script>
        $(document).ready(function () {
            $('#ModalAddCategory').modal('show');
        });
    </script>
  @endif
@endpush

@section('main')

<div class="modal fade" id="ModalAddCategory" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.categories.store') }}" method="POST">
            @csrf
                <div class="modal-body">
                    <div class="d-flex">
                        <div class="image">
                            <img src="{{ asset('images/confirmation.png') }}" alt="" srcset="">
                        </div>
                        <div class="close">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                    </div>
                    <div class="content">
                        <p class="title">Deseja criar uma nova categoria?</p>
                        <label class="label" for="name">Nome da categoria:</label><br>
                        <input class="form-control" type="text" name="name" value="{{ old('name') }}">
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="actions">
                        <button class="cancel" data-bs-dismiss="modal" type="button">Cancelar</button>
                        <button class="desactivate restore" type="submit">Criar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

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
                  <button class="cancel btns-restaurar" data-bs-dismiss="modal" type="button">Cancelar</button>
                  <button class="btn success btns-restaurar" data-bs-dismiss="modal" type="submit">Ok</button>
              </div>
          </div>
      </div>
  </div>
</div>
@endif

    <div class="grid-container">
        <div class="col-aside">
            <aside id="sidebar">
                <div class="sidebar-title">
                  <div class="sidebar-brand">
                    <span class="material-icons-outlined">school</span> AprendaOnline
                  </div>
                  <span class="material-icons-outlined" onclick="closeSidebar()">close</span>
                </div>
        
                <ul class="sidebar-list">
                  <li class="sidebar-list-item d-flex">
                    <a href="{{ route("admin.dashboard") }}" >
                      <span class="material-icons-outlined">dashboard</span> Dashboard
                    </a>
                  </li>
                  <li class="sidebar-list-item d-flex">
                    <a href="{{ route("admin.todos.cursos") }}" >
                      <span class="material-icons-outlined">inventory_2</span> Cursos
                    </a>
                  </li>
                  <li class="sidebar-list-item d-flex">
                    <a href="{{ route("admin.user.index") }}">
                      <span class="material-icons-outlined">person</span> Usuários
                    </a>
                  </li>
                  <li class="sidebar-list-item d-flex active">
                    <a href="{{ route("admin.categories.index") }}" >
                      <span class="material-icons-outlined">fact_check</span> Categorias
                    </a>
                  </li>
                  <li class="sidebar-list-item d-flex">
                    <a href="{{ route("admin.deletes") }}">
                      <span class="material-icons-outlined">delete</span> Exclusões
                    </a>
                  </li>
                  <li class="sidebar-list-item d-flex">
                    <a href="{{ route("admin.reports")}}">
                      <span class="material-icons-outlined">notification_important</span> Denuncias
                    </a>
                  </li>
                  <li class="sidebar-list-item d-flex">
                    <a href="#" >
                      <span class="material-icons-outlined">chat</span> Suporte
                    </a>
                  </li>
                </ul>
              </aside>
        </div>
        <div class="col-table">
            <form action="{{ route("admin.categories.create") }}" method="GET" class="mb-3 mt-4">
              <div class="input-group">
                  <input type="text" class="form-control" name="search_term" placeholder="Busque pelo nome da categoria" @if($searchTerm) value="{{$searchTerm}}" @endif>
                  <button type="submit" class="btn"><i class="fa fa-search"></i></button>
              </div>
            </form>
            @if ($searchTerm)
              <div class="d-flex align-items-center ml-5">
                <h4>Registros encontrados a partir do termo: "{{ $searchTerm }}"</h4>
                <a href="{{ route("admin.categories.create") }}"><i class="fa-regular fa-circle-xmark fa-xl"></i></a>
              </div>
            @endif
            <table class="table table-striped table-hover shadow">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Categoria</th>
                        <th scope="col">Ver Dados</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                        <tr>
                            <th scope="row">{{ $category->id }}</th>
                            <td>{{ $category->name }}</td>
                            <td class="centralizado"><a href="{{ route('admin.categories.data.show', $category->id) }}"><img src="{{ asset('images/entrar.png') }}" alt="Ver mais"></a></td>
                        </tr>
                    @empty
                        <td colspan="3">
                          <h4 class="pt-2">Nenhum registro encotrado.</h4>
                        </td>
                    @endforelse
                    <tr>
                        <td colspan="3" class="add-category">
                            <div class="d-flex align-items-center justify-content-center">
                                <img src="{{ asset('images/plus.png') }}" alt="icone-adicionar">
                                <span class="txt-add-category">Criar nova categoria</span>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="mt-4 d-flex justify-content-center">
              {{ $categories->links() }}
            </div>
        </div>
    </div>
    
@endsection