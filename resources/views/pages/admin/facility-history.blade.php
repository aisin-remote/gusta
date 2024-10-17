@extends('layout.master')

@push('plugin-styles')
    <!-- {!! Html::style('/assets/plugins/plugin.css') !!} -->
@endpush

@section('content')
    <div class="row">
        <div class="col-lg-12 grid-margin">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-5">Ticket List <small class="text-muted"> / Daftar Tiket / チケット一覧</small></h4>
                    <table class="table table-responsive-lg" id="allTicket">
                        <thead>
                            <tr>
                                <th class="text-center">ID Tiket</th>
                                <th class="text-center">PIC</th>
                                <th class="text-center">Nama Tamu</th>
                                <th class="text-center">Jumlah Tamu</th>
                                <th class="text-center">Tujuan</th>
                                <th class="text-center">Tanggal</th>
                                <th class="text-center">Status Kebutuhan</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            @if (!$appointments->isEmpty())
                                @foreach ($appointments as $appointment)
                                    <tr>
                                        <td class="display-4">{{ $appointment->id }} </td>
                                        <td class="display-4">{{ $appointment->name }} </td>
                                        <td class="display-4">{{ $appointment->guest_name }}</td>
                                        <td class="display-4">{{ $appointment->guest }}</td>
                                        <td class="display-4">{{ $appointment->purpose }}</td>
                                        <td class="display-4">{{ Carbon\Carbon::parse($appointment->date)->toFormattedDateString() }}</td>

                                        @if ($appointment->status == 'done')
                                            <td>
                                                <span
                                                    class="badge badge-pill badge-success p-2 text-light">Selesai Disiapkan
                                                </span>
                                            </td>
                                        @else
                                            <td>
                                                <span
                                                    class="badge badge-pill badge-warning p-2 text-light">Belum Disiapkan
                                                </span>
                                            </td>
                                        @endif

                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
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
