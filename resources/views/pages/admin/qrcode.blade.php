@extends('layout.master')

@push('plugin-styles')
    <!-- {!! Html::style('/assets/plugins/plugin.css') !!} -->
@endpush

@section('content')
    <div class="row">
        <div class="col-md-12">
            @if (session('scanned'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('scanned') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
        </div>
    </div>
    <div class="row justify-content-between">
        <div class="col-lg-6 grid-margin">
            <main class="page payment-page">
                <section class="payment-form dark">
                    <div class="container">
                        <form action="{{ route('qrScan.index') }}" method="POST">
                            {{ csrf_field() }}
                            <div class="products text-center">
                                <h3 class="title">Scan QR Code</h3>
                                <input id="qrcode" type="text" class="form-control my-4 py-4" placeholder="QR Code..."
                                    name="qrcode" autofocus>
                            </div>
                        </form>
                    </div>
                </section>
            </main>
        </div>
        <div class="col-lg-6 grid-margin">
            <main class="page payment-page">
                <section class="payment-form dark">
                    <div class="container">
                        <form>
                            <div class="products">
                                <h3 class="title">Ticket Details</h3>
                                @if (empty($appointments))
                                    <div class="text-center pt-3">
                                        <span class="">No barcode has been scanned</span>
                                    </div>
                                @else
                                    @if ($appointments->pic_approval === 'approved' && $appointments->dh_approval === 'approved')
                                        <?php $is_valid = false; ?>
                                        @if ($status == 'sukses_in')
                                            <?php $is_valid = true; ?>
                                            <div class="alert alert-success text-center" role="alert">
                                                Ticket valid! Visitor inside AIIA
                                            </div>
                                        @elseif($status == 'sukses_out')
                                            <?php $is_valid = true; ?>
                                            <div class="alert alert-success text-center" role="alert">
                                                Ticket valid! Visitor outside AIIA
                                            </div>
                                        @elseif($status == 'gagal_notyet')
                                            <div class="alert alert-danger text-center" role="alert">
                                                Not yet time to visit!
                                            </div>
                                        @elseif($status == 'gagal_expired')
                                            <div class="alert alert-danger text-center" role="alert">
                                                Ticket expired!
                                            </div>
                                        @endif
                                        @if($is_valid)
                                            <img class="rounded mx-auto d-block mb-4 img-fluid"
                                                src="{{ asset('uploads/doc/' . $appointments->doc) }}" width="400"
                                                height="200">
                                            <div class="d-flex justify-content-between">
                                                <span class="font-weight-bold">Personal Data</span>
                                            </div>

                                            <div class="d-flex justify-content-between">
                                                <span class="text-muted">Visitor Name</span>
                                                <span class="font-weight-bold">{{ $appointments->name }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <span class="text-muted">Visitor Company</span>
                                                <span class="font-weight-bold">{{ $appointments->user->company }}</span>
                                            </div>

                                            <div class="d-flex justify-content-between mt-4">
                                                <span class="font-weight-bold">Visit Plan</span>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <span class="text-muted">Visit Purpose</span>
                                                <span class="font-weight-bold">{{ $appointments->purpose }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <span class="text-muted">Visit Frequency</span>
                                                <span class="font-weight-bold">{{ $appointments->frequency }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <span class="text-muted">Visit Date</span>
                                                <span
                                                    class="font-weight-bold">{{ Carbon\Carbon::parse($appointments->start_date)->toFormattedDateString() }}
                                                    -
                                                    {{ Carbon\Carbon::parse($appointments->end_date)->toFormattedDateString() }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <span class="text-muted">Visit Time</span>
                                                <span class="font-weight-bold">{{ $appointments->time }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <span class="text-muted">Total Visitor</span>
                                                <span class="font-weight-bold">{{ $appointments->guest }}</span>
                                            </div>

                                            <div class="item mt-0">
                                            </div>
                                            <div class="total">PIC<span
                                                    class="price">{{ $appointments->pic->name }}</span>
                                            </div>
                                        @endif
                                    @elseif ($appointments === null)
                                        <div class="alert alert-danger" role="alert">
                                            Ticket Invalid!
                                        </div>
                                    @else
                                        <div class="alert alert-danger" role="alert">
                                            Ticket Invalid!
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </form>
                    </div>
                </section>
            </main>
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
            $(document).on('click', function() {
                $('#qrcode').focus();
            });
        });
    </script>
@endpush
