<nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
  <div class="text-center navbar-brand-wrapper d-flex align-items-top justify-content-center">
    <a class="navbar-brand brand-logo" href="{{ url('/dashboard') }}">
      
      {{-- logo --}}
      <img src="{{ url('assets/images/aiia-logo.svg') }}" alt="logo" /> </a>
      
      <a class="navbar-brand brand-logo-mini" href="{{ url('/dashboard') }}">
        <img src="{{ url('assets/images/gusta-mini.svg') }}" alt="logo" /> 
      </a>
    </div>
    <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
      <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
        <span class="mdi mdi-menu"></span>
      </button>
      <ul class="navbar-nav navbar-nav-left header-links">
      </ul>
      <ul class="navbar-nav navbar-nav-right">
        <li class="nav-item dropdown d-none d-xl-inline-block">
          <a class="nav-link dropdown-toggle" id="UserDropdown" href="#" data-toggle="dropdown" aria-expanded="false">
            <span class="profile-text d-none d-md-inline-flex">Welcome, {{ auth()->user()->name }}</span>
            <img class="img-xs rounded-circle" src="{{ url('assets/images/faces/face8.jpg') }}" alt="Profile image">
          </a>
          <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown">
            <a class="dropdown-item p-0">
              <div class="d-flex border-bottom w-100 justify-content-center">
                <div class="py-3 px-4 d-flex align-items-center justify-content-center">
                  <i class="mdi mdi-bookmark-plus-outline mr-0 text-gray"></i>
                </div>
                <div class="py-3 px-4 d-flex align-items-center justify-content-center border-left border-right">
                  <i class="mdi mdi-account-outline mr-0 text-gray"></i>
                </div>
                <div class="py-3 px-4 d-flex align-items-center justify-content-center">
                  <i class="mdi mdi-alarm-check mr-0 text-gray"></i>
                </div>
              </div>
            </a>
            <a href="{{ route('password.index') }}" class="dropdown-item"> Change Password </a>
            <form action="{{ route('logout.auth') }}" method="POST">
              {{ csrf_field() }}
              <button type="submit" class="dropdown-item"> Sign Out </button>
            </form>
          </div>
        </li>
      </ul>
      <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
        <span class="mdi mdi-menu icon-menu"></span>
      </button>
    </div>
  </nav>