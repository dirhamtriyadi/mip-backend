@extends('templates.main')

@push('styles')
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="{{ asset('adminlte') }}/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
@endpush

@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Edit Jam dan Hari Kerja</h1>
                    </div>
                    <div class="col-sm-6">
                        {{  Breadcrumbs::render('work-schedules.edit', $data->id) }}
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">

            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <!-- Default box -->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Edit Jam dan Hari Kerja</h3>

                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                        title="Collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-start">
                                    <a href="{{ route('work-schedules.index') }}" class="btn btn-warning mb-3"><i class="fas fa-arrow-left"></i> Kembali</a>
                                </div>
                                <form action="{{ route('work-schedules.update', $data->id) }}" method="POST" onsubmit="handleFormSubmit(event)">
                                    @csrf
                                    @method('PUT')

                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="work_start_time">Jam Mulai Kerja</label>
                                        <div class="input-group date" id="work_start_time" data-target-input="nearest">
                                            <input type="text" class="form-control datetimepicker-input"
                                                data-target="#work_start_time" name="work_start_time" placeholder="Masukan Jam" value="{{ old('work_start_time', $data->work_start_time) }}">
                                            <div class="input-group-append" data-target="#work_start_time"
                                                data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-clock"></i></div>
                                            </div>
                                        </div>
                                        @error('work_start_time')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="work_end_time">Jam Selesai Kerja</label>
                                        <div class="input-group date" id="work_end_time" data-target-input="nearest">
                                            <input type="text" class="form-control datetimepicker-input"
                                                data-target="#work_end_time" name="work_end_time" placeholder="Masukan Jam" value="{{ old('work_end_time', $data->work_end_time) }}">
                                            <div class="input-group-append" data-target="#work_end_time"
                                                data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-clock"></i></div>
                                            </div>
                                        </div>
                                        @error('work_end_time')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="working_days">Hari Kerja</label>
                                        @php
                                            $workingDays = $data->working_days;
                                        @endphp
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="working_days[]" value="Monday" id="Monday" {{ in_array('Monday', old('working_days', $workingDays)) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="Monday">Monday</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="working_days[]" value="Tuesday" id="Tuesday" {{ in_array('Tuesday', old('working_days', $workingDays)) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="Tuesday">Tuesday</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="working_days[]" value="Wednesday" id="Wednesday" {{ in_array('Wednesday', old('working_days', $workingDays)) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="Wednesday">Wednesday</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="working_days[]" value="Thursday" id="Thursday" {{ in_array('Thursday', old('working_days', $workingDays)) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="Thursday">Thursday</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="working_days[]" value="Friday" id="Friday" {{ in_array('Friday', old('working_days', $workingDays)) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="Friday">Friday</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="working_days[]" value="Saturday" id="Saturday" {{ in_array('Saturday', old('working_days', $workingDays)) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="Saturday">Saturday</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="working_days[]" value="Sunday" id="Sunday" {{ in_array('Sunday', old('working_days', $workingDays)) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="Sunday">Sunday</label>
                                        </div>
                                        @error('working_days')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mt-2 d-flex justify-content-center">
                                        <button type="submit" class="btn btn-primary"
                                            style="margin-top: 10px;">Update</button>
                                    </div>
                                </form>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                Footer
                            </div>
                            <!-- /.card-footer-->
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
            </div>
        </section>
        <!-- /.content -->
    </div>
@endsection

@push('scripts')
    <!-- InputMask -->
    <script src="{{ asset('adminlte') }}/plugins/moment/moment.min.js"></script>
    <script src="{{ asset('adminlte') }}/plugins/inputmask/jquery.inputmask.min.js"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="{{ asset('adminlte') }}/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
    <script>
        $(function() {
            //Date range picker
            $('#work_start_time').datetimepicker({
                format: 'HH:mm'
            });
            $('#work_end_time').datetimepicker({
                format: 'HH:mm'
            });
        });

        function handleFormSubmit(event) {
            event.preventDefault();
            const form = event.target;
            const checkboxes = form.querySelectorAll('input[name="working_days[]"]:checked');
            const workingDays = Array.from(checkboxes).map(checkbox => checkbox.value);
            const workingDaysJson = JSON.stringify(workingDays);
            // const workingDaysJson = workingDays;
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'working_days_json';
            hiddenInput.value = workingDaysJson;
            form.appendChild(hiddenInput);
            form.submit();
        }
    </script>

@endpush
