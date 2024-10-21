@extends('layout.master')

@push('plugin-styles')
    <!-- {!! Html::style('/assets/plugins/plugin.css') !!} -->
@endpush

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div id="alert-message" class="alert alert-dismissible fade show" role="alert" style="display: none;">
                <span id="alert-content"></span>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
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
                                <div class="input-group">
                                    <input id="qrcode" type="text" class="form-control py-3 rounded-sm mr-2"
                                        placeholder="Enter QR Code..." autofocus>
                                    <div class="input-group-append">
                                        <button class="btn btn-primary rounded-sm px-4" type="button">Scan</button>
                                    </div>
                                </div>
                                <p class="mt-3 text-muted">Please scan your QR code to proceed.</p>
                            </div>
                        </div>
                    </div>
                </section>
            </main>
        </div>

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

            // Handle the scanned QR code on pressing 'Enter'
            $('#qrcode').on('keypress', function(event) {
                if (event.which === 13) { // 13 is the Enter key code
                    let qrCode = $(this).val().trim();

                    if (qrCode.length > 0) {
                        // Send AJAX request to validate the QR code
                        $.ajax({
                            url: '{{ route('qrScan.validate') }}', // Ensure route exists for QR validation
                            method: 'get',
                            data: {
                                _token: '{{ csrf_token() }}',
                                qr_code: qrCode
                            },
                            success: function(response) {
                                if (response.status == 'success') {
                                    let cardDetails = '';
                                    let guestCount = response.details
                                        .length; // Get the number of guests

                                    let statusText = response.checkin_status === 'sukses_in' ?
                                        'Current Visitor Status: In' :
                                        'Current Visitor Status: Out';
                                    cardDetails += `
                                        <div class="alert alert-info mb-3 w-100">
                                            <strong>${statusText}</strong>
                                        </div>
                                    `;

                                    // Start the table for guest details
                                    cardDetails += `
                                        <table class="table table-bordered mb-4">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th class="text-center">Guest Name</th>
                                                    <th class="text-center">Guest ID Card</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                    `;

                                    // Loop through guest details
                                    response.details.forEach(function(guestCard) {
                                        cardDetails += `
                                            <tr>
                                                <td class="text-center">${guestCard.guest_name}</td>
                                                <td class="text-center">${guestCard.guest_id_card}</td>
                                            </tr>
                                        `;
                                    });

                                    // Close the table tag
                                    cardDetails += `</tbody></table>`;

                                    // Add the card images for each guest in a grid layout
                                    cardDetails += `
                                        <div class="row justify-content-center">
                                    `;

                                    response.details.forEach(function(guestCard) {
                                        cardDetails += `
                                        <div class="col-md-4">
                                            <div class="card">
                                                <img src="${guestCard.card_image_url}" class="img-fluid" alt="Card Image" style="width: 200px;">
                                            </div>
                                        </div>
                                    `;
                                    });

                                    cardDetails += `</div>`; // Close the row div

                                    // Adjust modal size based on guest count
                                    let modalSizeClass = '';
                                    if (guestCount === 1) {
                                        modalSizeClass = 'modal-sm'; // Small modal for 1 guest
                                    } else if (guestCount >= 2 && guestCount <= 3) {
                                        modalSizeClass =
                                            'modal-md'; // Medium modal for 2-3 guests
                                    } else if (guestCount >= 4) {
                                        modalSizeClass =
                                            'modal-lg'; // Large modal for 4 or more guests
                                    }

                                    // Set the modal size dynamically
                                    $('#modalSize').removeClass(
                                        'modal-sm modal-md modal-lg').addClass(
                                        modalSizeClass);

                                    // Load the content into the modal and show it
                                    $('#ticket-details-content').html(cardDetails);
                                    $('#ticketDetailsModal').modal('show');
                                } else {
                                    // Show error message
                                    $('#alert-content').text(response.message);
                                    $('#alert-message').addClass('alert-danger').show();

                                    setTimeout(function() {
                                        $('#alert-message').fadeOut('slow');
                                    }, 4000);
                                }
                            },
                            error: function(xhr) {
                                // Show error if AJAX request fails
                                $('#alert-content').text(
                                    'An error occurred. Please try again: ' + xhr
                                    .responseText);
                                $('#alert-message').addClass('alert-danger').show();

                                setTimeout(function() {
                                    $('#alert-message').fadeOut('slow');
                                }, 4000);
                            },
                            complete: function() {
                                // Clear the QR code input after processing
                                $('#qrcode').val('').focus();
                            }
                        });
                    }
                }
            });
        });
    </script>
@endpush
