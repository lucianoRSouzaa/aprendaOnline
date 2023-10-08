@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/category.css') }}"> 
    <link rel="stylesheet" href="{{ asset('css/data-course.css') }}"> 
    <style>
        .back-btn {
            position: absolute;
            top: 15px;
            text-decoration: none;
            color: #000;
            font-size: 1.5rem;
            cursor: pointer;
            z-index: 1000;
        }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('js/data-course.js') }}"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script>
        $(document).ready(function () {
            google.charts.load('current', {'packages':['corechart']});
            google.charts.setOnLoadCallback(drawCharts);

            function drawCharts() {
                // GRÁFICO DE COLUNA (CURSOS COMPLETADOS)
                var barDataCompletion = new google.visualization.DataTable();
                barDataCompletion.addColumn('string', 'Mês');
                barDataCompletion.addColumn('number', 'Concluido por');
                
                // Preenchendo os dados do gráfico
                barDataCompletion.addRows([
                    @foreach ($lastFiveMonthsCouseCompletetion as $date => $count)
                        ['{{ $date }}', {{ $count }}],
                    @endforeach
                ]);

                // Cria um formatter.
                var formatterCompletion = new google.visualization.NumberFormat(
                    {suffix: ' pessoas', pattern: '#'}
                );

                // Aplica o formatter à segunda coluna.
                formatterCompletion.format(barDataCompletion, 1);

                var barOptionsCompletion = {
                    title: 'Quantidade de conclusões do curso nos últimos 5 meses',
                    backgroundColor: '#eeeef6',
                    hAxis: {
                        title: 'Meses',
                        textStyle: {fontSize: 13}
                    },
                    vAxis: {
                        minValue: 0,
                        title: 'Quantidade de conclusão',
                        textStyle: {fontSize: 14}
                    },
                    titleTextStyle: {
                      fontSize: 15,
                    },
                    colors: ['#a7c957'],
                };

                var barChartCompletion = new google.visualization.ColumnChart(document.getElementById('barChartCompletion'));
                barChartCompletion.draw(barDataCompletion, barOptionsCompletion);


                // GRÁFICO DE COLUNA (AULAS COMPLETAS E INCOMPLETAS POR MÓDULO)
                var dataModuleColumn = google.visualization.arrayToDataTable([
                    ['Módulo', 'Aulas Concluídas (%)', 'Aulas Não Concluídas (%)'],
                    @foreach ($chartModuleData as $moduleData)
                        ['{{ $moduleData['moduleName'] }}', {{ $moduleData['completedPercentage'] }}, {{ $moduleData['incompletePercentage'] }}],
                    @endforeach
                ]);

                // Cria um formatter.
                var formatter = new google.visualization.NumberFormat(
                    {suffix: '%', pattern: '#'}
                );

                // Aplica o formatter à segunda e terceira colunas.
                formatter.format(dataModuleColumn, 1);
                formatter.format(dataModuleColumn, 2);

                var optionsModuleColumn = {
                    title: 'Taxa de conclusão de Aulas por Módulo (%)',
                    isStacked: true,
                    backgroundColor: '#eeeef6',
                    titleTextStyle: {
                      fontSize: 15,
                    },
                    hAxis: {
                        title: 'Módulos'
                    },
                    vAxis: {
                        title: 'Porcentagem de Conclusão (%)'
                    },
                    legend: { position: 'bottom' },
                    colors: ['#80c1ff', '#ff80ae'],
                };

                var chartModuleColumn = new google.visualization.ColumnChart(document.getElementById('chart_module_column'));
                chartModuleColumn.draw(dataModuleColumn, optionsModuleColumn);


                // GRÁFICO DE LINHA (CONCLUSÃO DE AULAS)
                var dataLineLessons = google.visualization.arrayToDataTable([
                    ['Mês', 'Aulas concluídas por usuários'],
                    @foreach ($lastFiveMonthsLessons as $date => $count)
                        ['{{ $date }}', {{ $count }}],
                    @endforeach
                ]);

                // Opções do gráfico de linha
                var optionsLineLessons = {
                    title: 'Conclusão de Aulas por Mês',
                    backgroundColor: '#eeeef6',
                    pointSize: 2,
                    pointShape: 'circle',
                    titleTextStyle: {
                      fontSize: 15,
                    },
                    legend: { position: 'bottom' }
                };

                // Crie um gráfico de linha
                var chartLineLessons = new google.visualization.LineChart(document.getElementById('line_lessons_chart'));

                // Desenhe o gráfico com os dados e opções
                chartLineLessons.draw(dataLineLessons, optionsLineLessons);


                // GRÁFICO DE BARRAS (FAVORITOS)
                var barData = new google.visualization.DataTable();
                barData.addColumn('string', 'Mês');
                barData.addColumn('number', 'Favoritos');
                
                // Preenchendo os dados do gráfico
                barData.addRows([
                    @foreach ($lastFiveMonths as $date => $count)
                        ['{{ $date }}', {{ $count }}],
                    @endforeach
                ]);

                var barOptions = {
                    title: 'Quantidade de favoritação do curso nos últimos 5 meses',
                    backgroundColor: '#eeeef6',
                    hAxis: {
                        title: 'Meses',
                        textStyle: {fontSize: 13}
                    },
                    vAxis: {
                        minValue: 0,
                        title: 'Quantidade de favoritação',
                        textStyle: {fontSize: 14}
                    },
                    titleTextStyle: {
                      fontSize: 15,
                    },
                    colors: ['#2a9d8f'],
                };

                var barChart = new google.visualization.ColumnChart(document.getElementById('barChart'));
                barChart.draw(barData, barOptions);

                // GRÁFICO DE LINHA (AVALIAÇÕES)
                var dataLineReviews = google.visualization.arrayToDataTable([
                    ['data', 'Avaliação do usuário', 'Média do curso'],
                    @foreach ($lastFiveReviews as $review)
                        ['{{ $review['date'] }}', {{ $review['avaliacao'] }}, {{ $review['media'] }}],
                    @endforeach
                ]);

                var optionsReviews = {
                    title: 'Avaliações do Curso',
                    curveType: 'function',
                    backgroundColor: '#eeeef6',
                    pointSize: 2,
                    pointShape: 'circle',
                    titleTextStyle: {
                      fontSize: 15,
                    },
                    legend: { position: 'bottom' }
                };

                var lineChartReviews = new google.visualization.LineChart(document.getElementById('curve_chart'));

                lineChartReviews.draw(dataLineReviews, optionsReviews);

                @if($period)
                // GRÁFICO DE LINHAS (INSCRIÇÕES)
                var dataLine = google.visualization.arrayToDataTable([
                    ['Data', 'Quantidade de inscrições'],
                    @foreach ($countDate     as $date => $count)
                        ['{{ $date }}', {{ $count }}],
                    @endforeach
                ]);
      
                var optionsLine = {
                    title: 'Quantidade de inscrições nos últimos {{ $titleLine }}',
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
            }
        });
    </script>
@endpush

@section('main')
    <div class="row">
        <a href="{{ route('course.data.index', $course->slug) }}" class="back-btn"><i class="fa fa-arrow-left" aria-hidden="true"></i> {{ trans('back') }}</a>
        <div class="col-12">
            <div class="row justify-content-center">
                <h2 class="text-center mt-3 mb-3">{{ trans('courseData') }} {{ $course->title }}</h2>
                <div class="charts row justify-content-center">
                    @if ($period)
                        
                        @if ($period == "last_21_days" || $period == "last_12_months")
                            <div id="lineChart" class="mt-2 mb-4" style="width: 100%; height: 400px;"></div>
                            <a class="full" href="{{ route("course.data.performance", $course->slug) }}"><i class="fa-regular fa-circle-xmark fa-xl"></i></a>
                        @else
                            <div id="lineChart" class="mt-2 mb-4" style="width: 50%; height: 400px;"></div>
                            <a class="half" href="{{ route("course.data.performance", $course->slug) }}"><i class="fa-regular fa-circle-xmark fa-xl"></i></a>
                        @endif
                        
                    @else

                        <div class="mt-2 d-flex flex-column justify-content-center align-items-center card-filter">
                            <h4>Ver números de inscrições nos últimos:</h4>
                            <form method="POST" action="{{ route('course.data.performance.form', $course->slug) }}">
                            @csrf
                                <input type="hidden" name="period" id="period">
                                <div class="d-flex justify-content-center btns-group gap-4 mb-3">
                                    <button id="days7" class="btn btn-outline-primary filter-btns">Últimos 7 Dias</button>
                                    <button id="days21" class="btn btn-outline-primary filter-btns">Últimos 21 Dias</button>
                                </div>
                                <div class="d-flex justify-content-center btns-group gap-4">
                                    <button id="months3" class="btn btn-outline-primary filter-btns">Últimos 3 Meses</button>
                                    <button id="months12" class="btn btn-outline-primary filter-btns">Últimos 12 Meses</button>
                                </div>         
                            </form>
                        </div>
                    @endif

                    <div id="barChart" class="mt-2 mb-4" style="width: 50%; height: 400px;"></div>
                    <div id="curve_chart" class="mt-2 mb-4" style="width: 50%; height: 400px;"></div>
                    <div id="line_lessons_chart" class="mt-2 mb-4" style="width: 50%; height: 400px;"></div>
                    <div id="chart_module_column" class="mt-2 mb-4" style="width: 50%; height: 400px;"></div>
                    <div id="barChartCompletion" class="mt-2 mb-4" style="width: 50%; height: 400px;"></div>
                </div>
            </div>
        </div>
    </div>
@endsection