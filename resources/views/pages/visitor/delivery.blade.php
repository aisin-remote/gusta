@extends('layout.master')

@push('plugin-styles')
    <!-- {!! Html::style('/assets/plugins/plugin.css') !!} -->
@endpush

@section('content')
    <div class="row">
        <div class="col-lg-12 grid-margin">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-5">Delivery Request Form</h4>
                    <form action="{{ route('appointment.create') }}" method="POST" enctype="multipart/form-data"
                        id="appointmentForm">
                        {{ csrf_field() }}

                        <div class="form-group row">
                            <div class="col-md-4">
                                <label for="inputEmail3" class="col-form-label">Delivery Purpose <small
                                        class="text-muted pl-0">/ Tujuan Pengiriman / 配送先 </small></label></label>
                            </div>
                            <div class="col-md-8">
                                <div class="boxes">
                                    <input type="checkbox" id="purpose-1" name="purpose-1">
                                    <label for="purpose-1">Sample Delivery</label>

                                    <input type="checkbox" id="purpose-2" name="purpose-2">
                                    <label for="purpose-2">Equipment Delivery</label>

                                    <input type="checkbox" id="check_purpose" name="purpose-3">
                                    <label for="check_purpose">Other</label>

                                    <input type="text" class="form-control mt-2" id="other_purpose" name="other_purpose"
                                        placeholder="other purpose...">
                                </div>
                                <small id="emailHelp" class="form-text text-muted mt-3">
                                    Select one or more</small>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-4">
                                <label for="inputEmail3" class="col-form-label">Select Date <small class="text-muted pl-0">/
                                        Pilih Tanggal / 日付を選択</small></label>
                            </div>
                            <div class="col-md-8">
                                <input type="date" name="date" id="date" class="form-control mt-1" />
                                <small id="emailHelp" class="form-text text-muted">Select Date</small>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-4">
                                <label for="inputEmail3" class="col-form-label">Select Time <small class="text-muted pl-0">/
                                        Pilih Waktu / 時間を選択</small></label>
                            </div>
                            <div class="col-md-8">
                                <input type="time" name="time" id="time" class="form-control mt-1" />
                                <small id="emailHelp" class="form-text text-muted">Select time</small>
                            </div>
                        </div>

                        <div class="row mt-5">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                <button type="submit" class="btn btn-lg btn-primary"><i
                                        class="mdi mdi-near-me pr-3"></i>Submit</button>
                            </div>
                        </div>
                    </form>

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
    <script>
        const form = document.getElementById('appointmentForm');
        form.addEventListener('submit', function(event) {
            if (!form.querySelector('input[type="checkbox"]:checked')) {
                event.preventDefault();
                alert('Please select at least one checkbox');
            }
        });

        $(".custom-file-input").on("change", function() {
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        });

        const checkbox = document.getElementById('check_purpose');

        $('#other_purpose').hide();
        $("input[type='checkbox']").on("change", function() {
            if (this.checked) {
                if (this == checkbox) {
                    $('#other_purpose').show();
                }
            } else {
                if (this == checkbox) {
                    $('#other_purpose').hide();
                }
            }
        });

        $('#dept').change(function() {
            $.ajax({
                url: '/get-pic',
                type: 'GET',
                data: {
                    dept: $(this).val()
                },
                success: function(pic) {
                    $('#pic_id').empty();
                    $.each(pic, function(key, value) {
                        $('#pic_id').append(
                            `<option value='${value.id}'> ${value.name}</option>`);
                    });
                }
            });
        });

        $('#date').change(function() {
            $.ajax({
                url: '/get-room',
                type: 'GET',
                data: {
                    date: $(this).val()
                },
                success: function(room) {
                    console.log(room);
                    $('#room').empty();
                    $('#room').append(`<option value='null'>-- Select Room --</option>`);
                    $.each(room, function(key, value) {
                        $('#room').append(
                            `<option value='${value.id}'> ${value.name}</option>`);
                    });
                }
            });
        });
    </script>
@endpush
