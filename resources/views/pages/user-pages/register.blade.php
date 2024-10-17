<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>GUSTA</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="../../vendors/simple-line-icons/css/simple-line-icons.css">
    <link rel="stylesheet" href="../../vendors/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" href="../../vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet"  href="{{ asset('css/style.css') }}">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <!-- endinject -->
    <!-- Layout styles -->
    <link rel="stylesheet" href="../../css/style.css" <!-- End layout styles -->
    <link rel="shortcut icon" href="../../images/favicon.png" />
  </head>
  <body>
    <div class="container-scroller">
      <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper d-flex align-items-center auth ">
          <div class="row flex-grow mb-5">
            <div class="col-lg-4 mx-auto">
                @if ($errors->any())
                  <div class="alert alert-danger">
                      <ul>
                          @foreach ($errors->all() as $error)
                              <li><small>{{ $error }}</small></li>
                          @endforeach
                      </ul>
                  </div>
                @endif
              <div class="auth-form-light text-left p-5 card-animate" id="login">
                <h4>New here?</h4>
                <h6 class="font-weight-light pb-4">Signing up is easy. It only takes a few steps</h6>
                <form action="{{ route('register.store') }}" method="POST">
                  {{ csrf_field() }}
                  <div class="form-group">
                    <input type="text" class="form-control form-control-lg" placeholder="Name" name="name" required autofocus>
                  </div>
                  <div class="form-group">
                    <input type="email" class="form-control form-control-lg" placeholder="Email Address" name="email" required>
                  </div>
                  <div class="form-group">
                    <input type="text" class="form-control form-control-lg" placeholder="Company" name="company"  required>
                  </div>
                  <div class="form-group">
                    <input type="password" class="form-control form-control-lg" placeholder="Password" name="password" required>
                  </div>
                  <div class="mt-3">
                    <button type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">SIGN UP</button>
                  </div>
                  <div class="text-center mt-4 font-weight-light"> Already have an account? <a href="{{ route('login') }}" class="text-primary">Login</a>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
        <!-- content-wrapper ends -->
      </div>
      <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="../../vendors/js/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="../../js/off-canvas.js"></script>
    <script src="../../js/misc.js"></script>
    <!-- endinject -->
    <script>
      var login = document.getElementById("login");
  
      window.addEventListener("load", (event) => {
          login.classList.add("from-top");
      });
    </script>
  </body>
</html>