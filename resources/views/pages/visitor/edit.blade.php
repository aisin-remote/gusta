@extends('layout.master')

@push('plugin-styles')
    <!-- {!! Html::style('/assets/plugins/plugin.css') !!} -->
@endpush

@section('content')
    <div class="row">
        <div class="col-lg-12 grid-margin">
            <div id="duplicateAlert" class="alert alert-danger d-none" role="alert">
                <strong>Error!</strong> <span id="duplicateMessage"></span>
            </div>
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-5">Edit Appointment <small class="text-muted pl-0">/ Ubah Janji Temu /
                            チケットを編集する</small></h4>

                    <form action="{{ route('appointment.update', $appointment->id) }}"
                        method="POST"enctype="multipart/form-data" id="appointmentEditForm">
                        {{ csrf_field() }}

                        <div class="form-group row">
                            <div class="col-md-4">
                                <label for="inputEmail3" class="col-form-label">Visitor Data
                                    <small class="text-muted pl-0">/ Data Tamu / 訪問者データ</small>
                                </label>
                            </div>
                        </div>

                        <div id="guestContainer">
                            @foreach ($appointment->guests as $index => $guest)
                                <div class="form-group row">
                                    <div class="col-md-4">
                                        <label class="col-form-label">&nbsp;</label>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" class="form-control mt-1" name="name[]"
                                            placeholder="Guest Name" value="{{ old('name.' . $index, $guest->name ?? '') }}"
                                            required>
                                        @if ($errors->has('name.' . $index))
                                            <span
                                                class="text-danger"><small>{{ $errors->first('name.' . $index) }}</small></span>
                                        @endif
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" class="form-control mt-1" name="cardId[]"
                                            placeholder="ID Card Number"
                                            value="{{ old('cardId.' . $index, $guest->id_card ?? '') }}" required
                                            maxlength="16" pattern="\d{16}" title="Card ID must be exactly 16 digits">
                                        <small class="form-text text-danger">*KTP</small>
                                        @if ($errors->has('cardId.' . $index))
                                            <span
                                                class="text-danger"><small>{{ $errors->first('cardId.' . $index) }}</small></span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Add New button -->
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


                        <div class="form-group row">
                            <div class="col-md-4">
                                <label for="visitPurpose" class="col-form-label">Visit Purpose <small
                                        class="text-muted pl-0">/
                                        Tujuan Kunjungan / 滞在目的</small></label>
                            </div>
                            <div class="col-md-8">
                                <div class="boxes">
                                    <input type="checkbox" id="purpose-1" name="purpose-1"
                                        {{ old('purpose-1', $appointment->purpose) == 'Company Visit' ? 'checked' : '' }}>
                                    <label for="purpose-1">Company Visit</label>

                                    <input type="checkbox" id="purpose-2" name="purpose-2"
                                        {{ old('purpose-2', $appointment->purpose) == 'Benchmarking' ? 'checked' : '' }}>
                                    <label for="purpose-2">Benchmarking</label>

                                    <input type="checkbox" id="purpose-3" name="purpose-3"
                                        {{ old('purpose-3', $appointment->purpose) == 'Trial' ? 'checked' : '' }}>
                                    <label for="purpose-3">Trial</label>

                                    <input type="checkbox" id="check_purpose" name="purpose-4"
                                        {{ old('purpose-4', $appointment->purpose) == 'Other' ? 'checked' : '' }}>
                                    <label for="check_purpose">Other</label>

                                    <input type="text" class="form-control mt-2" id="other_purpose" name="other_purpose"
                                        placeholder="Other purpose..."
                                        value="{{ old('other_purpose', $appointment->purpose == 'Other' ? $appointment->purpose : '') }}">
                                </div>

                                <small class="form-text text-muted mt-3">Select one or more</small>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-4">
                                <label for="selectDate" class="col-form-label">Select Date <small class="text-muted pl-0">/
                                        Pilih Tanggal / 日付を選択</small></label>
                            </div>
                            <div class="col-md-8">
                                <input type="date" name="date" class="form-control mt-1"
                                    value="{{ old('date', $appointment->date) }}" />
                                @if ($errors->has('date'))
                                    <span class="text-danger"><small>{{ $errors->first('date') }}</small></span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-4">
                                <label for="selectTime" class="col-form-label">Select Time <small class="text-muted pl-0">/
                                        Pilih Waktu / 時間を選択</small></label>
                            </div>
                            <div class="col-md-8">
                                <input type="time" name="time" class="form-control mt-1"
                                    value="{{ old('time', $appointment->time) }}" />
                                @if ($errors->has('time'))
                                    <span class="text-danger"><small>{{ $errors->first('time') }}</small></span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-4">
                                <label for="selectArea" class="col-form-label">Select Area <small
                                        class="text-muted pl-0">/
                                        Pilih Area / エリアを選択</small></label>
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

                        <div class="form-group row">
                            <div class="col-md-4">
                                <label for="selectPIC" class="col-form-label">PIC <small class="text-muted pl-0">/ PIC /
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

                        <!-- IPK Form field for Contractors -->
                        @if (session()->get('category') == 'Contractor' || session()->get('category') == 'contractor')
                            <div class="row mt-1">
                                <div class="col-md-4">
                                    <label for="ipkForm" class="col-form-label">IPK Form <small
                                            class="text-muted pl-0">/ Form
                                            IPK / 安全フォーム</small> <span class="text-danger">*PNG</span></label>
                                </div>
                                <div class="col-md-8">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="ipk" name="ipk_form">
                                        <label class="custom-file-label" for="ipk">Choose file</label>
                                    </div>
                                </div>
                            </div>
                        @endif

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
        $(document).ready(function() {
            // Pre-existing guest names and card IDs if available
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

            let guestIndex =
                {{ count($appointment->guests) }}; // Start from the current count of guests

            function hasDuplicates(selector) {
                let values = [];
                let hasDuplicate = false;

                $(selector).each(function() {
                    let value = $(this).val();
                    if (values.includes(value)) {
                        hasDuplicate = true;
                        return false; // Stop the loop if duplicate is found
                    }
                    values.push(value);
                });

                return hasDuplicate;
            }

            // On form submission
            $('form').on('submit', function(e) {
                let duplicateMessage = '';
                let hasError = false;
                const submitButton = $(this).find('.submit-btn');
                const spinner = submitButton.find('.spinner-border');
                const buttonText = submitButton.find('.btn-text');
                const iconBtn = submitButton.find('.icon-btn');

                submitButton.prop('disabled', true);
                spinner.removeClass('d-none');
                iconBtn.addClass('d-none');
                buttonText.text('Loading...');

                // Check for duplicate card IDs
                if (hasDuplicates('input[name="cardId[]"]')) {
                    duplicateMessage = 'ID Card numbers can\'t be the same.';
                    hasError = true;

                    // Reset button state
                    submitButton.prop('disabled', false);
                    spinner.addClass('d-none');
                    iconBtn.removeClass('d-none');
                    buttonText.text('Submit');
                }

                if (hasError) {
                    e.preventDefault(); // Prevent form submission
                    $('#duplicateMessage').text(duplicateMessage);
                    $('#duplicateAlert').removeClass('d-none'); // Show alert

                    // Set a timeout to hide the alert after 4 seconds (4000 milliseconds)
                    setTimeout(function() {
                        $('#duplicateAlert').addClass('d-none');
                    }, 4000);

                    return false; // Stop execution
                } else {
                    $('#duplicateAlert').addClass('d-none'); // Hide alert if no error
                }
            });

            $('#addGuestBtn').click(function() {
                // Create a new row
                var newGuestRow = `
                    <div class="form-group row">
                        <div class="col-md-4">
                            <label class="col-form-label">&nbsp;</label>
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control mt-1" name="name[]" placeholder="Guest Name" required>
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control mt-1" name="cardId[]" placeholder="ID Card Number" required maxlength="16" pattern="\\d{16}" title="Card ID must be exactly 16 digits">
                            <small class="form-text text-danger">*KTP</small>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-danger remove-guest p-2">
                                <i class="mdi mdi-minus"></i> Remove
                            </button>
                        </div>
                    </div>
                `;

                // Append the new row after the last guest row in the guest section
                $('#guestContainer .form-group.row:last').after(newGuestRow);
                guestIndex++; // Increment the guest index if needed for your purposes
            });

            // Event delegation for removing a guest row
            $(document).on('click', '.remove-guest', function() {
                $(this).closest('.form-group.row').remove(); // Remove the row on button click
            });
        });
    </script>
@endpush
