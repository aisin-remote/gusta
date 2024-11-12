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
            <div class="auth-form-light text-left p-5 card-animate login" id="login">
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
                        <input type="phone" class="form-control form-control-lg" placeholder="08xxx" name="phone_number"
                            required>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control form-control-lg" placeholder="Company" name="company"
                            required>
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control form-control-lg" placeholder="Password" name="password"
                            id="password" required>
                        <!-- Real-time password validation -->
                        <div id="password-strength">
                            <ul>
                                <li id="length" class="invalid">At least 8 characters</li>
                                <li id="uppercase" class="invalid">At least one uppercase letter</li>
                                <li id="number" class="invalid">At least one number</li>
                                <li id="special" class="invalid">At least one special character</li>
                            </ul>
                            <div id="password-icon" class="text-right" style="display:none;">
                                <i class="fas fa-check-circle" style="color: green;"></i>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control form-control-lg" placeholder="Confirm Password"
                            name="password_confirmation" required>
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
@push('custom-scripts')
    <script>
        $(document).ready(function() {
            // Real-time validation for password field
            $('#password').on('keyup', function() {
                var password = $(this).val();

                // Length check
                if (password.length >= 8) {
                    $('#length').removeClass('invalid').addClass('valid');
                } else {
                    $('#length').removeClass('valid').addClass('invalid');
                }

                // Uppercase letter check
                if (/[A-Z]/.test(password)) {
                    $('#uppercase').removeClass('invalid').addClass('valid');
                } else {
                    $('#uppercase').removeClass('valid').addClass('invalid');
                }

                // Number check
                if (/[0-9]/.test(password)) {
                    $('#number').removeClass('invalid').addClass('valid');
                } else {
                    $('#number').removeClass('valid').addClass('invalid');
                }

                // Special character check
                if (/[@$!%*?&]/.test(password)) {
                    $('#special').removeClass('invalid').addClass('valid');
                } else {
                    $('#special').removeClass('valid').addClass('invalid');
                }

                // Check if all criteria are met and enable submit button
                if ($('#password-strength .valid').length === 4) {
                    $('#password-strength').addClass('valid');
                    $('#password-icon').show(); // Show the check icon when all criteria are met
                } else {
                    $('#password-strength').removeClass('valid');
                    $('#password-icon').hide(); // Hide the check icon if criteria are not met
                }
            });
        });
    </script>
@endpush
