@extends('layout.master')

@push('plugin-styles')
    <!-- {!! Html::style('/assets/plugins/plugin.css') !!} -->
@endpush

@section('content')
    <div class="row">
        <div class="col-lg-12 grid-margin">

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-5">
                        Create Form <small class="text-muted pl-0">/ Buat Form / フォームを作成する</small>
                    </h4>

                    <form action="{{ route('appointment.create') }}" method="POST" enctype="multipart/form-data"
                        id="appointmentForm">
                        {{ csrf_field() }}

                        {{-- VISITOR DATA --}}
                        <div class="form-group row align-items-start">
                            <div class="col-md-3">
                                <label class="col-form-label">
                                    Visitor Data <small class="text-muted pl-0">/ Data Tamu / 訪問者データ</small>
                                </label>
                            </div>

                            {{-- Guest #1 --}}
                            <div class="col-md-4">
                                <input type="text" class="form-control mt-1" name="name[]" placeholder="Guest Name"
                                    value="{{ old('name.0') }}" required>
                                @if ($errors->has('name.*'))
                                    <span class="text-danger"><small>{{ $errors->first('name.*') }}</small></span>
                                @endif
                            </div>

                            <div class="col-md-4">
                                <select class="form-control mt-1 id-type" name="id_type[]" required>
                                    <option value="">-- Select ID Type --</option>
                                    <option value="KTP" {{ old('id_type.0') == 'KTP' ? 'selected' : '' }}>KTP</option>
                                    <option value="SIM" {{ old('id_type.0') == 'SIM' ? 'selected' : '' }}>SIM</option>
                                    <option value="ID CARD" {{ old('id_type.0') == 'ID CARD' ? 'selected' : '' }}>ID Card
                                    </option>
                                </select>
                                @if ($errors->has('id_type.*'))
                                    <span class="text-danger"><small>{{ $errors->first('id_type.*') }}</small></span>
                                @endif
                            </div>

                            <div class="col-md-1">
                                <button type="button" class="btn btn-primary p-2" id="addGuestBtn">
                                    <i class="mdi mdi-plus"></i> Add
                                </button>
                            </div>
                        </div>

                        {{-- PURPOSE --}}
                        <div class="form-group row">
                            <div class="col-md-4">
                                <label class="col-form-label">Visit Purpose
                                    <small class="text-muted pl-0">/ Tujuan Kunjungan / 滞在目的</small>
                                </label>
                            </div>
                            <div class="col-md-8">
                                <div class="boxes">
                                    <input type="checkbox" id="purpose-1" name="purpose-1"
                                        {{ old('purpose-1') ? 'checked' : '' }}>
                                    <label for="purpose-1">Company Visit</label>

                                    <input type="checkbox" id="purpose-2" name="purpose-2"
                                        {{ old('purpose-2') ? 'checked' : '' }}>
                                    <label for="purpose-2">Benchmarking</label>

                                    <input type="checkbox" id="purpose-3" name="purpose-3"
                                        {{ old('purpose-3') ? 'checked' : '' }}>
                                    <label for="purpose-3">Trial</label>

                                    <input type="checkbox" id="check_purpose" name="purpose-4"
                                        {{ old('purpose-4') ? 'checked' : '' }}>
                                    <label for="check_purpose">Other</label>

                                    <input type="text" class="form-control mt-2" id="other_purpose" name="other_purpose"
                                        placeholder="other purpose..." value="{{ old('other_purpose') }}">
                                </div>
                                @if ($errors->has('purpose-1'))
                                    <span class="text-danger"><small>{{ $errors->first('purpose-1') }}</small></span>
                                @endif
                                <small class="form-text text-muted mt-3">Select one or more</small>
                            </div>
                        </div>

                        {{-- DATE --}}
                        <div class="form-group row">
                            <div class="col-md-4">
                                <label class="col-form-label">Select Date
                                    <small class="text-muted pl-0">/ Pilih Tanggal / 日付を選択</small>
                                </label>
                            </div>
                            <div class="col-md-8">
                                <input type="date" name="date" id="date" class="form-control mt-1"
                                    value="{{ old('date') }}" />
                                @if ($errors->has('date'))
                                    <span class="text-danger"><small>{{ $errors->first('date') }}</small></span>
                                @endif
                                <small class="form-text text-muted">Select Date</small>
                            </div>
                        </div>

                        {{-- TIME --}}
                        <div class="form-group row">
                            <div class="col-md-4">
                                <label class="col-form-label">Select Time
                                    <small class="text-muted pl-0">/ Pilih Waktu / 時間を選択</small>
                                </label>
                            </div>
                            <div class="col-md-8">
                                <input type="time" name="time" id="time" class="form-control mt-1"
                                    value="{{ old('time') }}" />
                                @if ($errors->has('time'))
                                    <span class="text-danger"><small>{{ $errors->first('time') }}</small></span>
                                @endif
                                <small class="form-text text-muted">Select time</small>
                            </div>
                        </div>

                        {{-- AREA --}}
                        <div class="form-group row">
                            <div class="col-md-4">
                                <label class="col-form-label">Select Area
                                    <small class="text-muted pl-0">/ Pilih Area / エリアを選択</small>
                                </label>
                            </div>
                            <div class="col-md-8">
                                <select class="form-control mt-1" id="area" name="area_id" required>
                                    <option value="0">-- Select Area --</option>
                                    @foreach ($areas as $area)
                                        <option value="{{ $area->id }}"
                                            {{ old('area_id') == $area->id ? 'selected' : '' }}>
                                            {{ $area->area }}
                                        </option>
                                    @endforeach
                                </select>
                                @if ($errors->has('area_id'))
                                    <span class="text-danger"><small>{{ $errors->first('area_id') }}</small></span>
                                @endif
                            </div>
                        </div>

                        {{-- PIC --}}
                        <div class="form-group row">
                            <div class="col-md-4">
                                <label class="col-form-label">PIC <small class="text-muted pl-0">/ PIC /
                                        担当者</small></label>
                            </div>
                            <div class="col-md-4 col-sm-8">
                                <select class="form-control mt-1" id="dept" name="pic_dept" required>
                                    <option value="0">-- Select Department --</option>
                                    @foreach ($departments as $dept)
                                        <option value="{{ $dept->id }}"
                                            {{ old('pic_dept') == $dept->id ? 'selected' : '' }}>
                                            {{ $dept->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @if ($errors->has('pic_dept'))
                                    <span class="text-danger"><small>{{ $errors->first('pic_dept') }}</small></span>
                                @endif
                                <small class="form-text text-muted">Responsible person to be met</small>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <select class="form-control mt-1" id="pic_id" name="pic_id" required>
                                    <option value="0">-- Select PIC --</option>
                                </select>
                                @if ($errors->has('pic_id'))
                                    <span class="text-danger"><small>{{ $errors->first('pic_id') }}</small></span>
                                @endif
                            </div>
                        </div>

                        {{-- IPK (Contractor only) --}}
                        @if (session()->get('category') == 'Contractor' || session()->get('category') == 'contractor')
                            <div class="row mt-1">
                                <div class="col-md-4">
                                    <label class="col-form-label">IPK Form
                                        <small class="text-muted pl-0">/ Form IPK / 安全フォーム</small>
                                    </label>
                                </div>
                                <div class="col-md-8">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="ipk" name="ipk_form"
                                            required>
                                        <label class="custom-file-label" for="ipk">Choose file</label>
                                        @if ($errors->has('ipk_form'))
                                            <span
                                                class="text-danger"><small>{{ $errors->first('ipk_form') }}</small></span>
                                        @endif
                                        <small class="text-danger">*JPG/PNG/PDF/XLXS</small>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- SUBMIT --}}
                        <div class="row mt-5">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                <button type="submit" class="btn btn-lg btn-primary submit-btn">
                                    <i class="mdi mdi-near-me pr-3 icon-btn"></i>
                                    <span class="spinner-border spinner-border-sm d-none" role="status"
                                        aria-hidden="true"></span>
                                    <span class="btn-text pl-3">Submit</span>
                                </button>
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
        window.appData = {
            category: "{{ session()->get('category') }}",
            company: "{{ session()->get('company') }}",
            oldGuests: @json(old('name', []))
        };
    </script>

    {{-- Script khusus halaman ini: Toggle Other purpose + Add/Remove guest (tanpa cardId) --}}
    <script>
        (function() {
            const otherPurpose = document.getElementById('other_purpose');
            const chkOther = document.getElementById('check_purpose');

            function syncOther() {
                if (!chkOther || !otherPurpose) return;
                otherPurpose.style.display = chkOther.checked ? 'block' : 'none';
            }
            if (otherPurpose) otherPurpose.style.display = 'none';
            if (chkOther) {
                syncOther();
                chkOther.addEventListener('change', syncOther);
            }

            // Add guest (tanpa field ID Number)
            document.getElementById('addGuestBtn')?.addEventListener('click', function() {
                const form = document.getElementById('appointmentForm');
                const newRow = document.createElement('div');
                newRow.className = 'form-group row';

                newRow.innerHTML = `
                    <div class="col-md-4 offset-md-3">
                        <input type="text" class="form-control mt-1" name="name[]" placeholder="Guest Name" required>
                    </div>
                    <div class="col-md-4">
                        <div class="input-group mt-1">
                            <select class="form-control id-type" name="id_type[]" required>
                                <option value="">-- Select ID Type --</option>
                                <option value="KTP">KTP</option>
                                <option value="SIM">SIM</option>
                                <option value="ID CARD">ID Card</option>
                            </select>
                            <div class="input-group-append">
                                <button type="button" class="btn btn-danger remove-guest">
                                    <i class="mdi mdi-minus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                `;

                // Sisipkan sebelum blok "Purpose"
                const firstPurpose = form.querySelector('.boxes')?.closest('.form-group.row');
                form.insertBefore(newRow, firstPurpose);
            });

            // Remove guest
            document.addEventListener('click', function(e) {
                if (e.target.closest('.remove-guest')) {
                    e.preventDefault();
                    const row = e.target.closest('.form-group.row');
                    row?.remove();
                }
            });

            // UX: tombol submit -> loading
            document.getElementById('appointmentForm')?.addEventListener('submit', function() {
                const submitBtn = this.querySelector('.submit-btn');
                const spinner = submitBtn.querySelector('.spinner-border');
                const btnText = submitBtn.querySelector('.btn-text');
                const iconBtn = submitBtn.querySelector('.icon-btn');

                submitBtn.disabled = true;
                spinner.classList.remove('d-none');
                iconBtn.classList.add('d-none');
                btnText.textContent = 'Loading...';
            });
        })();
    </script>

    {{-- Jika masih ingin pakai visitor.js milikmu, tetap load --}}
    {!! Html::script('/assets/js/website/visitor.js') !!}
@endpush
