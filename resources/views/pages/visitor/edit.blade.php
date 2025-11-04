@extends('layout.master')

@push('plugin-styles')
    <!-- {!! Html::style('/assets/plugins/plugin.css') !!} -->
@endpush

@section('content')
    <div class="row">
        <div class="col-lg-12 grid-margin">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-5">Edit User Management</h4>

                    <form action="{{ route('appointment.update', $appointment->id) }}" method="POST"
                        enctype="multipart/form-data" id="appointmentEditForm">
                        {{ csrf_field() }}

                        {{-- VISITOR DATA --}}
                        <div class="form-group row">
                            <div class="col-md-4">
                                <label class="col-form-label">
                                    Visitor Data <small class="text-muted pl-0">/ Data Tamu / 訪問者データ</small>
                                </label>
                            </div>
                        </div>

                        <div id="guestContainer">
                            @foreach ($appointment->guests as $index => $guest)
                                <div class="form-group row">
                                    <div class="col-md-4">
                                        <label class="col-form-label">&nbsp;</label>
                                    </div>

                                    <div class="col-md-4">
                                        <input type="text" class="form-control mt-1" name="name[]"
                                            placeholder="Guest Name" value="{{ old('name.' . $index, $guest->name ?? '') }}"
                                            required>
                                        @if ($errors->has('name.' . $index))
                                            <span class="text-danger">
                                                <small>{{ $errors->first('name.' . $index) }}</small>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="col-md-4">
                                        @php
                                            $idTypeVal = old('id_type.' . $index, $guest->id_type ?? 'KTP') ?: 'KTP';
                                        @endphp
                                        <div class="d-flex gap-2">
                                            <select class="form-control mt-1 id-type" name="id_type[]" required>
                                                <option value="">-- Select ID Type --</option>
                                                <option value="KTP" {{ $idTypeVal == 'KTP' ? 'selected' : '' }}>KTP
                                                </option>
                                                <option value="SIM" {{ $idTypeVal == 'SIM' ? 'selected' : '' }}>SIM
                                                </option>
                                                <option value="ID CARD" {{ $idTypeVal == 'ID CARD' ? 'selected' : '' }}>ID
                                                    Card</option>
                                            </select>

                                            <button type="button" class="btn btn-danger mt-1 remove-guest">
                                                <i class="mdi mdi-minus"></i> Remove
                                            </button>
                                        </div>

                                        @if ($errors->has('id_type.' . $index))
                                            <span class="text-danger">
                                                <small>{{ $errors->first('id_type.' . $index) }}</small>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Add New --}}
                        <div class="form-group row">
                            <div class="col-md-4">
                                <label class="col-form-label">&nbsp;</label>
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-primary p-2" id="addGuestBtn">
                                    <i class="mdi mdi-plus"></i> Add New
                                </button>
                            </div>
                        </div>

                        {{-- PURPOSE --}}
                        @php
                            // Robust pre-fill for purpose:
                            $purposeRaw = old('purpose_text', $appointment->purpose ?? '');
                            $tokens = array_filter(array_map('trim', explode(',', $purposeRaw)));
                            $known = ['Company Visit', 'Benchmarking', 'Trial'];
                            $hasCompany = old('purpose-1', in_array('Company Visit', $tokens)) ? true : false;
                            $hasBenchmark = old('purpose-2', in_array('Benchmarking', $tokens)) ? true : false;
                            $hasTrial = old('purpose-3', in_array('Trial', $tokens)) ? true : false;
                            $otherTokens = array_values(array_diff($tokens, $known));
                            $otherPrefill = old('other_purpose', implode(', ', $otherTokens));
                            $hasOther = old('purpose-4', $otherPrefill !== '') ? true : false;
                        @endphp
                        <div class="form-group row">
                            <div class="col-md-4">
                                <label for="visitPurpose" class="col-form-label">Visit Purpose
                                    <small class="text-muted pl-0">/ Tujuan Kunjungan / 滞在目的</small>
                                </label>
                            </div>
                            <div class="col-md-8">
                                <div class="boxes">
                                    <input type="checkbox" id="purpose-1" name="purpose-1"
                                        {{ $hasCompany ? 'checked' : '' }}>
                                    <label for="purpose-1">Company Visit</label>

                                    <input type="checkbox" id="purpose-2" name="purpose-2"
                                        {{ $hasBenchmark ? 'checked' : '' }}>
                                    <label for="purpose-2">Benchmarking</label>

                                    <input type="checkbox" id="purpose-3" name="purpose-3"
                                        {{ $hasTrial ? 'checked' : '' }}>
                                    <label for="purpose-3">Trial</label>

                                    <input type="checkbox" id="check_purpose" name="purpose-4"
                                        {{ $hasOther ? 'checked' : '' }}>
                                    <label for="check_purpose">Other</label>

                                    <input type="text" class="form-control mt-2" id="other_purpose" name="other_purpose"
                                        placeholder="Other purpose..." value="{{ $otherPrefill }}">
                                </div>
                                <small class="form-text text-muted mt-3">Select one or more</small>
                                {{-- Keep original full purpose text hidden for parsing fallback --}}
                                <input type="hidden" name="purpose_text" value="{{ $purposeRaw }}">
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
                                <input type="date" name="date" class="form-control mt-1"
                                    value="{{ old('date', $appointment->date) }}" />
                                @if ($errors->has('date'))
                                    <span class="text-danger"><small>{{ $errors->first('date') }}</small></span>
                                @endif
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
                                <input type="time" name="time" class="form-control mt-1"
                                    value="{{ old('time', $appointment->time) }}" />
                                @if ($errors->has('time'))
                                    <span class="text-danger"><small>{{ $errors->first('time') }}</small></span>
                                @endif
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
                                <select class="form-control mt-1" name="area_id" required>
                                    <option value="0">-- Select Area --</option>
                                    @foreach ($areas as $area)
                                        <option value="{{ $area->id }}"
                                            {{ old('area_id', $appointment->area_id) == $area->id ? 'selected' : '' }}>
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
                                <select class="form-control mt-1" name="pic_dept" required>
                                    <option value="0">-- Select Department --</option>
                                    @foreach ($departments as $dept)
                                        <option value="{{ $dept->id }}"
                                            {{ old('pic_dept', $appointment->pic_dept) == $dept->id ? 'selected' : '' }}>
                                            {{ $dept->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @if ($errors->has('pic_dept'))
                                    <span class="text-danger"><small>{{ $errors->first('pic_dept') }}</small></span>
                                @endif
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <select class="form-control mt-1" name="pic_id" required>
                                    <option value="0">-- Select PIC --</option>
                                    @foreach ($pics as $pic)
                                        <option value="{{ $pic->id }}"
                                            {{ old('pic_id', $appointment->pic_id) == $pic->id ? 'selected' : '' }}>
                                            {{ $pic->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @if ($errors->has('pic_id'))
                                    <span class="text-danger"><small>{{ $errors->first('pic_id') }}</small></span>
                                @endif
                            </div>
                        </div>

                        {{-- IPK (Contractor only) --}}
                        @if (strtolower((string) session()->get('category')) === 'contractor')
                            <div class="row mt-1">
                                <div class="col-md-4">
                                    <label class="col-form-label">IPK Form
                                        <small class="text-muted pl-0">/ Form IPK / 安全フォーム</small>
                                        <span class="text-danger">*PNG/PDF/JPG</span>
                                    </label>
                                </div>
                                <div class="col-md-8">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="ipk" name="ipk_form">
                                        <label class="custom-file-label" for="ipk">Choose file</label>
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
                                    <span class="btn-text pl-3">Update</span>
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
        (function() {
            // Toggle "Other purpose" input visibility
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

            // Add guest (name + id_type only)
            document.getElementById('addGuestBtn')?.addEventListener('click', function() {
                const container = document.getElementById('guestContainer');
                const newRow = document.createElement('div');
                newRow.className = 'form-group row';
                newRow.innerHTML = `
                    <div class="col-md-4"><label class="col-form-label">&nbsp;</label></div>
                    <div class="col-md-4">
                        <input type="text" class="form-control mt-1" name="name[]" placeholder="Guest Name" required>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex gap-2">
                            <select class="form-control mt-1 id-type" name="id_type[]" required>
                                <option value="">-- Select ID Type --</option>
                                <option value="KTP">KTP</option>
                                <option value="SIM">SIM</option>
                                <option value="ID CARD">ID Card</option>
                            </select>
                            <button type="button" class="btn btn-danger mt-1 remove-guest">
                                <i class="mdi mdi-minus"></i> Remove
                            </button>
                        </div>
                    </div>
                `;
                container.appendChild(newRow);
            });

            // Remove guest row
            document.addEventListener('click', function(e) {
                if (e.target.closest('.remove-guest')) {
                    e.preventDefault();
                    const row = e.target.closest('.form-group.row');
                    row?.remove();
                }
            });

            // Submit loading UX
            document.getElementById('appointmentEditForm')?.addEventListener('submit', function() {
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
@endpush
