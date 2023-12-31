@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/curso-show.css') }}">

    <style>
        .box .div-principal span{
            background-image: url("{{ asset('images/indiceModulo.png') }}");
        }

        #bar-5::before {
            width: {{$averageRatingsPerScore[5] ?? 0}}%;
        }

        #bar-4::before {
            width: {{$averageRatingsPerScore[4] ?? 0}}%;
        }

        #bar-3::before {
            width: {{$averageRatingsPerScore[3] ?? 0}}%;
        }

        #bar-2::before {
            width: {{$averageRatingsPerScore[2] ?? 0}}%;
        }

        #bar-1::before {
            width: {{$averageRatingsPerScore[1] ?? 0}}%;
        }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('js/jquery.raty.js') }}"></script>
    <script src="{{ asset('js/detalhes-curso.js') }}"></script>
    <script>
        $('#starAvarage').raty({ 
            path: '/images/star',
            readOnly: true,
            score: {{ number_format($course->average_rating, 2) }},
        });
    </script>
@endpush

@section('header')
    <div class="container">
        <div class="d-flex justify-content-between">
            <!-- ***** Logo Start ***** -->
            @auth
                <a href="{{ route('course.data.index', $course->slug) }}" class="logo">
                    @if (Cookie::get('theme_preference', 'light') === 'dark')
                        <img class="logo2" src="{{ asset('images/logoMenu.png') }}" alt="">
                    @else
                        <img class="logo2" src="{{ asset('images/logoMenu2.png') }}" alt="">
                    @endif
                </a>   
            @endauth
            @guest
                <a href="{{ route('courses.viewer') }}" class="logo">
                    @if (Cookie::get('theme_preference', 'light') === 'dark')
                        <img class="logo2" src="{{ asset('images/logoMenu.png') }}" alt="">
                    @else
                        <img class="logo2" src="{{ asset('images/logoMenu2.png') }}" alt="">
                    @endif
                </a>  
            @endguest
            <!-- ***** Logo End ***** -->
            <!-- ***** Menu Start ***** -->
            <div class="search-container">
                <form action="curso.php" method="POST" class="search-bar">
                    <input type="text" name="searchTerm" id="search-input" placeholder="{{ trans('searchPlaceholder') }}">
                    <button type="submit" id="search-button"><i class="fa fa-search"></i></button>
                </form>
            </div>    
        </div>
    </div>
@endsection

@section('main')
    <div class="container">
        <div class="avaliacoes_title page-data-reviews mb-4">
            <h2>{{ trans('rates') }}:</h2>
        </div>
        <div class="row box-avaliacoes mb-5">
            <div class="col-4 dados-avaliacoes">
                <div class="box-nota">
                    <h3>{{ trans('ratings') }}</h3>
                    <div class="d-flex align-items-center flex-column">
                        <div class="box-estrelas-avaliacoes d-flex align-items-center">
                            <p class="avarage">{{ number_format($course->average_rating, 1) }}</p>
                            <div id="starAvarage">

                            </div>
                        </div>
                        <p>{{ $ratingsCount }} {{ trans('ratingsInThisCourse') }}</p>
                    </div>
                </div>
                <div class="box-filtrar">
                    <h3 class="mb-2">{{ trans('filterBy') }}</h3>
                    <form action="{{ route('course.data.reviews', $course->slug) }}" method="GET" id="formStarFilter">
                        <input type="hidden" name="starFilter" id="starFilter">
                    </form>
                    @for ($score = 5; $score >= 1; $score--)
                        @php
                            $proportion = $averageRatingsPerScore[$score] ?? 0;
                        @endphp
                        <div class="d-flex align-items-center avaliacao-bar" data-score="{{ $score }}">
                            <div class="d-flex align-items-center">
                                <p class="no-marg">{{ $score }}</p>
                                <img src="{{ asset('images/star/star-on.png') }}" alt="">
                            </div>
                            <div class="container-bar">
                                <div class="bar-empty" id="bar-{{ $score }}"></div>
                                <div class="bar-filled" style="width: {{ $proportion }}%"></div>
                            </div>
                            <p class="no-marg porcent">{{ number_format($proportion, 0) }}%</p>
                        </div>
                    @endfor
                </div>
            </div>
            <div class="col-8">
                <div class="d-flex justify-content-between align-items-center coment-title">
                    <h2>{{ trans('comments') }}</h2>
                </div>
                <div class="comments">
                    @if (count($ratings) > 0 && $starFilter)
                        <div class="d-flex align-items-center">
                            <p class="ps-3 mb-0 mt-1">{{ count($ratings) }} avaliações encontradas com {{ $starFilter }} estrelas:</p>
                            <div class="d-flex justify-content-center ps-1">
                                <a class="cancel-search" href="{{ route("courses.show", $course->slug) }}"><i class="fa-regular fa-circle-xmark fa-lg"></i></a>
                            </div>
                        </div>
                    @endif
                    @forelse ($ratings as $rating)
                        <div class="comment">
                            <h4>{{ $rating->user->name }}</h4>
                            <div>
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= $rating->rating)
                                        <img alt="{{ $i }}" width="26px" src="{{ asset('images/star/star-on.png') }}">
                                    @else
                                        <img alt="{{ $i }}" width="26px" src="{{ asset('images/star/star-off.png') }}">
                                    @endif
                                @endfor
                            </div>
                            <p class="mt-2">{{ $rating->comment }}</p>
                        </div>
                    @empty
                        <div class="mt-5">
                            <h4 class="text-center">Nenhum registro @if($starFilter)encontrados com {{$starFilter}} estrelas @endif</h4>
                            @if($starFilter)
                            <div class="d-flex justify-content-center">
                                <a class="cancel-search" href="{{ route("course.data.reviews", $course->slug) }}"><i class="fa-regular fa-circle-xmark fa-xl"></i>Cancelar pesquisa</a>
                            </div>
                            @endif
                        </div>
                    @endforelse
                    <div class="d-flex justify-content-center align-items-end pt-3">
                        {{ $ratings->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection