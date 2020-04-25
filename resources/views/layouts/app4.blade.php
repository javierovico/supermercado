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
    <style>
        #app {
            display: flex;
            min-height: 100vh;
            flex-direction: column;
        }
        main {
            flex: 1 0 auto;
        }
    </style>
</head>
<body>
    <div id="app">
        <header>
            <!-- Dropdown Structure -->
            <ul id="dropdown1" class="dropdown-content">
                <li><a v-on:click="cambiarSeccion('categorias')">Categorias</a></li>
                <li><a v-on:click="cambiarSeccion('productos')">Productos</a></li>
                <li class="divider"></li>
                @auth
                    <li>
                        <a href="#" onclick="$('#logout-form').submit()">Salir</a>
                    </li>
                @else
                    <li>
                        <a v-on:click="cambiarSeccion('iniciar')">Iniciar</a>
                        @if(Route::has('register'))
                            <a href="{{route('register')}}">Registrarse</a>
                        @endif
                    </li>
                @endif
            </ul>
            <nav>
                <div class="nav-wrapper">
                    <a href="{{route('index')}}" data-target="mobile-demo" class="brand-logo">Logo</a>
                    <a href="#" data-target="mobile-demo" class="sidenav-trigger"><i class="material-icons">menu</i></a>
                    <ul class="right hide-on-med-and-down">
                        <!-- Dropdown Trigger -->
                        <li>
                            <a class="dropdown-trigger" href="#!" data-target="dropdown1">
                                {{auth()->check()?Auth::user()->name:'Invitado'}}
                                <i class="material-icons right">arrow_drop_down</i>
                            </a>
                        </li>
                        @auth()
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        @endauth
                    </ul>
                </div>
            </nav>
            <ul class="sidenav" id="mobile-demo">
                <li><a href="sass.html">Sass</a></li>
                <li><a href="badges.html">Components</a></li>
                <li><a href="collapsible.html">Javascript</a></li>
                <li><a href="mobile.html">Mobile</a></li>
            </ul>
        </header>
        <main class="py-4">
            @yield('content')
        </main>
        <footer class="page-footer">
            <div class="container">
                <div class="row">
                    <div class="col l6 s12">
                        <h5 class="white-text">Footer Content</h5>
                        <p class="grey-text text-lighten-4">You can use rows and columns here to organize your footer content.</p>
                    </div>
                    <div class="col l4 offset-l2 s12">
                        <h5 class="white-text">Links</h5>
                        <ul>
                            <li><a class="grey-text text-lighten-3" href="#!">Link 1</a></li>
                            <li><a class="grey-text text-lighten-3" href="#!">Link 2</a></li>
                            <li><a class="grey-text text-lighten-3" href="#!">Link 3</a></li>
                            <li><a class="grey-text text-lighten-3" href="#!">Link 4</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="footer-copyright">
                <div class="container">
                    © 2014 Copyright Text
                    <a class="grey-text text-lighten-4 right" href="#!">More Links</a>
                </div>
            </div>
        </footer>
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
            $('.sidenav').sidenav();
        })
    </script>
</body>
</html>
