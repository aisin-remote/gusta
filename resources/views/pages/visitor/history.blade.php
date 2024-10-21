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
                    <h4 class="card-title mb-5">Appointment List <small class="text-muted"> / Daftar Janji Temu /
                            チケット一覧</small>
                    </h4>
                    <table class="table table-responsive-lg" id="allTicket">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">PIC</th>
                                <th class="text-center">Visit Purpose <small class="text-muted"> / 訪問目的</small></th>
                                <th class="text-center">Visit Date <small class="text-muted"> / 訪問日</small></th>
                                <th class="text-center">Status</th>
                                <th class="text-center">QR Code</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            @if (!$appointments->isEmpty())
                                @foreach ($appointments as $appointment)
                                    <tr>
                                        <td class="display-4">{{ $loop->iteration }} </td>
                                        <td class="display-4">{{ $appointment->pic->name }}</td>
                                        <td class="display-4">{{ $appointment->purpose }}</td>
                                        <td class="display-4">
                                            {{ Carbon\Carbon::parse($appointment->date)->toFormattedDateString() }}</td>

                                        @if ($appointment->pic_approval === 'pending' && $appointment->dh_approval === 'pending')
                                            <td>
                                                <span
                                                    class="badge badge-pill badge-warning p-2 text-light">{{ $appointment->dh_approval }}</span>
                                            </td>
                                            <td>
                                                <button class="btn btn-icons btn-inverse-info" data-toggle="tooltip"
                                                    title="QR disable" disabled>
                                                    <i class="mdi mdi-qrcode"></i>
                                                </button>
                                            </td>
                                        @elseif($appointment->pic_approval === 'approved' && $appointment->dh_approval === 'pending')
                                            <td>
                                                <span class="badge badge-pill badge-warning p-2 text-light">Pending Dept.
                                                    Head</span>
                                            </td>
                                            <td>
                                                <button class="btn btn-icons btn-inverse-info" data-toggle="tooltip"
                                                    title="QR disable" disabled>
                                                    <i class="mdi mdi-qrcode"></i>
                                                </button>
                                            </td>
                                        @elseif($appointment->pic_approval === 'approved' && $appointment->dh_approval === 'approved')
                                            <td>
                                                <span
                                                    class="badge badge-pill badge-success p-2 text-light">{{ $appointment->dh_approval }}
                                                </span>
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
                                            <td><span
                                                    class="badge badge-pill badge-danger p-2 text-light">{{ $appointment->dh_approval }}</span>
                                            </td>
                                            <td><button hidden>QR code</button></td>
                                        @endif
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
                    [0, "desc"]
                ],
                "lengthChange": false
            });
        });
    </script>
@endpush
