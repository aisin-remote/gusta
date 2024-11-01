@extends('layout.master')

@push('plugin-styles')
    <!-- {!! Html::style('/assets/plugins/plugin.css') !!} -->
@endpush

@section('content')
    <div class="row">
        <div class="col-md-12">
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
        </div>
    </div>

    <div class="row justify-content-between">
        <div class="col-lg-12 grid-margin">
            <main class="page payment-page">
                <section class="payment-form">
                    <div class="container">
                        <div class="card shadow-lg p-4 border-0 text-center">
                            <div class="card-body">
                                <h3 class="card-title mb-4">Scan QR Code</h3>
                                <form action="{{ route('qrScan.validate') }}" method="POST" enctype="multipart/form-data">
                                    {{ csrf_field() }}
                                    <div class="input-group">
                                        <input id="qrcode" type="password" class="form-control py-3 rounded-sm mr-2"
                                            placeholder="Enter QR Code..." name="qr_code" autofocus>
                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-primary rounded-sm px-4"
                                                type="button">Scan</button>
                                        </div>
                                    </div>
                                </form>
                                <p class="mt-3 text-muted">Please scan your QR code to proceed.</p>
                            </div>
                        </div>
                    </div>
                </section>
            </main>
        </div>

        <div id="qrDetails" class="col-12"></div>

        <!-- Modal for showing ticket details -->
        <div class="modal fade" id="ticketDetailsModal" tabindex="-1" role="dialog" aria-labelledby="ticketDetailsLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document" id="modalSize">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ticketDetailsLabel">Ticket Details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body d-flex flex-column justify-content-center align-items-center"
                        id="ticket-details-content">
                        <!-- Ticket details will be loaded here via AJAX -->
                    </div>
                    <div class="modal-footer">
                        <!-- Close button -->
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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

    <script>
        $(document).ready(function() {
            $('#qrcode').focus();

            // Automatically refocus on the QR code input field when clicking on the page
            $(document).on('click', function() {
                $('#qrcode').focus();
            });
        });
    </script>
@endpush
