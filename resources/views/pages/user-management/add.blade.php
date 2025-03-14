@extends('layout.master')
@push('style')
    <style>
        .position-relative {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            right: 20px;
            top: 30%;
            transform: translateY(-50%);
            cursor: pointer;
        }

        input[type="password"] {
            padding-right: 30px;
            /* Memberikan ruang untuk icon eye */
        }
    </style>
@endpush
@section('content')
    <div class="row">
        <div class="col-lg-12 grid-margin">
            <div class="card">
                <div class="card-body p-5">
                    <div class="row">
                        <div class="col-10">
                            <h4 class="card-title mb-5">Add User</h4>
                        </div>
                    </div>
                    @if (session('errors'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul>
                                @foreach (session('errors')->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    <!-- Form untuk Edit User -->
                    <form action="{{ route('admin.user.store') }}" method="POST">
                        {{ csrf_field() }}
                        <div class="form-group row">
                            <label for="name" class="col-sm-2 col-form-label">Name</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="name" name="name" placeholder="name"
                                    required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-sm-2 col-form-label">Email</label>
                            <div class="col-sm-10">
                                <input type="email" class="form-control" id="email" name="email"
                                    placeholder="xxxx@gmail.com" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="phone_number" class="col-sm-2 col-form-label">Phone Number</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="phone_number" name="phone_number"
                                    placeholder="08xxxx">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="department" class="col-sm-2 col-form-label">Department</label>
                            <div class="col-sm-10">
                                <select class="form-control" id="departments" name="departments" required>
                                    <option value="" selected disabled>Select Department</option>
                                    @foreach ($departments as $department)
                                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="company" class="col-sm-2 col-form-label">Company</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="company" name="company"
                                    placeholder="company">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="role" class="col-sm-2 col-form-label">Role</label>
                            <div class="col-sm-10">
                                <select class="form-control" id="role" name="role" required>
                                    <option value="" selected disabled>Select Role</option>
                                    <option value="admin">Admin</option>
                                    <option value="approver">Approver
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-sm-2 col-form-label">Password</label>
                            <div class="col-sm-10 position-relative">
                                <input type="password" class="form-control" id="password" name="password"
                                    placeholder="*******" required>
                                <span class="toggle-password" onclick="togglePasswordVisibility('password')">
                                    <i class="mdi mdi-eye"></i>
                                </span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password_confirmation" class="col-sm-2 col-form-label">Confirm Password</label>
                            <div class="col-sm-10 position-relative">
                                <input type="password" class="form-control" id="password_confirmation"
                                    name="password_confirmation" placeholder="*******" required>
                                <span class="toggle-password" onclick="togglePasswordVisibility('password_confirmation')">
                                    <i class="mdi mdi-eye"></i>
                                </span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-10 offset-sm-2">
                                <button type="submit" class="btn btn-primary">Add User</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('custom-scripts')
    <script>
        function togglePasswordVisibility(inputId) {
            const input = document.getElementById(inputId);
            const icon = input.nextElementSibling.querySelector('i');

            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove("mdi-eye");
                icon.classList.add("mdi-eye-off");
            } else {
                input.type = "password";
                icon.classList.remove("mdi-eye-off");
                icon.classList.add("mdi-eye");
            }
        }
    </script>
@endpush
