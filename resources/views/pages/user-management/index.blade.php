@extends('layout.master')

@push('plugin-styles')
    <!-- CSS untuk DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap4.min.css">
@endpush

@section('content')
    <div class="row">
        <div class="col-lg-12 grid-margin">
            <div class="card">
                <div class="card-body p-5">
                    <div class="row">
                        <div class="col-10">
                            <h4 class="card-title mb-5">User Management</h4>
                        </div>
                    </div>
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    {{-- Tabel dengan DataTables --}}
                    <table class="table table-responsive-lg table-hover w-100" id="allTicket">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Name</th>
                                <th class="text-center">Email</th>
                                <th class="text-center">Phone Number</th>
                                <th class="text-center">Department</th>
                                <th class="text-center">Company</th>
                                <th class="text-center">Role</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->phone_number }}</td>
                                    <td>{{ $user->has_department->name ?? '-' }}</td>
                                    <td>{{ $user->company }}</td>
                                    <td>{{ $user->role }}</td>
                                    <td>
                                        <!-- Action buttons -->
                                        <a href="{{ route('admin.user.edit', $user->id) }}" class="btn btn-warning btn-sm mr-2"><i
                                                class="mdi mdi-pencil"></i>Edit</a>
                                        <form action="{{ route('admin.user.destroy', $user->id) }}" method="POST"
                                            class="d-inline-block">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button type="submit" class="btn btn-danger btn-sm"
                                                onclick="return confirm('Are you sure you want to delete this user?')"><i
                                                    class="mdi mdi-delete"></i>Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('plugin-scripts')
    <!-- JS untuk DataTables -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap4.min.js"></script>
@endpush

@push('custom-scripts')
    <script>
        $(document).ready(function() {
            $('#allTicket').DataTable({
                "order": [
                    [0, 'asc']
                ],
                "lengthChange": false,
                "paging": true,
                "searching": true,
                "info": true,
                "dom": '<"d-flex justify-content-between mb-2"<"btn-container"><"search-container"f>>t<"d-flex justify-content-between"ip>',
                initComplete: function() {
                    // Tambahkan tombol ke dalam container
                    $("div.btn-container").html(
                        `<a href="{{ route('admin.user.create') }}" class="btn btn-primary">+ Tambah User</a>`
                    );
                }
            });
        });
    </script>
@endpush
