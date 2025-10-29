$(document).ready(function() {

    $(function() {
        $(']').tooltip()
    });

    $('#allTicket').DataTable({
        "lengthChange": false
    });

    $('.nav-tabs > li a[title]').tooltip();

    //Wizard
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {

        var target = $(e.target);

        if (target.parent().hasClass('disabled')) {
            return false;
        }
    });

    $(".next-step").click(function(e) {

        var active = $('.wizard .nav-tabs li.active');
        active.next().removeClass('disabled');
        nextTab(active);

    });
    $(".prev-step").click(function(e) {

        var active = $('.wizard .nav-tabs li.active');
        prevTab(active);

    });

    function nextTab(elem) {
        $(elem).next().find('a[data-toggle="tab"]').click();
    }

    function prevTab(elem) {
        $(elem).prev().find('a[data-toggle="tab"]').click();
    }


    $('.nav-tabs').on('click', 'li', function() {
        $('.nav-tabs li.active').removeClass('active');
        $(this).addClass('active');
    });


    const dry_food = document.getElementById('dry-food');
    const wet_food = document.getElementById('wet-food');
    const lunch = document.getElementById('lunch');
    const candy = document.getElementById('candy');
    const coffee = document.getElementById('coffee');
    const tea = document.getElementById('tea');
    const mineral_water = document.getElementById('mineral-water');
    const soft_drink = document.getElementById('soft-drink');
    const helm = document.getElementById('helm');
    const handuk = document.getElementById('handuk');
    const speaker = document.getElementById('speaker');
    const speaker_wireless = document.getElementById('speaker-wireless');
    const motor = document.getElementById('motor');
    const mobil = document.getElementById('mobil');
    const mini_bus = document.getElementById('mini-bus');
    const bus = document.getElementById('bus');

    // food
    $('#dry-food-quantity').hide();
    $('#wet-food-quantity').hide();
    $('#lunch-quantity').hide();
    $('#candy-quantity').hide();
    $("input[type='checkbox']").on("change", function() {
        if (this.checked) {
            if (this == dry_food) {
                $('#dry-food-quantity').show();
            } else if (this == wet_food) {
                $('#wet-food-quantity').show();
            } else if (this == lunch) {
                $('#lunch-quantity').show();
            } else if (this == candy) {
                $('#candy-quantity').show();
            }
        } else {
            if (this == dry_food) {
                $('#dry-food-quantity').hide();
            } else if (this == wet_food) {
                $('#wet-food-quantity').hide();
            } else if (this == lunch) {
                $('#lunch-quantity').hide();
            } else if (this == candy) {
                $('#candy-quantity').hide();
            }
        }
    });

    // drink
    $('#coffee-quantity').hide();
    $('#tea-quantity').hide();
    $('#soft-drink-quantity').hide();
    $('#mineral-water-quantity').hide();
    $("input[type='checkbox']").on("change", function() {
        if (this.checked) {
            if (this == coffee) {
                $('#coffee-quantity').show();
            } else if (this == tea) {
                $('#tea-quantity').show();
            } else if (this == soft_drink) {
                $('#soft-drink-quantity').show();
            } else if (this == mineral_water) {
                $('#mineral-water-quantity').show();
            }
        } else {
            if (this == coffee) {
                $('#coffee-quantity').hide();
            } else if (this == tea) {
                $('#tea-quantity').hide();
            } else if (this == soft_drink) {
                $('#soft-drink-quantity').hide();
            } else if (this == mineral_water) {
                $('#mineral-water-quantity').hide();
            }
        }
    });

    // Plant tour
    $('#helm-quantity').hide();
    $('#handuk-quantity').hide();
    $('#speaker-quantity').hide();
    $('#speaker-wireless-quantity').hide();
    $("input[type='checkbox']").on("change", function() {
        if (this.checked) {
            if (this == helm) {
                $('#helm-quantity').show();
            } else if (this == handuk) {
                $('#handuk-quantity').show();
            } else if (this == speaker) {
                $('#speaker-quantity').show();
            } else if (this == speaker_wireless) {
                $('#speaker-wireless-quantity').show();
            }
        } else {
            if (this == helm) {
                $('#helm-quantity').hide();
            } else if (this == handuk) {
                $('#handuk-quantity').hide();
            } else if (this == speaker) {
                $('#speaker-quantity').hide();
            } else if (this == speaker_wireless) {
                $('#speaker-wireless-quantity').hide();
            }
        }
    });

    // Parkir
    $('#motor-quantity').hide();
    $('#mobil-quantity').hide();
    $('#mini-bus-quantity').hide();
    $('#bus-quantity').hide();
    $("input[type='checkbox']").on("change", function() {
        if (this.checked) {
            if (this == motor) {
                $('#motor-quantity').show();
            } else if (this == mobil) {
                $('#mobil-quantity').show();
            } else if (this == mini_bus) {
                $('#mini-bus-quantity').show();
            } else if (this == bus) {
                $('#bus-quantity').show();
            }
        } else {
            if (this == motor) {
                $('#motor-quantity').hide();
            } else if (this == mobil) {
                $('#mobil-quantity').hide();
            } else if (this == mini_bus) {
                $('#mini-bus-quantity').hide();
            } else if (this == bus) {
                $('#bus-quantity').hide();
            }
        }
    });

    $('.view-document').on('click', function() {
        var filePath = $(this).data(
            'file-path'); // Get the file path from data attribute
        var fileExtension = filePath.split('.').pop()
            .toLowerCase(); // Get file extension

        if (fileExtension === 'jpg' || fileExtension === 'jpeg' || fileExtension ===
            'png' || fileExtension === 'gif') {
            $('#documentContent').hide(); // Hide iframe
            $('#imageContent').attr('src', filePath).show(); // Show image
        } else {
            $('#imageContent').hide(); // Hide image
            $('#documentContent').attr('src', filePath).show(); // Show iframe
        }
    });

    // Optional: Reset the iframe and image src when modal is closed
});