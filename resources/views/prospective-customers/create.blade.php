@extends('templates.main')

@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Tambah Calon Nasabah</h1>
                    </div>
                    <div class="col-sm-6">
                        {{ Breadcrumbs::render('prospective-customers.create') }}
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
                                <h3 class="card-title">Tambah Calon Nasabah</h3>

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
                                    <a href="{{ route('prospective-customers.index') }}" class="btn btn-warning mb-3"><i
                                            class="fas fa-arrow-left"></i> Kembali</a>
                                </div>
                                <form action="{{ route('prospective-customers.store') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="name">Nama *</label>
                                        <input type="text" class="form-control" id="name" name="name"
                                            placeholder="Masukkan Nama" value="{{ old('name') }}" required>

                                        @error('name')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="no_ktp">Nomor KTP *</label>
                                        <input type="text" class="form-control" id="no_ktp" name="no_ktp"
                                            placeholder="Masukkan Nomor KTP" value="{{ old('no_ktp') }}" required>

                                        @error('no_ktp')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="bank_id">Bank *</label>
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
                                    <div class="form-group">
                                        <label for="ktp">Foto KTP *</label>

                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="ktp" name="ktp"
                                                value="{{ old('ktp') }}" required>
                                            <label class="custom-file-label" for="ktp">Choose file</label>
                                        </div>
                                        @error('ktp')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="kk">Foto KK *</label>

                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="kk" name="kk"
                                                value="{{ old('kk') }}" required>
                                            <label class="custom-file-label" for="kk">Choose file</label>
                                        </div>
                                        @error('kk')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="status">Status</label>
                                        <select class="form-control select2" style="width: 100%;" id="status"
                                            name="status">
                                            <option selected>Pilih Status Proses</option>
                                            <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>
                                                Menunggu</option>
                                            <option value="approved" {{ old('status') == 'approved' ? 'selected' : '' }}>
                                                Disetujui</option>
                                            <option value="rejected" {{ old('status') == 'rejected' ? 'selected' : '' }}>
                                                Ditolak</option>
                                        </select>
                                        @error('status')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="status_message">Status Pesan</label>
                                        <textarea class="form-control" id="status_message" name="status_message" placeholder="Masukkan status pesan"
                                            rows="3">{{ old('status_message') }}</textarea>
                                        @error('status_message')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
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
@endpush

@push('scripts')
    <!-- Select2 -->
    <script src="{{ asset('adminlte') }}/plugins/select2/js/select2.full.min.js"></script>
    <!-- bs-custom-file-input -->
    <script src="{{ asset('adminlte') }}/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>

    <script>
        $(function() {
            bsCustomFileInput.init();
            //Initialize Select2 Elements
            $('.select2').select2({
                theme: 'bootstrap4'
            });
        });
    </script>
@endpush
