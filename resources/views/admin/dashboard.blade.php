@extends('layouts.app')

@push('styles')
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
@endpush

@push('scripts')
  <script>
    @if ($period)
        var period1Value = '{{ $period }}';
        $('#period-1').val(period1Value);
    @elseif ($period2)
        var period2Value = '{{ $period2 }}';
        $('#period-2').val(period2Value);
    @endif
  </script>

  <script src="{{ asset('js/admin.js') }}"></script>
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  <script>
    $(document).ready(function () {
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawCharts);
      function drawCharts() {
        @if($period)
          // GRÁFICO DE LINHAS (INSCRIÇÕES)
          var dataLine = google.visualization.arrayToDataTable([
              ['Data', 'Quantidade de logins'],
              @foreach ($countDate     as $date => $count)
                  ['{{ $date }}', {{ $count }}],
              @endforeach
          ]);

          var optionsLine = {
              title: 'Quantidade de logins {{ $titleLine }}',
              backgroundColor: '#eeeef6',
              pointSize: 3,
              pointShape: 'circle',
              titleTextStyle: {
                fontSize: 15,
              },
              hAxis: {
                  textStyle: {
                      fontSize: 13
                  }
              }
          };

          var chart = new google.visualization.LineChart(document.getElementById('lineChart'));

          chart.draw(dataLine, optionsLine);
        @endif

        @if($period2)
          // GRÁFICO DE LINHAS (INSCRIÇÕES)
          var dataLine2 = google.visualization.arrayToDataTable([
              ['Data', 'Quantidade de usuários cadastrados'],
              @foreach ($countDate2     as $date => $count)
                  ['{{ $date }}', {{ $count }}],
              @endforeach
          ]);

          var optionsLine2 = {
              title: 'Quantidade de cadastros {{ $titleLine2 }}',
              backgroundColor: '#eeeef6',
              pointSize: 3,
              pointShape: 'circle',
              titleTextStyle: {
                fontSize: 15,
              },
              hAxis: {
                  textStyle: {
                      fontSize: 13
                  }
              }
          };

          var chart2 = new google.visualization.LineChart(document.getElementById('lineChart2'));

          chart2.draw(dataLine2, optionsLine2);
        @endif
      }
    });
  </script>
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
        <div class="header-right d-flex">
          <span class="material-icons-outlined">notifications</span>
          <span id="config" class="material-icons-outlined">account_circle</span>
          <div class="notification-div">
            <p class="text-center">Menu</p>
            <hr>
            <div class="themes d-flex">
                <div class="theme w-50 d-flex justify-content-center align-items-center">
                    <i class="fa fa-sun-o fa-xl" aria-hidden="true"></i>
                </div>
                <span class="line"></span>
                <div class="theme w-50 d-flex justify-content-center align-items-center">
                    <i class="fa-solid fa-moon fa-xl" aria-hidden="true"></i>
                </div>                        
            </div>
            <a href="{{ route('logout') }}"><i class="fa-solid fa-arrow-right-from-bracket fa-lg"></i>{{ trans('logout') }}</a>
        </div>
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
              <p class="txt-principal">Usuários</p>
              <span class="material-icons-outlined text-white">person</span>
            </div>
            <span class="txt-principal font-weight-bold">{{ $qtdUsers }}</span>
            <a href="{{ route("admin.user.index") }}" class="stretched-link"></a>
          </div>
        </div>
        <div class="row chartsDiv">
          @if ($period)
                        
            @if ($period == "hours_24" || $period == "months_11" || $period == "days_14")
                <div id="lineChart" class="mt-2 mb-4" style="width: 100%; height: 400px;"></div>
                <a class="full" href="{{ route("admin.dashboard.form") }}"><i class="fa-regular fa-circle-xmark fa-xl"></i></a>
            @else
                <div id="lineChart" class="mt-2 mb-4" style="width: 50%; height: 400px;"></div>
                <a class="half" href="{{ route("admin.dashboard.form") }}"><i class="fa-regular fa-circle-xmark fa-xl"></i></a>
            @endif
              
          @else

            <div class="mt-2 d-flex flex-column justify-content-center align-items-center card-filter mb-4">
                <h4>Ver quantidade de logins nos últimos:</h4>
                <form method="POST" action="{{ route('admin.dashboard.form') }}" id="form1">
                @csrf
                    <input type="hidden" name="period" id="period">
                    <input type="hidden" name="period2" id="period-2">
                    <div class="d-flex justify-content-center btns-group gap-4 mb-3">
                        <button id="hours24" class="btn btn-outline-primary filter-btns">Últimas 24 horas</button>
                        <button id="days14" class="btn btn-outline-primary filter-btns">Últimos 14 Dias</button>
                    </div>
                    <div class="d-flex justify-content-center btns-group gap-4">
                        <button id="months3" class="btn btn-outline-primary filter-btns">Últimas 4 Meses</button>
                        <button id="months12" class="btn btn-outline-primary filter-btns">Últimos 12 Meses</button>
                    </div>         
                </form>
            </div>
          @endif

          @if ($period2)
                        
            @if ($period2 == "months_11" || $period2 == "days_14")
                <div id="lineChart2" class="mt-2 mb-4" style="width: 100%; height: 400px;"></div>
                <a class="full2" href="{{ route("admin.dashboard.form") }}"><i class="fa-regular fa-circle-xmark fa-xl"></i></a>
            @else
                <div id="lineChart2" class="mt-2 mb-4" style="width: 50%; height: 400px;"></div>
                <a class="@if($period && $period == 'months_3' || $period == null) half2 @else half2full @endif" href="{{ route("admin.dashboard.form") }}"><i class="fa-regular fa-circle-xmark fa-xl"></i></a>
            @endif
              
          @else

            <div class="mt-2 d-flex flex-column justify-content-center align-items-center card-filter">
                <h4>Ver cadastros no site nos últimos:</h4>
                <form method="POST" action="{{ route('admin.dashboard.form') }}" id="form2">
                @csrf
                    <input type="hidden" name="period2" id="period2">
                    <input type="hidden" name="period" id="period-1">
                    <div class="d-flex justify-content-center btns-group gap-4 mb-3">
                        <button id="days_5" class="btn btn-outline-primary filter-btns2">Últimos 5 Dias</button>
                        <button id="days_14" class="btn btn-outline-primary filter-btns2">Últimos 14 Dias</button>
                    </div>
                    <div class="d-flex justify-content-center btns-group gap-4">
                        <button id="months_3" class="btn btn-outline-primary filter-btns2">Últimos 4 Meses</button>
                        <button id="months_12" class="btn btn-outline-primary filter-btns2">Últimos 12 Meses</button>
                    </div>         
                </form>
            </div>
          @endif
        </div>
      </main>
      <!-- End Main -->

    </div>
@endsection