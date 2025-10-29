$(document).ready(function() {
    $('#allTicket').DataTable({
        order: [
            [0, "desc"]
        ],
        "lengthChange": false
    });

    $(document).on('click', '.openModalBtn', function() {
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