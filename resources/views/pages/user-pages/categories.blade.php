@extends('layout.auth')
<style>
    /* Keyframes for the fade-in animation from top */
    @keyframes fadeInFromTop {
        0% {
            opacity: 0;
            transform: translateY(-20px);
            /* Start 20px above */
        }

        100% {
            opacity: 1;
            transform: translateY(0);
            /* End at normal position */
        }
    }

    /* Apply animation to each card */
    .animate-card {
        opacity: 0;
        /* Initially invisible */
        animation: fadeInFromTop 0.6s ease forwards;
        /* Use new animation */
        transform: translateY(-20px);
        /* Start slightly above */
    }

    /* Delays for each card */
    .animate-card.show {
        opacity: 1;
    }

    /* Hover animation */
    .card-hover {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card-hover:hover {
        transform: scale(1.05);
        /* Slight zoom effect */
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        /* Add a shadow on hover */
    }

    #back-button {
        position: absolute;
        top: 20px;
        left: 50px;
        z-index: 1000;
    }
</style>

@section('main')
    <!-- Back Button positioned at the top-left -->

    <a href="/portal" class="btn btn-primary" id="back-button">
        <i class="mdi mdi-arrow-left"></i> Back
    </a>

    <div class="container d-flex justify-content-center align-items-center">
        <!-- Row to wrap the cards -->
        <div class="row justify-content-center w-100">
            <div class="col-lg-12 col-md-4 col-sm-6 col-12 mb-4 card-animate login mb-5">
                <h2 class="text-center">Select a Category to Continue</h2>
                <p class="text-center">Select the category according to your visit purpose</p>
            </div>
            <!-- Visitor Card -->
            <div class="col-lg-6 col-md-6 col-sm-12 col-12 mb-4 animate-card card-click login">
                <div class="btn text-center p-5 h-100 d-flex flex-column justify-content-center card-hover"
                    style="background-color: #FF8C8A;">
                    <i class="mdi mdi-account-circle mdi-48px mb-3" style="color: #ffffff;"></i>
                    <h3 style="font-weight: bolder; color: #ffffff;">Visitor</h3>
                    <small style="color: white;">to make an appointment with employees</small>
                </div>
            </div>

            <!-- Contractor Card -->
            <div class="col-lg-6 col-md-6 col-sm-12 col-12 mb-4 animate-card card-click login">
                <div class="btn text-center p-5 h-100 d-flex flex-column justify-content-center card-hover"
                    style="background-color: #FFB97F;">
                    <i class="mdi mdi-hammer mdi-48px mb-3" style="color: #ffffff;"></i>
                    <h3 style="font-weight: bolder; color: #ffffff;">Contractor</h3>
                    <small style="color: white;">for delivery of goods and materials, make sure you have an IPK form</small>
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
    <script>
        $(document).ready(function() {
            // When the back button is clicked, clear localStorage
            $('#back-button').on('click', function() {
                $.ajax({
                    url: '{{ route('removeCompany') }}', // Use the route defined for removing the company session
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}' // Add the CSRF token
                    },
                    success: function(response) {
                        if (response.success) {
                            window.location.href =
                                '/portal'; // Redirect to the portal after removing the session
                        }
                    },
                    error: function() {
                        alert('Failed to remove company session.');
                    }
                });
            });

            $('.card-click').on('click', function() {
                var category = $(this).find('h3').text().trim(); // Get the category from the card's heading

                // Create a hidden form dynamically
                var form = $('<form>', {
                    method: 'POST',
                    action: '{{ route('setCategory') }}' // Adjust the route to set the category
                });

                // Add CSRF token
                form.append($('<input>', {
                    type: 'hidden',
                    name: '_token',
                    value: '{{ csrf_token() }}'
                }));

                // Add category input
                form.append($('<input>', {
                    type: 'hidden',
                    name: 'category',
                    value: category
                }));

                // Append the form to the body and submit it
                $('body').append(form);
                form.submit();
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Select all cards by their new class
            const cards = document.querySelectorAll('.animate-card');

            // Iterate over each card and add a delay for the animation
            cards.forEach((card, index) => {
                setTimeout(() => {
                    card.classList.add('show'); // Add the show class to start the animation
                }, index * 300); // Add a delay between each card
            });
        });
    </script>
@endpush
