@extends('layout.master')
<?php
$currentTime = Carbon\Carbon::now();

?>
@push('plugin-styles')
    <!-- {!! Html::style('/assets/plugins/plugin.css') !!} -->
@endpush
<?php
function has_all_null_values($array)
{
    foreach ($array as $key => $value) {
        if (!is_null($value) && $value !== 0) {
            return false;
        }
    }
    return true;
}
?>
@section('content')

    @can('superadmin')
    <div class="row">
        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 grid-margin stretch-card">
            <div class="card card-statistics">
                <div class="card-body">
                    <div
                        class="d-flex flex-md-column flex-xl-row flex-wrap justify-content-between align-items-md-center justify-content-xl-between">
                        <div class="float-left">
                            <i class="mdi mdi-cube text-danger icon-lg"></i>
                        </div>
                        <div class="float-right">
                            <p class="mb-0 text-right">Total Delivery</p>
                            <div class="fluid-container">
                                <h3 class="font-weight-medium text-right mb-0">{{ $deliveries }}</h3>
                            </div>
                        </div>
                    </div>
                    <p class="text-muted mt-3 mb-0 text-left text-md-center text-xl-left">
                        <i class="mdi mdi-alert-octagon mr-1" aria-hidden="true"></i> Jumlah Delivery <small
                            class="text-muted"> / Jumlah delivery selama 1 hari</small>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 grid-margin stretch-card">
            <div class="card card-statistics">
                <div class="card-body">
                    <div
                        class="d-flex flex-md-column flex-xl-row flex-wrap justify-content-between align-items-md-center justify-content-xl-between">
                        <div class="float-left">
                            <i class="mdi mdi-account-box-multiple text-info icon-lg"></i>
                        </div>
                        <div class="float-right">
                            <p class="mb-0 text-right">Total Person</p>
                            <div class="fluid-container">
                                <h3 class="font-weight-medium text-right mb-0">{{ $deliveries }}</h3>
                            </div>
                        </div>
                    </div>
                    <p class="text-muted mt-3 mb-0 text-left text-md-center text-xl-left pt-1">
                        <i class="mdi mdi-reload mr-1" aria-hidden="true"></i> Visitor inside AIIA<small
                            class="text-muted"> / Jumlah orang yang delivery dalam satu hari</small>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 grid-margin">
            <div class="card">
                <div class="card-body p-5">
                    <div class="row container-fluid">
                        <div class="col-10">
                            <h4 class="card-title mb-5">Today's Appointment<small class="text-muted"> / Janji Temu Hari
                                    ini</small></h4>
                        </div>
                    </div>
                    {{-- <div class="table-responsive"> --}}
                    <table class="table table-responsive-lg table-hover w-100" id="allTicket">
                        <thead>
                            <tr>
                                <th class="text-center">Visitor Name</th>
                                <th class="text-center">Visitor Company</th>
                                <th class="text-center">Destination</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            @if (!$deliveries->isEmpty())
                                @foreach ($deliveries as $delivery)
                                    <tr>
                                        <td class="display-4">{{ $delivery->name }}</td>
                                        <td class="display-4">{{ $delivery->company }}</td>
                                        <td class="display-4">{{ $delivery->destination }}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                    {{-- </div> --}}
                </div>
            </div>
        </div>
    </div>
    @endcan

    @can('admin')
        <div class="row">
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 grid-margin stretch-card">
                <div class="card card-statistics">
                    <div class="card-body">
                        <div
                            class="d-flex flex-md-column flex-xl-row flex-wrap justify-content-between align-items-md-center justify-content-xl-between">
                            <div class="float-left">
                                <i class="mdi mdi-cube text-danger icon-lg"></i>
                            </div>
                            <div class="float-right">
                                <p class="mb-0 text-right">Total Company</p>
                                <div class="fluid-container">
                                    <h3 class="font-weight-medium text-right mb-0">{{ $companies }}</h3>
                                </div>
                            </div>
                        </div>
                        <p class="text-muted mt-3 mb-0 text-left text-md-center text-xl-left">
                            <i class="mdi mdi-alert-octagon mr-1" aria-hidden="true"></i> Jumlah Perusahaan <small
                                class="text-muted"> / Jumlah Perusahaan selama 1 hari</small>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 grid-margin stretch-card">
                <div class="card card-statistics">
                    <div class="card-body">
                        <div
                            class="d-flex flex-md-column flex-xl-row flex-wrap justify-content-between align-items-md-center justify-content-xl-between">
                            <div class="float-left">
                                <i class="mdi mdi-account-box-multiple text-info icon-lg"></i>
                            </div>
                            <div class="float-right">
                                <p class="mb-0 text-right">Total Person</p>
                                <div class="fluid-container">
                                    <h3 class="font-weight-medium text-right mb-0">{{ $visitors }}</h3>
                                </div>
                            </div>
                        </div>
                        <p class="text-muted mt-3 mb-0 text-left text-md-center text-xl-left pt-1">
                            <i class="mdi mdi-reload mr-1" aria-hidden="true"></i> Visitor inside AIIA<small
                                class="text-muted"> / Jumlah orang yang delivery selama 1 hari</small>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 grid-margin">
                <div class="card">
                    <div class="card-body p-5">
                        <div class="row container-fluid">
                            <div class="col-10">
                                <h4 class="card-title mb-5">Today's Appointment<small class="text-muted"> / Janji Temu Hari
                                        ini</small></h4>
                            </div>
                        </div>
                        {{-- <div class="table-responsive"> --}}
                        <table class="table table-responsive-lg table-hover w-100" id="allTicket">
                            <thead>
                                <tr>
                                    <th class="text-center">Visitor Name</th>
                                    <th class="text-center">Visitor Company</th>
                                    <th class="text-center">Destination</th>
                                    <th class="text-center">Entry Time</th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                @if (!$deliveries->isEmpty())
                                    @foreach ($deliveries as $delivery)
                                        <tr>
                                            <td class="display-4">{{ $delivery->name }}</td>
                                            <td class="display-4">{{ $delivery->company }}</td>
                                            <td class="display-4">{{ $delivery->destination }}</td>
                                            <td class="display-4">{{ $delivery->created_at }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                        {{-- </div> --}}
                    </div>
                </div>
            </div>
        </div>
    @endcan

@endsection

@push('plugin-scripts')
    {!! Html::script('/assets/plugins/chartjs/chart.min.js') !!}
    {!! Html::script('/assets/plugins/jquery-sparkline/jquery.sparkline.min.js') !!}
@endpush

@push('custom-scripts')
    {!! Html::script('/assets/js/dashboard.js') !!}
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function() {

            $('#allTicket').DataTable({
                order: [
                    [3, 'asc']
                ],
                "lengthChange": false
            });

        });
    </script>
@endpush
