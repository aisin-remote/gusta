@extends('layout.master')

@push('plugin-styles')
    <!-- {!! Html::style('/assets/plugins/plugin.css') !!} -->
@endpush

@section('content')
    <div class="row">
        <div class="col-lg-12 grid-margin">
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
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-5">Appointment History <small class="text-muted"> / Histori Janji Temu /
                            予約履歴
                        </small>
                    </h4>
                    <table class="table table-responsive-lg table-hover w-100" id="allTicket">
                        <thead>
                            <tr>
                                <th class="text-center">PIC</th>
                                <th class="text-center">Phone Number</th>
                                <th class="text-center">Destination Company</th>
                                <th class="text-center">Visit Purpose</th>
                                <th class="text-center">Visit Date</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">QR Code</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            @if (!$appointments->isEmpty())
                                @foreach ($appointments as $appointment)
                                    <tr>
                                        <td class="display-4">{{ $appointment->pic->name }}</td>
                                        <td class="display-4">{{ $appointment->pic->phone_number }}</td>
                                        <td class="display-4">{{ $appointment->pic->company }}</td>
                                        <td class="display-4">{{ $appointment->purpose }}</td>
                                        <td class="display-4">
                                            {{ Carbon\Carbon::parse($appointment->date)->toFormattedDateString() }}</td>

                                        @if ($appointment->pic_approval === 'pending' && $appointment->dh_approval === 'pending')
                                            <td>
                                                <h5>
                                                    <span class="badge badge-warning p-2 text-light">Waiting
                                                        Approval</span>
                                                </h5>
                                            </td>
                                            <td>
                                                <button class="btn btn-icons btn-inverse-info" data-toggle="tooltip"
                                                    title="QR disable" disabled>
                                                    <i class="mdi mdi-qrcode"></i>
                                                </button>
                                            </td>
                                        @elseif($appointment->pic_approval === 'approved' && $appointment->dh_approval === 'pending')
                                            <td>
                                                <h5>
                                                    <span class="badge badge-warning p-2 text-light">Waiting
                                                        Approval</span>
                                                </h5>
                                            </td>
                                            <td>
                                                <button class="btn btn-icons btn-inverse-info" data-toggle="tooltip"
                                                    title="QR disable" disabled>
                                                    <i class="mdi mdi-qrcode"></i>
                                                </button>
                                            </td>
                                        @elseif($appointment->pic_approval === 'approved' && $appointment->dh_approval === 'approved')
                                            <td>
                                                <h5>
                                                    <span
                                                        class="badge badge-success p-2 text-light">{{ $appointment->dh_approval }}
                                                    </span>
                                                </h5>
                                            </td>
                                            @php

                                                $current_date = date('Y-m-d');
                                                $end_date = date($appointment->date);

                                            @endphp
                                            @if ($current_date > $end_date)
                                                <td>
                                                    <button data-toggle="modal"
                                                        data-target="#expiredModal-{{ $appointment->id }}"data-toggle="tooltip"
                                                        title="QR Code" type="submit"
                                                        class="btn btn-icons btn-inverse-info">
                                                        <i class="mdi mdi-qrcode"></i>
                                                    </button>
                                                </td>
                                            @else
                                                <td>
                                                    <button data-toggle="modal"
                                                        data-target="#demoModal-{{ $appointment->id }}"data-toggle="tooltip"
                                                        title="QR Code" type="submit"
                                                        class="btn btn-icons btn-inverse-info">
                                                        <i class="mdi mdi-qrcode"></i>
                                                    </button>
                                                </td>
                                            @endif
                                        @else
                                            <td>
                                                <h5>
                                                    <span
                                                        class="badge badge-danger p-2 text-light">{{ $appointment->dh_approval }}</span>
                                                </h5>
                                            </td>
                                            <td>
                                                <button class="btn btn-icons btn-inverse-info" data-toggle="tooltip"
                                                    title="Rejected" disabled>
                                                    <i class="mdi mdi-qrcode"></i>
                                                </button>
                                            </td>
                                        @endif
                                        <td>
                                            <button data-toggle="modal" class="btn btn-icons btn-inverse-info openModalBtn"
                                                data-appointment-id="{{ $appointment->id }}" data-toggle="tooltip"
                                                title="Detail">
                                                <i class="mdi mdi-information"></i>
                                            </button>


                                            @if ($appointment->pic_approval == 'pending' || $appointment->dh_approval == 'pending')
                                                <a href="{{ route('appointment.edit', $appointment->id) }}" type="submit"
                                                    class="btn btn-icons btn-inverse-warning" data-toggle="tooltip"
                                                    title="edit"
                                                    style="opacity: 0.5; cursor: not-allowed; pointer-events: none;">
                                                    <i class="mdi mdi-pencil"></i>
                                                </a>
                                                <a href="javascript:void(0);"
                                                    class="btn btn-icons btn-inverse-danger deleteButton"
                                                    data-toggle="tooltip" data-appointment-id="{{ $appointment->id }}"
                                                    title="delete"
                                                    style="opacity: 0.5; cursor: not-allowed; pointer-events: none;">
                                                    <i class="mdi mdi-delete"></i>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>

                    <!-- Modal -->
                    @foreach ($appointments as $appointment)
                        <div class="modal fade auto-off" id="demoModal-{{ $appointment->id }}" tabindex="-1"
                            role="dialog" aria-labelledby="demoModal-{{ $appointment->id }}" aria-hidden="true">
                            <div class="modal-dialog animated zoomInDown modal-dialog-centered" role="document">
                                <div class="modal-content">

                                    <div class="container-fluid">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        <div class="row">
                                            <div class="col-md-12 text-center py-5 px-sm-5 ">
                                                <h2>Your Barcode is Here!</h2>
                                                <p class="text-muted">show this barcode to the security guard</p>
                                                <span>{!! \QrCode::size(200)->generate($appointment->qr_code) !!}</span>
                                                <form class="pt-5">
                                                    <button type="submit" class="btn btn-primary" data-dismiss="modal"
                                                        aria-label="Close">close modal</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <!-- Modal Ends -->

                    <!-- Modal Expired-->
                    @foreach ($appointments as $appointment)
                        <div class="modal fade auto-off" id="expiredModal-{{ $appointment->id }}" tabindex="-1"
                            role="dialog" aria-labelledby="demoModal-{{ $appointment->id }}" aria-hidden="true">
                            <div class="modal-dialog animated zoomInDown modal-dialog-centered" role="document">
                                <div class="modal-content">

                                    <div class="container-fluid">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        <div class="row">
                                            <div class="col-md-12 text-center py-5 px-sm-5 ">
                                                <h2>Im sorry!</h2>
                                                <p class="text-muted pt-2">Your barcode has expired, please make another
                                                    ticket</p>
                                                <img src="{{ asset('assets/images/expired/expire.png') }}" alt=""
                                                    width="200">
                                                <form class="pt-5">
                                                    <button type="submit" class="btn btn-primary" data-dismiss="modal"
                                                        aria-label="Close">close modal</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <!-- Modal Ends -->

                    <!-- Detail Modal Template -->
                    <div class="modal fade" id="detailModal" data-backdrop="static" data-keyboard="false"
                        tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-body">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Ticket Details</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="px-4 py-1" id="modalContent">
                                        <!-- Content will be loaded here -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Confirmation Modal -->
                    <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" role="dialog"
                        aria-labelledby="deleteModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    Are you sure you want to delete this appointment?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                    <form id="deleteForm" method="POST">
                                        {{ csrf_field() }}
                                        <button type="submit" class="btn btn-danger">Confirm Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('plugin-scripts')
    {!! Html::script('/assets/plugins/chartjs/chart.min.js') !!}
    {!! Html::script('/assets/plugins/jquery-sparkline/jquery.sparkline.min.js') !!}
@endpush

@push('custom-scripts')
    {!! Html::script('/assets/js/dashboard.js') !!}
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap4.min.js"></script>

    <script>
        $(document).ready(function() {

            $(function() {
                $('[data-toggle="tooltip"]').tooltip()
            });

            $('#allTicket').DataTable({
                //order by desc
                "order": [
                    [5, "desc"]
                ],
                "lengthChange": false
            });

            $('.deleteButton').on('click', function() {
                // Get the appointment ID from the button's data attribute
                var appointmentId = $(this).data('appointment-id');

                // Set the form action to the delete route with the appointment ID
                $('#deleteForm').attr('action', '/appointment/' + appointmentId + '/destroy');

                // Show the confirmation modal
                $('#deleteConfirmationModal').modal('show');
            });

            $('.openModalBtn').on('click', function() {
                // Get the appointment ID from the button's data attribute
                var appointmentId = $(this).data('appointment-id');

                // Use AJAX to fetch the appointment details
                $.ajax({
                    url: '/appointment/modal/' + appointmentId,
                    type: 'GET',
                    success: function(data) {
                        console.log(data);
                        // Start generating modal content
                        var modalContent = `
                        <div class="d-flex justify-content-between pt-4">
                            <span class="font-weight-bold h4">Plan Visit</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Visit Purpose</span>
                            <span class="font-weight-bold">${data.purpose}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Visit Date</span>
                            <span class="font-weight-bold">${data.formatted_date}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Visit Time</span>
                            <span class="font-weight-bold">${data.formatted_time}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Total Visitor</span>
                            <span class="font-weight-bold">${data.guests.length}</span>
                        </div>
                        <hr class="new1">
                        <div class="d-flex justify-content-between">
                            <span class="font-weight-bold h4">Visitor Data</span>
                        </div>
                        <div class="d-flex justify-content-between mb-4">
                            <span class="text-muted">Visitor Company</span>
                            <span class="font-weight-bold">${data.user.company}</span>
                        </div>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Name</th>
                                    <th scope="col" class="text-right">ID Card</th>
                                </tr>
                            </thead>
                            <tbody>`;

                        // Loop through guests to populate the table rows
                        data.guests.forEach((guest, index) => {
                            modalContent += `
                            <tr>
                                <th scope="row">${index + 1}</th>
                                <td>${guest.name}</td>
                                <td class="text-right">${guest.id_card}</td>
                            </tr>`;
                        });

                        modalContent += `
                            </tbody>
                        </table>
                        <hr class="new1">
                        <div class="d-flex justify-content-between">
                            <span class="font-weight-bold">PIC</span>
                            <span class="font-weight-bold">${data.pic.name}</span>
                        </div>`;

                        // Add rejection reasons if they are present
                        if (data.rejection_reason.note) {
                            modalContent += `
                            <hr class="new1">
                            <div class="d-flex justify-content-center mb-3">
                            <button class="btn btn-inverse-danger w-100 d-flex justify-content-between align-items-center p-3">
                                <span class="font-weight-bold text-left">Reject Reason</span>
                                <span class="font-weight-bold text-right">${data.rejection_reason.note}</span>
                            </button>`;
                        }

                        // Insert the generated content into the modal
                        $('#modalContent').html(modalContent);

                        // Show the modal
                        $('#detailModal').modal('show');
                    },
                    error: function(xhr) {
                        console.error('Error loading appointment details:', xhr);
                    }
                });

            });
        });
    </script>
@endpush
