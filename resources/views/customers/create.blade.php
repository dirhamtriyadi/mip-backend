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
                                    <a href="{{ route('customers.index') }}" class="btn btn-warning mb-3"><i class="fas fa-arrow-left"></i> Kembali</a>
                                </div>
                                <form id="bank-account-form" action="{{ route('customers.store') }}" method="POST">
                                    @csrf

                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="no">No Kontrak/Rekening</label>
                                        <input type="number" class="form-control" id="no" name="no"
                                            placeholder="Masukkan Nama" value="{{ old('no') }}">
                                        @error('no')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="name_customer">Nama Nasabah</label>
                                        <input type="text" class="form-control" id="name_customer" name="name_customer"
                                            placeholder="Masukkan Nama" value="{{ old('name_customer') }}">
                                        @error('name_customer')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="address">Alamat</label>
                                        <textarea class="form-control" id="address" name="address"
                                            placeholder="Masukkan Nama">{{ old('address') }}</textarea>
                                        @error('address')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="name_bank">Nama Bank</label>
                                        <input type="text" class="form-control" id="name_bank" name="name_bank"
                                            placeholder="Masukkan Nama" value="{{ old('name_bank') }}">
                                        @error('name_bank')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="total_bill">Total Tagihan</label>
                                        <input type="text" class="form-control text-left" id="total_bill" name="total_bill"
                                            placeholder="Masukkan Total Tagihan" value="{{ old('total_bill') }}" data-mask>
                                        @error('total_bill')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="installment">Angsuran Per Bulan</label>
                                        <input type="text" class="form-control text-left" id="installment" name="installment"
                                            placeholder="Masukkan Angsuran Per Bulan" value="{{ old('installment') }}" data-mask>
                                        @error('installment')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    {{-- <div class="form-group" style="margin-top: 10px;">
                                        <label for="remaining_installment">Sisa Angsuran</label>
                                        <input type="text" class="form-control text-left" id="remaining_installment" name="remaining_installment"
                                            placeholder="Masukkan Sisa Angsuran" value="{{ old('remaining_installment') }}" data-mask>
                                        @error('remaining_installment')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div> --}}

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

@endpush

@push('scripts')
    <!-- InputMask -->
    <script src="{{ asset('adminlte') }}/plugins/moment/moment.min.js"></script>
    <script src="{{ asset('adminlte') }}/plugins/inputmask/jquery.inputmask.min.js"></script>

    <script>
        $(function() {
            //Datemask dd/mm/yyyy
            $('#datemask').inputmask('dd/mm/yyyy', {
                'placeholder': 'dd/mm/yyyy'
            })
            //Datemask2 mm/dd/yyyy
            $('#datemask2').inputmask('mm/dd/yyyy', {
                'placeholder': 'mm/dd/yyyy'
            })
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

            // Remove format total_bill
            $('#bank-account-form').submit(function() {
                $('#total_bill').inputmask('remove');
                $('#installment').inputmask('remove');
            });
        })
    </script>
@endpush
