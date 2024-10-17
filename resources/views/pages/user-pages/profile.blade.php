@extends('layout.master')

@push('plugin-styles')
<!-- {!! Html::style('/assets/plugins/plugin.css') !!} -->
@endpush

@section('content')
<div class="row mt-5">
    <div class="col-lg-6 grid-margin mx-auto">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-5">Change Password <small class="text-muted pl-0">/ Ubah Password / パスワードを変更する</small></h4>
                
                <form action="{{ route('appointment.create') }}" method="post" enctype="multipart/form-data" id="appointmentForm">
                    {{ csrf_field() }}

                    <div class="form-group">
                        <label for="inputEmail3" class="col-form-label">Visitor Name <small class="text-muted pl-0">/ Nama Tamu / お客様のお名前</small></label>
                        <input type="text" class="form-control mt-2" id="nama" name="nama" placeholder="Insert Name...">
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control form-control-lg" id="exampleInputPassword1" placeholder="Password" name="password" required>
                    </div>

                    <div class="row mt-5">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                            <button type="submit" class="btn btn-lg btn-primary"><i class="mdi mdi-near-me pr-3"></i>Update</button>
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
@endpush
