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
    <script>
        const courseTitle = '{{ $course->title }}';
        $('#input-href-page').val(window.location.href);

        document.addEventListener("DOMContentLoaded", function() {
            // Quando o conteúdo for carregado, verifique se a URL contém "?page"
            if(window.location.href.indexOf("?page") > -1 || window.location.href.indexOf("starFilter") > -1) {
                // Se sim, role para a parte inferior da página
                window.scrollTo(0,document.body.scrollHeight);
            }
        });
    </script>
    <script src="{{ asset('js/detalhes-curso.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('#starAvarage').raty({ 
                path: '/images/star',
                readOnly: true,
                score: {{ number_format($course->average_rating, 2) }},
            });

            @if(session()->has('error'))
                $('#ModalError').modal('show');
            @endif

            @if (session()->has('success'))
                $('#ModalSucess').modal('show');
            @endif
        });
    </script>

    <script src="https://unpkg.com/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://unpkg.com/tippy.js@6.3.1/dist/tippy-bundle.umd.min.js"></script>

    <script>
        tippy('#perfil', {
            content: `
                    <div class="shadow tooltip-box" style="width: 300px;">
                        <div class="d-flex justify-content-center mt-1">
                            <img src="{{ asset($course->creator->image) }}" alt="Imagem">
                        </div>
                        <p class="text-center fs-6">{{ $course->creator->name }}</p>
                        <div class="d-flex gap-2 mb-2">
                            <a href="{{ route('start.chat', $course->creator->id) }}" class="btn btn-primary w-100"><i class="fa-regular fa-comment-dots pe-2"></i>Inciar chat</a>
                            <a href="{{ route('user.show', $course->creator->email) }}" class="btn btn-dark w-100"><i class="fa-regular fa-user pe-2"></i>Ver perfil</a>
                        </div>
                    </div>
                `,
            allowHTML: true,
            interactive: true,
            placement: 'right-end',
            delay: 200,
            arrow: false,
            theme: 'light',
            inertia: true,
        });
    </script>
@endpush

@section('header')
    <div class="header container">
        <div class="d-flex justify-content-between">
            <!-- ***** Logo Start ***** -->
            @auth
                <a href="{{ auth()->user()->isCreator() ? route('courses.creator') : route('courses.viewer') }}" class="logo">
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
                <form action="{{ route('courses.search') }}" method="GET" class="search-bar">
                    <input type="text" name="title" id="search-input" placeholder="{{ trans('searchPlaceholder') }}">
                    <button type="submit" id="search-button"><i class="fa fa-search"></i></button>
                </form>
            </div>    
        </div>
    </div>
@endsection

@section('main')

@if (session()->has('error'))
    <div class="modal fade" id="ModalError" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="d-flex">
                        <div class="image">
                            <img src="{{ asset('images/warning.png') }}" alt="Icone de perigo" srcset="">
                        </div>
                        <div class="close">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                    </div>
                    <div class="content">
                        <span class="title">Ação não permitida!</span>
                        <p class="message">{{ session('error') }}</p>
                    </div>
                    <div class="actions">
                        <button class="cancel" data-bs-dismiss="modal" aria-label="Close" type="button">Fechar</button>
                        <button class="desactivate" data-bs-dismiss="modal" aria-label="Close" type="button">Ok</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

@if (session()->has('success'))
    <div class="modal fade" id="ModalSucess" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="d-flex">
                        <div class="image">
                            <img src="{{ asset('images/sucess.png') }}" alt="" srcset="">
                        </div>
                        <div class="close">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                    </div>
                    <div class="content">
                        <span class="title">Sucesso!</span>
                        <p class="message">{{ session('success') }}</p>
                    </div>
                    <div class="actions">
                        <button class="cancel btns-restaurar" data-bs-dismiss="modal" type="button">Fechar</button>
                        <button class="btn success btns-restaurar" data-bs-dismiss="modal" type="button">Ok</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

@if ($userIsSubscribed)
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
@endif

    <div class="modal fade" id="shareModal" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalToggleLabel">Compartilhar</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="content">                        
                        <div id="share-buttons">

                            <!-- facebook -->
                            <a class="facebook" target="blank"><i class="fab fa-facebook"></i></a>
                            
                            <!-- twitter -->
                            <a class="twitter" target="blank"><i class="fab fa-twitter"></i></a>
                            
                            <!-- linkedin -->
                            <a class="linkedin" target="blank"><i class="fab fa-linkedin"></i></a>
                            
                            <!-- reddit -->
                            <a class="reddit" target="blank"><i class="fab fa-reddit"></i></a>
                          
                            <!-- whatsapp-->
                            <a class="whatsapp" target="blank"><i class="fab fa-whatsapp"></i></a>
                          
                            <!-- telegram-->
                            <a class="telegram" target="blank"><i class="fab fa-telegram"></i></a>
                          
                            <div class="input-div d-flex align-items-center mt-2 mb-2 justify-content-between">
                                <input type="text" value="" id="input-href-page">
                                <button id="copy-btn" class="btn btn-primary">copiar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
                                    @foreach ($course->modules as $module)
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

    <div class="container main-details">
        <div class="row align-items-center">
            <div class="col-6 col-descriptions">
                <h1 class="title">{{ $course->title }}</h1>
                <p class="mb-0 mt-2">{{ $course->views }} visualizações</p>
                <p id="perfil" class="mb-2 mt-0">Criador por: <span class="name-creator">{{ $course->creator->name }}</span></p>
                <p class="mt-1">{{ $course->description }}</p>
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
                    <div class="compart" data-bs-toggle="modal" data-bs-target="#shareModal">
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
        <div class="row align-items-center div-modulos-aulas">
            <div class="col-md-8 col-sm-12">
                <div class="box mt-4">
                    @forelse ($course->modules as $module)
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
                    @empty
                        <p class="mb-0">Nenhum módulo foi criado ainda</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="learn_title mb-4">
            <h2>O que você irá aprender:</h2>
        </div>
        <div class="row align-items-center">
            <div class="col-md-8 col-sm-12">
                <div class="box box-learn">
                    <ul class="learn-list">
                        @foreach ($whatWillLeran as $learn)
                            <li><img src="{{ asset('images/list-content.png') }}" alt="Icone">{{ $learn }}</li>
                        @endforeach
                    </ul>
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
                            <p class="avarage">{{ number_format($course->average_rating, 1) }}</p>
                            <div id="starAvarage">

                            </div>
                        </div>
                        <p>{{$ratingsCount}} {{ trans('ratingsInThisCourse') }}</p>
                    </div>
                </div>
                <div class="box-filtrar">
                    <h3 class="mb-2">{{ trans('filterBy') }}</h3>
                    <form action="{{ route('courses.show', $course->slug) }}" method="GET" id="formStarFilter">
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
                <div class="comments pb-0">
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
                                <a class="cancel-search" href="{{ route("courses.show", $course->slug) }}"><i class="fa-regular fa-circle-xmark fa-xl"></i>Cancelar pesquisa</a>
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