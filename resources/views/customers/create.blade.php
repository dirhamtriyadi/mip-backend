@extends('templates.main')

@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Tambah Nasabah</h1>
                    </div>
                    <div class="col-sm-6">
                        {{ Breadcrumbs::render('customers.create') }}
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
                                <h3 class="card-title">Tambah Nasabah</h3>

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
                                    <a href="{{ route('customers.index') }}" class="btn btn-warning mb-3"><i
                                            class="fas fa-arrow-left"></i> Kembali</a>
                                </div>
                                <form id="bank-account-form" action="{{ route('customers.store') }}" method="POST">
                                    @csrf

                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="no_contract">No Kontrak *</label>
                                        <input type="number" class="form-control" id="no_contract" name="no_contract"
                                            placeholder="Masukkan Nama" value="{{ old('no_contract') }}" required>
                                        @error('no_contract')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="bank_account_number">No Rekening</label>
                                        <input type="number" class="form-control" id="bank_account_number"
                                            name="bank_account_number" placeholder="Masukkan Nama"
                                            value="{{ old('bank_account_number') }}">
                                        @error('bank_account_number')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="name_customer">Nama Nasabah *</label>
                                        <input type="text" class="form-control" id="name_customer" name="name_customer"
                                            placeholder="Masukkan Nama Nasabah" value="{{ old('name_customer') }}" required>
                                        @error('name_customer')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="name_mother">Nama Ibu</label>
                                        <input type="text" class="form-control" id="name_mother" name="name_mother"
                                            placeholder="Masukkan Nama Ibu" value="{{ old('name_mother') }}">
                                        @error('name_mother')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="phone_number">Nomor HP</label>
                                        <input type="text" class="form-control" id="phone_number" name="phone_number"
                                            placeholder="Masukkan Nomor HP" value="{{ old('phone_number') }}"
                                            data-inputmask='"mask": "9999-9999-99999"' data-mask>
                                        @error('phone_number')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="address">Alamat</label>
                                        <textarea class="form-control" id="address" name="address" placeholder="Masukkan Nama">{{ old('address') }}</textarea>
                                        @error('address')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="village">Desa</label>
                                        <input type="text" class="form-control" id="village" name="village"
                                            placeholder="Masukkan Desa" value="{{ old('village') }}">
                                        @error('village')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="subdistrict">Kecamatan</label>
                                        <input type="text" class="form-control" id="subdistrict" name="subdistrict"
                                            placeholder="Masukkan Kecamatan" value="{{ old('subdistrict') }}">
                                        @error('subdistrict')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="bank_id">Bank</label>
                                        <select class="form-control select2" style="width: 100%;" id="bank_id"
                                            name="bank_id">
                                            <option value="" selected>Pilih Bank</option>
                                            @foreach ($banks as $bank)
                                                <option value="{{ $bank->id }}"
                                                    {{ old('bank_id') == $bank->id ? 'selected' : '' }}>
                                                    {{ $bank->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('bank_id')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    {{-- <div class="form-group" style="margin-top: 10px;">
                                        <label for="user_id">Petugas</label>
                                        <select class="form-control select2" style="width: 100%;" id="user_id"
                                            name="user_id">
                                            <option value="" selected>Pilih Petugas</option>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}"
                                                    {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                                    {{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('user_id')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div> --}}
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="margin_start">Margin Awal</label>
                                        <input type="text" class="form-control text-left" id="margin_start"
                                            name="margin_start" placeholder="Masukkan Margin Awal"
                                            value="{{ old('margin_start') }}" data-mask>
                                        @error('margin_start')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="os_start">Outstanding Awal</label>
                                        <input type="text" class="form-control text-left" id="os_start"
                                            name="os_start" placeholder="Masukkan Outstanding Awal"
                                            value="{{ old('os_start') }}" data-mask>
                                        @error('os_start')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="margin_remaining">Margin Sisa</label>
                                        <input type="text" class="form-control text-left" id="margin_remaining"
                                            name="margin_remaining" placeholder="Masukkan Margin Sisa"
                                            value="{{ old('margin_remaining') }}" data-mask>
                                        @error('margin_remaining')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="installments">Angsuran Per Bulan</label>
                                        <input type="text" class="form-control text-left" id="installments"
                                            name="installments" placeholder="Masukkan Angsuran Per Bulan"
                                            value="{{ old('installments') }}" data-mask>
                                        @error('installments')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="month_arrears">Tunggak Bulan</label>
                                        <input type="number"  class="form-control" id="month_arrears" name="month_arrears"
                                            placeholder="Masukkan Tunggak Bulan" value="{{ old('month_arrears') }}">
                                        @error('month_arrears')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="arrears">Tunggakan</label>
                                        <input type="text" class="form-control text-left" id="arrears"
                                            name="arrears" placeholder="Masukkan Tunggakan"
                                            value="{{ old('arrears') }}" data-mask>
                                        @error('arrears')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="due_date">Tanggal Jatuh Tempo</label>
                                        <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                            <input type="text" class="form-control datetimepicker-input"
                                                data-target="#reservationdate" name="due_date" placeholder="Masukan Tanggal" value="{{ old('due_date') }}">
                                            <div class="input-group-append" data-target="#reservationdate"
                                                data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                        @error('due_date')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="description">Keterangan Nasabah</label>
                                        <textarea class="form-control" name="description" id="description" rows="5"
                                            placeholder="Masukan Keterangan Nasabah">{{ old('description') }}</textarea>

                                        @error('description')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    @include('templates.form.required')

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

@push('styles')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('adminlte') }}/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="{{ asset('adminlte') }}/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet"
        href="{{ asset('adminlte') }}/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
@endpush

@push('scripts')
    <!-- Select2 -->
    <script src="{{ asset('adminlte') }}/plugins/select2/js/select2.full.min.js"></script>
    <!-- InputMask -->
    <script src="{{ asset('adminlte') }}/plugins/moment/moment.min.js"></script>
    <script src="{{ asset('adminlte') }}/plugins/inputmask/jquery.inputmask.min.js"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="{{ asset('adminlte') }}/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>

    <script>
        $(function() {
            //Initialize Select2 Elements
            $('.select2').select2({
                theme: 'bootstrap4'
            });
            //Datemask dd/mm/yyyy
            $('#datemask').inputmask('dd/mm/yyyy', {
                'placeholder': 'dd/mm/yyyy'
            })
            //Datemask2 mm/dd/yyyy
            $('#datemask2').inputmask('mm/dd/yyyy', {
                'placeholder': 'mm/dd/yyyy'
            })
            // Date picker
            $('#reservationdate').datetimepicker({
                // format date YYYY-MM-DD
                format: 'YYYY-MM-DD'
                // format: 'L'
            });
            // Input data-mask to currency Rupiah
            $('[data-mask]').inputmask({
                'removeMaskOnSubmit': true
            });
            // Input margin_start to currency Rupiah
            $('#margin_start').inputmask('numeric', {
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
                // 'autoUnmask': true,
                // 'unmaskAsNumber': true,
                'removeMaskOnSubmit': true
            });
            // Input os_start to currency Rupiah
            $('#os_start').inputmask('numeric', {
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
                // 'autoUnmask': true,
                // 'unmaskAsNumber': true,
                'removeMaskOnSubmit': true
            });
            // Input margin_remaining to currency Rupiah
            $('#margin_remaining').inputmask('numeric', {
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
                // 'autoUnmask': true,
                // 'unmaskAsNumber': true,
                'removeMaskOnSubmit': true
            });
            // Input installments to currency Rupiah
            $('#installments').inputmask('numeric', {
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
                // 'autoUnmask': true,
                // 'unmaskAsNumber': true,
                'removeMaskOnSubmit': true
            });
        })
    </script>
@endpush
