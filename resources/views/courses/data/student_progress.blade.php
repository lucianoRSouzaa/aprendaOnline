@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/category.css') }}"> 
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
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script>
        $(document).ready(function () {
            google.charts.load('current', {'packages':['corechart']});
            google.charts.setOnLoadCallback(drawCharts);

            function drawCharts() {
                // GRÁFICO DE PIZZA
                var dataPie = google.visualization.arrayToDataTable([
                    ['Status', 'Quantidade'],
                    ['Conclusões', {{ $completions }}],
                    ['Inscrições', {{ $subscriptions }}]
                ]);

                // Preenchendo os dados do gráfico

                var optionsPie = {
                    title: 'Taxa de Conclusão do Curso',
                    is3D: true,
                    backgroundColor: '#eeeef6',
                    colors:['#66b5ff','#ff80ae'],
                    fontSize: 13,
                    titleTextStyle: {
                      fontSize: 18
                    },
                    sliceVisibilityThreshold: 0
                };

                // Crie um objeto de gráfico de pizza
                var chart = new google.visualization.PieChart(document.getElementById('pieChart'));
                chart.draw(dataPie, optionsPie);


                // GRÁFICO DE BARRAS
                // Crie um array para armazenar os dados do gráfico de barras
                var barData = new google.visualization.DataTable();
                barData.addColumn('string', 'Aula');
                barData.addColumn('number', 'Quantidade de alunos');

                // Preenchendo os dados do gráfico
                barData.addRows([
                    @foreach($datas as $data)
                        ['{{ $data['lesson'] }}', {{ $data['completed'] }} ],
                    @endforeach
                ]);

                var barOptions = {
                    title: 'Quantidade de alunos que concluíram cada aula',
                    backgroundColor: '#eeeef6',
                    hAxis: {
                        title: 'Aulas',
                        textStyle: {fontSize: 13}
                    },
                    vAxis: {
                        minValue: 0,
                        title: 'Quantidade de alunos',
                        textStyle: {fontSize: 13}
                    },
                    titleTextStyle: {
                      fontSize: 15,
                    },
                    colors: ['#2a9d8f'],
                };

                // Crie um objeto de gráfico de barras
                var barChart = new google.visualization.ColumnChart(document.getElementById('barChart'));
                barChart.draw(barData, barOptions);

                // GRÁFICO DE LINHAS
                var dataLine = google.visualization.arrayToDataTable([
                    ['Hora do Dia', 'Aulas concluídas por usuários'],
                    ['00:00', {{ $filledHourCounts[0] }}],
                    ['1:00', {{ $filledHourCounts[1] }}],
                    ['2:00', {{ $filledHourCounts[2] }}],
                    ['3:00', {{ $filledHourCounts[3] }}],
                    ['4:00', {{ $filledHourCounts[4] }}],
                    ['5:00', {{ $filledHourCounts[5] }}],
                    ['6:00', {{ $filledHourCounts[6] }}],
                    ['7:00', {{ $filledHourCounts[7] }}],
                    ['8:00', {{ $filledHourCounts[8] }}],
                    ['9:00', {{ $filledHourCounts[9] }}],
                    ['10:00', {{ $filledHourCounts[10] }}],
                    ['11:00', {{ $filledHourCounts[11] }}],
                    ['12:00', {{ $filledHourCounts[12] }}],
                    ['13:00', {{ $filledHourCounts[13] }}],
                    ['14:00', {{ $filledHourCounts[14] }}],
                    ['15:00', {{ $filledHourCounts[15] }}],
                    ['16:00', {{ $filledHourCounts[16] }}],
                    ['17:00', {{ $filledHourCounts[17] }}],
                    ['18:00', {{ $filledHourCounts[18] }}],
                    ['19:00', {{ $filledHourCounts[19] }}],
                    ['20:00', {{ $filledHourCounts[20] }}],
                    ['21:00', {{ $filledHourCounts[21] }}],
                    ['22:00', {{ $filledHourCounts[22] }}],
                    ['23:00', {{ $filledHourCounts[23] }}]
                ]);
      
                var optionsLine = {
                    title: 'Conclusões de Aulas por usuários de acordo com a Hora do Dia',
                    backgroundColor: '#eeeef6',
                    pointSize: 3,
                    pointShape: 'circle',
                    hAxis: {
                        textStyle: {
                            fontSize: 13
                        }
                    }
                };

                var chart = new google.visualization.LineChart(document.getElementById('lineChart'));

                chart.draw(dataLine, optionsLine);
            }
        });
    </script>
@endpush

@section('main')
    <div class="row">
        <a href="{{ url()->previous() }}" class="back-btn"><i class="fa fa-arrow-left" aria-hidden="true"></i> {{ trans('back') }}</a>
        <div class="col-12">
            <div class="row justify-content-center">
                <h2 class="text-center mt-3 mb-3">{{ trans('courseData') }} {{ $course->title }}</h2>
                <div class="d-flex gap-3">
                    <div id="barChart" class="chart"></div>
                    <div id="pieChart" class="chart"></div>
                </div>
                <div id="lineChart" class="mt-3 mb-4" style="width: 100%; height: 400px;"></div>
            </div>
        </div>
    </div>
@endsection