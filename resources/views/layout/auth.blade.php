<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>GUSTA</title>
    <!-- plugins:css -->
    <link rel="shortcut icon" href="{{ asset('/favicon.ico') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <!-- plugin css -->
    {!! Html::style('assets/plugins/@mdi/font/css/materialdesignicons.min.css') !!}
    {!! Html::style('assets/plugins/perfect-scrollbar/perfect-scrollbar.css') !!}
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <!-- endinject -->
    <!-- Layout styles -->
    {!! Html::style('assets/plugins/@mdi/font/css/materialdesignicons.min.css') !!}
    <link rel="stylesheet" href="../../css/style.css" />
    <link rel="shortcut icon" href="../../images/favicon.png" />
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <style>
        #password-strength ul {
            list-style: none;
            padding-left: 0;
            margin-top: 10px;
        }

        #password-strength li {
            color: red;
        }

        #password-strength li.valid {
            color: green;
        }

        #password-strength li.invalid {
            color: red;
        }

        .forgot-password-link {
            color: #007bff;
            font-weight: normal;
            font-size: 0.8rem;
            text-decoration: underline;
            cursor: pointer;
        }

        .forgot-password-link:hover {
            color: #0056b3;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper full-page-wrapper">
            <div class="content-wrapper d-flex align-items-center auth">
                @yield('main')
            </div>
            <!-- content-wrapper ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
    <!-- base js -->
    {!! Html::script('js/app.js') !!}
    {!! Html::script('assets/plugins/perfect-scrollbar/perfect-scrollbar.min.js') !!}
    <!-- end base js -->

    <!-- plugin js -->
    @stack('plugin-scripts')
    <!-- end plugin js -->

    <!-- common js -->
    {!! Html::script('assets/js/off-canvas.js') !!}
    {!! Html::script('assets/js/hoverable-collapse.js') !!}
    {!! Html::script('assets/js/misc.js') !!}
    {!! Html::script('assets/js/settings.js') !!}
    {!! Html::script('assets/js/todolist.js') !!}

    <script>
        var loginElements = document.getElementsByClassName("login");

        window.addEventListener("load", (event) => {
            Array.from(loginElements).forEach((login) => {
                login.classList.add("from-top");
            });
        });
    </script>
    @stack('custom-scripts')
</body>

</html>
