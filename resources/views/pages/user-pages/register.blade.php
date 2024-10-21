@extends('layout.auth')

@section('main')
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
                        <input type="text" class="form-control form-control-lg" placeholder="Name" name="name" required
                            autofocus>
                    </div>
                    <div class="form-group">
                        <input type="email" class="form-control form-control-lg" placeholder="Email Address"
                            name="email" required>
                    </div>
                    <div class="form-group">
                        <input type="phone" class="form-control form-control-lg" placeholder="08xxx" name="email"
                            required>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control form-control-lg" placeholder="Company" name="company"
                            required>
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control form-control-lg" placeholder="Password" name="password"
                            required>
                    </div>
                    <div class="mt-3">
                        <button type="submit"
                            class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">SIGN UP</button>
                    </div>
                    <div class="text-center mt-4 font-weight-light"> Already have an account? <a href="{{ route('login') }}"
                            class="text-primary">Login</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
