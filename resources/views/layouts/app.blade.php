<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!--Import Google Icon Font-->
{{--    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">--}}
    <link rel="icon" href="http://www.kamaleon360.com/wp-content/uploads/2019/04/logo.png" sizes="32x32" />
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
{{--    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">--}}
    <script>
        const urlBase = "{{URL::to('/')}}";
    </script>
    <style>
        #principal {
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
        @yield('content')
    </div>
    <script src="{{ asset('js/app.js') }}" defer></script>
{{--    <script src="{{asset('js/jquery-3.4.0.min.js')}}"></script>--}}
{{--    <script src="{{ asset('js/bootstrap.min.js') }}" defer></script>--}}
    <script type="text/javascript">
        // $.ajaxSetup({
        //     headers: {
        //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //     }
        // });
        // $(document).ready(function () {
        //     $('.dropdown-trigger').dropdown();
        //     $('.sidenav').sidenav();
        // })
    </script>
</body>
</html>
