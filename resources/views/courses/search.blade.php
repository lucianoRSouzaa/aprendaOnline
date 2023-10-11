@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/cursos-page-search.css') }}">

    <style>
        .card .card-overlay{
            background-color: rgba(105, 105, 105, 0.5); 
            border-radius: 15px;
            top: 12px;
            right: 12px;
        }

        .card-content {
            padding: 12px;
            color: #000;
            text-align: center; 
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('js/creator.js') }}"></script>
    <script src="{{ asset('js/script.js') }}"></script>

    <script>
        $(document).ready(function() {  
          $('.sort').click(function() {
            var avaliacoesOptions = $('.options-rating');

            $(this).toggleClass('open');
            
            // Alternar a classe "show" (adicionar/remover)
            avaliacoesOptions.slideToggle("slow");
          });
          $('.categories').click(function() {
            var categoriesOptions = $('.options-categories');

            $(this).toggleClass('open');
            
            // Alternar a classe "show" (adicionar/remover)
            categoriesOptions.slideToggle("slow");
          });

            @if($selectedRating)
                $('.sort').trigger('click');
            @endif
            @if($selectedCategories)
                $('.categories').trigger('click');
            @endif
        });
    </script>
@endpush


@section('header')
    <nav class="navbar-dark container">
        <!-- logo -->
        <div class="logo">
            <a href="{{ route('courses.creator') }}" aria-label="Ir para o menu da página de criadores de conteúdo">
                <img class="logo2" src="{{ asset('images/logoMenu2.png') }}" alt="">
            </a>
        </div>
        <div class="links">
            <ul class="nav-itens d-flex align-items-center">
                <!-- itens -->
                <li><a href="#">{{ trans('myCourses') }}</a></li>
                <li><a href="#">{{ trans('support') }}</a></li>
                <li class="d-flex align-items-center profile" id="config"><img src="{{ asset($user->image) }}" class="rounded-circle avatar" alt=""><p class="nameUser">{{$user->name}} <i class="fa fa-angle-down" aria-hidden="true"></i></p></li>
                <div class="notification-div">
                    <p class="text-center">Menu</p>
                    <hr>
                    @if (!session()->has('user_role'))
                        <a href="{{ route('courses.toggleMode') }}"><i class="fa-solid fa-user-graduate fa-lg"></i>{{ trans('toggleModeStudent') }}</a>
                    @endif
                    <a href="{{ route('user.show', auth()->user()->id) }}"><i class="fa fa-user fa-lg" aria-hidden="true"></i>{{ trans('profile') }}</a>
                    <a href="{{ route('configs') }}"><i class="fa fa-cog fa-lg" aria-hidden="true"></i>{{ trans('settings') }}</a>
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
            </ul>
        </div>

        <!-- botão do menu responsivo -->
        <div class="toggle_btn">
            <i class="fa fa-bars" aria-hidden="true"></i>
        </div>

        <!-- links dentro do menu responsivo -->
        <div class="dropdown_menu">
            <li><a href="#">Cursos</a></li>
            <li><a href="#">Ferramentas</a></li>
            <li><a href="#">Suporte</a></li>
            <li><a href="#" class="gradient-button" data-bs-target="#Modal1" data-bs-toggle="modal">Entrar</a></li>
        </div>
    </nav>
@endsection

@section('main')
<form action="" method="get">
    <div class="serach">
        <div class="container pt-4 pb-5">
            <h3>Encontre o curso que você procura!</h3>
            <div class="box-search shadow mt-2">
                <div class="d-flex">
                    <div class="input-txt d-flex align-items-center">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input class="w-100 border-none first-input" value="{{ $courseTitleSearch }}" type="text" name="title" placeholder="Pesquise pelo nome do curso">
                    </div>
                    <div class="input-txt d-flex align-items-center">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input class="w-100 border-none" type="text" name="author" placeholder="Pesquise pelo nome do autor do curso" value="{{ $author }}">
                    </div>
                    <div class="btns d-flex align-items-center">
                        <a href="{{ route('courses.search') }}" class="btn">Limpar</a>
                        <button type="submit" class="btn btn-primary">pesquisar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-3 pt-4 pb-5">
            <h4 class="text-center">Filtrar por:</h4>
            <div class="d-flex align-items-center justify-content-between sort mt-3">
                <h5>Avaliações</h5>
                <i class="fa-solid fa-chevron-down"></i>
            </div>
            <div class="options-rating">
                @for ($i = 4.5; $i >= 0.5; $i -= 1)
                    <div class="d-flex align-items-center star-option">
                        <input type="radio" name="rating" id="rating{{ $i }}" value="{{ $i }}" {{ $i == $selectedRating ? 'checked' : '' }}>
                        @for ($j = 1; $j <= 5; $j += 1)
                            @if ($j <= ceil($i))
                                @if ($j > $i && is_float($i))
                                    <img src="{{ asset('images/star/star-half.png') }}" alt="">
                                @else
                                    <img src="{{ asset('images/star/star-on.png') }}" alt="">
                                @endif
                            @else
                                <img src="{{ asset('images/star/star-off.png') }}" alt="">
                            @endif
                        @endfor
                        <span>{{ $i }} e acima</span>
                    </div>
                @endfor
            </div>
            <div class="d-flex align-items-center justify-content-between categories">
                <h5>Categorias</h5>
                <i class="fa-solid fa-chevron-down"></i>
            </div>
            <div class="options-categories">
                @forelse ($categories as $category)
                    <div class="d-flex align-items-center option-category">
                        <input type="checkbox" name="categories[]" value="{{ $category->id }}" @if(in_array($category->id, $selectedCategories)) checked @endif>
                        <span>{{ $category->name }}</span>
                    </div>
                @empty
                    <div class="d-flex align-items-center option-category">
                        <p>Nenhuma categoria disponível</p>
                    </div>
                @endforelse
            </div>
        </div>
        <div class="col-9">
            <div class="d-flex result-text-div justify-content-between align-items-center">
                <h4 class="result-text">Registros encontrados: {{ $filteredCoursesQtd }}
                    @if ($courseTitleSearch || $author || $selectedCategories || $selectedRating || $selectedSort)
                        <a href="{{ route("courses.search") }}"><i class="fa-regular fa-circle-xmark"></i></a>
                    @endif
                </h4>
                <select name="sort" id="sort">
                    <option value="" selected disabled>Ordernar por</option>
                    <option value="popularity" {{ $selectedSort === 'popularity' ? 'selected' : '' }}>Mais populares</option>
                    <option value="rating" {{ $selectedSort === 'rating' ? 'selected' : '' }}>Melhores avaliações</option>
                    <option value="newest" {{ $selectedSort === 'newest' ? 'selected' : '' }}>Mais recentes</option>
                    <option value="oldest" {{ $selectedSort === 'oldest' ? 'selected' : '' }}>Mais antigos</option>
                </select>                
            </div>
            <div class="cards-result d-flex flex-wrap justify-content-evenly mt-1">
                @forelse ($filteredCourses as $course)
                    <div class="card mt-3">
                        <img class="img-card" src="{{ asset('storage/' . $course->image) }}" alt="Imagem do Card">
                        <div class="card-content">
                            <p class="title">{{ $course->title }}</p>
                            <p>{{ trans('madeBy') }} {{ $course->creator->name }}</p>
                            <div class="d-flex align-items-center">
                                <p>{{ trans('ratings') }} {{ number_format($course->average_rating, 1) }}</p>
                                <img class="star" src="{{ asset('images/star/star-on.png') }}" alt="estrela da classificação">
                            </div>
                            
                            <a href="{{ route('courses.show', $course->slug) }}" class="stretched-link"></a>
                        </div>
                    </div>   
                @empty
                @endforelse
            </div>
            <div class="mt-4 d-flex justify-content-center">
                {{ $filteredCourses->links() }}
            </div>
        </div>
    </div>
</form>
@endsection
