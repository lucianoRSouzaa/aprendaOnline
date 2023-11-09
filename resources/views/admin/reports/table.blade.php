@extends('layouts.app')

@push('styles')
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/reports.css') }}">
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            @if (session()->has('success'))
                $('#ModalSuccess').modal('show');
            @endif
        });
    </script>
@endpush

@section('main')

@if (session()->has('success'))
    <div class="modal fade" id="ModalSuccess" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
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
                        <button class="cancel btns-restaurar" data-bs-dismiss="modal" type="button">Fechar</button>
                        <button class="btn success btns-restaurar" data-bs-dismiss="modal" type="button">Ok</button>
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
                  <li class="sidebar-list-item d-flex">
                    <a href="{{ route("admin.deletes") }}">
                      <span class="material-icons-outlined">delete</span> Exclusões
                    </a>
                  </li>
                  <li class="sidebar-list-item d-flex active">
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
            <form action="{{ route("admin.reports") }}" method="GET" class="mb-3 mt-4">
              <div class="input-group">
                  <input type="text" class="form-control" name="search_term" placeholder="Digite sua pesquisa..." @if($searchTerm) value="{{$searchTerm}}" @endif>
                  <select class="form-select" name="search_type">
                      <option value="course_title">Nome do Curso</option>
                      <option value="author">Autor do curso</option>
                      <option value="lesson_title">Aula denunciada</option>
                      <option value="user">Denunciado por</option>
                      <option value="description">Descrição da denúncia</option>
                  </select>
                  <button type="submit" class="btn"><i class="fa fa-search"></i></button>
              </div>
            </form>
            @if ($searchTerm)
              <div class="d-flex align-items-center ml-5">
                <h4>Registros encontrados a partir do termo: "{{ $searchTerm }}"</h4>
                <a href="{{ route("admin.reports") }}"><i class="fa-regular fa-circle-xmark fa-xl"></i></a>
              </div>
            @endif
            <a href="{{ route("admin.reports.slides")}}">Ver denuncias em forma de slide >></a>
            <table class="table table-striped table-hover shadow">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Curso</th>
                        <th scope="col">Autor do curso denunciado</th>
                        <th scope="col">Aula denunciada</th>
                        <th scope="col">Denunciado por</th>
                        <th scope="col">Descrição da denúncia</th>
                        <th scope="col">Ver mais</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reports as $report)
                        <tr>
                            <th scope="row">{{ $report->id }}</th>
                            <td>{{ $report->course->title }}</td>
                            <td>{{ $report->course->creator->name }}</td>
                            <td>{{ $report->lesson ? $report->lesson->title : '_____' }}</td>
                            <td>{{ $report->user->name }}</td>
                            <td class="descricao">{{ $report->description }}</td>
                            <td class="centralizado"><a href="{{ route('admin.reports.slide', $report->id) }}"><img src="{{ asset('images/entrar.png') }}" alt="Ver mais"></a></td>
                        </tr>
                    @empty
                      <tr>
                        <td colspan="7">Nenhum registro encontrado</td>
                      </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-4 d-flex justify-content-center">
              {{ $reports->links() }}
            </div>
        </div>
    </div>
    
@endsection