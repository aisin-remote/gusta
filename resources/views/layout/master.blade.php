<!DOCTYPE html>
<html>

<head>
    <title>GUSTA</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="_token" content="{{ csrf_token() }}">

    <!-- Content Security Policy -->
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

    <link rel="shortcut icon" href="{{ secure_asset('favicon.ico') }}">
    <link rel="stylesheet" href="{{ secure_asset('css/app.css') }}">

    <!-- plugin css -->
    <link rel="stylesheet" href="{{ secure_asset('assets/plugins/@mdi/font/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ secure_asset('assets/plugins/perfect-scrollbar/perfect-scrollbar.css') }}">
    <link rel="stylesheet" href="{{ secure_asset('css/dataTables.bootstrap4.min.css') }}">

    <!-- end plugin css -->

    @stack('plugin-styles')

    <!-- common css -->
    <link rel="stylesheet" href="{{ secure_asset('css/app.css') }}">
    <!-- end common css -->

    @stack('style')
</head>

<body data-base-url="{{ url('/') }}" class="sidebar-icon-only">

    <div class="container-scroller" id="app">
        @include('layout.header')
        <div class="container-fluid page-body-wrapper">
            @include('layout.sidebar')
            <div class="main-panel">
                <div class="content-wrapper">
                    @yield('content')
                </div>
                @include('layout.footer')
            </div>
        </div>
    </div>

    <!-- base js -->
    <script src="{{ secure_asset('js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ secure_asset('js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ secure_asset('assets/plugins/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <!-- end base js -->

    <!-- plugin js -->
    @stack('plugin-scripts')
    <!-- end plugin js -->

    <!-- common js -->
    <script src="{{ secure_asset('assets/js/off-canvas.js') }}"></script>
    <script src="{{ secure_asset('assets/js/hoverable-collapse.js') }}"></script>
    {{-- <script src="{{ secure_asset('assets/js/misc.js') }}"></script> --}}
    <script src="{{ secure_asset('assets/js/settings.js') }}"></script>
    <script src="{{ secure_asset('assets/js/todolist.js') }}"></script>
    <!-- end common js -->

    @stack('custom-scripts')
</body>

</html>
