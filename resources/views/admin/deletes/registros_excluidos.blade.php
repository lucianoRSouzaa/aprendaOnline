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


          @if (session()->has('errorPassword'))
            $('#ModalErrorPassword').modal('show');
          @endif

          @if (session('success'))
            $('#ModalSucess').modal('show');
          @endif

          @if (session('error'))
            $('#ModalError').modal('show');
          @endif
      });
  </script>
@endpush

@section('main')
<div class="modal fade" id="ModalConfirmacaoExclusao" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
  <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-body">
              <form action="{{ route('admin.delete-permanently', ['id' => ':id', 'type' => ':type']) }}" method="POST" class="delete-form">
              @csrf
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
                    <input class="input-password mt-1" type="password" name="password" required placeholder="Insira sua senha para confirmar a exclusão">
                </div>
                <div class="actions">
                    <button class="cancel" data-bs-dismiss="modal" type="button">Cancelar</button>
                    <button class="desactivate" type="submit">Deletar</button>
                </div>
              </form>
          </div>
      </div>
  </div>
</div>

<div class="modal fade" id="ModalConfirmacaoRestaurar" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
  <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-body">
            <form action="{{ route('admin.restore', ['id' => ':id', 'type' => ':type']) }}" method="POST" class="restore-form">
            @csrf
                <div class="d-flex">
                    <div class="image">
                        <img src="{{ asset('images/confirmation.png') }}" alt="" srcset="">
                    </div>
                    <div class="close">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                </div>
                <div class="content">
                    <span class="title">Você tem certeza que deseja restaurar?</span>
                    <p id="text-confirmation-restaurar" class="message"></p>
                    <input class="input-password mt-1" type="password" name="password" required placeholder="Insira sua senha para confirmar a restauração">
                </div>
                <div class="actions">
                    <button class="cancel" data-bs-dismiss="modal" type="button">Cancelar</button>
                    <button class="desactivate restore" type="submit">Restaurar</button>
                </div>
            </form>
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
                  <p class="message">Sua ação foi concluída com sucesso. O registro foi processado conforme sua solicitação.</p>
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

@if (session()->has('errorPassword'))
<div class="modal fade" id="ModalErrorPassword" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
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
                  <p class="message">{{ session('errorPassword') }}</p>
              </div>
              <div class="actions">
                <button class="btn btn-outline-danger" data-bs-dismiss="modal" type="button">{{ trans('cancel') }}</button>
                <button class="cancel" data-bs-dismiss="modal" type="button">Ok</button>
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
                      <span class="material-icons-outlined">chat</span> Suporte
                    </a>
                  </li>
                </ul>
              </aside>
        </div>
        <div class="col-table pt-3 pb-5">
            <form action="{{ route("admin.registros.excluidos", ['tipo' => $tipo]) }}" method="GET" class="mb-3 mt-2">
              <div class="input-group">
                  <input type="text" class="form-control" name="search_term" placeholder="Digite sua pesquisa..." @if ($searchTerm) value="{{ $searchTerm }}" @endif>
                  <button type="submit" class="btn"><i class="fa fa-search"></i></button>
              </div>
            </form>
            @if ($searchTerm)
              <div class="d-flex align-items-center ml-5">
                <h4>Registros encontrados a partir do termo: "{{ $searchTerm }}"</h4>
                <a href="{{ route("admin.registros.excluidos", ['tipo' => $tipo]) }}"><i class="fa-regular fa-circle-xmark fa-xl"></i></a>
              </div>
            @endif
            <table class="table table-hover shadow">
                @if ($tipo === 'cursos')
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Curso</th>
                            <th scope="col">Ações</th>
                            <th scope="col">Ver</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($registros as $registro)
                            <tr onclick="toggleModulesLessonsDeleted({{ $registro->id }})">
                                <th scope="row">{{ $registro->id }}</th>
                                <td class="exclusao-titulo">{{ $registro->title }}</td>
                                <td>
                                  <a class="btn btn-danger delete-course-icon" data-bs-target="#ModalConfirmacaoExclusao" data-bs-toggle="modal" data-course-id="{{ $registro->id }}" data-course-title="{{ $registro->title }}">
                                    <i class="fa-regular fa-trash-can fa-lg"></i>
                                    <span>excluir permanentemente</span>
                                  </a>
                                  <br>
                                  <a class="btn btn-primary restore-course-icon" data-bs-target="#ModalConfirmacaoRestaurar" data-bs-toggle="modal" data-course-id="{{ $registro->id }}" data-course-title="{{ $registro->title }}">
                                    <i class="fa-solid fa-clock-rotate-left fa-lg"></i>
                                    <span>Restaurar curso</span>
                                  </a>
                                </td>
                                <td>
                                  @if($registro->modules->count() > 0)
                                    <i class="fa fa-angle-down" aria-hidden="true" id="icon-module-{{ $registro->id }}"></i>
                                  @endif
                                </td>
                            </tr>
                            @if($registro->modules->count() > 0)
                              <tr class="exclusoes modulos-excluidos" id="modulo-{{ $registro->id }}">
                                <td colspan="4">
                                  <h5>Módulos e aulas excluídos de: {{ $registro->title }}</h5>
                                  <ul class="box-modules-lessons-deleted fa-ul">
                                    @foreach ($registro->modules as $modules)
                                      <li class="module-title"><i class="fa-li fa fa-book fa-lg"></i><p>{{ $modules->title }}</p></li>
                                      @foreach ($modules->lessons as $lesson)
                                        <li class="lesson-title"><i class="fa-li fa fa-video"></i>{{ $lesson->title }}</li>
                                      @endforeach
                                    @endforeach
                                  </ul>
                                </td>
                              </tr>
                            @endif
                        @empty
                        <td colspan="4">
                          <h4 class="pt-2">Nenhum registro encotrado.</h4>
                        </td>
                        @endforelse
                    </tbody>
                @elseif ($tipo === 'modulos')
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Módulo</th>
                            <th scope="col">Ações</th>
                            <th scope="col">Ver</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($registros as $registro)
                            <tr onclick="toggleLessonsDeleted({{ $registro->id }})">
                                <th scope="row">{{ $registro->id }}</th>
                                <td>{{ $registro->title }}</td>
                                <td>
                                  <a class="btn btn-danger delete-module-icon" data-bs-target="#ModalConfirmacaoExclusao" data-bs-toggle="modal" data-module-id="{{ $registro->id }}" data-module-title="{{ $registro->title }}">
                                    <i class="fa-regular fa-trash-can fa-lg"></i>
                                    <span>excluir permanentemente</span>
                                  </a>
                                  <br>
                                  <a class="btn btn-primary restore-module-icon" data-bs-target="#ModalConfirmacaoRestaurar" data-bs-toggle="modal" data-module-id="{{ $registro->id }}" data-module-title="{{ $registro->title }}">
                                    <i class="fa-solid fa-clock-rotate-left fa-lg"></i>
                                    <span>Restaurar módulo</span>
                                  </a>
                                </td>
                                <td>
                                  @if($registro->lessons->count() > 0)
                                    <i class="fa fa-angle-down" aria-hidden="true" id="icon-aula-{{ $registro->id }}"></i>
                                  @endif
                                </td>
                            </tr>
                            @if($registro->lessons->count() > 0)
                              <tr class="exclusoes aulas-excluidos" id="aula-{{ $registro->id }}">
                                <td colspan="4">
                                  <h5>Aulas excluídas de: {{ $registro->title }}</h5>
                                  <ul class="box-modules-lessons-deleted fa-ul">
                                    @foreach ($registro->lessons as $lesson)
                                      <li class="lesson-title"><i class="fa-li fa fa-video"></i>{{ $lesson->title }}</li>
                                    @endforeach
                                  </ul>
                                </td>
                              </tr>
                            @endif
                          @empty
                          <td colspan="4">
                            <h4 class="pt-2">Nenhum registro encotrado.</h4>
                          </td>
                          @endforelse
                      </tbody>
                @elseif ($tipo === 'aulas')
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Aula</th>
                            <th scope="col">Vídeo</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($registros as $registro)
                            <tr>
                                <th scope="row">{{ $registro->id }}</th>
                                <td>{{ $registro->title }}</td>
                                <td>{{ $registro->video->name }}</td>
                                <td>
                                  <a class="btn btn-danger delete-lesson-icon" data-bs-target="#ModalConfirmacaoExclusao" data-bs-toggle="modal" data-lesson-id="{{ $registro->id }}" data-lesson-title="{{ $registro->title }}">
                                    <i class="fa-regular fa-trash-can fa-lg"></i>
                                    <span>excluir permanentemente</span>
                                  </a>
                                  <br>
                                  <a class="btn btn-primary restore-lesson-icon" data-bs-target="#ModalConfirmacaoRestaurar" data-bs-toggle="modal" data-lesson-id="{{ $registro->id }}" data-lesson-title="{{ $registro->title }}">
                                    <i class="fa-solid fa-clock-rotate-left fa-lg"></i>
                                    <span>Restaurar aula</span>
                                  </a>
                                </td>
                            </tr>
                        @empty
                          <td colspan="4">
                            <h4 class="pt-2">Nenhum registro encotrado.</h4>
                          </td>
                        @endforelse
                    </tbody>
                @endif
            </table>
            <div class="mt-4 d-flex justify-content-center">
              {{ $registros->links() }}
            </div>
        </div>
    </div>
    
@endsection