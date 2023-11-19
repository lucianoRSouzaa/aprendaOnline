@extends('layouts.app')

@push('styles')
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/deletes.css') }}">
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
        <div class="col-table">
          <div class="row justify-content-center">
            <h2 class="text-center mt-3 mb-3">O que você gostaria de ver?</h2>
            <div class="col-md-6 col-sm-11 mb-4">
              <div class="card">
                <h5 class="card-title card-header"><strong>Cursos excluídos</strong></h5>
                <div class="card-body">
                  <p class="card-text">Aqui, você pode ver registros de cursos excluídos. Você poderá ver informações detalhadas sobre os cursos e seus módulos e aulas que foram removidos do sistema.</p>
                  <p class="qtd-registros"><strong>Quantidade de registros: </strong>{{ $qtdCursosExcluido }}</p>
                  <a href="{{ route('admin.registros.excluidos', 'cursos') }}" class="btn btn-primary stretched-link">Visualizar</a>
                </div>
              </div>
            </div>
            <div class="col-md-6 col-sm-11 mb-4">
              <div class="card">
                <h5 class="card-title card-header"><strong>Módulos excluídos</strong></h5>
                <div class="card-body">
                  <p class="card-text">Aqui, você pode ver registros de módulos excluídos. Você poderá ver informações detalhadas sobre os módulos e suas aulas que foram removidos do sistema.</p>
                  <p class="qtd-registros"><strong>Quantidade de registros: </strong>{{ $qtdModulosExcluido }}</p>
                  <a href="{{ route('admin.registros.excluidos', 'modulos') }}" class="btn btn-primary stretched-link">Visualizar</a>
                </div>
              </div>
            </div>
            <div class="col-md-6 col-sm-11 mb-4">
              <div class="card">
                <h5 class="card-title card-header"><strong>Aulas excluídas</strong></h5>
                <div class="card-body">
                  <p class="card-text">Aqui, você pode ver registros de aulas excluídas. Você poderá ver informações detalhadas sobre as aulas que foram removidas do sistema.</p>
                  <p class="qtd-registros"><strong>Quantidade de registros: </strong>{{ $qtdAulasExcluido }}</p>
                  <a href="{{ route('admin.registros.excluidos', 'aulas') }}" class="btn btn-primary stretched-link">Visualizar</a>
                </div>
              </div>
            </div>
            <div class="col-md-6 col-sm-11 mb-4">
              <div class="card">
                <h5 class="card-title card-header"><strong>Todos os registros</strong></h5>
                <div class="card-body">
                  <p class="card-text">Nesta página, você tem acesso a todos os registros de exclusões da plataforma. Recorra a ela sempre que precisar realizar pesquisas avançadas e personalizadas.</p>
                  <p class="qtd-registros"><strong>Quantidade de registros: </strong>{{ $qtdTotalExcluido }}</p>
                  <a href="{{ route('admin.todos.registros.excluidos') }}" class="btn btn-primary stretched-link">Visualizar</a>
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>
    
@endsection