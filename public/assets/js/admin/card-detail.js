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