@extends('layouts.app')

@push('styles')
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/deletes.css') }}">
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
          <div class="row justify-content-center">
            <h2 class="text-center mt-3 mb-3">O que você gostaria de fazer?</h2>
            <div class="col-md-6 col-sm-11 mb-4">
              <div class="card">
                <h5 class="card-title card-header"><strong>Ver e criar categorias</strong></h5>
                <div class="card-body">
                  <p class="card-text">Esta página permite que você visualize todas as categorias existentes. Além disso, você tem a opção de criar novas categorias conforme necessário. Esta é uma ferramenta essencial para manter o conteúdo organizado e fácil de navegar.</p>
                  <a href="{{ route('admin.categories.create') }}" class="btn btn-primary stretched-link mt">Visualizar</a>
                </div>
              </div>
            </div>
            <div class="col-md-6 col-sm-11 mb-4">
              <div class="card">
                <h5 class="card-title card-header"><strong>Ver dados referentes as categorias</strong></h5>
                <div class="card-body">
                  <p class="card-text">Este cartão oferece uma visão detalhada dos dados associados a cada categoria. Você pode ver estatísticas, como o número de postagens em cada categoria, a popularidade das categorias e outras informações relevantes. Esta é uma ferramenta valiosa para entender quais categorias estão recebendo mais atenção e quais podem precisar de mais conteúdo.</p>
                  <a href="{{ route('admin.categories.data') }}" class="btn btn-primary stretched-link">Visualizar</a>
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>
    
@endsection