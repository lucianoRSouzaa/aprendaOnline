@extends('layouts.app')

@push('styles')
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/reports.css') }}">
    <link rel="stylesheet" href="{{ asset('css/category.css') }}"> 
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
                  <li class="sidebar-list-item d-flex">
                    <a href="{{ route("admin.todos.cursos") }}" >
                      <span class="material-icons-outlined">inventory_2</span> Cursos
                    </a>
                  </li>
                  <li class="sidebar-list-item d-flex active">
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
                      <span class="material-icons-outlined">chat</span> Suporte
                    </a>
                  </li>
                </ul>
            </aside>
        </div>
        <div class="col-table">
          <form action="{{ route("admin.user.index") }}" method="GET" class="mb-3 mt-4">
            <div class="input-group">
                <input type="text" class="form-control" name="search_term" placeholder="Digite sua pesquisa..." @if($searchTerm) value="{{$searchTerm}}" @endif>
                <select class="form-select" name="search_type">
                    <option value="user">Usuário</option>
                    <option value="role">Função</option>
                </select>
                <button type="submit" class="btn"><i class="fa fa-search"></i></button>
            </div>
          </form>
          @if ($searchTerm)
            <div class="d-flex align-items-center ml-5">
              <h4>Registros encontrados a partir do termo: "{{ $searchTerm }}"</h4>
              <a href="{{ route("admin.user.index") }}"><i class="fa-regular fa-circle-xmark fa-xl"></i></a>
            </div>
          @endif
          <table class="table table-striped table-hover shadow">
              <thead class="table-dark">
                  <tr>
                      <th scope="col">#</th>
                      <th scope="col">Usuário</th>
                      <th scope="col">Função</th>
                      <th scope="col">Cadastrado em:</th>
                      <th scope="col">Ver perfil</th>
                  </tr>
              </thead>
              <tbody>
                  @forelse($users as $user)
                      <tr>
                          <th scope="row">{{ $user->id }}</th>
                          <td>{{ $user->name }}</td>
                          <td>{{ $user->role }}</td>
                          <td>{{ $user->created_at->format('d-m-Y H:i') }}</td>
                          <td class="centralizado"><a href="{{ route('user.show', $user->email) }}"><img src="{{ asset('images/entrar.png') }}" alt="Ver mais"></a></td>
                      </tr>
                  @empty
                    <td colspan="5">
                      <h4 class="pt-2">Nenhum registro encotrado.</h4>
                    </td>
                  @endforelse
              </tbody>
          </table>
          <div class="mt-4 d-flex justify-content-center">
            {{ $users->links() }}
          </div>
        </div>
    </div>
    
@endsection