@extends('layout.master')
<?php
$currentTime = Carbon\Carbon::now();
?>
@push('plugin-styles')
@endpush
<?php
function has_all_null_values($array)
{
    foreach ($array as $value) {
        if (!is_null($value) && $value !== 0) {
            return false;
        }
    }
    return true;
}
?>
@section('content')

    @can('superadmin')
        {{-- BAGIAN SUPERADMIN TIDAK DIUBAH --}}
        <div class="row">
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 grid-margin stretch-card">
                <div class="card card-statistics">
                    <div class="card-body">
                        <div class="d-flex flex-md-column flex-xl-row justify-content-between">
                            <div class="float-left">
                                <i class="mdi mdi-cube text-danger icon-lg"></i>
                            </div>
                            <div class="float-right">
                                <p class="mb-0 text-right">Total Delivery</p>
                                <h3 class="font-weight-medium text-right mb-0">{{ $deliveries }}</h3>
                            </div>
                        </div>
                        <p class="text-muted mt-3 mb-0 text-center">
                            Jumlah Delivery / sehari
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 grid-margin stretch-card">
                <div class="card card-statistics">
                    <div class="card-body">
                        <div class="d-flex flex-md-column flex-xl-row justify-content-between">
                            <div class="float-left">
                                <i class="mdi mdi-account-box-multiple text-info icon-lg"></i>
                            </div>
                            <div class="float-right">
                                <p class="mb-0 text-right">Total Person</p>
                                <h3 class="font-weight-medium text-right mb-0">{{ $deliveries }}</h3>
                            </div>
                        </div>
                        <p class="text-muted mt-3 mb-0 text-center">
                            Visitor inside AIIA / sehari
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- TABLE SUPERADMIN --}}
        <div class="row">
            <div class="col-lg-12 grid-margin">
                <div class="card">
                    <div class="card-body p-5">
                        <h4 class="card-title mb-5">Today's Appointment</h4>

                        <table class="table table-responsive-lg table-hover w-100" id="allTicket">
                            <thead>
                                <tr>
                                    <th class="text-center">Visitor Name</th>
                                    <th class="text-center">Visitor Company</th>
                                    <th class="text-center">Destination</th>
                                </tr>
                            </thead>
                            <tbody class="text-center">

                                @if (count($deliveries) > 0)
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

                    </div>
                </div>
            </div>
        </div>
    @endcan



    {{-- ============================= ADMIN ============================= --}}
    @can('admin')
        <div class="row">
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 grid-margin stretch-card">
                <div class="card card-statistics">
                    <div class="card-body">
                        <div class="d-flex flex-md-column flex-xl-row justify-content-between">
                            <div class="float-left">
                                <i class="mdi mdi-cube text-danger icon-lg"></i>
                            </div>
                            <div class="float-right">
                                <p class="mb-0 text-right">Total Company</p>
                                <h3 class="font-weight-medium text-right mb-0" id="companyCount">{{ $companies }}</h3>
                            </div>
                        </div>
                        <p class="text-muted mt-3 mb-0 text-center">
                            Jumlah Perusahaan / 1 hari
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 grid-margin stretch-card">
                <div class="card card-statistics">
                    <div class="card-body">
                        <div class="d-flex flex-md-column flex-xl-row justify-content-between">
                            <div class="float-left">
                                <i class="mdi mdi-account-box-multiple text-info icon-lg"></i>
                            </div>
                            <div class="float-right">
                                <p class="mb-0 text-right">Total Person</p>
                                <h3 class="font-weight-medium text-right mb-0" id="visitorCount">{{ $visitors }}</h3>
                            </div>
                        </div>
                        <p class="text-muted mt-3 mb-0 text-center">
                            Visitor / 1 hari
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ================= FILTER BULAN ================= --}}
        <div class="row mb-4">
            <div class="col-md-4">
                <form action="{{ route('delivery.history') }}" method="GET" class="d-flex">

                    <select name="month" class="form-control mr-2">
                        @for ($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ $selectedMonth == $m ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                            </option>
                        @endfor
                    </select>

                    <select name="year" class="form-control mr-2">
                        @for ($y = 2024; $y <= now()->year; $y++)
                            <option value="{{ $y }}" {{ $selectedYear == $y ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endfor
                    </select>

                    <button class="btn btn-primary">Filter</button>
                </form>
            </div>
        </div>
        {{-- ================= END FILTER ================= --}}

        <div class="row">
            <div class="col-lg-12 grid-margin">
                <div class="card">
                    <div class="card-body p-5">
                        <div class="row container-fluid">
                            <div class="col-10">
                                <h4 class="card-title mb-5">Delivery Drivers List</h4>
                            </div>
                        </div>

                        <table class="table table-responsive-lg table-hover w-100" id="allTicket">
                            <thead>
                                <tr>
                                    <th class="text-center">Driver Name</th>
                                    <th class="text-center">Driver Company</th>
                                    <th class="text-center">Destination</th>
                                    <th class="text-center">Entry Time</th>
                                </tr>
                            </thead>
                            <tbody class="text-center">

                                @if (count($deliveries) > 0)
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
            let table = $('#allTicket').DataTable({
                order: [
                    [3, 'desc']
                ],
                lengthChange: false,
                processing: true
            });

            function updateStats() {
                $.ajax({
                    url: "{{ url('/delivery/stat') }}",
                    type: "GET",
                    dataType: "json",
                    success: function(response) {
                        $('#companyCount').text(response.companies);
                        $('#visitorCount').text(response.visitors);

                        table.clear();
                        $.each(response.deliveries, function(index, delivery) {
                            table.row.add([
                                `<td class="display-4">${delivery.name}</td>`,
                                `<td class="display-4">${delivery.company}</td>`,
                                `<td class="display-4">${delivery.destination}</td>`,
                                `<td class="display-4">${delivery.created_at}</td>`
                            ]);
                        });
                        table.draw(false);
                    }
                });
            }

            setInterval(updateStats, 5000);
        });
    </script>
@endpush
