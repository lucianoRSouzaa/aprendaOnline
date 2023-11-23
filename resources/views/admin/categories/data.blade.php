@extends('layouts.app')

@push('styles')
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/deletes.css') }}">
    <link rel="stylesheet" href="{{ asset('css/category.css') }}"> 
@endpush

@push('scripts')
    <script src="{{ asset('js/category.js') }}"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script>
        $(document).ready(function () {
            google.charts.load('current', {'packages':['corechart']});
            google.charts.setOnLoadCallback(drawCharts);

            function drawCharts() {
                // GRÁFICO DE PIZZA
                // Criando um array para armazenar os dados do gráfico
                var dataPie = new google.visualization.DataTable();
                dataPie.addColumn('string', 'Categoria');
                dataPie.addColumn('number', 'Quantidade');

                // passando os dados das categorias
                var categoriesData = [
                @foreach ($categoriesChart as $category)
                    ['{{ $category->name }}', {{ $category->courses_count }}],
                @endforeach
                ];

                // Preenchendo os dados do gráfico
                dataPie.addRows(categoriesData);

                var optionsPie = {
                    title: 'Categorias com mais cursos',
                    is3D: true,
                    backgroundColor: '#e7e7f3',
                    colors:['#66b5ff','#ff80ae', '#c3c3ef', '#a375bd', '#fddc9b'],
                    fontSize: 13,
                    titleTextStyle: {
                      fontSize: 18
                    }
                };

                // Crie um objeto de gráfico de pizza
                var chart = new google.visualization.PieChart(document.getElementById('pieChart'));
                chart.draw(dataPie, optionsPie);

                // GRÁFICO DE BARRAS
                // Crie um array para armazenar os dados do gráfico de barras
                var barData = new google.visualization.DataTable();
                barData.addColumn('string', 'Categoria');
                barData.addColumn('number', 'Média de Classificação');
                barData.addColumn({ role: 'style' });

                // Preenchendo os dados do gráfico
                barData.addRows([
                    @foreach($categoriesBarChart as $categoryBar)
                        ['{{ $categoryBar->name }}', {{ $categoryBar->average_rating }}, 'stroke-color: #703593; stroke-width: 3; fill-color: #C5A5CF'],
                    @endforeach
                ]);

                var barOptions = {
                    title: 'Melhores Categorias por Média de Classificação',
                    backgroundColor: '#e7e7f3',
                    hAxis: {
                        title: 'Categoria',
                        titleTextStyle: {color: '#333'}
                    },
                    vAxis: {
                        minValue: 0,
                        title: 'Média de Classificação',
                    },
                    titleTextStyle: {
                      fontSize: 14
                    }
                };

                // Crie um objeto de gráfico de barras
                var barChart = new google.visualization.ColumnChart(document.getElementById('barChart'));
                barChart.draw(barData, barOptions);

                // GRÁFICO DE LINHAS
              @if ($data)
                var dataLine = new google.visualization.DataTable();
                dataLine.addColumn('string', 'Mês');
                dataLine.addColumn('number', '{{ $category1Name }}');
                dataLine.addColumn({type: 'string', role: 'tooltip'});
                @if($category2Name)
                dataLine.addColumn('number', '{{ $category2Name }}');
                dataLine.addColumn({type: 'string', role: 'tooltip'});
                @endif
                @if($category3Name)
                dataLine.addColumn('number', '{{ $category3Name }}');
                dataLine.addColumn({type: 'string', role: 'tooltip'});
                @endif

                dataLine.addRows([
                  @foreach($data as $dataLine)
                        @if($category3Name)
                        ['{{ $dataLine[0] }}', {{ $dataLine[1] }}, '{{ $category1Name }}\nTotal: {{ $dataLine[1] }}\nCriados no mês: {{ $dataLine[2] }}',
                                            {{ $dataLine[3] }}, '{{ $category2Name }}\nTotal: {{ $dataLine[3] }}\nCriados no mês: {{ $dataLine[4] }}',
                                            {{ $dataLine[5] }}, '{{ $category3Name }}\nTotal: {{ $dataLine[5] }}\nCriados no mês: {{ $dataLine[6] }}'],
                        @elseif($category2Name)
                        ['{{ $dataLine[0] }}', {{ $dataLine[1] }}, '{{ $category1Name }}\nTotal: {{ $dataLine[1] }}\nCriados no mês: {{ $dataLine[2] }}',
                                            {{ $dataLine[3] }}, '{{ $category2Name }}\nTotal: {{ $dataLine[3] }}\nCriados no mês: {{ $dataLine[4] }}'],

                        @else ['{{ $dataLine[0] }}', {{ $dataLine[1] }}, '{{ $category1Name }}\nTotal: {{ $dataLine[1] }}\nCriados no mês: {{ $dataLine[2] }}'],
                        @endif
                    @endforeach
                ]);

                var optionsLine = {
                  title: 'Quantidade total de inscrições de usuários por mês',
                  backgroundColor: '#e7e7f3',
                  pointSize: 3,
                  pointShape: 'circle',
                  vAxis: {
                    title: 'Quantidade de inscrições'
                  },
                  legend: { position: 'bottom' }
                };

                var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

                chart.draw(dataLine, optionsLine);
              @endif
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
                <h2 class="text-center mt-3 mb-3">Dados de categorias</h2>
                <div class="d-flex gap-3">
                    <div id="pieChart" class="chart"></div>
                    <div id="barChart" class="chart"></div>
                </div>

                @empty($data)
                <div class="mt-4">
                  <h5>Selecione categorias e um intervalo de meses para visualizar as inscrições em cursos por mês:</h5>
                    <form action="{{ route('admin.categories.data') }}" method="POST">
                    @csrf
                      <div>
                        <label class="form-label mt-1" for="select-category1">Selecione a primeira categoria:</label>
                        <select class="form-select" name="select-category1" required>
                          <option value="" disabled selected>-- Selecione --</option>
                          @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                          @endforeach
                        </select>
                        @error('select-category1')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                      </div>
                      <div id="select-category2" class="disabled">
                        <label class="form-label mt-2" for="select-category2">Selecione a segunda categoria:</label>
                        <select class="form-select" name="select-category2">
                          <option value="" disabled selected>-- Selecione --</option>
                          @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                          @endforeach
                        </select>
                        @error('select-category2')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                      </div>
                      <div id="select-category3" class="disabled">
                        <label class="form-label mt-2" for="select-category3">Selecione a terceira categoria:</label>
                        <select class="form-select" name="select-category3">
                          <option value="" disabled selected>-- Selecione --</option>
                          @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                          @endforeach
                        </select>
                        @error('select-category3')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                      </div>
                      <div onclick="adicionarCampo()" class="add-response d-flex align-items-center mt-2">
                        <img src="{{ asset('images/plus.png') }}" class="img-add">
                        <p class="txt">Adicionar outra categoria (opcional)</p>
                      </div>  
                      <div>
                        <label class="form-label mt-3" for="month">Selecione os meses para a busca:</label>
                        <div class="d-flex">
                          <span>Do mês: </span>
                          <select class="select-data" required name="since">
                            <option value="January">Janeiro</option>
                            <option value="February">Fevereiro</option>
                            <option value="March">Março</option>
                            <option value="April">Abril</option>
                            <option value="May">Maio</option>
                            <option value="June">Junho</option>
                            <option value="July">Julho</option>
                            <option value="August">Agosto</option>
                            <option value="September">Setembro</option>
                            <option value="October">Outubro</option>
                            <option value="November">Novembro</option>
                            <option value="December">Dezembro</option>
                          </select>
                          @error('since')
                            <div class="text-danger">{{ $message }}</div>
                          @enderror
                          <span>Até: </span>
                          <select class="select-data" required name="to">
                            <option value="January">Janeiro</option>
                            <option value="February">Fevereiro</option>
                            <option value="March">Março</option>
                            <option value="April">Abril</option>
                            <option value="May">Maio</option>
                            <option value="June">Junho</option>
                            <option value="July">Julho</option>
                            <option value="August">Agosto</option>
                            <option value="September">Setembro</option>
                            <option value="October">Outubro</option>
                            <option value="November">Novembro</option>
                            <option selected value="December">Dezembro</option>
                          </select>
                          @error('to')
                            <div class="text-danger">{{ $message }}</div>
                          @enderror
                          <span>Do ano de: </span>
                          <select class="select-data" required name="year">
                            <option value="2022">2022</option>
                            <option selected value="2023">2023</option>
                            <option value="2024">2024</option>
                          </select>
                        </div>
                      </div>
                      <div class="d-flex justify-content-center mt-1 gap-3">
                        <button type="submit" class="btn btn-outline-primary mt-2">Pesquisar</button>
                        <button type="reset" class="btn btn-outline-danger mt-2">Cancelar</button>
                      </div>
                    </form>
                </div>    
                @endempty

                @if ($data)
                  <div class="d-flex justify-content-between align-items-center mt-3">
                    <h4 class="title-search">Resultado da pesquisa:</h4>
                    <a href="{{ route("admin.categories.data") }}"><i class="fa-regular fa-circle-xmark fa-xl"></i></a>
                  </div>
                  <div id="curve_chart" style="width: 100%; height: 350px;"></div>
                @endif

                <div class="mt-4 mb-5">
                  <h5>Acesse cada categoria individualmente e obtenha mais dados: </h5>
                  <a class="btn btn-primary" href="{{ route("admin.categories.create") }}">Ver Todas as Categorias <i class="fa-solid fa-angles-right"></i></a>
                </div>
            </div>
        </div>
    </div>
    
@endsection

