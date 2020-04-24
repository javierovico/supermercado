<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!--Import Google Icon Font-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script src="{{ asset('js/app.js') }}" defer></script>
    <!--Import materialize.css-->
    <link type="text/css" rel="stylesheet" href="{{ asset('css/materialize.min.css') }}"  media="screen,projection"/>

    <script src="{{ asset('js/materialize.min.js') }}" defer></script>

    <link rel="icon" href="http://www.kamaleon360.com/wp-content/uploads/2019/04/logo.png" sizes="32x32" />
{{--    <link href="{{ asset('css/app.css') }}" rel="stylesheet">--}}
    <script>
        const urlBase = "{{URL::to('/')}}";
    </script>
</head>
<body>
    <div id="app">
        <!-- Dropdown Structure -->
        <ul id="dropdown1" class="dropdown-content">
            <li>
                <a href="#" onclick="$('#logout-form').submit()">Salir</a>
            </li>
            <li><a href="#!">two</a></li>
            <li class="divider"></li>
            <li><a href="#!">three</a></li>
        </ul>
        <nav>
            <div class="nav-wrapper">
                <a href="#!" class="brand-logo">Logo</a>
                <ul class="right hide-on-med-and-down">
                    <!-- Dropdown Trigger -->
                    @guest
                        <li>
                            <a href="{{route('login')}}">Iniciar</a>
                        </li>
                        @if(Route::has('register'))
                            <li>
                                <a href="{{route('register')}}">Registrarse</a>
                            </li>
                        @endif
                    @else
                        <li>
                            <a class="dropdown-trigger" href="#!" data-target="dropdown1">
                                {{Auth::user()->name}}
                                <i class="material-icons right">arrow_drop_down</i>
                            </a>
                        </li>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    @endguest
                </ul>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
    <script src="{{asset('js/jquery-3.5.0.min.js')}}"></script>
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(document).ready(function () {
            $('.dropdown-trigger').dropdown();
        })
    </script>
    @yield('modal')
    @yield('javascript')
</body>
</html>
