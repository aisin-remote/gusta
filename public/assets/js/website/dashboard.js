$(document).ready(function() {

    let table = $('#allTicket').DataTable({
        order: [
            [3, 'desc']
        ],
        "lengthChange": false
    });

    let adminTable = $('#adminTable').DataTable({
        order: [
            [3, 'desc']
        ],
        "lengthChange": false
    });

    function updateStats() {
        $.ajax({
            url: "{{ url('/visitor/stat') }}", // API route
            type: "GET",
            dataType: "json",
            success: function(response) {
                $('#totalCount').text(response.total);
                $('#todayCount').text(response.today);
                $('#insideCount').text(response.inside);

                // Clear existing table data
                adminTable.clear().draw();

                // Add new data
                $.each(response.appointments, function(index, appointment) {
                    console.log(appointment.checkin);
                    let checkinTime = appointment.checkin.checkin_at ? appointment
                        .checkin
                        .checkin_at : '--:--';
                    let checkoutTime = appointment.checkin.checkout_at ?
                        appointment.checkin
                        .checkout_at : '--:--';
                    let statusClass = appointment.checkin && appointment.checkin
                        .status === 'in' ? 'badge-success' : 'badge-danger';
                    let statusText = appointment.checkin ? appointment.checkin.status :
                        'unknown';
                    let formattedDate = new Date(appointment.date).toDateString();

                    let row = [
                        appointment.user.name,
                        appointment.user.company,
                        appointment.purpose,
                        formattedDate,
                        checkinTime,
                        checkoutTime,
                        appointment.pic.name,
                        `<span class="badge badge-pill ${statusClass} p-2 text-light">${statusText}</span>`
                    ];

                    adminTable.row.add(row).draw(false);
                });
            }
        });
    }

    // Run updateStats() every 5 seconds
    setInterval(updateStats, 5000);

});