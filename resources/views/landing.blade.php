@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <style>
        .slider{
            background-image: url("{{ asset('images/img-bg.jpg') }}");
            background-repeat: no-repeat;
        }
    </style>

@endpush

@push('scripts')
    <script src="{{ asset('js/script.js') }}"></script>
    <script src="{{ asset('js/landing.js') }}"></script>

    {{-- vendo se tem erro ao logar e ao cadastrar --}}
    @if ($errors->has('modal') && $errors->first('modal') === 'login')
        <script>
            $(document).ready(function () {
                $('#ModalLogin').modal('show');
            });
        </script>
    @elseif ($errors->has('modal') && $errors->first('modal') === 'cadastro')
        <script>
            $(document).ready(function () {
                $('#ModalCad').modal('show');
            });
        </script>
    @endif

    {{-- vendo se user (não autenticado) tentou acessar uma rota protegida --}}
    @if (session('authError'))
        <script>
            $(document).ready(function () {
                $('#ModalErro').modal('show');
            });
        </script>
    @endif

    @if (session('forgotPassword'))
        <script>
            $(document).ready(function () {
                $('#ModalForgotPassword').modal('show');
            });
        </script>
    @endif

    @if (session('resetPassword'))
        <script>
            $(document).ready(function () {
                $('#ModalResetPassword').modal('show');
            });
        </script>
    @endif

    @if (session('successVerification'))
        <script>
            $(document).ready(function () {
                $('#ModalSucess').modal('show');
            });
        </script>
    @endif

    @if (session('verification'))
        <script>
            $(document).ready(function () {
                $('#ModalVerification').modal('show');
            });
        </script>
    @endif

    @if (session('uAreSuspended'))
        <script>
            $(document).ready(function () {
                $('#ModalSuspended').modal('show');
            });
        </script>
    @endif
@endpush

@section('header')
@if (session()->has('authError'))
    <div class="modal fade" id="ModalErro" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="d-flex">
                        <div class="image">
                            <img src="{{ asset('images/warning.png') }}" alt="" srcset="">
                        </div>
                        <div class="close">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                    </div>
                    <div class="content">
                        <span class="title">{{trans('accessDeniedAuthRequired')}}</span>
                        <p class="message">{{ session('authError') }}</p>
                    </div>
                    <div class="actions">
                        <button class="desactivate" id="authButton" type="button">{{ trans('authenticate') }}</button>
                        <button class="cancel" data-bs-dismiss="modal" type="button">{{ trans('cancel') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

@if (session()->has('uAreSuspended'))
<div class="modal fade modal-email" id="ModalSuspended" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="d-flex">
                    <div class="image">
                        <img src="{{ asset('images/warning.png') }}" alt="" srcset="">
                    </div>
                    <div class="close">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                </div>
                <div class="content">
                    <span class="title">Erro, ação inválida!</span>
                    <p class="message">{{ session('uAreSuspended') }}</p>
                </div>
                <div class="actions">
                    <button class="cancel btns-restaurar" data-bs-dismiss="modal" type="button">Cancelar</button>
                    <button class="btn desactivate btns-restaurar" data-bs-dismiss="modal" type="submit">Recorrer</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@if (session()->has('verification'))
<div class="modal fade modal-email" id="ModalVerification" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
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
                    <span class="title">Confirmação necessária!</span>
                    <p class="message">{{ session('verification') }}</p>
                </div>
                <div class="actions">
                    <button class="btn btns-restaurar cancel" data-bs-dismiss="modal" type="submit">Ok</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@if (session()->has('successVerification'))
<div class="modal fade modal-email" id="ModalSucess" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
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
                    <p class="message">{{ session('successVerification') }}</p>
                </div>
                <div class="actions">
                    <button class="btn success success btns-restaurar" data-bs-dismiss="modal" type="submit">Ok</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@if (session()->has('forgotPassword'))
    <div class="modal fade password" id="ModalForgotPassword" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <form action="{{ route('password.email') }}" method="post">
                    @csrf
                        <div class="d-flex">
                            <div class="image">
                                <img src="{{ asset('images/confirmation.png') }}" alt="" srcset="">
                            </div>
                            <div class="close">
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                        </div>
                        <div class="content">
                            <span class="title">Esqueceu sua senha?</span>
                            <p class="message">{{ session('forgotPassword') }}</p>
                            <input type="email" name="email" placeholder="Insira seu email...">
                        </div>
                        <div class="actions">
                            <button class="cancel" type="button">{{ trans('cancel') }}</button>
                            <button class="btn btns-restaurar restore" type="submit">Enviar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endif

@if (session()->has('resetPassword'))
    <div class="modal fade password password-reset" id="ModalResetPassword" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <form method="POST" action="{{ route('password.update') }}">
                    @csrf
                        <input type="hidden" name="token" value="{{ session('token') }}">
                        <div class="d-flex">
                            <div class="image">
                                <img src="{{ asset('images/confirmation.png') }}" alt="" srcset="">
                            </div>
                            <div class="close">
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                        </div>
                        <div class="content">
                            <div class="mb-3">
                                <label for="email" class="form-label">Endereço de Email</label>
                                <input type="email" readonly name="email" value="{{ session('email') }}" required autofocus>
                                @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        
                            <div class="mb-3">
                                <label for="password" class="form-label">Nova Senha</label>
                                <input type="password" name="password" required>
                                @error('password')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        
                            <div class="mb-3">
                                <label class="form-label">Confirmação de Senha</label>
                                <input type="password" name="password_confirmation" required>
                            </div>
                        </div>
                        <div class="actions mt-3">
                            <button class="cancel" type="button">{{ trans('cancel') }}</button>
                            <button class="btn btns-restaurar restore" type="submit">Redefinir Senha</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endif

    <nav class="navbar-dark fixed-top">
        <!-- logo -->
        <div class="logo">
            <a href="{{ route('home') }}">
                <img class="logo1" src="{{ asset('images/logoMenu.png') }}" alt="">
                <img class="logo2" src="{{ asset('images/logoMenu2.png') }}" alt="">
            </a>
        </div>
        <div class="links">
            <ul class="nav-itens">
                <!-- itens -->
                <li><a href="#">Cursos</a></li>
                <li><a href="#">Ferramentas</a></li>
                <li><a href="#">Suporte</a></li>
            </ul>
        </div>
        <!-- botão entrar que chama o modal1 -->
        @auth
            @can('manage-courses')
                <a href="{{ route('courses.creator') }}" class="gradient-button">
                    <i class="fa fa-sign-in-alt"></i> {{ trans('login') }}
                </a>
            @else
                <a href="{{ route('courses.viewer') }}" class="gradient-button">
                    <i class="fa fa-sign-in-alt"></i> {{ trans('login') }}
                </a>
            @endcan
        @endauth
        @guest
            <a class="gradient-button" data-bs-target="#Modal1" data-bs-toggle="modal">
                <i class="fa fa-sign-in-alt"></i> {{ trans('login') }}
            </a>
        @endguest   

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

    <!-- modais de login/cadastro -->
    <div class="modal fade" id="Modal1" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title" id="exampleModalToggleLabel">LOGIN</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <p>{{ trans('welcome') }}</p>
                    <img src="{{ asset('images/logo5.png') }}" width="200" alt="" srcset="">
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" id="abrir-modal-login">Login</button>
                    <button class="btn btn-primary" id="abrir-modal-cad">{{ trans('register') }}</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="ModalCad" aria-hidden="true" aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title" id="exampleModalToggleLabel2">{{ trans('register') }}</h1>
                    <button onclick="limparForm('form_cadastro')" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="form_cadastro" method="POST" action="{{ route('register') }}">
                @csrf
                    <div class="modal-body">
                    
                        <label>{{ trans('name') }}</label>
                        <input id="name" type="text" name="name" value="{{ old('cad_name') }}" required>

                        <label>Email</label>
                        <input id="cad_email" type="email" name="email" value="{{ old('cad_email') }}" required>
                        @error('email')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror


                        <label>{{ trans('password') }}</label>
                        <input id="cad_password" type="password" value="{{ old('cad_password') }}" name="password" required>
                        @error('password')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror

                        <label>{{ trans('confirmPassword') }}</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" required>
                        @error('password_confirmation')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror

                        <div id="abrir-modal-termo-criador" class="checkbox d-flex">
                            <input id="termo-criador-input" name="termo-criador-input" type="checkbox"/>
                            <label for="termo-criador-input">{{ trans('iWishBeContentCreator') }}</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" type="submit">{{ trans('register') }}</button>
                        <button class="btn btn-primary" onclick="limparForm('form_cadastro')" id="voltarModalButton2"><i class="fa fa-arrow-left" aria-hidden="true"></i> {{ trans('back') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="ModalLogin" aria-hidden="true" aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalToggleLabel2">LOGIN</h1>
                    <button onclick="limparForm('form_login')" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="form_login" method="POST" action="{{ route('login') }}">
                @csrf
                    <div class="modal-body">
                        <label>Email</label>
                        <input value="{{ old('email') }}" type="email" id="login_email" name="email" required>

                        <label>{{ trans('password') }}</label>
                        <input value="{{ old('password') }}" type="password" id="login_password" name="password" required>

                        <!--<div class="checkbox">
                            <input id="remember" type="checkbox" />
                            <label for="remember">Remember me on this computer</label>
                        </div>-->
                        @error('erroLogin')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror

                        @if (session()->has('forgotPasswordText'))
                            <div class="d-flex justify-content-center mt-2">
                                <a href="{{ route('password.request') }}" class="link-cad-modal">Esqueceu sua senha?</a>
                            </div>
                        @else
                            <div class="d-flex justify-content-center">
                                <a data-bs-target="#ModalCad" data-bs-toggle="modal" id="link-sem-conta-md-login" class="link-cad-modal">{{ trans('dontHaveAccountYetCreateOneNow') }}</a>
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" type="submit">{{ trans('login') }}</button>
                        <button class="btn btn-primary" onclick="limparForm('form_login')" id="voltarModalButton"><i class="fa fa-arrow-left" ></i> {{ trans('back') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade modal-termo" id="ModalTermoCriador" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title" id="exampleModalToggleLabel"></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <p>
                        {{ trans('statementOfResponsibilityTextPart1') }}
                    </p>
                    <p>
                        {{ trans('statementOfResponsibilityTextPart2') }}
                    </p>
                    <p><strong>{{ trans('statementOfResponsibilityTextPart3') }}</strong> {{ trans('statementOfResponsibilityTextPart4') }} </p>
                    <ul class="termo-lista-consequencias">
                        <li>{{ trans('statementOfResponsibilityTextPart5') }} </li>
                        <li>{{ trans('statementOfResponsibilityTextPart6') }} </li>
                        <li>{{ trans('statementOfResponsibilityTextPart7') }} </li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button id="concordar-termo" class="btn btn-success">{{ trans('agree') }}</button>
                    <button id="recusar-termo" class="btn btn-danger">{{ trans('refuse') }}</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('main')
    <section class="slider">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-9 col-md-10">
                    <div class="block text-center">
                        <h1 class="fadeInUp mb-5">AprendaOnline</h1>
                        <p class="d-block mb-3 text-white">{{ trans('welcomeText') }}</p>
                        @can('manage-courses')
                            <a href="{{ route('courses.creator') }}">{{ trans('seeCourses') }} <i class="fa fa-angle-double-right" aria-hidden="true"></i></a>
                        @else
                            <a href="{{ route('courses.viewer') }}">{{ trans('seeCourses') }} <i class="fa fa-angle-double-right" aria-hidden="true"></i></a>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection