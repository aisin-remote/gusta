@extends('layout.master')

@push('plugin-styles')
    <!-- {!! Html::style('/assets/plugins/plugin.css') !!} -->
@endpush

@section('content')
<h4 class="mb-4" style="font-weight:bolder;">Book Room</h4>
<div class="cards-1 section-gray">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="card card-blog">
                    <div class="card-image">
                        <a href="#"> <img class="img" src="http://adamthemes.com/demo/code/cards/images/blog01.jpeg">
                            <div class="card-caption"></div>
                        </a>
                        <div class="ripple-cont"></div>
                    </div>
                    <div class="table">
                        <h6 class="category text-dark" style="font-weight: bolder;">Lavender Room</h6>
                        <p class="card-description">  </p>
                        <a href="" type="submit" class="btn btn-primary px-4 py-2">Book</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-blog">
                    <div class="card-image">
                        <a href="#"> <img class="img" src="http://adamthemes.com/demo/code/cards/images/blog02.jpeg">
                            <div class="card-caption"></div>
                        </a>
                        <div class="ripple-cont"></div>
                    </div>
                    <div class="table">
                        <h6 class="category text-dark" style="font-weight: bolder;">Executive Room</h6>
                        <p class="card-description"> </p>
                        <button type="submit" class="btn btn-primary px-4 py-2">Book</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-blog">
                    <div class="card-image">
                        <a href="#"> <img class="img" src="http://adamthemes.com/demo/code/cards/images/blog03.jpeg">
                            <div class="card-caption"></div>
                        </a>
                        <div class="ripple-cont"></div>
                    </div>
                    <div class="table">
                        <h6 class="category text-dark" style="font-weight: bolder;">Jasmine Room</h6>
                        <p class="card-description">  </p>
                        <button type="submit" class="btn btn-primary px-4 py-2">Book</button>
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