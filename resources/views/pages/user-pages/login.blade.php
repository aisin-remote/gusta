@extends('layout.auth')

@section('main')
    <div class="row flex-grow" id="card">
        <div class="col-lg-4 mx-auto text-center">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

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
                <h1 class="text-left" style="font-weight: bold;">Login to GUSTA</h1>
                <p class="text-left mb-4">Guest App for making appointments and delivery contractors</p>
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
                    <div class="form-group text-right">
                        <a href="{{ route('password.request') }}" class="forgot-password-link" data-toggle="tooltip"
                            data-placement="right" title="Click here to reset your password if you've forgotten it.">Forgot
                            Password?</a>
                    </div>
                    <div class="g-recaptcha" data-sitekey="{{ env('NOCAPTCHA_SITEKEY') }}"></div>
                    <div class="mt-3 mb-2">
                        <button type="submit"
                            class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">SIGN IN</button>
                    </div>
                    <small>
                        This site is protected by reCAPTCHA and the Google
                        <a href="https://policies.google.com/privacy">Privacy Policy</a> and
                        <a href="https://policies.google.com/terms">Terms of Service</a> apply.
                    </small>
                    <div class="text-center mt-4 font-weight-light"> Don't have an account? <a
                            href="{{ route('register.index') }}" class="text-primary">Create</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

<script>
    grecaptcha.ready(function() {
        grecaptcha.execute('{{ env('NOCAPTCHA_SITEKEY') }}', {
            action: 'login'
        }).then(function(token) {
            // Append token to the form
            var recaptchaResponse = document.createElement('input');
            recaptchaResponse.setAttribute('type', 'hidden');
            recaptchaResponse.setAttribute('name', 'g-recaptcha-response');
            recaptchaResponse.setAttribute('value', token);
            document.getElementById('loginForm').appendChild(recaptchaResponse);
        });
    });
</script>
