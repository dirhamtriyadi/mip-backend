@extends('templates.main')

@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Tambah User</h1>
                    </div>
                    <div class="col-sm-6">
                        {{ Breadcrumbs::render('users.create') }}
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
                                <h3 class="card-title">Tambah User</h3>

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
                                    <a href="{{ route('users.index') }}" class="btn btn-warning mb-3"><i class="fas fa-arrow-left"></i> Kembali</a>
                                </div>
                                <form action="{{ route('users.store') }}" method="POST">
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
                                        <label for="email">Email *</label>
                                        <input type="email" class="form-control" id="email" name="email"
                                            placeholder="Masukkan Email" value="{{ old('email') }}" required>

                                        @error('email')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="password">Password *</label>
                                        <input type="password" class="form-control" id="password" name="password"
                                            placeholder="Masukkan Password" value="{{ old('password') }}" required>

                                        @error('password')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="password_confirmation">Konfirmasi Password *</label>
                                        <input type="password" class="form-control" id="password_confirmation"
                                            name="password_confirmation" placeholder="Masukkan Konfirmasi Password"
                                            value="{{ old('password') }}" required>

                                        @error('password_confirmation')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="nik">NIK *</label>
                                        <input type="text" class="form-control" id="nik" name="nik"
                                            placeholder="Masukkan NIK" value="{{ old('nik') }}" required>

                                        @error('nik')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="role">Role</label>
                                        <select class="form-control" id="role" name="role[]" multiple>
                                            <option value="">Pilih Role</option>
                                            @foreach ($roles as $role)
                                                {{-- <option value="{{ $role->name }}">{{ $role->name }}</option> --}}
                                                {{-- create option select with old blade --}}
                                                <option value="{{ $role->name }}"
                                                    {{ in_array($role->name, old('role', [])) ? 'selected' : '' }}>
                                                    {{ $role->name }}
                                                </option>
                                            @endforeach
                                        </select>

                                        @error('role')
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
