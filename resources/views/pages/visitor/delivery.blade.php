<!DOCTYPE html>
<html>

<head>
    <title>GUSTA</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="_token" content="{{ csrf_token() }}">

    <link rel="shortcut icon" href="{{ secure_asset('favicon.ico') }}">
    <link rel="stylesheet" href="{{ secure_asset('css/app.css') }}">

    <!-- plugin css -->
    <link rel="stylesheet" href="{{ secure_asset('assets/plugins/@mdi/font/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ secure_asset('assets/plugins/perfect-scrollbar/perfect-scrollbar.css') }}">

    <!-- common css -->
    <link rel="stylesheet" href="{{ secure_asset('css/app.css') }}">
    <!-- end common css -->

    @stack('style')
</head>

<body data-base-url="{{ url('/') }}" class="sidebar-icon-only">
    <div class="container-scroller" id="app">
        <div class="page-body-wrapper w-100 vh-100">
            <div class="main-panel w-100">
                <div class="content-wrapper">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title mb-5">Delivery Request Form</h4>
                                    <form action="{{ route('delivery.create') }}" method="POST"
                                        enctype="multipart/form-data" id="appointmentForm">
                                        {{ csrf_field() }}
                                        <div class="form-group row">
                                            <div class="col-md-4">
                                                <label for="inputPt" class="col-form-label">Company Name <small
                                                        class="text-muted pl-0">/ Nama Perusahaan / 会社名</small></label>
                                            </div>
                                            <div class="col-md-8">
                                                <input type="text" name="company" id="inputPt"
                                                    class="form-control mt-1" placeholder="Enter company name" />
                                                <small id="emailHelp" class="form-text text-muted">Enter your company
                                                    name</small>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-4">
                                                <label for="inputName" class="col-form-label">Your Name <small
                                                        class="text-muted pl-0">/ Nama Anda / 名前</small></label>
                                            </div>
                                            <div class="col-md-6">
                                                <div id="nameContainer">
                                                    <div class="input-group mb-2">
                                                        <input type="text" name="name[]" class="form-control"
                                                            placeholder="Enter name" />
                                                    </div>
                                                </div>
                                                <small class="form-text text-muted">Add one or more names</small>
                                            </div>
                                            <div class="col-md-2">
                                                <button type="button" class="btn btn-success btn-block"
                                                    id="addName"><i class="mdi mdi-plus"></i> Add More</button>
                                            </div>
                                        </div>
                                        <div class="row mt-4">
                                            <div class="col-md-12">
                                                <h5>Destination
                                                    <small class="text-muted pl-0">/ Destinasi / 行き先</small>
                                                </h5>
                                            </div>
                                        </div>
                                        <div class="row mt-4">
                                            <div class="col-md-4">
                                                <button type="submit" name="destination" value="Office"
                                                    class="card text-white bg-danger border-0">
                                                    <div class="card-body text-center">
                                                        <h5 class="card-title text-white mb-0">Office</h5>
                                                    </div>
                                                </button>
                                            </div>
                                            <div class="col-md-4">
                                                <button type="submit" name="destination" value="Unit"
                                                    class="card text-white bg-warning border-0">
                                                    <div class="card-body text-center">
                                                        <h5 class="card-title text-white mb-0">Unit</h5>
                                                    </div>
                                                </button>
                                            </div>
                                            <div class="col-md-4">
                                                <button type="submit" name="destination" value="Body"
                                                    class="card text-white bg-success border-0">
                                                    <div class="card-body text-center">
                                                        <h5 class="card-title text-white mb-0">Body</h5>
                                                    </div>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <footer class="footer w-100">
                    <div class="container-fluid clearfix">
                        <span class="text-muted d-block text-center text-sm-left d-sm-inline-block">Copyright ©
                            <a href="#">ITD</a> 2022 <i class="mdi mdi-heart text-danger"></i></span>
                    </div>
                </footer>
            </div>
        </div>
    </div>

    <!-- base js -->
    <script src="{{ secure_asset('js/app.js') }}"></script>
    <script src="{{ secure_asset('assets/plugins/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>

    <!-- plugin js -->
    <script src="{{ secure_asset('assets/plugins/chartjs/chart.min.js') }}"></script>
    <script src="{{ secure_asset('assets/plugins/jquery-sparkline/jquery.sparkline.min.js') }}"></script>

    <!-- common js -->
    <script src="{{ secure_asset('assets/js/off-canvas.js') }}"></script>
    <script src="{{ secure_asset('assets/js/hoverable-collapse.js') }}"></script>
    <script src="{{ secure_asset('assets/js/misc.js') }}"></script>
    <script src="{{ secure_asset('assets/js/settings.js') }}"></script>
    <script src="{{ secure_asset('assets/js/todolist.js') }}"></script>
    <script src="{{ secure_asset('assets/js/dashboard.js') }}"></script>
    <script src="{{ secure_asset('assets/js/sweetalert.min.js') }}"></script>
    {{-- <script src="{{ secure_asset('assets/js/delivery.js') }}" defer></script> --}}

    @if (session('success'))
        <script>
            swal({
                title: "Success",
                text: "{{ session('success') }}",
                icon: "success",
                button: "OK"
            });
        </script>
    @endif
</body>

</html>
