@extends('layouts.app')

@push('styles')
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/reports.css') }}">
    <link rel="stylesheet" href="{{ asset('css/deletes.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('js/admin-deletes.js') }}"></script>
@endpush


@section('main')
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
                  <li class="sidebar-list-item d-flex active">
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
                      <span class="material-icons-outlined">settings</span> Configurações
                    </a>
                  </li>
                </ul>
              </aside>
        </div>
        <div class="col-table">
            <form action="{{ route("admin.todos.cursos") }}" method="GET" class="mb-3 mt-4">
              <div class="input-group">
                  <input type="text" class="form-control" name="search_term" placeholder="Busque pelo título do curso" @if($searchTerm) value="{{$searchTerm}}" @endif>
                  <button type="submit" class="btn"><i class="fa fa-search"></i></button>
              </div>
            </form>
            @if ($searchTerm)
              <div class="d-flex align-items-center ml-5">
                <h4>Registros encontrados a partir do termo: "{{ $searchTerm }}"</h4>
                <a href="{{ route("admin.todos.cursos") }}"><i class="fa-regular fa-circle-xmark fa-xl"></i></a>
              </div>
            @endif
            <table class="table table-hover shadow">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Curso</th>
                        <th scope="col">Status</th>
                        <th scope="col">Ver</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cursos as $curso)
                        <tr onclick="toggleModulesLessonsDeleted({{ $curso->id }})">
                            <th scope="row">{{ $curso->id }}</th>
                            <td class="exclusao-titulo">{{ $curso->title }}</td>
                            @if ($curso->trashed()) 
                                <td>
                                    <div class="d-flex align-items-center justify-content-center excluido border-curso">
                                        <span class="ball excluido"></span> excluído
                                    </div>
                                </td>
                            @else
                                <td>
                                    <div class="d-flex align-items-center justify-content-center ativo border-curso">
                                        <span class="ball ativo"></span> ativo
                                    </div>
                                </td>
                            @endif
                            <td>
                              @if($curso->modules->count() > 0)
                                <i class="fa fa-angle-down" aria-hidden="true" id="icon-module-{{ $curso->id }}"></i>
                              @endif
                            </td>
                        </tr>
                        @if($curso->modules->count() > 0)
                          <tr class="exclusoes modulos-excluidos" id="modulo-{{ $curso->id }}">
                            <td colspan="4">
                              <h5>Módulos e aulas excluídos de: {{ $curso->title }}</h5>
                              <ul class="box-modules-lessons-deleted fa-ul">
                                @foreach ($curso->modules as $modules)
                                  <li class="module-title"><i class="fa-li fa fa-book fa-lg"></i><p>{{ $modules->title }} {{ $modules->trashed() ? "(excluído)" : ''}}</p></li>
                                  @foreach ($modules->lessons as $lesson)
                                    <li class="lesson-title"><i class="fa-li fa fa-video"></i>{{ $lesson->title }} {{ $lesson->trashed() ? "(excluído)" : ''}}</li>
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
            </table>
            <div class="mt-4 d-flex justify-content-center">
              {{ $cursos->links() }}
            </div>
        </div>
    </div>
    
@endsection