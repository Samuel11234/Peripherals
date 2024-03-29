<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- Scripts e Stylesheet nativos --}}
    <link rel="stylesheet" type="text/css" href="{{ asset('css') }}/@yield('css').css">
    <script defer src="{{ asset('js') }}/@yield('js').js"></script>
    <script src="{{ asset('js/valida-forms.js') }}"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/admin/navbar.css') }}">

    {{-- Font Aweasome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" />

    {{-- Carrossel --}}
    <script src="https://unpkg.com/embla-carousel/embla-carousel.umd.js"></script>
    <script src="https://unpkg.com/embla-carousel-autoplay/embla-carousel-autoplay.umd.js"></script>

    <title>
        @section('title')
            Peripherals -
        @show
    </title>

</head>

<body>
    <main class="global-wrapper">

        @if (!Request::is('adm/auth/*'))
            <header id="getMenu">
                <img class="img-logo" src="{{ asset('images/logo_escrita.png') }}" alt="Peripherals - Logo Branca"
                    id="logo">

                <!-- Barra_de_menu_lateral -->
                <nav id="navLinks">
                    <ul>
                        <li><a href="{{ route('page-relatorios') }}">Relatórios</a></li>
                        <li><a href="{{ route('page-listPedidos') }}">Pedidos</a></li>
                        <li><a href="{{ route('page-listProdutos') }}">Produtos</a></li>
                        <li><a href="{{ route('page-listClientes') }}">Clientes</a></li>
                        <li><a href="{{ route('page-listComentarios') }}">Feedback</a></li>
                        <li><a href="{{ route('page-listAdm') }}">Administração</a></li>
                        @if (array_values(Session::get('user'))[0]['poder'] == 9)
                            <div class="dropdown-link">
                                <button class="btn-config">Configurações▾</button>
                                <!-- <i class="fa-solid fa-angle-down"></i> -->
                                <li class="dropdown-content">
                                    <a href="{{ route('page-listMenus') }}">Menus</a>
                                    <a href="{{ route('page-listCarrossel') }}">Carrossel</a>
                                    <a href="{{ route('page-listCupons') }}">Cupons</a>
                                    <a href="{{ route('lista-log') }}">Log de Ações</a>
                                    <a href="{{ route('page-listConfig') }}">Configurações</a>
                                </li>
                            </div>
                        @endif
                    </ul>
                </nav>
                <!-- Fim_Barra_de_menu_lateral -->

                <!-- ADM_logado -->
                @if (array_values(Session::get('user'))[0]['poder'] == "9")
                    <button class="button-nav" id="userSession">Sysadmin: <br>{{array_values(Session::get('user'))[0]['email']}}</button>
                @elseif (array_values(Session::get('user'))[0]['poder'] == "8")
                    <button class="button-nav" id="userSession">Gerente: <br>{{array_values(Session::get('user'))[0]['email']}}</button>
                @elseif (array_values(Session::get('user'))[0]['poder'] == "7")
                    <button class="button-nav" id="userSession">SAC: <br>{{array_values(Session::get('user'))[0]['email']}}</button>
                @elseif (array_values(Session::get('user'))[0]['poder'] == "7")
                    <button class="button-nav" id="userSession">Repositor: <br>{{array_values(Session::get('user'))[0]['email']}}</button>
                @endif

                <!-- Encerrar_sessão -->
                <button class="button-nav" id="logoutSession"
                    onclick="window.location.href='{{ route('auth-sair') }}'">Logout</button>

            </header>
            <!-- Fim_da_sessão_header -->
            <div class="fix"></div>

            <section class="container-content">
                @yield('content')
            </section>
        @else
            <section class="container-login">
                @yield('content')
            </section>
        @endif

    </main>
</body>
<script src="{{ asset('js/admin/admin.js') }}"></script>

</html>
