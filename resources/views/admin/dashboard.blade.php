@extends('layouts.app')

@push('styles')
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
@endpush

@push('scripts')
  <script src="{{ asset('js/admin.js') }}"></script>
@endpush

@section('body')
    <div class="grid-container">

      <!-- Header -->
      <header class="header">
        <div class="menu-icon" onclick="openSidebar()">
          <span class="material-icons-outlined">menu</span>
        </div>
        <div class="header-left">
          <span class="material-icons-outlined">search</span>
        </div>
        <div class="header-right">
          <span class="material-icons-outlined">notifications</span>
          <span class="material-icons-outlined">email</span>
          <span class="material-icons-outlined">account_circle</span>
        </div>
      </header>
      <!-- End Header -->

      <!-- Sidebar -->
      <aside id="sidebar">
        <div class="sidebar-title">
          <div class="sidebar-brand">
            <span class="material-icons-outlined">school</span> AprendaOnline
          </div>
          <span class="material-icons-outlined" onclick="closeSidebar()">close</span>
        </div>

        <ul class="sidebar-list">
          <li class="sidebar-list-item d-flex active">
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
      <!-- End Sidebar -->

      <!-- Main -->
      <main class="main-container">
        <div class="main-title">
          <p class="font-weight-bold">DASHBOARD</p>
        </div>

        <div class="main-cards">

          <div class="card">
            <div class="card-inner">
              <p class="txt-principal">DENUNCIAS</p>
              <span class="material-icons-outlined text-white">notification_important</span>
            </div>
            <span class="txt-principal font-weight-bold">{{$qtdDenuncia}}</span>
            <a href="{{ route("admin.reports")}}" class="stretched-link"></a>
          </div>

          <div class="card">
            <div class="card-inner">
              <p class="txt-principal">EXCLUSÕES</p>
              <span class="material-icons-outlined text-white">delete</span>
            </div>
            <span class="txt-principal font-weight-bold">{{ $qtdTotalExcluido }}</span>
            <a href="{{ route("admin.deletes") }}" class="stretched-link"></a>
          </div>

          <div class="card">
            <div class="card-inner">
              <p class="txt-principal">CURSOS</p>
              <span class="material-icons-outlined text-white">inventory_2</span>
            </div>
            <span class="txt-principal font-weight-bold">{{ $qtdCursos }}</span>
            <a href="{{ route("admin.todos.cursos") }}" class="stretched-link"></a>
          </div>

          <div class="card">
            <div class="card-inner">
              <p class="txt-principal">DADOS</p>
              <span class="material-icons-outlined text-white">shopping_cart</span>
            </div>
            <span class="txt-principal font-weight-bold">79</span>
            <a href="https://www.google.com.br/?gws_rd=ssl" class="stretched-link"></a>
          </div>

        </div>
      </main>
      <!-- End Main -->

    </div>
@endsection