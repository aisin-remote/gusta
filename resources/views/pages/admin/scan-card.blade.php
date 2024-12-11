@extends('layout.master')

@push('plugin-styles')
    <!-- {!! Html::style('/assets/plugins/plugin.css') !!} -->
    <style>
        .card-success {
            background-color: #d4edda !important;
            /* Bootstrap's light green for success */
            border-color: #c3e6cb !important;
        }
    </style>
@endpush

@section('content')
    <div class="d-flex mb-3">
        <a href="/qrScanView" class="btn btn-primary p-3">
            ← Back
        </a>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div id="dynamic-alert"></div>
        </div>
    </div>

    <div class="alert alert-info text-center my-4">
        <strong>{{ $checkin_status === 'sukses_in' ? 'Current Visitor Status: In' : 'Current Visitor Status: Out' }}</strong>
    </div>

    <div class="row">
        @foreach ($details as $index => $guestCard)
            <div class="col-md-4">
                <div class="card mb-4 shadow-sm" id="card-{{ $index }}">
                    <div class="card-header text-center">
                        <strong>{{ $guestCard['guest_name'] }}</strong>
                    </div>
                    <div class="card-body text-center">
                        <img src="{{ $guestCard['card_image_url'] }}" class="img-fluid mb-3" alt="Card Image"
                            style="width: 150px;">
                        <p><small><strong>ID Card:</strong> {{ $guestCard['guest_id_card'] }}</small></p>
                        <input type="hidden" name="card_id" value="{{ $guestCard['card_id'] }}">
                        <input type="hidden" name="guest_id" value="{{ $guestCard['guest_id'] }}">
                        <input type="text" class="form-control input-qr" placeholder="Scan QR Code"
                            id="input-{{ $index }}" />
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection

@push('plugin-scripts')
    {!! Html::script('/assets/plugins/chartjs/chart.min.js') !!}
    {!! Html::script('/assets/plugins/jquery-sparkline/jquery.sparkline.min.js') !!}
@endpush

@push('custom-scripts')
    {!! Html::script('/assets/js/dashboard.js') !!}

    <script>
        function showAlert(type, message) {
            // Clear any existing dynamic alert
            $('#dynamic-alert').empty();

            // Create the new alert element
            const alert = $(`
                <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                    ${message}
                </div>
            `);

            // Append the alert to the placeholder
            $('#dynamic-alert').append(alert);

            // Automatically close the alert after 3 seconds
            setTimeout(function() {
                alert.alert('close');
            }, 3000);
        }

        $(document).ready(function() {
            var current_status = @json($checkin_status);
            let inputValues = [];
            $('.input-qr').first().focus();

            // Handle Enter key press to move focus to the next input
            $(document).on('keypress', '.input-qr', function(event) {
                if (event.which === 13) { // Enter key
                    event.preventDefault(); // Prevent form submission

                    // Find the current input and get its index
                    let currentInput = $(this);
                    let currentIndex = $('.input-qr').index(currentInput);
                    let cardDiv = $('#card-' + currentIndex);

                    inputValues[currentIndex] = {
                        serial: currentInput.val(), // QR code input value
                        card_id: currentInput.siblings('input[name="card_id"]')
                            .val(), // Card ID from hidden input
                        guest_id: currentInput.siblings('input[name="guest_id"]')
                            .val() // Guest ID from hidden input
                    };

                    // Find the next input based on the index
                    let nextInput = $('.input-qr').eq(currentIndex + 1);

                    // Update the card
                    $.ajax({
                        type: 'GET',
                        url: "{{ url('/cardScan') }}",
                        data: {
                            guest_id: inputValues[currentIndex].guest_id,
                            card_id: inputValues[currentIndex].card_id,
                            serial: inputValues[currentIndex].serial,
                            current_status: current_status
                        },
                        success: function(data) {
                            // Handle the response from the controller
                            if (data.status === 'success') {
                                cardDiv.addClass('card-success'); // Add green background
                                currentInput.prop('disabled', true); // Disable current input

                                // Check if there's a next input
                                if (nextInput.length) {
                                    nextInput.focus(); // Move to next input
                                } else {
                                    // Redirect to the /card page if it's the last card
                                    window.location.href = "{{ url('/card') }}" + '/' +
                                        inputValues[currentIndex].card_id;
                                }
                            } else {
                                showAlert('danger', data.message);
                                $('.input-qr').val('');
                                $('.input-qr').first().focus();
                            }
                        },
                        error: function(error) {
                            console.error('Gagal:', error);
                            $('.input-qr').first().focus();
                        }
                    });
                }
            });

        });
    </script>
@endpush
