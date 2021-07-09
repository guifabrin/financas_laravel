<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>


    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet"><!-- Styles -->
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootswatch@5.0.1/dist/{{ $theme }}/bootstrap.min.css">
    <style>
        html {
            font-size: {{ ($fontSize ?? 1) * 12 }}px;
        }

        {{ $compactMode ?? false ? '.hide-compact {display: none;}' : '' }}

    </style>
    @yield('stylesheet')
</head>

<body>
    <style>
        body {
            background-color: transparent !important;
            padding-top: 0px !important;
        }

    </style>
    <div id="app">
        <div class="container-fluid">
            <div class="row title" style="display: none">
                <div class="col-md-9">
                    <h2>@yield('title')</h2>
                </div>
            </div>
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    @yield('script')
</body>

</html>
