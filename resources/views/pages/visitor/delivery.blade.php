<!DOCTYPE html>
<html>

<head>
    <title>GUSTA</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="_token" content="{{ csrf_token() }}">

    <link rel="shortcut icon" href="{{ asset('/favicon.ico') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <!-- plugin css -->
    {!! Html::style('assets/plugins/@mdi/font/css/materialdesignicons.min.css') !!}
    {!! Html::style('assets/plugins/perfect-scrollbar/perfect-scrollbar.css') !!}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap4.min.css">

    <!-- end plugin css -->
    {!! Html::style('/assets/plugins/plugin.css') !!}

    <!-- common css -->
    {!! Html::style('css/app.css') !!}
    <!-- end common css -->

    @stack('style')
</head>

<body data-base-url="{{ url('/') }}" class="sidebar-icon-only">

    <div class="container-scroller" id="app">        
        <div class="page-body-wrapper w-100 vh-100">            
            <div class="main-panel w-100">
                <div class="content-wrapper">
                    <div class="row">
                        <div class="col-12"> <!-- Menghapus grid-margin -->
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title mb-5">Delivery Request Form</h4>
                                    <form action="{{ route('delivery.create') }}" method="POST" enctype="multipart/form-data"
                                        id="appointmentForm">
                                        {{ csrf_field() }}
                                        <div class="form-group row">
                                            <div class="col-md-4">
                                                <label for="inputPt" class="col-form-label">Company Name <small class="text-muted pl-0">/
                                                        Nama Perusahaan / 会社名</small></label>
                                            </div>
                                            <div class="col-md-8">
                                                <input type="text" name="company" id="inputPt" class="form-control mt-1" placeholder="Enter company name" />
                                                <small id="emailHelp" class="form-text text-muted">Enter your company name</small>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-4">
                                                <label for="inputName" class="col-form-label">Your Name <small class="text-muted pl-0">/
                                                        Nama Anda / 名前</small></label>
                                            </div>
                                            <div class="col-md-6">
                                                <div id="nameContainer">
                                                    <div class="input-group mb-2">
                                                        <input type="text" name="name[]" class="form-control" placeholder="Enter name" />
                                                    </div>
                                                </div>
                                                <small class="form-text text-muted">Add one or more names</small>
                                            </div>
                                            <div class="col-md-2">
                                                <button type="button" class="btn btn-success btn-block" id="addName"><i class="mdi mdi-plus"></i> Add More</button>
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
                                                <button type="submit" name="destination" value="Office" class="card text-white bg-danger border-0">
                                                    <div class="card-body text-center">
                                                        <h5 class="card-title text-white mb-0">Office</h5>
                                                    </div>
                                                </button>
                                            </div>
                                            <div class="col-md-4">
                                                <button type="submit" name="destination" value="Unit" class="card text-white bg-warning border-0">
                                                    <div class="card-body text-center">
                                                        <h5 class="card-title text-white mb-0">Unit</h5>
                                                    </div>
                                                </button>
                                            </div>
                                            <div class="col-md-4">
                                                <button type="submit" name="destination" value="Body" class="card text-white bg-success border-0">
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
                    <span class="text-muted d-block text-center text-sm-left d-sm-inline-block">Copyright ©  <a href="#">ITD</a> 2022 <i class="mdi mdi-heart text-danger"></i></span>
                    </div>
                </footer>
            </div>
        </div>
    </div>
    

    <!-- base js -->
    {!! Html::script('js/app.js') !!}
    {!! Html::script('assets/plugins/perfect-scrollbar/perfect-scrollbar.min.js') !!}
    <!-- end base js -->

    <!-- plugin js -->
    {!! Html::script('/assets/plugins/chartjs/chart.min.js') !!}
    {!! Html::script('/assets/plugins/jquery-sparkline/jquery.sparkline.min.js') !!}
    <!-- end plugin js -->

    <!-- common js -->
    {!! Html::script('assets/js/off-canvas.js') !!}
    {!! Html::script('assets/js/hoverable-collapse.js') !!}
    {!! Html::script('assets/js/misc.js') !!}
    {!! Html::script('assets/js/settings.js') !!}
    {!! Html::script('assets/js/todolist.js') !!}
    <!-- end common js -->
    {!! Html::script('/assets/js/dashboard.js') !!}
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    
    <!-- Custom Script untuk SweetAlert jika session success ada -->
    @if(session('success'))
    <script>
        swal({
            title: "Success",
            text: "{{ session('success') }}",
            icon: "success",
            button: "OK"
        });
    </script>
    @endif

    <script>
        document.getElementById("addName").addEventListener("click", function() {
            let container = document.getElementById("nameContainer");
            let inputGroup = document.createElement("div");
            inputGroup.classList.add("input-group", "mb-2");

            let input = document.createElement("input");
            input.type = "text";
            input.name = "name[]";
            input.classList.add("form-control");
            input.placeholder = "Enter name";

            let buttonGroup = document.createElement("div");
            buttonGroup.classList.add("input-group-append");

            let removeButton = document.createElement("button");
            removeButton.type = "button";
            removeButton.classList.add("btn", "btn-danger");
            removeButton.innerHTML = '<i class="mdi mdi-minus"></i>';
            removeButton.addEventListener("click", function() {
                container.removeChild(inputGroup);
            });

            buttonGroup.appendChild(removeButton);
            inputGroup.appendChild(input);
            inputGroup.appendChild(buttonGroup);
            container.appendChild(inputGroup);
        });
    </script>
</body>
</html>