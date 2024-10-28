@extends('layout.auth')

@section('main')
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="row justify-content-center w-100">
            <div class="col-lg-12 col-md-12 col-sm-12 col-12 mb-4 card-animate login mb-5">
                <h2 class="text-center">Select a Company to Continue</h2>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-12 mb-4 card-animate login card-click mb-4" id="aisin-card"
                data-company="AIIA">
                <div class="auth-form-light text-center p-5">
                    <img class="mb-4" src="{{ url('assets/images/aiia-logo.png') }}" alt="logo" width="200" />
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-12 mb-4 card-animate login card-click mb-4" id="advics-card"
                data-company="advics">
                <div class="auth-form-light text-center p-1">
                    <img class="mb-4" src="{{ url('assets/images/advics-logo.png') }}" alt="logo" width="200" />
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
            // Check if a company has already been selected in localStorage
            if (localStorage.getItem('company')) {
                window.location.href = '/category'; // Redirect if a company is already selected
            }

            // Handle card click event
            $('.card-click').on('click', function() {
                var company = $(this).data('company'); // Get the company from the data attribute

                // Create a hidden form dynamically
                var form = $('<form>', {
                    method: 'POST',
                    action: '{{ route('setCompany') }}' // The route to set the company
                });

                // Add CSRF token
                form.append($('<input>', {
                    type: 'hidden',
                    name: '_token',
                    value: '{{ csrf_token() }}'
                }));

                // Add company input
                form.append($('<input>', {
                    type: 'hidden',
                    name: 'company',
                    value: company
                }));

                // Append the form to the body and submit it
                $('body').append(form);
                form.submit();
            });
        });

        var loginElements = document.getElementsByClassName("login");
        console.log(loginElements);
        window.addEventListener("load", (event) => {
            Array.from(loginElements).forEach((login) => {
                login.classList.add("from-top");
            });
        });

        document.querySelectorAll('.card-animate').forEach(card => {
            card.addEventListener('mouseover', () => {
                card.style.transition = 'transform 0.3s ease';
                card.style.transform = 'scale(1.05)';
            });

            card.addEventListener('mouseout', () => {
                card.style.transform = 'scale(1)';
                card.style.boxShadow = 'none';
            });
        });
    </script>
@endpush
