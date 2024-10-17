@extends('layout.master')

@push('plugin-styles')
    <!-- {!! Html::style('/assets/plugins/plugin.css') !!} -->
@endpush

@section('content')
<h4 class="mb-4" style="font-weight:bolder;">Book Room</h4>
<div class="cards-1 section-gray">
    <div class="container">
        <div class="row">
            <div class="jumbotron jumbotron-fluid">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <h2 class="title">Lavender Room</h2>
                            <p class="sub"></p>
                        </div>
                    </div>
                    <div class="row mt-5">
                        <div class="col-md-7">
                            <img src="http://adamthemes.com/demo/code/cards/images/blog01.jpeg" alt="" class="jumbo">
                        </div>
                        <div class="col-md-3">
                            <img src="http://adamthemes.com/demo/code/cards/images/blog02.jpeg" alt="" class="mini">
                            <img src="http://adamthemes.com/demo/code/cards/images/blog03.jpeg" alt="" class="mini-2">
                        </div>
                    </div>
                </div>
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
    {!! Html::script('/assets/js/dashboard.js') !!}
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap4.min.js"></script>

    <script>
        $(document).ready(function() {

            $(function() {
                $('[data-toggle="tooltip"]').tooltip()
            });

            $('#allTicket').DataTable({
                //order by desc
                "order": [
                    [0, "desc"]
                ],
                "lengthChange": false
            });



        });
    </script>
@endpush