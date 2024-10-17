@extends('layout.master')

@push('plugin-styles')
<!-- {!! Html::style('/assets/plugins/plugin.css') !!} -->
@endpush

@section('content')
<div class="row">
    <div class="col-lg-8 grid-margin mx-auto">
        @if (session('updated'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <small>{{ session('updated') }}</small>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <small>{{ session('error') }}</small>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li><small>{{ $error }}</small></li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="card">
            <div class="card-body mt-2">
                <h4 class="card-title mb-4">Change Password<small class="text-muted pl-0">/ Ubah Password / 
                    パスワードを変更する</small></h4>
                
                <form action="{{ route('password.update') }}" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="inputEmail3" class="col-form-label">Email</label>
                                <input type="text" class="form-control mt" id="email" name="email" placeholder="Insert Name..." value="{{ auth()->user()->email }}">
                            </div>
                            <div class="form-group">
                                <label for="inputEmail3" class="col-form-label">Current Password <small class="text-muted pl-0"> / 現在のパスワード</small></label>
                                <input type="password" class="form-control mt" id="current-password" name="current-password" placeholder="******" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="inputEmail3" class="col-form-label">New Password <small class="text-muted pl-0"> / 新しいパスワード</small></label>
                                <input type="password" class="form-control mt" id="new-password" name="new-password" placeholder="******" required>
                            </div>
                            <div class="form-group">
                                <label for="inputEmail3" class="col-form-label">Password Confirmation<small class="text-muted pl-0"> / パスワードの確認</small></label>
                                <input type="password" class="form-control mt" id="password_confirmation" name="new-password_confirmation" placeholder="******" required>
                            </div> 
                        </div>
                    </div>
                    <div class="row mt-3">
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
        if(this.checked) {
            if(this == checkbox){
                $('#other_purpose').show();
            }
        }else{
             if(this == checkbox){
                $('#other_purpose').hide();
            }
        }
    });

    $('#dept').change(function(){
        $.ajax({
            url: '/get-pic',
            type: 'GET',
            data: { 
                dept: $(this).val() 
            },
            success: function(pic) {
                $('#pic').empty();
                $.each(pic, function(key, value) {
                    $('#pic').append(`<option value='${value.name}'> ${value.name}</option>`);
                });
            }
        });
    });
</script>
@endpush
