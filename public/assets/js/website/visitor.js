var category = window.appData.category;
var company = window.appData.company;
var oldGuests = window.appData.oldGuests;
var oldCardIds = window.appData.oldCardIds;

$(document).ready(function () {
    const form = document.getElementById('appointmentForm');

    form.addEventListener('submit', function (event) {
        if (!form.querySelector('input[type="checkbox"]:checked')) {
            event.preventDefault();
            alert('Please select at least one checkbox');
        }
    });

    $(".custom-file-input").on("change", function () {
        var fileName = $(this).val().split("\\").pop();
        $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });

    const checkbox = document.getElementById('check_purpose');

    $('#other_purpose').hide();
    $("input[type='checkbox']").on("change", function () {
        if (this.checked && this === checkbox) {
            $('#other_purpose').show();
        } else if (this === checkbox) {
            $('#other_purpose').hide();
        }
    });

    const $dateInput = $('#date');
    const $timeInput = $('#time');
    const now = new Date();
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');
    const currentTime = `${hours}:${minutes}`;
    const today = now.toISOString().split('T')[0];

    $dateInput.attr('min', today);

    function updateTimeConstraints() {
        const selectedDate = $dateInput.val();
        if (selectedDate === today) {
            $timeInput.attr('min', currentTime);
        } else {
            $timeInput.removeAttr('min');
        }
    }

    updateTimeConstraints();
    $dateInput.on('change', updateTimeConstraints);

    $timeInput.on('change', function () {
        const selectedDate = $dateInput.val();
        const selectedTime = $(this).val();
        if (selectedDate === today && selectedTime < currentTime) {
            alert("Please select a current or future time.");
            $(this).val('');
        }
    });

    $('#dept').change(function () {
        $.ajax({
            url: '/get-pic',
            type: 'GET',
            data: {
                dept: $(this).val(),
                company: company,
            },
            success: function (pic) {
                $('#pic_id').empty();
                $.each(pic, function (key, value) {
                    $('#pic_id').append(
                        `<option value='${value.id}'> ${value.name}</option>`
                    );
                });
            }
        });
    });

    $('#date').change(function () {
        $.ajax({
            url: '/get-room',
            type: 'GET',
            data: {
                date: $(this).val()
            },
            success: function (room) {
                $('#room').empty().append(`<option value='null'>-- Select Room --</option>`);
                $.each(room, function (key, value) {
                    $('#room').append(
                        `<option value='${value.id}'> ${value.name}</option>`
                    );
                });
            }
        });
    });

    function hasDuplicates(selector) {
        let values = [];
        let hasDuplicate = false;

        $(selector).each(function () {
            let value = $(this).val();
            if (values.includes(value)) {
                hasDuplicate = true;
                return false;
            }
            values.push(value);
        });

        return hasDuplicate;
    }

    $('form').on('submit', function (e) {
        let duplicateMessage = '';
        let hasError = false;
        const submitButton = $(this).find('.submit-btn');
        const spinner = submitButton.find('.spinner-border');
        const buttonText = submitButton.find('.btn-text');
        const iconBtn = submitButton.find('.icon-btn');

        submitButton.prop('disabled', true);
        spinner.removeClass('d-none');
        iconBtn.addClass('d-none');
        buttonText.text('Loading...');

        if (hasDuplicates('input[name="cardId[]"]')) {
            duplicateMessage = 'ID Card numbers can\'t be the same.';
            hasError = true;

            submitButton.prop('disabled', false);
            spinner.addClass('d-none');
            iconBtn.removeClass('d-none');
            buttonText.text('Submit');
        }

        if (hasError) {
            e.preventDefault();
            $('#duplicateMessage').text(duplicateMessage);
            $('#duplicateAlert').removeClass('d-none');

            setTimeout(function () {
                $('#duplicateAlert').addClass('d-none');
            }, 4000);

            return false;
        } else {
            $('#duplicateAlert').addClass('d-none');
        }
    });

    $('#addGuestBtn').click(function () {
        var guestIndex = $('input[name="name[]"]').length;

        var newGuestInput = $('<div>', { class: 'form-group row' })
            .append($('<div>', { class: 'col-md-3' }).append(
                $('<label>', { class: 'col-form-label' }).html('&nbsp;')
            ))
            .append($('<div>', { class: 'col-md-3' }).append(
                $('<input>', {
                    type: 'text',
                    class: 'form-control mt-1',
                    name: 'name[]',
                    placeholder: 'Guest Name',
                    required: true,
                    value: oldGuests[guestIndex] || ''
                })
            ))
            .append($('<div>', { class: 'col-md-3' }).append(
                $('<input>', {
                    type: 'text',
                    class: 'form-control mt-1',
                    name: 'cardId[]',
                    placeholder: 'ID Card Number',
                    required: true,
                    maxlength: 16,
                    pattern: '\\d{16}',
                    title: 'Card ID must be exactly 16 digits',
                    value: oldCardIds[guestIndex] || ''
                })
            ))
            .append($('<div>', { class: 'col-md-2' }).append(
                $('<div>', { class: 'custom-file mt-1' })
                    .append($('<input>', {
                        type: 'file',
                        class: 'form-control',
                        id: 'photo-' + guestIndex,
                        name: 'photo[' + guestIndex + ']',
                        required: true,
                        accept: 'image/jpeg, image/png'
                    }))
                    .append($('<label>', {
                        class: 'custom-file-label',
                        for: 'photo-' + guestIndex,
                        text: 'Choose file'
                    }))
                    .append($('<small>', {
                        class: 'text-danger',
                        text: '*Self Photo (JPG/PNG)'
                    }))
            ))
            .append($('<div>', { class: 'col-md-1' }).append(
                $('<button>', {
                    type: 'button',
                    class: 'btn btn-danger remove-guest',
                    text: 'Remove'
                }).click(function () {
                    $(this).closest('.form-group').remove();
                })
            ));

        $('#jumlahTamu').closest('.form-group').after(newGuestInput);
    });
});