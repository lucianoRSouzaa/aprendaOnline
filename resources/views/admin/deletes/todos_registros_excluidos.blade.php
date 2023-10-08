@extends('layouts.app')

@push('styles')
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/reports.css') }}">
    <link rel="stylesheet" href="{{ asset('css/deletes.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('js/admin-deletes.js') }}"></script>

  <script>
      $(document).ready(function() {

          $('#ModalConfirmacaoExclusao').on('hidden.bs.modal', function () {
            var form = $('#ModalConfirmacaoExclusao .modal-content').find('form.delete-form');
            var originalAction = "{{ route('admin.delete-permanently', ['id' => ':id', 'type' => ':type']) }}";
            form.attr('action', originalAction);
          });

          $('#ModalConfirmacaoRestaurar').on('hidden.bs.modal', function () {
            var form = $('#ModalConfirmacaoRestaurar .modal-content').find('form.restore-form');
            var originalAction = "{{ route('admin.restore', ['id' => ':id', 'type' => ':type']) }}";
            form.attr('action', originalAction);
          });
      });
  </script>

  @if (session('success'))
  <script>
      $(document).ready(function () {
          $('#ModalSucess').modal('show');
      });
  </script>
  @endif

  @if (session('error'))
  <script>
    $(document).ready(function () {
        $('#ModalError').modal('show');
    });
  </script>
  @endif
@endpush

@section('main')
<div class="modal fade" id="ModalConfirmacaoExclusao" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
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
                  <span class="title">Você tem certeza que deseja excluir?</span>
                  <p id="text-confirmation" class="message"></p>
              </div>
              <div class="actions">
                  <button class="cancel" data-bs-dismiss="modal" type="button">Cancelar</button>
                  <form action="{{ route('admin.delete-permanently', ['id' => ':id', 'type' => ':type']) }}" method="POST" class="delete-form">
                      @csrf
                      <button class="desactivate" type="submit">Deletar</button>
                  </form>
              </div>
          </div>
      </div>
  </div>
</div>

<div class="modal fade" id="ModalConfirmacaoRestaurar" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
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
                  <span class="title">Você tem certeza que deseja restaurar?</span>
                  <p id="text-confirmation-restaurar" class="message"></p>
              </div>
              <div class="actions">
                  <button class="cancel" data-bs-dismiss="modal" type="button">Cancelar</button>
                  <form action="{{ route('admin.restore', ['id' => ':id', 'type' => ':type']) }}" method="POST" class="restore-form">
                      @csrf
                      <button class="desactivate restore" type="submit">Restaurar</button>
                  </form>
              </div>
          </div>
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
                  <span class="title">{{ session('success') }}</span>
                  <p class="message">Todos os registros foram apagados, é como se eles nunca tivessem existidos!</p>
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
                  <span class="title">Erro ao restaurar!</span>
                  <p class="message">{{ session('error') }}</p>
              </div>
              <div class="actions">
                  <button class="cancel" data-bs-dismiss="modal" type="button">Cancelar</button>
                  <a href="{{ route('admin.todos.registros.excluidos', ['search_term' => session('title'), 'search_type' => session('type')]) }}" class="desactivate" type="button">Restaurar</a>
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
                  <li class="sidebar-list-item d-flex">
                    <a href="{{ route("admin.categories.index") }}" >
                      <span class="material-icons-outlined">fact_check</span> Categorias
                    </a>
                  </li>
                  <li class="sidebar-list-item d-flex active">
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
                      <span class="material-icons-outlined">settings</span> Configurações
                    </a>
                  </li>
                </ul>
              </aside>
        </div>
        <div class="col-table pt-3 pb-5">
            <form action="{{ route("admin.todos.registros.excluidos") }}" method="GET" class="mb-3 mt-4">
                <div class="input-group">
                    <input type="text" class="form-control" name="search_term" placeholder="Digite sua pesquisa...">
                    <select class="form-select" name="search_type">
                        <option value="course_title">Nome do Curso</option>
                        <option value="module_title">Nome do Módulo</option>
                        <option value="lesson_title">Nome da Aula</option>
                    </select>
                    <button type="submit" class="btn"><i class="fa fa-search"></i></button>
                </div>
            </form>
            @if ($searchTerm)
              <div class="d-flex align-items-center ml-5">
                <h4>Registros encontrados a partir do termo: "{{ $searchTerm }}"</h4>
                <a href="{{ route("admin.todos.registros.excluidos") }}"><i class="fa-regular fa-circle-xmark fa-xl"></i></a>
              </div>
            @endif
            <table class="table table-striped table-hover shadow">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Registro excluído</th>
                        <th scope="col">Título</th>
                        <th scope="col">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($resultados as $resultado)
                        <tr onclick="toggleModulesLessonsDeleted({{ $resultado->id }})">
                            <th scope="row">{{ $resultado->id }}</th>
                            <td>
                                {{ $resultado->tipo }}
                            </td>
                            <td class="exclusao-titulo">{{ $resultado->title }}</td>
                            <td>
                                <a class="btn btn-danger delete-all-icon" data-bs-target="#ModalConfirmacaoExclusao" data-bs-toggle="modal" data-id="{{ $resultado->id }}" data-title="{{ $resultado->title }}" data-type="{{ $resultado->tipo }}">
                                    <i class="fa-regular fa-trash-can fa-lg"></i>
                                    <span>excluir permanentemente</span>
                                </a>
                                    <br>
                                <a class="btn btn-primary restore-all-icon" data-bs-target="#ModalConfirmacaoRestaurar" data-bs-toggle="modal" data-id="{{ $resultado->id }}" data-title="{{ $resultado->title }}" data-type="{{ $resultado->tipo }}">
                                    <i class="fa-solid fa-clock-rotate-left fa-lg"></i>
                                    <span>Restaurar curso</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <td colspan="4">
                            <h4 class="pt-2">Nenhum registro encotrado.</h4>
                        </td>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
@endsection