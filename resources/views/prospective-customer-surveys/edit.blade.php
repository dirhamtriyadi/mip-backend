@extends('templates.main')

@push('styles')
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet"
        href="{{ asset('adminlte') }}/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
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
                        <h1>Edit Survey</h1>
                    </div>
                    <div class="col-sm-6">
                        {{ Breadcrumbs::render('prospective-customer-surveys.edit', $data) }}
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
                        <div class="mb-3">
                            <a href="{{ route('prospective-customer-surveys.index') }}" class="btn btn-warning"><i
                                    class="fas fa-arrow-left"></i> Kembali</a>
                        </div>
                        <form id="survey-form" action="{{ route('prospective-customer-surveys.update', $data->id) }}"
                            enctype="multipart/form-data" method="POST">
                            @method('PUT')
                            @csrf
                            <!-- Default box -->
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">1. CIF</h3>

                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                            title="Collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>

                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="user_id">Petugas</label>
                                        <select class="form-control select2" style="width: 100%;" id="user_id"
                                            name="user_id">
                                            <option value="" selected>Pilih Petugas</option>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}"
                                                    {{ old('user_id') == $user->id || $data->user_id == $user->id ? 'selected' : '' }}>
                                                    {{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('user_id')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="status">Status</label>
                                        <select class="form-control select2" style="width: 100%;" id="status"
                                            name="status">
                                            <option selected>Pilih Status Proses</option>
                                            <option value="pending" {{ old('status') == 'pending' || $data->status == 'pending' ? 'selected' : '' }}>
                                                Menunggu</option>
                                            <option value="ongoing" {{ old('status') == 'ongoing' || $data->status == 'ongoing' ? 'selected' : '' }}>
                                                Proses</option>
                                            <option value="done" {{ old('status') == 'done' || $data->status == 'done' ? 'selected' : '' }}>Berhasil
                                            </option>
                                        </select>
                                        @error('status')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="name">Nama calon nasabah *</label>
                                        <input type="text" class="form-control" id="name" name="name"
                                            placeholder="Masukkan nama calon nasabah" value="{{ old('name', $data->name) }}">
                                        @error('name')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="address">Alamat calon nasabah *</label>
                                        <input type="text" class="form-control" id="address" name="address"
                                            placeholder="Masukkan alamat calon nasabah" value="{{ old('address', $data->address) }}">
                                        @error('address')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="number_ktp">No KTP calon nasabah *</label>
                                        <input type="text" class="form-control" id="number_ktp" name="number_ktp"
                                            placeholder="Masukkan nomor KTP calon nasabah" value="{{ old('number_ktp', $data->number_ktp) }}">
                                        @error('number_ktp')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="address_status">Status alamat calon nasabah *</label>
                                        <input type="text" class="form-control" id="address_status" name="address_status"
                                            placeholder="Masukkan status alamat calon nasabah"
                                            value="{{ old('address_status', $data->address_status) }}">
                                        @error('address_status')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="phone_number">No telepon calon nasabah *</label>
                                        <input type="text" class="form-control" id="phone_number" name="phone_number"
                                            placeholder="Masukkan nomor telepon calon nasabah"
                                            value="{{ old('phone_number', $data->phone_number) }}">
                                        @error('phone_number')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="npwp">NPWP calon nasabah</label>
                                        <input type="text" class="form-control" id="npwp" name="npwp"
                                            placeholder="Masukkan NPWP calon nasabah" value="{{ old('npwp', $data->npwp) }}">
                                        @error('npwp')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    @include('templates.form.required')
                                </div>
                            </div>
                            <!-- /.card -->
                            <!-- Default box -->
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">2. Pendapatan</h3>

                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                            title="Collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>

                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="job_type">Jenis Pekerjaan</label>
                                        <input type="text" class="form-control" id="job_type" name="job_type"
                                            placeholder="Masukkan jenis pekerjaan" value="{{ old('job_type', $data->job_type) }}">
                                        @error('job_type')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="company_name">Nama Perusahaan</label>
                                        <input type="text" class="form-control" id="company_name" name="company_name"
                                            placeholder="Masukkan nama perusahaan" value="{{ old('company_name', $data->company_name) }}">
                                        @error('company_name')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="job_level">Jabatan</label>
                                        <input type="text" class="form-control" id="job_level" name="job_level"
                                            placeholder="Masukkan jabatan" value="{{ old('job_level', $data->job_level) }}">
                                        @error('job_level')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="employee_tenure">Lama Kerja</label>
                                        <input type="text" class="form-control" id="employee_tenure"
                                            name="employee_tenure" placeholder="Masukkan lama kerja"
                                            value="{{ old('employee_tenure', $data->employee_tenure) }}">
                                        @error('employee_tenure')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="employee_status">Status Karyawan</label>
                                        <input type="text" class="form-control" id="employee_status"
                                            name="employee_status" placeholder="Masukkan status karyawan"
                                            value="{{ old('employee_status', $data->employee_status) }}">
                                        @error('employee_status')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="salary">Gaji *</label>
                                        <input type="text" class="form-control" id="salary" name="salary"
                                            placeholder="Masukkan gaji" value="{{ old('salary', $data->salary) }}">
                                        @error('salary')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="other_business">Usaha Tambahan *</label>
                                        <input type="text" class="form-control" id="other_business"
                                            name="other_business" placeholder="Masukkan usaha tambahan"
                                            value="{{ old('other_business', $data->other_business) }}">
                                        @error('other_business')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="monthly_living_expenses">Biaya hidup per bulan *</label>
                                        <input type="text" class="form-control" id="monthly_living_expenses"
                                            name="monthly_living_expenses" placeholder="Masukkan biaya hidup per bulan"
                                            value="{{ old('monthly_living_expenses', $data->monthly_living_expenses) }}">
                                        @error('monthly_living_expenses')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="children">Tanggungan Anak</label>
                                        <input type="text" class="form-control" id="children" name="children"
                                            placeholder="Masukkan tanggungan" value="{{ old('children', $data->children) }}">
                                        @error('children')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="wife">Tanggungan Istri</label>
                                        <input type="text" class="form-control" id="wife" name="wife"
                                            placeholder="Masukkan tanggungan istri" value="{{ old('wife', $data->wife) }}">
                                        @error('wife')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="couple_jobs">Pekerjaan Pasangan</label>
                                        <input type="text" class="form-control" id="couple_jobs" name="couple_jobs"
                                            placeholder="Masukkan pekerjaan pasangan" value="{{ old('couple_jobs', $data->couple_jobs) }}">
                                        @error('couple_jobs')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="couple_business">Usaha Pasangan</label>
                                        <input type="text" class="form-control" id="couple_business"
                                            name="couple_business" placeholder="Masukkan usaha pasangan"
                                            value="{{ old('couple_business', $data->couple_business) }}">
                                        @error('couple_business')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="couple_income">Pendapatan Pasangan</label>
                                        <input type="text" class="form-control" id="couple_income"
                                            name="couple_income" placeholder="Masukkan pendapatan pasangan"
                                            value="{{ old('couple_income', $data->couple_income) }}">
                                        @error('couple_income')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    @include('templates.form.required')
                                </div>
                            </div>
                            <!-- /.card -->
                            <!-- Default box -->
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">3. Hutang</h3>

                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                            title="Collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>

                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="bank_debt">Bank</label>
                                        <input type="text" class="form-control" id="bank_debt" name="bank_debt"
                                            placeholder="Masukkan hutang bank" value="{{ old('bank_debt') }}">
                                        @error('bank_debt')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="cooperative_debt">Koperasi</label>
                                        <input type="text" class="form-control" id="cooperative_debt"
                                            name="cooperative_debt" placeholder="Masukkan hutang koperasi"
                                            value="{{ old('cooperative_debt') }}">
                                        @error('cooperative_debt')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="personal_debt">Perorangan</label>
                                        <input type="text" class="form-control" id="personal_debt"
                                            name="personal_debt" placeholder="Masukkan hutang perorangan"
                                            value="{{ old('personal_debt') }}">
                                        @error('personal_debt')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="online_debt">Online</label>
                                        <input type="text" class="form-control" id="online_debt" name="online_debt"
                                            placeholder="Masukkan hutang online" value="{{ old('online_debt') }}">
                                        @error('online_debt')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    @include('templates.form.required')
                                </div>
                            </div>
                            <!-- /.card -->
                            <!-- Default box -->
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">4. Scorring</h3>

                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                            title="Collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>

                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="customer_character_analysis">Analisa Karakter Nasabah</label>
                                        <input type="text" class="form-control" id="customer_character_analysis"
                                            name="customer_character_analysis"
                                            placeholder="Masukkan analisa karakter nasabah"
                                            value="{{ old('customer_character_analysis') }}">
                                        @error('customer_character_analysis')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="financial_report_analysis">Analisa Laporan Keuangan</label>
                                        <input type="text" class="form-control" id="financial_report_analysis"
                                            name="financial_report_analysis"
                                            placeholder="Masukkan analisa laporan keuangan"
                                            value="{{ old('financial_report_analysis') }}">
                                        @error('financial_report_analysis')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="slik_result">Hasil Slik</label>
                                        <input type="text" class="form-control" id="slik_result" name="slik_result"
                                            placeholder="Masukkan hasil slik" value="{{ old('slik_result') }}">
                                        @error('slik_result')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    @include('templates.form.required')
                                </div>
                            </div>
                            <!-- /.card -->
                            <!-- Default box -->
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">5. Informasi Tambahan dan Pengajuan</h3>

                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                            title="Collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>

                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="info_provider_name">Nama Pemberi Informasi</label>
                                        <input type="text" class="form-control" id="info_provider_name"
                                            name="info_provider_name" placeholder="Masukkan nama pemberi informasi"
                                            value="{{ old('info_provider_name') }}">
                                        @error('info_provider_name')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="info_provider_position">Jabatan Pemberi Informasi</label>
                                        <input type="text" class="form-control" id="info_provider_position"
                                            name="info_provider_position" placeholder="Masukkan jabatan pemberi informasi"
                                            value="{{ old('info_provider_position') }}">
                                        @error('info_provider_position')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="workplace_condition">Kondisi Tempat Kerja</label>
                                        <input type="text" class="form-control" id="workplace_condition"
                                            name="workplace_condition" placeholder="Masukkan kondisi tempat kerja"
                                            value="{{ old('workplace_condition') }}">
                                        @error('workplace_condition')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="employee_count">Banyak Karyawan</label>
                                        <input type="text" class="form-control" id="employee_count"
                                            name="employee_count" placeholder="Masukkan banyak karyawan"
                                            value="{{ old('employee_count') }}">
                                        @error('employee_count')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="business_duration">Lama Usaha Kantor</label>
                                        <input type="text" class="form-control" id="business_duration"
                                            name="business_duration" placeholder="Masukkan lama usaha kantor"
                                            value="{{ old('business_duration') }}">
                                        @error('business_duration')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="office_address">Alamat Kantor</label>
                                        <input type="text" class="form-control" id="office_address"
                                            name="office_address" placeholder="Masukkan alamat kantor"
                                            value="{{ old('office_address') }}">
                                        @error('office_address')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="office_phone">Telepon Kantor</label>
                                        <input type="text" class="form-control" id="office_phone" name="office_phone"
                                            placeholder="Masukkan telepon kantor" value="{{ old('office_phone') }}">
                                        @error('office_phone')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="loan_application">Pengajuan</label>
                                        <input type="text" class="form-control" id="loan_application"
                                            name="loan_application" placeholder="Masukkan Pengajuan"
                                            value="{{ old('loan_application') }}">
                                        @error('loan_application')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    @include('templates.form.required')
                                </div>
                            </div>
                            <!-- /.card -->
                            <!-- Default box -->
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">6. Rekomendasi dari</h3>

                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                            title="Collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>

                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="recommendation_from_vendors">Vendor</label>
                                        <input type="text" class="form-control" id="recommendation_from_vendors"
                                            name="recommendation_from_vendors" placeholder="Masukkan nama vendor"
                                            value="{{ old('recommendation_from_vendors') }}">
                                        @error('recommendation_from_vendors')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="recommendation_from_treasurer">Bendahara</label>
                                        <input type="text" class="form-control" id="recommendation_from_treasurer"
                                            name="recommendation_from_treasurer" placeholder="Masukkan nama bendahara"
                                            value="{{ old('recommendation_from_treasurer') }}">
                                        @error('recommendation_from_treasurer')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="recommendation_from_other">Lainnya</label>
                                        <textarea class="form-control" id="recommendation_from_other" name="recommendation_from_other"
                                            placeholder="Masukkan lainnya" rows="3">{{ old('recommendation_from_other') }}</textarea>
                                        @error('recommendation_from_other')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    @include('templates.form.required')
                                </div>
                            </div>
                            <!-- /.card -->
                            <!-- Default box -->
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">7. Wawancara 1</h3>

                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                            title="Collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>

                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="source_1_full_name">Nama lengkap sumber informasi pertama</label>
                                        <input type="text" class="form-control" id="source_1_full_name"
                                            name="source_1_full_name"
                                            placeholder="Masukkan nama lengkap sumber informasi pertama"
                                            value="{{ old('source_1_full_name') }}">
                                        @error('source_1_full_name')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="source_1_gender">Jenis kelamin sumber informasi pertama</label>
                                        <input type="text" class="form-control" id="source_1_gender"
                                            name="source_1_gender"
                                            placeholder="Masukkan jenis kelamin sumber informasi pertama"
                                            value="{{ old('source_1_gender') }}">
                                        @error('source_1_gender')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="source_1_source_relationship">Hubungan sumber informasi pertama</label>
                                        <input type="text" class="form-control" id="source_1_source_relationship"
                                            name="source_1_source_relationship"
                                            placeholder="Masukkan hubungan sumber informasi pertama"
                                            value="{{ old('source_1_source_relationship') }}">
                                        @error('source_1_source_relationship')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="source_1_source_character">Karakteristik sumber informasi
                                            pertama</label>
                                        <input type="text" class="form-control" id="source_1_source_character"
                                            name="source_1_source_character"
                                            placeholder="Masukkan karakteristik sumber informasi pertama"
                                            value="{{ old('source_1_source_character') }}">
                                        @error('source_1_source_character')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="source_1_knows_prospect_customer">Kenal Dengan Calon Nasabah?</label>
                                        <input type="text" class="form-control" id="source_1_knows_prospect_customer"
                                            name="source_1_knows_prospect_customer" placeholder="Masukkan keterangan"
                                            value="{{ old('source_1_knows_prospect_customer') }}">
                                        @error('source_1_knows_prospect_customer')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="source_1_prospect_lives_at_address">Calon Nasabah Tinggal di Alamat
                                            tersebut?</label>
                                        <input type="text" class="form-control"
                                            id="source_1_prospect_lives_at_address"
                                            name="source_1_prospect_lives_at_address" placeholder="Masukkan keterangan"
                                            value="{{ old('source_1_prospect_lives_at_address') }}">
                                        @error('source_1_prospect_lives_at_address')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="source_1_length_of_residence">Lama Tinggal</label>
                                        <input type="text" class="form-control" id="source_1_length_of_residence"
                                            name="source_1_length_of_residence" placeholder="Masukkan lama tinggal"
                                            value="{{ old('source_1_length_of_residence') }}">
                                        @error('source_1_length_of_residence')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="source_1_house_ownership_status">Status Kepemilikan Rumah</label>
                                        <input type="text" class="form-control" id="source_1_house_ownership_status"
                                            name="source_1_house_ownership_status"
                                            placeholder="Masukkan status kepemilikan rumah"
                                            value="{{ old('source_1_house_ownership_status') }}">
                                        @error('source_1_house_ownership_status')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="source_1_prospect_status">Status Calon Nasabah</label>
                                        <input type="text" class="form-control" id="source_1_prospect_status"
                                            name="source_1_prospect_status" placeholder="Masukkan status calon nasabah"
                                            value="{{ old('source_1_prospect_status') }}">
                                        @error('source_1_prospect_status')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="source_1_number_of_dependents">Jumlah Tanggungan</label>
                                        <input type="text" class="form-control" id="source_1_number_of_dependents"
                                            name="source_1_number_of_dependents" placeholder="Masukkan jumlah tanggungan"
                                            value="{{ old('source_1_number_of_dependents') }}">
                                        @error('source_1_number_of_dependents')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="source_1_prospect_character">Karakter Calon Nasabah</label>
                                        <input type="text" class="form-control" id="source_1_prospect_character"
                                            name="source_1_prospect_character"
                                            placeholder="Masukkan karakter calon nasabah"
                                            value="{{ old('source_1_prospect_character') }}">
                                        @error('source_1_prospect_character')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    @include('templates.form.required')
                                </div>
                            </div>
                            <!-- /.card -->
                            <!-- Default box -->
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">8. Wawancara 2</h3>

                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                            title="Collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>

                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="source_2_full_name">Nama lengkap sumber informasi kedua</label>
                                        <input type="text" class="form-control" id="source_2_full_name"
                                            name="source_2_full_name"
                                            placeholder="Masukkan nama lengkap sumber informasi kedua"
                                            value="{{ old('source_2_full_name') }}">
                                        @error('source_2_full_name')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="source_2_gender">Jenis kelamin sumber informasi kedua</label>
                                        <input type="text" class="form-control" id="source_2_gender"
                                            name="source_2_gender"
                                            placeholder="Masukkan jenis kelamin sumber informasi kedua"
                                            value="{{ old('source_2_gender') }}">
                                        @error('source_2_gender')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="source_2_source_relationship">Hubungan sumber informasi kedua</label>
                                        <input type="text" class="form-control" id="source_2_source_relationship"
                                            name="source_2_source_relationship"
                                            placeholder="Masukkan hubungan sumber informasi kedua"
                                            value="{{ old('source_2_source_relationship') }}">
                                        @error('source_2_source_relationship')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="source_2_source_character">Karakteristik sumber informasi kedua</label>
                                        <input type="text" class="form-control" id="source_2_source_character"
                                            name="source_2_source_character"
                                            placeholder="Masukkan karakteristik sumber informasi kedua"
                                            value="{{ old('source_2_source_character') }}">
                                        @error('source_2_source_character')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="source_2_knows_prospect_customer">Kenal Dengan Calon Nasabah?</label>
                                        <input type="text" class="form-control" id="source_2_knows_prospect_customer"
                                            name="source_2_knows_prospect_customer" placeholder="Masukkan keterangan"
                                            value="{{ old('source_2_knows_prospect_customer') }}">
                                        @error('source_2_knows_prospect_customer')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="source_2_prospect_lives_at_address">Calon Nasabah Tinggal di Alamat
                                            tersebut?</label>
                                        <input type="text" class="form-control"
                                            id="source_2_prospect_lives_at_address"
                                            name="source_2_prospect_lives_at_address" placeholder="Masukkan keterangan"
                                            value="{{ old('source_2_prospect_lives_at_address') }}">
                                        @error('source_2_prospect_lives_at_address')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="source_2_length_of_residence">Lama Tinggal</label>
                                        <input type="text" class="form-control" id="source_2_length_of_residence"
                                            name="source_2_length_of_residence" placeholder="Masukkan lama tinggal"
                                            value="{{ old('source_2_length_of_residence') }}">
                                        @error('source_2_length_of_residence')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="source_2_house_ownership_status">Status Kepemilikan Rumah</label>
                                        <input type="text" class="form-control" id="source_2_house_ownership_status"
                                            name="source_2_house_ownership_status"
                                            placeholder="Masukkan status kepemilikan rumah"
                                            value="{{ old('source_2_house_ownership_status') }}">
                                        @error('source_2_house_ownership_status')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="source_2_prospect_status">Status Calon Nasabah</label>
                                        <input type="text" class="form-control" id="source_2_prospect_status"
                                            name="source_2_prospect_status" placeholder="Masukkan status calon nasabah"
                                            value="{{ old('source_2_prospect_status') }}">
                                        @error('source_2_prospect_status')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="source_2_number_of_dependents">Jumlah Tanggungan</label>
                                        <input type="text" class="form-control" id="source_2_number_of_dependents"
                                            name="source_2_number_of_dependents" placeholder="Masukkan jumlah tanggungan"
                                            value="{{ old('source_2_number_of_dependents') }}">
                                        @error('source_2_number_of_dependents')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="source_2_prospect_character">Karakter Calon Nasabah</label>
                                        <input type="text" class="form-control" id="source_2_prospect_character"
                                            name="source_2_prospect_character"
                                            placeholder="Masukkan karakter calon nasabah"
                                            value="{{ old('source_2_prospect_character') }}">
                                        @error('source_2_prospect_character')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    @include('templates.form.required')
                                </div>
                            </div>
                            <!-- /.card -->
                            <!-- Default box -->
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">9. Catatan Rekomendasi PT</h3>

                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                            title="Collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>

                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="recommendation_pt">Catatan Rekomendasi PT</label>
                                        <select class="form-control select2" style="width: 100%;" id="recommendation_pt"
                                            name="recommendation_pt">
                                            <option value="" selected>Pilih Catatan Rekomendasi PT</option>
                                            <option value="yes"
                                                {{ old('recommendation_pt') == 'yes' ? 'selected' : '' }}>
                                                Ya</option>
                                            <option value="no"
                                                {{ old('recommendation_pt') == 'no' ? 'selected' : '' }}>
                                                Tidak</option>
                                        </select>
                                        @error('recommendation_pt')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="descriptionSurvey">Keterangan</label>
                                        <textarea class="form-control" id="descriptionSurvey" name="descriptionSurvey" placeholder="Masukkan keterangan"
                                            rows="3">{{ old('descriptionSurvey') }}</textarea>
                                        @error('descriptionSurvey')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="locationSurvey">Tempat</label>
                                        <input type="text" class="form-control" id="locationSurvey"
                                            name="locationSurvey" placeholder="Masukkan tempat"
                                            value="{{ old('locationSurvey') }}">
                                        @error('locationSurvey')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="dateSurvey">Tanggal</label>
                                        <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                            <input type="text" class="form-control datetimepicker-input"
                                                data-target="#reservationdate" name="dateSurvey"
                                                placeholder="Masukan Tanggal" value="{{ old('dateSurvey') }}">
                                            <div class="input-group-append" data-target="#reservationdate"
                                                data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                        @error('dateSurvey')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="latitude">Latitude</label>
                                        <input type="text" class="form-control" id="latitude" name="latitude"
                                            placeholder="Masukkan latitude" value="{{ old('latitude') }}">
                                        @error('latitude')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="longitude">Longitude</label>
                                        <input type="text" class="form-control" id="longitude" name="longitude"
                                            placeholder="Masukkan longitude" value="{{ old('longitude') }}">
                                        @error('longitude')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="locationString">Lokasi</label>
                                        <input type="text" class="form-control" id="locationString"
                                            name="locationString"
                                            placeholder="Masukkan lokasi latitude dan longitude digabungkan dengan koma"
                                            value="{{ old('locationString') }}">
                                        @error('locationString')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="signature_officer">Tanda tangan officer</label>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="signature_officer"
                                                name="signature_officer" value="{{ old('signature_officer') }}">
                                            <label class="custom-file-label" for="signature_officer">Choose file</label>
                                        </div>
                                        @if ($data->signature_officer)
                                            <img src="{{ asset('storage/' . $data->signature_officer) }}" alt="signature_officer" style="width: 100px; height: 100px;">
                                        @else
                                            -
                                        @endif
                                        @error('signature_officer')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="signature_customer">Tanda tangan customer</label>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="signature_customer"
                                                name="signature_customer" value="{{ old('signature_customer') }}">
                                            <label class="custom-file-label" for="signature_customer">Choose file</label>
                                        </div>
                                        @if ($data->signature_customer)
                                            <img src="{{ asset('storage/' . $data->signature_customer) }}" alt="signature_customer" style="width: 100px; height: 100px;">
                                        @else
                                            -
                                        @endif
                                        @error('signature_customer')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="signature_couple">Tanda tangan pasangan</label>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="signature_couple"
                                                name="signature_couple" value="{{ old('signature_couple') }}">
                                            <label class="custom-file-label" for="signature_couple">Choose file</label>
                                        </div>
                                        @if ($data->signature_couple)
                                            <img src="{{ asset('storage/' . $data->signature_couple) }}" alt="signature_couple" style="width: 100px; height: 100px;">
                                        @else
                                            -
                                        @endif
                                        @error('signature_couple')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    @include('templates.form.required')
                                </div>
                            </div>
                            <!-- /.card -->
                            <!-- Default box -->
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">10. Berkas</h3>

                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                            title="Collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>

                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="workplace_image1">Gambar gedung 1</label>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="workplace_image1"
                                                name="workplace_image1" value="{{ old('workplace_image1') }}">
                                            <label class="custom-file-label" for="workplace_image1">Choose file</label>
                                        </div>
                                        @if ($data->workplace_image1)
                                            <img src="{{ asset('storage/' . $data->workplace_image1) }}" alt="workplace_image1" style="width: 100px; height: 100px;">
                                        @else
                                            -
                                        @endif
                                        @error('workplace_image1')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="workplace_image2">Gambar gedung 2</label>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="workplace_image2"
                                                name="workplace_image2" value="{{ old('workplace_image2') }}">
                                            <label class="custom-file-label" for="workplace_image2">Choose file</label>
                                        </div>
                                        @if ($data->workplace_image2)
                                            <img src="{{ asset('storage/' . $data->workplace_image2) }}" alt="workplace_image2" style="width: 100px; height: 100px;">
                                        @else
                                            -
                                        @endif
                                        @error('workplace_image2')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="customer_image">Gambar customer</label>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="customer_image"
                                                name="customer_image" value="{{ old('customer_image') }}">
                                            <label class="custom-file-label" for="customer_image">Choose file</label>
                                        </div>
                                        @if ($data->customer_image)
                                            <img src="{{ asset('storage/' . $data->customer_image) }}" alt="customer_image" style="width: 100px; height: 100px;">
                                        @else
                                            -
                                        @endif
                                        @error('customer_image')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="ktp_image">Gambar KTP</label>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="ktp_image"
                                                name="ktp_image" value="{{ old('ktp_image') }}">
                                            <label class="custom-file-label" for="ktp_image">Choose file</label>
                                        </div>
                                        @if ($data->ktp_image)
                                            <img src="{{ asset('storage/' . $data->ktp_image) }}" alt="ktp_image" style="width: 100px; height: 100px;">
                                        @else
                                            -
                                        @endif
                                        @error('ktp_image')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="loan_guarantee_image1">Gambar jaminan pinjaman 1</label>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="loan_guarantee_image1"
                                                name="loan_guarantee_image1" value="{{ old('loan_guarantee_image1') }}">
                                            <label class="custom-file-label" for="loan_guarantee_image1">Choose
                                                file</label>
                                        </div>
                                        @if ($data->loan_guarantee_image1)
                                            <img src="{{ asset('storage/' . $data->loan_guarantee_image1) }}" alt="loan_guarantee_image1" style="width: 100px; height: 100px;">
                                        @else
                                            -
                                        @endif
                                        @error('loan_guarantee_image1')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="loan_guarantee_image2">Gambar jaminan pinjaman 2</label>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="loan_guarantee_image2"
                                                name="loan_guarantee_image2" value="{{ old('loan_guarantee_image2') }}">
                                            <label class="custom-file-label" for="loan_guarantee_image2">Choose
                                                file</label>
                                        </div>
                                        @if ($data->loan_guarantee_image2)
                                            <img src="{{ asset('storage/' . $data->loan_guarantee_image2) }}" alt="loan_guarantee_image2" style="width: 100px; height: 100px;">
                                        @else
                                            -
                                        @endif
                                        @error('loan_guarantee_image2')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="kk_image">Gambar KK</label>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="kk_image"
                                                name="kk_image" value="{{ old('kk_image') }}">
                                            <label class="custom-file-label" for="kk_image">Choose file</label>
                                        </div>
                                        @if ($data->kk_image)
                                            <img src="{{ asset('storage/' . $data->kk_image) }}" alt="kk_image" style="width: 100px; height: 100px;">
                                        @else
                                            -
                                        @endif
                                        @error('kk_image')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="id_card_image">Gambar ID card</label>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="id_card_image"
                                                name="id_card_image" value="{{ old('id_card_image') }}">
                                            <label class="custom-file-label" for="id_card_image">Choose file</label>
                                        </div>
                                        @if ($data->id_card_image)
                                            <img src="{{ asset('storage/' . $data->id_card_image) }}" alt="id_card_image" style="width: 100px; height: 100px;">
                                        @else
                                            -
                                        @endif
                                        @error('id_card_image')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="salary_slip_image1">Gambar bukti gaji 1</label>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="salary_slip_image1"
                                                name="salary_slip_image1" value="{{ old('salary_slip_image1') }}">
                                            <label class="custom-file-label" for="salary_slip_image1">Choose file</label>
                                        </div>
                                        @if ($data->salary_slip_image1)
                                            <img src="{{ asset('storage/' . $data->salary_slip_image1) }}" alt="salary_slip_image1" style="width: 100px; height: 100px;">
                                        @else
                                            -
                                        @endif
                                        @error('salary_slip_image1')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="salary_slip_image2">Gambar bukti gaji 2</label>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="salary_slip_image2"
                                                name="salary_slip_image2" value="{{ old('salary_slip_image2') }}">
                                            <label class="custom-file-label" for="salary_slip_image2">Choose file</label>
                                        </div>
                                        @if ($data->salary_slip_image2)
                                            <img src="{{ asset('storage/' . $data->salary_slip_image2) }}" alt="salary_slip_image2" style="width: 100px; height: 100px;">
                                        @else
                                            -
                                        @endif
                                        @error('salary_slip_image2')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    @include('templates.form.required')
                                </div>
                            </div>
                            <!-- /.card -->
                            <div class="mt-2 d-flex justify-content-center">
                                <button type="submit" class="btn btn-primary" style="margin-top: 10px;">Submit</button>
                            </div>
                        </form>
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
            $('#survey-form').submit(function() {
                $('#amount').inputmask('remove');
            });
        });
    </script>
@endpush
