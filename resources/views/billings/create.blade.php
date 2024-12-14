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
                        <h1>Tambah Penagihan</h1>
                    </div>
                    <div class="col-sm-6">
                        {{ Breadcrumbs::render('billings.create') }}
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">

            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <h5><i class="icon fas fa-ban"></i> Alert!</h5>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @session('error')
                            <div class="alert alert-danger">
                                <h5><i class="icon fas fa-ban"></i> Alert!</h5>
                                {{ session('error') }}
                            </div>
                        @endsession
                        <!-- Default box -->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Tambah Penagihan</h3>

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
                                    <a href="{{ route('billings.index') }}" class="btn btn-warning mb-3"><i class="fas fa-arrow-left"></i> Kembali</a>
                                </div>
                                <form id="billing-form" action="{{ route('billings.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf

                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="no_billing">Nomor Tagihan</label>
                                        <input type="text" class="form-control" id="no_billing" name="no_billing"
                                            placeholder="Masukkan Nomor Tagihan" value="{{ old('no_billing') }}">
                                        @error('no_billing')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="date">Tanggal</label>
                                        <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                            <input type="text" class="form-control datetimepicker-input"
                                                data-target="#reservationdate" name="date" placeholder="Masukan Tanggal" value="{{ old('date') }}">
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
                                        <label for="customer_id">Nasabah</label>
                                        <select class="form-control select2" style="width: 100%;" id="customer_id" name="customer_id">
                                            <option value="" selected>Pilih Nasabah</option>
                                            @foreach ($customers as $customer)
                                                <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>{{ $customer->name_customer }}</option>
                                            @endforeach
                                        </select>
                                        @error('customer_id')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="user_id">Petugas</label>
                                        <select class="form-control select2" style="width: 100%;" id="user_id" name="user_id">
                                            <option value="" selected>Pilih Petugas</option>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('user_id')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="destination">Tujuan Penagihan</label>
                                        <select class="form-control select2" style="width: 100%;" id="destination" name="destination">
                                            <option value="" selected>Pilih Tujuan</option>
                                            <option value="visit" {{ old('destination') == 'visit' ? 'selected' : '' }}>Kunjungan</option>
                                            <option value="promise" {{ old('destination') == 'promise' ? 'selected' : '' }}>Janji Bayar</option>
                                            <option value="pay" {{ old('destination') == 'pay' ? 'selected' : '' }}>Bayar</option>
                                        </select>
                                        @error('destination')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;" id="image-visit-group">
                                        <label for="image_visit">Bukti Kunjungan</label>

                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="image_visit"
                                                name="image_visit" value="{{ old('image_visit') }}">
                                            <label class="custom-file-label" for="image_visit">Choose file</label>
                                        </div>
                                        @error('image_visit')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;" id="description-visit-group">
                                        <label for="description_visit">Keterangan Kunjungan</label>
                                        <textarea class="form-control" id="description_visit" name="description_visit"
                                            placeholder="Masukkan Keterangan Penagihan">{{ old('description_visit') }}</textarea>
                                        @error('description_visit')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;" id="date-promise-group">
                                        <label for="promise_date">Tanggal Janji Bayar</label>
                                        <div class="input-group date" id="promise_date" data-target-input="nearest">
                                            <input type="text" class="form-control datetimepicker-input"
                                                data-target="#promise_date" name="promise_date" placeholder="Masukan Tanggal Bayar" value="{{ old('promise_date') }}">
                                            <div class="input-group-append" data-target="#promise_date"
                                                data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                        @error('promise_date')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;" id="image-promise-group">
                                        <label for="image_promise">Bukti Janji Bayar</label>

                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="image_promise"
                                                name="image_promise" value="{{ old('image_promise') }}">
                                            <label class="custom-file-label" for="image_promise">Choose file</label>
                                        </div>
                                        @error('image_promise')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;" id="description-promise-group">
                                        <label for="description_promise">Keterangan Janji Bayar</label>
                                        <textarea class="form-control" id="description_promise" name="description_promise"
                                            placeholder="Masukkan Keterangan Penagihan">{{ old('description_promise') }}</textarea>
                                        @error('description_promise')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;" id="amount-group">
                                        <label for="amount">Total Bayar</label>
                                        <input type="text" class="form-control text-left" id="amount" name="amount"
                                            placeholder="Masukkan Total Bayar" value="{{ old('amount') }}" data-mask>
                                        @error('amount')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;" id="image-amount-group">
                                        <label for="image_amount">Bukti Bayar</label>

                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="image_amount"
                                                name="image_amount" value="{{ old('image_amount') }}">
                                            <label class="custom-file-label" for="image_amount">Choose file</label>
                                        </div>
                                        @error('image_amount')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;" id="description-amount-group">
                                        <label for="description_amount">Keterangan Bayar</label>
                                        <textarea class="form-control" id="description_amount" name="description_amount"
                                            placeholder="Masukkan Keterangan Penagihan">{{ old('description_amount') }}</textarea>
                                        @error('description_amount')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;" id="signature_officer-group">
                                        <label for="signature_officer">TTD Petugas</label>

                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="signature_officer"
                                                name="signature_officer" value="{{ old('signature_officer') }}">
                                            <label class="custom-file-label" for="signature_officer">Choose file</label>
                                        </div>
                                        @error('signature_officer')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;" id="signature_customer-group">
                                        <label for="signature_customer">TTD Nasabah</label>

                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="signature_customer"
                                                name="signature_customer" value="{{ old('signature_customer') }}">
                                            <label class="custom-file-label" for="signature_customer">Choose file</label>
                                        </div>
                                        @error('signature_customer')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mt-2 d-flex justify-content-center">
                                        <button type="submit" class="btn btn-primary"
                                            style="margin-top: 10px;">Submit</button>
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
            $('.select2').select2({
                theme: 'bootstrap4'
            });

            $('#reservationdate').datetimepicker({
                // format date YYYY-MM-DD
                format: 'YYYY-MM-DD'
                // format: 'L'
            });

            $('#promise_date').datetimepicker({
                // format date YYYY-MM-DD
                format: 'YYYY-MM-DD'
                // format: 'L'
            });

            // Input data-mask to currency Rupiah
            $('[data-mask]').inputmask('numeric', {
                'alias': 'numeric',
                'groupSeparator': '.',
                'autoGroup': true,
                'digits': 0,
                'digitsOptional': false,
                'prefix': 'Rp ',
                'placeholder': '0',
                'rightAlign': false,
                'allowMinus': false,
                'allowPlus': false,
                'autoUnmask': true,
                'unmaskAsNumber': true,
            });

            // Remove format amount
            $('#billing-form').submit(function() {
                $('#amount').inputmask('remove');
            });

            // Show/hide fields based on destination
            function toggleFields() {
                var destination = $('#destination').val();
                if (destination === 'visit') {
                    $('#image-visit-group').show();
                    $('#description-visit-group').show();
                    $('#date-promise-group').hide();
                    $('#image-promise-group').hide();
                    $('#description-promise-group').hide();
                    $('#amount-group').hide();
                    $('#image-amount-group').hide();
                    $('#description-amount-group').hide();
                    $('#signature_officer-group').show();
                    $('#signature_customer-group').show();
                } else if (destination === 'promise') {
                    $('#image-visit-group').hide();
                    $('#description-visit-group').hide();
                    $('#date-promise-group').show();
                    $('#image-promise-group').show();
                    $('#description-promise-group').show();
                    $('#amount-group').hide();
                    $('#image-amount-group').hide();
                    $('#description-amount-group').hide();
                    $('#signature_officer-group').show();
                    $('#signature_customer-group').show();
                } else if (destination === 'pay') {
                    $('#image-visit-group').hide();
                    $('#description-visit-group').hide();
                    $('#date-promise-group').hide();
                    $('#image-promise-group').hide();
                    $('#description-promise-group').hide();
                    $('#amount-group').show();
                    $('#image-amount-group').show();
                    $('#description-amount-group').show();
                    $('#signature_officer-group').show();
                    $('#signature_customer-group').show();
                } else {
                    $('#image-visit-group').hide();
                    $('#description-visit-group').hide();
                    $('#date-promise-group').hide();
                    $('#image-promise-group').hide();
                    $('#description-promise-group').hide();
                    $('#amount-group').hide();
                    $('#image-amount-group').hide();
                    $('#description-amount-group').hide();
                    $('#signature_officer-group').hide();
                    $('#signature_customer-group').hide();
                }
            }

            // Initial toggle
            toggleFields();

            // Toggle fields on change
            $('#destination').change(function() {
                toggleFields();
            });
        });
    </script>
@endpush
