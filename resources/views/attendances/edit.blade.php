@extends('templates.main')

@push('styles')
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="{{ asset('adminlte') }}/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('adminlte') }}/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="{{ asset('adminlte') }}/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('adminlte') }}/dist/css/adminlte.min.css?v=3.2.0">
@endpush

@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Edit Kehadiran</h1>
                    </div>
                    <div class="col-sm-6">
                        {{  Breadcrumbs::render('attendances.edit', $data->id) }}
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
                                <h3 class="card-title">Edit Kehadiran</h3>

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
                                    <a href="{{ route('attendances.index') }}" class="btn btn-warning mb-3"><i class="fas fa-arrow-left"></i> Kembali</a>
                                </div>
                                <form action="{{ route('attendances.update', $data->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="user_id">User</label>
                                        <select class="form-control select2" style="width: 100%;" data-select2-id="1"
                                            tabindex="-1" aria-hidden="true" id="user_id" name="user_id">
                                            <option value="" selected>Pilih User</option>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}" {{ $data->user_id == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('user_id')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="code">Kode Absen</label>
                                        <input type="text" class="form-control" id="code" name="code"
                                            placeholder="Masukkan Kode Absen" value="{{ old('code', $data->code) }}">
                                        @error('code')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="date">Tanggal</label>
                                        <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                            <input type="text" class="form-control datetimepicker-input"
                                                data-target="#reservationdate" name="date" placeholder="Masukan Tanggal" value="{{ old('date', $data->date) }}">
                                            <div class="input-group-append" data-target="#reservationdate"
                                                data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                        @error('date')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="time_check_in">Jam Masuk</label>
                                        <div class="input-group date" id="time_check_in" data-target-input="nearest">
                                            <input type="text" class="form-control datetimepicker-input"
                                                data-target="#time_check_in" name="time_check_in" placeholder="Masukan Jam" value="{{ old('time_check_in', $data->time_check_in) }}">
                                            <div class="input-group-append" data-target="#time_check_in"
                                                data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-clock"></i></div>
                                            </div>
                                        </div>
                                        @error('time_check_in')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="time_check_out">Jam Pulang</label>
                                        <div class="input-group date" id="time_check_out" data-target-input="nearest">
                                            <input type="text" class="form-control datetimepicker-input"
                                                data-target="#time_check_out" name="time_check_out" placeholder="Masukan Jam" value="{{ old('time_check_out', $data->time_check_out) }}">
                                            <div class="input-group-append" data-target="#time_check_out"
                                                data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-clock"></i></div>
                                            </div>
                                        </div>
                                        @error('time_check_out')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="type">Tipe Kehadiran</label>
                                        <select class="form-control" style="width: 100%;" data-select2-id="1"
                                            tabindex="-1" aria-hidden="true" id="type" name="type">
                                            <option value="" selected>Pilih Kehadiran</option>
                                            <option value="present" {{ old('type', $data->type) == 'present' ? 'selected' : '' }}>Hadir</option>
                                            <option value="sick" {{ old('type', $data->type) == 'sick' ? 'selected' : '' }}>Sakit</option>
                                            <option value="permit" {{ old('type', $data->type) == 'permit' ? 'selected' : '' }}>Izin</option>
                                        </select>
                                        @error('type')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="reason_late">Keterangan Masuk</label>
                                        <input type="text" class="form-control" id="reason_late"
                                            name="reason_late" placeholder="Masukkan Keterangan"
                                            value="{{ old('reason_late', $data->reason_late) }}">
                                        @error('reason_late')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="reason_early_out">Keterangan Pulang</label>
                                        <input type="text" class="form-control" id="reason_early_out"
                                            name="reason_early_out" placeholder="Masukkan Nama"
                                            value="{{ old('reason_early_out', $data->reason_late) }}">
                                        @error('reason_early_out')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="image_check_in">Foto Selfie Masuk</label>

                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="image_check_in"
                                                name="image_check_in">
                                            <label class="custom-file-label" for="image_check_in">Choose file</label>
                                        </div>
                                        @error('image_check_in')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                        @if ($data->image_check_in)
                                            <div class="mt-2">
                                                <img src="{{ asset('images/attendances/' . $data->image_check_in) }}" alt="Current Image" width="150">
                                            </div>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label for="image_check_out">Foto Selfie Pulang</label>

                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="image_check_out"
                                                name="image_check_out" value="{{ old('image_check_out') }}">
                                            <label class="custom-file-label" for="image_check_out">Choose file</label>
                                        </div>
                                        @error('image_check_out')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                        @if ($data->image_check_out)
                                            <div class="mt-2">
                                                <img src="{{ asset('images/attendances/' . $data->image_check_out) }}" alt="Current Image" width="150">
                                            </div>
                                        @endif
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="location_check_in">Lokasi Masuk</label>
                                        <input type="text" class="form-control" id="location_check_in" name="location_check_in"
                                            placeholder="Masukkan koordinat salin dari google maps" value="{{ old('location_check_in', $data->location_check_in) }}">
                                        @error('location_check_in')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="location_check_out">Lokasi Pulang</label>
                                        <input type="text" class="form-control" id="location_check_out" name="location_check_out"
                                            placeholder="Masukkan koordinat salin dari google maps" value="{{ old('location_check_out', $data->location_check_out) }}">
                                        @error('location_check_out')
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
    <!-- Select2 -->
    <script src="{{ asset('adminlte') }}/plugins/select2/js/select2.full.min.js"></script>
    <!-- InputMask -->
    <script src="{{ asset('adminlte') }}/plugins/moment/moment.min.js"></script>
    <script src="{{ asset('adminlte') }}/plugins/inputmask/jquery.inputmask.min.js"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="{{ asset('adminlte') }}/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
    <!-- bs-custom-file-input -->
    <script src="{{ asset('adminlte') }}/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
    <script>
        $(function() {
            bsCustomFileInput.init();

            //Initialize Select2 Elements
            $('.select2').select2()

            $('#reservationdate').datetimepicker({
                // format date YYYY-MM-DD
                format: 'YYYY-MM-DD'
                // format: 'L'
            });

            $('#time_check_in').datetimepicker({
                // format 24 hours
                format: 'HH:mm'
            });

            $('#time_check_out').datetimepicker({
                // format 24 hours
                format: 'HH:mm'
            });

            $('#user_id').on('change', function() {
                let name = $(this).find(':selected').text();
                let date = $('#reservationdate').val();
                // create code with name and timestamp
                $('#code').val(name + moment(date).format('DD/MM/YYYY'));
            });

            $('#reservationdate').on('change.datetimepicker', function() {
                let name = $('#user_id').find(':selected').text();
                let date = $('.datetimepicker-input').val();
                // create code with name and timestamp
                $('#code').val(name + moment(date).format('DD/MM/YYYY'));
            });
        });
    </script>
@endpush
