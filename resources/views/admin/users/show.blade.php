@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/user.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('js/user.js') }}"></script>

    <script>

        $(document).ready(function() {
            @if($role)
                $('#{{ $role }}').trigger('click');
            @endif
    
            // slider
            var totalCards =  {{ $user->subscriptions->count() }}
            var totalCardsFavs =  {{ $user->favorites->count() }}
            var totalCardsComplet =  {{ $user->completions->count() }}
            @if($coursesCreated)
                var totalCardsCreated = {{ $coursesCreated->count() }}
            @endif
    
            if (totalCards > 0) {
                var inscritosSlider = createSlider('slider', 'prev', 'next', 'counter', totalCards);
                $('#inscrito').click(function(){
                    inscritosSlider.updateTotalCards({{ $user->subscriptions->count() }});
                })
            }
            if (totalCardsFavs > 0) {
                var favoritosSlider = createSlider('sliderFavs', 'prevFavs', 'nextFavs', 'counterFavs', totalCardsFavs);
                $('#favoritado').click(function(){
                    favoritosSlider.updateTotalCards({{ $user->favorites->count() }});
                })
            }
            if (totalCardsComplet > 0) {
                var completadoSlider = createSlider('sliderComplet', 'prevComplet', 'nextComplet', 'counterComplet', totalCardsComplet);
                $('#concluido').click(function(){
                    completadoSlider.updateTotalCards({{ $user->completions->count() }});
                })
            }
            
            @if($coursesCreated)
                if (totalCardsCreated > 0) {
                    var cursosCriadosSlider = createSlider('sliderCreated', 'prevCreated', 'nextCreated', 'counterCreated', totalCardsCreated);
                    $('#criados').click(function(){
                        cursosCriadosSlider.updateTotalCards({{ $coursesCreated->count() }});
                    })
                }
            @endif
    
            function createSlider(sliderId, prevId, nextId, counterId, totalCards) {
                var atual = totalCards < 5 ? totalCards : 5;
                var slider = document.getElementById(sliderId);
                var next = document.getElementById(nextId);
                var prev = document.getElementById(prevId);
                var counter = document.getElementById(counterId);
                counter.textContent = atual + '/' + totalCards;
    
                var scrollAmount = 0;
                var visibleCards = 5;
    
                function updateButtons() {
                    prev.style.display = scrollAmount <= 0 ? 'none' : 'block';
                    next.style.display = (scrollAmount + 1) * visibleCards >= totalCards ? 'none' : 'block';
                }
    
                next.addEventListener('click', function() {
                    if ((scrollAmount + 1) * visibleCards < totalCards) {
                        slider.scrollLeft += slider.offsetWidth;
                        scrollAmount += 1;
                    }
    
                    atual += 5;
                    atual = Math.min(atual, totalCards);
    
                    counter.textContent = atual + '/' + totalCards;
    
                    updateButtons();
                });
    
                prev.addEventListener('click', function() {
                    if (scrollAmount > 0) {
                        slider.scrollLeft -= slider.offsetWidth;
                        scrollAmount -= 1;
                    }
    
                    atual -= 5;
                    atual = Math.max(atual, 5);
    
                    counter.textContent = atual + '/' + totalCards;
    
                    updateButtons();
                });
    
                updateButtons();
    
                return {
                    updateTotalCards: function(newTotalCards) {
                        var atual = totalCards < 5 ? totalCards : 5;
                        totalCards = newTotalCards;
                        counter.textContent = atual + '/' + totalCards;
                        updateButtons();
                    }
                };
            }
        });
    
    </script>
@endpush

@section('main')
    <a href="{{ auth()->user()->isAdmin() ? route('admin.user.index') : route('courses.creator') }}" class="back-btn page-show"><i class="fa fa-arrow-left" aria-hidden="true"></i> {{ trans('back') }}</a>
    <div class="container">
        <div class="row shadow mb-3">
            <section class="col-12 dados-div">
                <div class="d-flex align-items-center">
                    <img class="photo" src="{{ asset($user->image) }}" alt="Foto do usuário"></img>
                    <div class="dados">
                        <p>{{ $user->name }}</p>
                        <p>{{ $user->email }}</p>
                        <p>{{ trans('userType') }} {{ $user->role }}</p>
                    </div>
                </div>
                @can('edit-profile', $user->email)
                    <a href="{{ route('user.edit', $user->email) }}" class="edit-perfil">
                        <i class="fa-solid fa-pen fa-xs"></i>
                        {{ trans('editData') }}
                    </a>
                @endcan
            </section>
            <section class="col-12 nav-div">
                <ul class="d-flex nav gap-5">
                    <li id="inscrito" class="active">{{ trans('enrolledCourses') }}</li>
                    @if ($user->isCreator())
                        <li id="criados">{{ trans('coursesCreated') }}</li>
                    @endif
                    <li id="concluido">{{ trans('completedCourses') }}</li>
                    <li id="favoritado">{{ trans('favoriteCourses') }}</li>
                    <li id="denuncias">{{ trans('reportsMade') }}</li>
                    <li id="avaliacoes">{{ trans('evaluationsMade') }}</li>
                </ul>
            </section>
            
            <form action="{{ route('user.show', ['email' => $user->email]) }}" method="GET">
                <input id="role" name="role" type="hidden" value="inscrito">
                <div class="input-group">
                    <input type="text" class="form-control" id="search_term" name="search_term" placeholder="{{ trans('searchPlaceholderNameCourse') }}" @if($searchTerm) value="{{ $searchTerm }}" @endif>
                    <button type="submit" class="btn" aria-label="Pesquisar"><i class="fa fa-search"></i></button>
                </div>
            </form>

            <section id="cursos-inscritos" class="col-12 show-div show">
                @if ($role == "inscrito")
                    <div class="result-pesquisa">
                        <h5>{{ trans('registersFoundFromTerm') }} {{ $searchTerm }}</h5>
                        <a href="{{ route('user.show', ['id' => $user->id]) }}"><i class="fa-regular fa-circle-xmark fa-xl"></i></a>
                    </div>
                @endif

                @if ($user->subscriptions->count() > 0)
                    
                    <div class="d-flex">
                        <i id="prev" class="fa-solid fa-angle-left prev"></i>
                        <i id="next" class="fa-solid fa-angle-right next"></i>
                    </div>
                
                    <div class="slider" id="slider">
                        @foreach ($user->subscriptions as $subscript)
                            <div class="card">
                                <img class="img-card" src="{{ asset('storage/' . $subscript->course->image) }}" alt="Imagem do Card">
                                <div class="card-content">
                                    <p class="title">{{ $subscript->course->title }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                
                    <p class="text-center mt-3 counter" id="counter"></p>
                @else
                    <h4 class="text-center mt-1">{{ trans('noRegistration') }}</h4>
                @endif                
            </section>

            @if ($user->isCreator())
            <section id="cursos-criados" class="col-12 show-div">
                @if ($role == "criados")
                    <div class="result-pesquisa">
                        <h5>{{ trans('registersFoundFromTerm') }} {{ $searchTerm }}</h5>
                        <a href="{{ route('user.show', ['id' => $user->id]) }}"><i class="fa-regular fa-circle-xmark fa-xl"></i></a>
                    </div>
                @endif

                @if ($coursesCreated->count() > 0)
                    
                    <div class="d-flex">
                        <i id="prevCreated" class="fa-solid fa-angle-left prev"></i>
                        <i id="nextCreated" class="fa-solid fa-angle-right next"></i>
                    </div>
                
                    <div class="slider" id="sliderCreated">
                        @foreach ($coursesCreated as $created)
                            <div class="card">
                                <img class="img-card" src="{{ asset('storage/' . $created->image) }}" alt="Imagem do Card">
                                <div class="card-content">
                                    <p class="title">{{ $created->title }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                
                    <p class="text-center mt-3 counter" id="counterCreated"></p>
                @else
                    <h4 class="text-center mt-1">{{ trans('noCreated') }}</h4>
                @endif                
            </section>
            @endif

            <section id="cursos-concluidos" class="col-12 show-div">
                @if ($role == "concluido")
                    <div class="result-pesquisa">
                        <h5>{{ trans('registersFoundFromTerm') }} {{ $searchTerm }}</h5>
                        <a href="{{ route('user.show', ['id' => $user->id]) }}"><i class="fa-regular fa-circle-xmark fa-xl"></i></a>
                    </div>
                @endif

                @if ($user->completions->count() > 0)

                    <div class="d-flex">
                        <i id="prevComplet" class="fa-solid fa-angle-left prev"></i>
                        <i id="nextComplet" class="fa-solid fa-angle-right next"></i>
                    </div>

                    <div class="slider" id="sliderComplet">
                        @foreach ($user->completions as $completed)
                            <div class="card">
                                <img class="img-card" src="{{ asset('storage/' . $completed->course->image) }}" alt="Imagem do Card">
                                <div class="card-content">
                                    <p class="title">{{ $completed->course->title }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <p class="text-center mt-3 counter" id="counterComplet"></p>
                
                @else
                    <h4 class="text-center mt-1">{{ trans('noCompletedCourses') }}</h4>
                @endif
            </section>

            <section id="cursos-favoritos" class="col-12 show-div">
                @if ($role == "favoritado")
                    <div class="result-pesquisa">
                        <h5>{{ trans('registersFoundFromTerm') }} {{ $searchTerm }}</h5>
                        <a href="{{ route('user.show', ['id' => $user->id]) }}"><i class="fa-regular fa-circle-xmark fa-xl"></i></a>
                    </div>
                @endif

                @if ($user->favorites->count() > 0)

                    <div class="d-flex">
                        <i id="prevFavs" class="fa-solid fa-angle-left prev"></i>
                        <i id="nextFavs" class="fa-solid fa-angle-right next"></i>
                    </div>

                    <div class="slider" id="sliderFavs">
                        @foreach ($user->favorites as $favorite)
                            <div class="card">
                                <img class="img-card" src="{{ asset('storage/' . $favorite->course->image) }}" alt="Imagem do Card">
                                <div class="card-content">
                                    <p class="title">{{ $favorite->course->title }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <p class="text-center mt-3 counter" id="counterFavs"></p>
                
                @else
                    <h4 class="text-center mt-1">{{ trans('noFavoriteCourses') }}</h4>
                @endif
            </section>

            <section id="denuncias-feitas" class="col-12 show-div">
                @if ($role == "denuncias")
                    <div class="result-pesquisa">
                        <h5>{{ trans('complaintsFoundFromTerm') }} {{ $searchTerm }}</h5>
                        <a href="{{ route('user.show', ['id' => $user->id]) }}"><i class="fa-regular fa-circle-xmark fa-xl"></i></a>
                    </div>
                @endif

                @forelse ($user->reports as $report)
                    <h5> {{ $report->course->title }} </h5>
                    <ul>
                        <li><span class="strong">{{ trans('reportStatus') }} </span>{{ $report->status }}</li>
                        <li><span class="strong">{{ trans('descriptionOfComplaint') }} </span>{{ $report->description }}</li>
                        @if ($report->lesson_id)
                            <li><span class="strong">{{ trans('lessonReported') }} </span> {{ $report->lesson->title }}</li>
                        @endif
                    </ul>

                    <hr class="hr-denuncias">
                @empty
                    <h4 class="text-center mt-1">{{ trans('noReportsMade') }}</h4>
                @endforelse
            </section>

            <section id="avaliacoes-feitas" class="col-12 show-div">
                @if ($role == "avaliacoes")
                    <div class="result-pesquisa mb-2">
                        <h5>{{ trans('reviewsFoundFromTerm') }} {{ $searchTerm }}</h5>
                        <a href="{{ route('user.show', ['id' => $user->id]) }}"><i class="fa-regular fa-circle-xmark fa-xl"></i></a>
                    </div>
                @endif

                @forelse ($user->courseRatings as $courseRating)
                    <h5> {{ $courseRating->course->title }} </h5>
                    <ul>
                        <li><span class="strong">{{ trans('numberOfStars') }} </span>
                            @for ($i = 1; $i <= $courseRating->rating; $i++)
                                <i class="fa-solid fa-star fa-lg"></i>
                            @endfor
                        </li>
                        @if ($courseRating->comment)
                            <li><span class="strong">{{ trans('comment') }} </span> {{ $courseRating->comment }}</li>
                        @endif
                    </ul>

                    <hr class="hr-avaliacoes">
                @empty
                    <h4 class="text-center mt-1">{{ trans('noReviewsMade') }}</h4>
                @endforelse
            </section>
        </div>
    </div>
@endsection