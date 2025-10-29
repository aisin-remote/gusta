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
    var current_status = window.appData.checkin_status;
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