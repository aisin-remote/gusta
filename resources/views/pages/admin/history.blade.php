@extends('layout.master')

@push('plugin-styles')
    <!-- {!! Html::style('/assets/plugins/plugin.css') !!} -->
@endpush

@section('content')
    <div class="row">
        <div class="col-lg-12 grid-margin">

            @if (session()->has('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @elseif (session()->has('reject'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('reject') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-5">Ticket History <small class="text-muted"> / チケット履歴</small></h4>
                    {{-- <div class="table-responsive"> --}}
                    <table class="table table-responsive-lg table-hover w-100" id="allTicket">
                        <thead>
                            <tr>
                                <th class="text-center">PIC</th>
                                <th class="text-center">Visitor Company <small class="text-muted"> / 合計ゲスト</small></th>
                                <th class="text-center">Total Guest <small class="text-muted"> / 会社</small></th>
                                <th class="text-center">Visit Purpose <small class="text-muted"> / 訪問目的</small></th>
                                <th class="text-center">Visit Date <small class="text-muted"> / 訪問日</small></th>
                                <th class="text-center">Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            @if (!$appointments->isEmpty())
                                @foreach ($appointments as $appointment)
                                    <tr>
                                        <td class="display-4">{{ $appointment->pic->name }}</td>
                                        <td class="display-4">{{ $appointment->user->company }}</td>
                                        <td class="display-4">{{ count($appointment->guests) }}</td>
                                        <td class="display-4">{{ $appointment->purpose }}</td>
                                        <td class="display-4">
                                            {{ Carbon\Carbon::parse($appointment->date)->toFormattedDateString() }}</td>
                                        {{-- <td class="display-4">{{ $appointment->guest }}</td> --}}

                                        @if ($appointment->pic_approval == 'pending' && $appointment->dh_approval == 'pending')
                                            <td class="display-4">
                                                <h4>
                                                    <span class="badge badge-secondary">Waiting Approval</span>
                                                </h4>
                                            </td>
                                        @elseif($appointment->pic_approval == 'approved' && $appointment->dh_approval == 'pending')
                                            <td class="display-4">
                                                <h4>
                                                    <span class="badge badge-secondary">Waiting Approval</span>
                                                </h4>
                                            </td>
                                        @elseif($appointment->pic_approval == 'approved' && $appointment->dh_approval == 'approved')
                                            <td class="display-4">
                                                <h4>
                                                    <span class="badge badge-success p-2 text-light">Approved</span>
                                                </h4>
                                            </td>
                                        @elseif($appointment->pic_approval == 'rejected' && $appointment->dh_approval == 'rejected')
                                            <td class="display-4">
                                                <h4>
                                                    <span class="badge badge-danger p-2 text-light">Rejected</span>
                                                </h4>
                                            </td>
                                        @elseif($appointment->pic_approval == 'approved' && $appointment->dh_approval == 'rejected')
                                            <td class="display-4">
                                                <h4>
                                                    <span class="badge badge-danger p-2">Rejected</span>
                                                </h4>
                                            </td>
                                        @endif

                                        <td class="display-4">
                                            {{-- detail --}}
                                            <button data-toggle="modal" class="btn btn-icons btn-inverse-info openModalBtn"
                                                data-appointment-id="{{ $appointment->id }}" data-toggle="tooltip"
                                                title="Detail">
                                                <i class="mdi mdi-information"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                    {{-- </div> --}}

                    <!-- Detail Modal Template -->
                    <div class="modal fade" id="detailModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
                        aria-labelledby="staticBackdropLabel" aria-hidden="true">
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
                    <!-- Modal Ends -->

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
                order: [
                    [0, "desc"]
                ],
                "lengthChange": false
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
