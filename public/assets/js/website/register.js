$(document).ready(function() {
    // Real-time validation for password field
    $('#password').on('keyup', function() {
        var password = $(this).val();

        // Length check
        if (password.length >= 8) {
            $('#length').removeClass('invalid').addClass('valid');
        } else {
            $('#length').removeClass('valid').addClass('invalid');
        }

        // Uppercase letter check
        if (/[A-Z]/.test(password)) {
            $('#uppercase').removeClass('invalid').addClass('valid');
        } else {
            $('#uppercase').removeClass('valid').addClass('invalid');
        }

        // Number check
        if (/[0-9]/.test(password)) {
            $('#number').removeClass('invalid').addClass('valid');
        } else {
            $('#number').removeClass('valid').addClass('invalid');
        }

        // Special character check
        if (/[@$!%*?&]/.test(password)) {
            $('#special').removeClass('invalid').addClass('valid');
        } else {
            $('#special').removeClass('valid').addClass('invalid');
        }

        // Check if all criteria are met and enable submit button
        if ($('#password-strength .valid').length === 4) {
            $('#password-strength').addClass('valid');
            $('#password-icon').show(); // Show the check icon when all criteria are met
        } else {
            $('#password-strength').removeClass('valid');
            $('#password-icon').hide(); // Hide the check icon if criteria are not met
        }
    });
});