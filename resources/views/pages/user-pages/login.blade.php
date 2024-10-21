@extends('layout.auth')

@section('main')
    <div class="row flex-grow" id="card">
        <div class="col-lg-4 mx-auto text-center">

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <small>{{ session('error') }}</small>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li><small>{{ $error }}</small></li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="auth-form-light text-center p-5 card-animate login" id="login">
                <img class="mb-4" src="{{ url('assets/images/aiia-logo.png') }}" alt="logo" width="200" />
                <form class="pt-3" action="{{ route('login.auth') }}" method="POST">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <input type="email" class="form-control form-control-lg" id="exampleInputEmail1"
                            placeholder="Email address" name="email" required>
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control form-control-lg" id="exampleInputPassword1"
                            placeholder="Password" name="password" required>
                    </div>
                    <div class="mt-3">
                        <button type="submit"
                            class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">SIGN
                            IN</button>
                    </div>
                    <div class="text-center mt-4 font-weight-light"> Don't have an account? <a
                            href="{{ route('register.index') }}" class="text-primary">Create</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
