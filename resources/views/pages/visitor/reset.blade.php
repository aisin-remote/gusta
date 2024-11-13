@extends('layout.auth')

@section('main')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li><small>{{ $error }}</small></li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="card">
                    <div class="card-header">Reset Password</div>

                    <div class="card-body">
                        <form action="{{ route('password.hardReset') }}" method="POST">
                            {{ csrf_field() }}
                            <input type="hidden" name="token" value="{{ $token }}">
                            <div class="form-group">
                                <input id="email" type="email" class="form-control form-control-lg" name="email"
                                    value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus
                                    placeholder="Email address">
                            </div>

                            <div class="form-group">
                                <input type="password" class="form-control form-control-lg" placeholder="Password"
                                    name="password" id="password" required>
                                <!-- Real-time password validation -->
                                <div id="password-strength">
                                    <div class="row">
                                        <div class="col-4">
                                            <ul>
                                                <li id="length" class="invalid">At least 8 characters</li>
                                                <li id="uppercase" class="invalid">At least one uppercase letter</li>
                                            </ul>
                                        </div>
                                        <div class="col-4">
                                            <ul>
                                                <li id="number" class="invalid">At least one number</li>
                                                <li id="special" class="invalid">At least one special character</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div id="password-icon" class="text-right" style="display:none;">
                                        <i class="fas fa-check-circle" style="color: green;"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control form-control-lg" placeholder="Confirm Password"
                                    name="password_confirmation" required>
                            </div>
                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        Reset Password
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
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
