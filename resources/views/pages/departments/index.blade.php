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
                            <h4 class="card-title mb-5">Departments</h4>
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

                    {{-- Tombol untuk menambah departemen --}}
                    <button class="btn btn-primary mb-4" id="addDepartmentBtn">+ Add Department</button>

                    {{-- Form untuk tambah departemen, disembunyikan terlebih dahulu --}}
                    <form id="addDepartmentsForm" action="{{ route('department.store') }}" method="POST">
                        {{ csrf_field() }}
                        <div id="departmentInputsContainer">
                            {{-- Label untuk Department Name dan Code, hanya muncul sekali --}}
                            <div class="form-group" id="departmentNameLabel" style="display: none;">
                                <label for="name"></label>
                            </div>
                            {{-- Form input departemen akan muncul di sini --}}
                        </div>
                        {{-- Tombol Save All, disembunyikan terlebih dahulu --}}
                        <button type="submit" class="btn btn-success mt-3" id="saveAllBtn" style="display: none;">Save
                            All</button>
                    </form>

                    {{-- Tabel dengan DataTables --}}
                    <table class="table table-responsive-lg table-hover w-100" id="allTicket">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Code</th>
                                <th class="text-center">Name</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            @foreach ($departments as $department)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $department->code }}</td>
                                    <td>{{ $department->name }}</td>
                                    <td>
                                        <!-- Action buttons -->
                                        <a href="#" class="btn btn-warning btn-sm mr-2" data-toggle="modal"
                                            data-target="#editDepartmentModal" data-id="{{ $department->id }}"
                                            data-code="{{ $department->code }}" data-name="{{ $department->name }}"><i
                                                class="mdi mdi-pencil"></i>Edit</a>
                                        <form action="{{ route('department.destroy', $department->id) }}" method="POST"
                                            class="d-inline-block">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button type="submit" class="btn btn-danger btn-sm"
                                                onclick="return confirm('Are you sure you want to delete this department?')"><i
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
    @foreach ($departments as $department)
        <div class="modal fade" id="editDepartmentModal" tabindex="-1" role="dialog"
            aria-labelledby="editDepartmentModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editDepartmentModalLabel">Edit Department</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('department.update', $department->id) }}" method="POST">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label for="editCode">Department Code</label>
                                <input type="text" class="form-control" id="editCode" name="code"
                                    value="{{ old('code', $department->code) }}" required readonly>
                            </div>

                            <div class="form-group">
                                <label for="editName">Department Name</label>
                                <input type="text" class="form-control" id="editName" name="name"
                                    value="{{ old('name', $department->name) }}" required>
                            </div>

                            <button type="submit" class="btn btn-primary">Update Department</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    <!-- Modal Edit Department -->
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
            });

            // Menambahkan form input baru setiap kali tombol diklik
            $('#addDepartmentBtn').click(function() {
                var newDepartmentInput = `
                    <div class="form-group mt-3 d-flex align-items-center">
                        <input type="text" class="form-control" name="codes[]" placeholder="Code" required style="width: 150px;">
                        <input type="text" class="form-control ml-3" name="departments[]" placeholder="Name" required style="flex-grow: 1;">
                        <button type="button" class="btn btn-danger btn-sm ml-3" id="removeDepartmentBtn">&times;</button>
                    </div>
                `;
                $('#departmentInputsContainer').append(newDepartmentInput);

                // Menampilkan label "Department Name" jika ada input
                $('#departmentNameLabel').show();

                // Menampilkan tombol Save All setelah setidaknya satu form input ditambahkan
                $('#saveAllBtn').show();
            });

            // Menghapus form input departemen saat tombol "X" diklik
            $(document).on('click', '#removeDepartmentBtn', function() {
                $(this).closest('.form-group').remove();

                // Menyembunyikan tombol Save All jika tidak ada form input yang tersisa
                if ($('#departmentInputsContainer').children('.form-group').length === 0) {
                    $('#saveAllBtn').hide();
                    // Menyembunyikan label "Department Name" jika tidak ada input yang tersisa
                    $('#departmentNameLabel').hide();
                }
            });
            $(document).on('click', '[data-toggle="modal"]', function() {
                var departmentId = $(this).data('id');
                var departmentCode = $(this).data('code');
                var departmentName = $(this).data('name');

                // Set data ke dalam form modal
                $('#editDepartmentForm').attr('action', '/department/' +
                    departmentId); // Update URL action form sesuai dengan id departemen
                $('#editCode').val(departmentCode); // Set nilai kode
                $('#editName').val(departmentName); // Set nilai nama
            });


        });
    </script>
@endpush
