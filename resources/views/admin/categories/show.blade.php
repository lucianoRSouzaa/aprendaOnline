@extends('layouts.app')

@push('styles')
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/reports.css') }}">
    <link rel="stylesheet" href="{{ asset('css/category.css') }}"> 
@endpush
    
@push('scripts')
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script>
        $(document).ready(function () {
            google.charts.load('current', {'packages':['corechart']});
            google.charts.setOnLoadCallback(drawCharts);

            function drawCharts() {

                var dataLine = new google.visualization.DataTable();

                dataLine.addColumn('string', 'Mês');
                dataLine.addColumn('number', '{{ $category->name }}');
                dataLine.addColumn({type: 'string', role: 'tooltip'});

                dataLine.addRows([
                    @foreach ($data as $dataLine)   
                        ['{{ $dataLine[0] }}', {{ $dataLine[1] }}, 'Total: {{ $dataLine[1] }} cursos\nCriados no mês: {{ $dataLine[2] }}'],
                    @endforeach
                ]);

                var optionsLine = {
                title: 'Quantidade de cursos criados por mês nos últimos 6 meses',
                backgroundColor: '#e7e7f3',
                pointSize: 3,
                pointShape: 'circle',
                vAxis: {
                    title: 'Quantidade de cursos criados'
                },
                legend: { position: 'bottom' }
                };

                var chart = new google.visualization.LineChart(document.getElementById('lineChart'));

                chart.draw(dataLine, optionsLine);
            }
        });
    </script>
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
                <a href="{{ route('chat.index') }}" >
                    <span class="material-icons-outlined">chat</span> Suporte <span class="ball-msg-unread">{{ $qtdMsg }}</span>
                </a>
                </li>
            </ul>
            </aside>
    </div>
    <div class="col-table">
        <div class="row justify-content-center">
            <h2 class="text-center mt-3 mb-3">Dados da categoria: {{ $category->name }}</h2>
            <div class="row justify-content-center">
                <div class="col-8">
                    <div id="lineChart" class="chartShow"></div>
                </div>
                <div class="col-4">
                    <div class="card card-category-show">
                        <div class="card-header d-flex w-100 justify-content-center">
                            <h4>Categoria: {{ $category->name }}</h4>
                        </div>
                        <div class="card-body">
                            <ul>
                                <li>Quantidade de cursos relacionados: <span class="estiled-numbers">{{ $qtdCourse }}<span class="estiled-text">cursos<span></span></li>
                                <li>Classificação da categoria: <span class="estiled-numbers">{{ number_format($category->average_rating, 1) }} <i class="fa-solid fa-star fa-sm"></i></span></li>
                                <li>Quantidade de usuários na categoria: <span class="estiled-numbers">{{ $users }}<span class="estiled-text">usuário<span></span></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="div-table mb-5">
            <form action="{{ route("admin.categories.data.show", $category->id) }}" method="GET" class="mb-3 mt-4">
                <div class="input-group">
                    <input type="text" class="form-control" name="search_term" placeholder="Digite sua pesquisa..." @if($searchTerm) value="{{$searchTerm}}" @endif>
                    @if($searchTerm)
                    <a href="{{ route("admin.categories.data.show", $category->id) }}" class="btn unmark"><i class="fa-solid fa-xmark"></i></a>
                    @else
                    <button type="submit" class="btn"><i class="fa fa-search"></i></button>
                    @endif
                </div>
            </form>
            <table class="table table-striped table-hover shadow">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">Curso</th>
                        <th scope="col">Data de criação</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($courses as $course)
                        <tr>
                            <th scope="row">{{ $course->title }}</th>
                            <td>{{ $course->created_at->format('d-m-Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2">Nenhum registro encontrado</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-4 d-flex justify-content-center">
                {{ $courses->links() }}
            </div>
        </div>
    </div>
</div>
    
@endsection