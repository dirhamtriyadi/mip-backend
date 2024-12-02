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
                        <h1>Edit Penagihan</h1>
                    </div>
                    <div class="col-sm-6">
                        {{ Breadcrumbs::render('billings.edit', $data->id) }}
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
                                <h3 class="card-title">Edit Penagihan</h3>

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
                                <form id="billing-form" action="{{ route('billings.update', $data->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="no_billing">Nomor Tagihan</label>
                                        <input type="text" class="form-control" id="no_billing" name="no_billing"
                                            placeholder="Masukkan Nomor Tagihan" value="{{ old('no_billing', $data->no_billing) }}">
                                        @error('no_billing')
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
                                        <label for="bank_account_id">Nasabah</label>
                                        <select class="form-control select2" style="width: 100%;" id="bank_account_id" name="bank_account_id">
                                            <option value="" selected>Pilih Nasabah</option>
                                            @foreach ($bankAccounts as $bankAccount)
                                                <option value="{{ $bankAccount->id }}" {{ old('bank_account_id', $data->bank_account_id) == $bankAccount->id ? 'selected' : '' }}>{{ $bankAccount->name_customer }}</option>
                                            @endforeach
                                        </select>
                                        @error('bank_account_id')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="user_id">Petugas</label>
                                        <select class="form-control select2" style="width: 100%;" id="user_id" name="user_id">
                                            <option value="" selected>Pilih Petugas</option>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}" {{ old('user_id', $data->user_id) == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
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
                                            <option value="visit" {{ old('destination', $data->destination) == 'visit' ? 'selected' : '' }}>Kunjungan</option>
                                            <option value="promise" {{ old('destination', $data->destination) == 'promise' ? 'selected' : '' }}>Janji Bayar</option>
                                            <option value="pay" {{ old('destination', $data->destination) == 'pay' ? 'selected' : '' }}>Bayar</option>
                                        </select>
                                        @error('destination')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;" id="result-group">
                                        <label for="result">Hasil Penagihan</label>
                                        <input type="text" class="form-control" id="result" name="result"
                                            placeholder="Masukkan Hasil Penagihan" value="{{ old('result', $data->result) }}">
                                        @error('result')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;" id="promise-date-group">
                                        <label for="promise_date">Tanggal Janji Bayar</label>
                                        <div class="input-group date" id="promise_date" data-target-input="nearest">
                                            <input type="text" class="form-control datetimepicker-input"
                                                data-target="#promise_date" name="promise_date" placeholder="Masukan Tanggal Bayar" value="{{ old('promise_date', $data->promise_date) }}">
                                            <div class="input-group-append" data-target="#promise_date"
                                                data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                        @error('promise_date')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;" id="amount-group">
                                        <label for="amount">Total Bayar</label>
                                        <input type="text" class="form-control text-left" id="amount" name="amount"
                                            placeholder="Masukkan Total Bayar" value="{{ old('amount', $data->amount) }}" data-mask>
                                        @error('amount')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;" id="image-group">
                                        <label for="image_amount">Bukti Bayar</label>

                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="image_amount"
                                                name="image_amount" value="{{ old('image_amount') }}">
                                            <label class="custom-file-label" for="image_amount">Choose file</label>
                                        </div>
                                        @error('image_amount')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                        @if ($data->image_amount)
                                            <div class="mt-2">
                                                <img src="{{ asset('images/billings/' . $data->image_amount) }}" alt="Current Image" width="150">
                                            </div>
                                        @endif
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
                                        @if ($data->signature_officer)
                                            <div class="mt-2">
                                                <img src="{{ asset('images/billings/' . $data->signature_officer) }}" alt="Current Image" width="150">
                                            </div>
                                        @endif
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
                                        @if ($data->signature_customer)
                                            <div class="mt-2">
                                                <img src="{{ asset('images/billings/' . $data->signature_customer) }}" alt="Current Image" width="150">
                                            </div>
                                        @endif
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
                    $('#result-group').show();
                    $('#promise-date-group').hide();
                    $('#amount-group').hide();
                    $('#image-group').hide();
                    $('#signature_officer-group').hide();
                    $('#signature_customer-group').hide();
                } else if (destination === 'promise') {
                    $('#result-group').hide();
                    $('#promise-date-group').show();
                    $('#amount-group').hide();
                    $('#image-group').hide();
                    $('#signature_officer-group').show();
                    $('#signature_customer-group').show();
                } else if (destination === 'pay') {
                    $('#result-group').hide();
                    $('#promise-date-group').hide();
                    $('#amount-group').show();
                    $('#image-group').show();
                    $('#signature_officer-group').show();
                    $('#signature_customer-group').show();
                } else {
                    $('#result-group').hide();
                    $('#promise-date-group').hide();
                    $('#amount-group').hide();
                    $('#image-group').hide();
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
