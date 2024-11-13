@extends('layout.master')

@push('plugin-styles')
    <!-- {!! Html::style('/assets/plugins/plugin.css') !!} -->
    <style>
        .seat {
            display: inline-block;
            margin: 5px;
            width: 50px;
            height: 50px;
            line-height: 50px;
            text-align: center;
            border-radius: 5px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
        }

        .seat:hover {
            transform: scale(1.1);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .available {
            background-color: rgb(13, 143, 91);
            color: white;
        }

        .unavailable {
            background-color: grey;
            color: white;
        }

        .seat-number {
            font-weight: bold;
        }

        .seat-tooltip {
            display: none;
            position: absolute;
            top: -30px;
            left: 50%;
            transform: translateX(-50%);
            background-color: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 5px;
            border-radius: 5px;
            font-size: 0.8rem;
        }

        .seat:hover .seat-tooltip {
            display: block;
        }

        .card-details {
            padding: 20px;
        }

        .card-image {
            margin-bottom: 20px;
        }

        .card-title {
            font-size: 1.5rem;
            font-weight: bold;
        }

        .legend {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 20px;
        }

        .legend-item {
            display: flex;
            align-items: center;
            margin: 0 10px;
        }

        .legend-color {
            width: 20px;
            height: 20px;
            margin-right: 5px;
            border-radius: 3px;
        }

        .legend-available {
            background-color: rgb(13, 143, 91);
        }

        .legend-unavailable {
            background-color: #6c757d;
        }
    </style>
@endpush

@section('content')
    <div class="card mb-4 shadow-sm">
        <div class="card-header text-center">
            <strong class="card-title">{{ ucfirst($card->category) }} {{ $card->area->area }} Card Numbers</strong>
        </div>
        <div class="card-body text-center card-details my-4">
            <img src="{{ asset('uploads/cards/' . $card->card) }}" class="img-fluid mb-3 card-image" alt="Card Image"
                style="width: 150px;">
            <div class="legend mb-5">
                <div class="legend-item">
                    <div class="legend-color legend-available"></div>
                    <span>Available</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color legend-unavailable"></div>
                    <span>Unavailable</span>
                </div>
            </div>
            <div class="row text-center">
                <div class="col-12">
                    <div class="row">
                        @foreach ($card->card_status as $detailCards)
                            @php
                                $badgeClass = $detailCards->status == 'ready' ? 'available' : 'unavailable';
                            @endphp
                            <div class="col-1 seat {{ $badgeClass }}" data-serial="{{ $detailCards->serial }}"
                                data-status="{{ $detailCards->status }}" data-id="{{ $detailCards->id }}"
                                data-guest="{{ optional($detailCards->guest)->name }}"
                                data-card="{{ optional($detailCards->guest)->id_card }}" style="cursor: pointer;">
                                <span class="seat-number">{{ $detailCards->serial }}</span>
                                <div class="seat-tooltip">
                                    {{ $detailCards->status == 'ready' ? 'Available' : 'Unavailable' }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="detailModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="modal-header">
                        <h5 class="modal-title">Card Details</h5>
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
            // Function to handle the click event on a seat and show the modal
            $('.seat').on('click', function() {
                // Get seat details from the clicked seat element
                var serial = $(this).data('serial');
                var status = $(this).data('status');
                var seatId = $(this).data('id');
                var card = $(this).data('card');
                var guest = $(this).data('guest');

                // Prepare the modal content dynamically
                var modalContent = `
                    <div class="d-flex justify-content-between pt-4">
                        <span class="font-weight-bold h4">Card number : ${serial}</span>
                    </div>
                    <p>Status: ${status === 'used' ? '<span style="color:red">Unavailable</span>' : '<span style="color:green">Available</span>'}</p>
                `;

                // Conditionally add the "Guest Details" section if the status is 'used'
                if (status === 'used') {
                    modalContent += `
                        <div class="mb-3">
                            <hr class="new1">
                        </div>
                        <div class="d-flex justify-content-between pt-4">
                            <span class="font-weight-bold h4">Guest Details</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Guest Name</span>
                            <span class="font-weight-bold">${guest}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">ID Card</span>
                            <span class="font-weight-bold">${card}</span>
                        </div>
                    `;
                }

                // Set the modal content dynamically
                $('#modalContent').html(modalContent);

                // Show the modal
                $('#detailModal').modal('show');
            });

        });
    </script>
@endpush
