<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <!-- Styles -->
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootswatch@5.0.1/dist/{{ $theme ?? 'cosmo' }}/bootstrap.min.css">
    <link rel="shortcut icon" href="{{ asset('images/icon.png') }}">
    @yield('stylesheet')
    <style>
        html {
            font-size: {{ ($fontSize ?? 1) * 12 }}px;
        }

        {{ $compactMode ?? false ? '.hide-compact {display: none;}' : '' }}

    </style>
</head>

<body>
    <div id="app">
        @include('layouts.nav')
        <div class="container-fluid">
            <div class="row title">
                <div class="col-md-10">
                    <h2>@yield('title')</h2>
                </div>
                <div class="col-md-2 title-buttons">
                    @yield('title-buttons')
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

    <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Modal title</h5>
                    <button type="button" class="close btn btn-danger" data-dismiss="modal" aria-label="Close">
                        <i class="fa fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <iframe frameborder="0" style="width: 100%; height: 600px;"></iframe>
                </div>
            </div>
        </div>
    </div>
    <div class="loading" style="display: none;">
        <i class="fa fa-sync fa-spin"></i>
    </div>
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}?v={{ time() }}"></script>
    @yield('script')
</body>

</html>
