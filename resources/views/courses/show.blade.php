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
            score: {{ number_format($averageRating, 2) }},
        });
    </script>
@endpush

@section('header')
    <div class="container">
        <div class="d-flex justify-content-between">
            <!-- ***** Logo Start ***** -->
            @auth
                <a href="{{ auth()->user()->isCreator() ? route('courses.creator') : route('courses.viewer') }}" class="logo">
                    <img src="{{ asset('images/logoMenu2.png') }}" alt="Logo do site">
                </a>   
            @endauth
            @guest
                <a href="{{ route('courses.viewer') }}" class="logo">
                    <img src="{{ asset('images/logoMenu2.png') }}" alt="Logo do site">
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
    <div class="modal fade" id="RateModal" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
        <form method="post" action="{{ route('courses.rate', $course->slug) }}">
            @csrf
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="d-flex">
                            <div class="close">
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                        </div>
                        <div class="content">
                            <span class="title">{{ trans('rateUsWithStarsYourOpinionIsImportant') }}</span>
                            <p class="message">{{ trans('selectOption') }}</p>

                            <div id="star"></div>

                            <p id="text-comment" onclick="adicionarCampo()" class="mt-3 text-primary comment">{{ trans('WouldULikeToSendCommentToo') }}</p>

                            <div id="comment">

                            </div>
                        </div>
                        <div class="actions">
                            <button class="cancel" type="submit">{{ trans('rate') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="modal fade" id="ModalDenuncia" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="d-flex">
                        <div class="close">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                    </div>
                    <form action="{{ route('report.store', $course->slug) }}" method="POST">
                        @csrf
                        <div class="content">
                            <span class="title">{{ trans('report') }}: {{$course->title}}?</span>
                            <p class="mt-2">{{ trans('whatUWantToReport') }}</p>
                            <select class="form-control" name="denuncia" onchange="verificarSelecao()" id="denuncia-option">
                                <option selected disabled value="">--{{ trans('select') }}--</option>
                                <option value="curso">{{ trans('entireCourse') }}</option>
                                <option value="aula">{{ trans('lesson') }}</option>
                            </select>

                            <div id="denuncia-aula-option" class="selecao-aula">
                                <p class="mt-3">{{ trans('selectLessonUWantReport') }}</p>
                                <select class="form-control" name="selecao-aula" id="select-aula-option">
                                    <option selected disabled value="">--{{ trans('select') }}--</option>
                                    @foreach ($modules as $module)
                                        <optgroup label="{{$module->title}}">
                                            @foreach ($module->lessons as $lesson)
                                                <option value="{{ $lesson->id }}">{{ $lesson->title }}</option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                            </div>

                            <p class="mt-3">{{ trans('descriptionOfReport') }}</p>
                            <textarea name="desc" class="desc-txtarea"></textarea>
                        </div>
                        <div class="actions">
                            <button class="desactivate" type="submit">{{ trans('report') }}</button>
                            <button class="cancel" type="reset" data-bs-dismiss="modal">{{ trans('cancel') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row align-items-center">
            <div class="col-6 col-descriptions">
                <h1 class="title">{{ $course->title }}</h1>
                <p>{{ $course->description }}</p>
                @auth
                    @if ($user->subscriptions()->where('course_id', $course->id)->exists())
                        <form action="{{ route('courses.unsubscribe', $course->slug) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger">{{ trans('unsubscribe') }}</button>
                        </form>  
                    @else    
                        <form action="{{ route('courses.subscribe', $course->slug) }}" method="POST" style="display: inline;">
                        @csrf
                            <button type="submit" class="btn btn-outline-primary">{{ trans('subscribe') }}</button>
                        </form> 
                    @endif
                @endauth 
                @guest
                    <form action="{{ route('courses.subscribe', $course->slug) }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-outline-primary">{{ trans('subscribe') }}</button>
                    </form> 
                @endguest
            </div>
            <div class="col-6 colimg">
                <img class="img-size" src="{{ asset('storage/' . $course->image) }}" alt="imagem do curso">
                <div onclick="dropdownMore()" id="more-btn" class="more">
                    <img class="more-png" src="{{ asset('images/tres_pontos.png') }}" alt="icone de opções">
                </div>
                <div id="dropdown-options" class="dropdown-options">
                    <div class="compart">
                        <img src="{{ asset('images/share-svg.svg') }}" alt="">
                        <p>{{ trans('share') }}</p>
                    </div>
                @if ($userIsSubscribed)
                    <div class="classificar" data-bs-toggle="modal" data-bs-target="#RateModal">
                        <img src="{{ asset('images/star-off-2.png') }}" alt="" srcset="">
                        <p>{{ trans('rate') }}</p>
                    </div>
                @endif
                    <div class="denunciar" data-bs-toggle="modal" data-bs-target="#ModalDenuncia">
                        <img src="{{ asset('images/report-svg.svg') }}" alt="">
                        <p>{{ trans('report') }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="modulos_aulas_title">
            <h2>{{ trans('modulesAndLessons') }}</h2>
        </div>
        <div class="row align-items-center">
            <div class="col-md-8 col-sm-12">
                <div class="box">
                    @foreach ($modules as $module)
                        <div class="div-principal" onclick="toggleDivs(this)">
                            <div class="botoes">
                                <i class="fa fa-angle-down" aria-hidden="true"></i>
                            </div>
                            <div class="info-modulo">
                                <span>{{ $module->order }}</span>
                                <p class="texto-principal">{{ $module->title }}</p>
                            </div>
                        </div>
                        <div class="div-filhas">
                            <ul class="subitems">
                                @foreach ($module->lessons as $lesson)
                                    <li class="titulo_modulo d-flex align-items-center">
                                        <i class="fa-solid fa-video"></i>@if($admin && $admin == true) <a href="{{route("lessons.watch", ['courseSlug' => $course->slug, 'lessonSlug' => $lesson->slug])}}">{{ $lesson->title }}</a> @else <p>{{ $lesson->title }}</p> @endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="linha"></div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="avaliacoes_title mb-4">
            <h2>{{ trans('rates') }}:</h2>
        </div>
        <div class="row box-avaliacoes mb-5">
            <div class="col-4 dados-avaliacoes">
                <div class="box-nota">
                    <h3>{{ trans('ratings') }}</h3>
                    <div class="d-flex align-items-center flex-column">
                        <div class="box-estrelas-avaliacoes d-flex align-items-center">
                            <p class="avarage">{{ number_format($averageRating, 1) }}</p>
                            <div id="starAvarage">

                            </div>
                        </div>
                        <p>{{count($ratings)}} {{ trans('ratingsInThisCourse') }}</p>
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
                    <div class="d-flex align-items-center ordenar">
                        <svg viewBox="0 0 15 15" class="ordenar-icon">
                            <path fill="inherit" d="M7.36 2.988a.645.645 0 01-.02.912c-.271.26-.7.26-.972 0L4.82 2.415v10.68a.687.687 0 11-1.375 0V2.415L1.895 3.9c-.271.26-.7.26-.971 0a.645.645 0 01-.02-.912l.02-.02L3.646.36c.272-.26.7-.26.972 0L7.34 2.969zm6.875 8.413a.645.645 0 01-.02.02l-2.722 2.608c-.272.26-.7.26-.972 0L7.799 11.42a.645.645 0 010-.931c.271-.26.7-.26.972 0l1.549 1.483V1.293a.687.687 0 111.375 0v10.68l1.548-1.484c.272-.26.7-.26.972 0a.645.645 0 01.02.912z"></path>
                        </svg>
                        <p class="ordenar-text">ordenar por</p>
                    </div>
                </div>
                <div class="comments">
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
                </div>
            </div>
        </div>
    </div>
@endsection