@extends('layout.master')

@push('plugin-styles')
    <!-- {!! Html::style('/assets/plugins/plugin.css') !!} -->
@endpush

@section('content')
    <div class="row">
        @foreach ($cards as $index => $card)
            <div class="col-md-4">
                <div class="card mb-4 shadow-sm" id="card-{{ $index }}">
                    <div class="card-header text-center">
                        <strong>{{ ucfirst($card->category) }} {{ $card->area->area }} Card</strong>
                    </div>
                    <div class="card-body text-center">
                        <img src="{{ asset('uploads/cards/' . $card->card) }}" class="img-fluid mb-3" alt="Card Image"
                            style="width: 150px;">
                    </div>
                    <div class="card-footer text-center">
                        @php
                            // Find the available card for the current card ID
                            $availableCard = $availableCards->firstWhere('card_id', $card->id);
                            // Find the total card for the current card ID
                            $totalCard = $totalCards->firstWhere('card_id', $card->id);
                            $availableCount = $availableCard ? $availableCard->total : 0;
                            $totalCount = $totalCard ? $totalCard->total : 0;

                            // Determine the badge class based on availability
                            $badgeClass = $availableCount > 0 ? 'badge-success' : 'badge-danger';
                        @endphp
                        <div class="d-flex justify-content-center align-items-center">
                            <div class="mr-4">
                                <span class="badge {{ $badgeClass }}"
                                    style="font-size: 0.8rem; padding: 10px;">{{ $availableCount }}</span>
                                <small class="text-muted" style="font-size: 0.8rem;">Available</small>
                            </div>
                            <div>
                                <span class="badge badge-secondary"
                                    style="font-size: 0.8rem; padding: 10px;">{{ $totalCount }}</span>
                                <small class="text-muted" style="font-size: 0.8rem;">Total</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection

@push('plugin-scripts')
    {!! Html::script('/assets/plugins/chartjs/chart.min.js') !!}
    {!! Html::script('/assets/plugins/jquery-sparkline/jquery.sparkline.min.js') !!}
@endpush

@push('custom-scripts')
    {!! Html::script('/assets/js/dashboard.js') !!}

    <script>
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

        });
    </script>
@endpush
