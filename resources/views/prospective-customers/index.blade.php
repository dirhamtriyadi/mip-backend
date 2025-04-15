@extends('templates.main')

@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Calon Nasabah</h1>
                    </div>
                    <div class="col-sm-6">
                        {{ Breadcrumbs::render('prospective-customers') }}
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
                        @session('success')
                            <div class="alert alert-success">
                                <h5><i class="icon fas fa-check"></i> Alert!</h5>
                                {{ session('success') }}
                            </div>
                        @endsession
                        <!-- Default box -->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Calon Nasabah</h3>

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
                                <div class="d-flex justify-content-between">
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="start_date">Rentang Tanggal</label>
                                        <form action="{{ route('prospective-customers.index') }}" method="get">
                                            <div class="d-flex">
                                                <div class="input-group date" id="start_date" data-target-input="nearest">
                                                    <input type="text" class="form-control datetimepicker-input"
                                                        data-target="#start_date" name="start_date"
                                                        placeholder="Masukan Tanggal" value="{{ $start_date }}">
                                                    <div class="input-group-append" data-target="#start_date"
                                                        data-toggle="datetimepicker">
                                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                    </div>
                                                </div>
                                                <span class="input-group-text">s/d</span>
                                                <div class="input-group date" id="end_date" data-target-input="nearest">
                                                    <input type="text" class="form-control datetimepicker-input"
                                                        data-target="#end_date" name="end_date"
                                                        placeholder="Masukan Tanggal" value="{{ $end_date }}">
                                                    <div class="input-group-append" data-target="#end_date"
                                                        data-toggle="datetimepicker">
                                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                    </div>
                                                </div>
                                                <button type="submit" class="btn btn-primary ml-2">Filter</button>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="d-flex flex-column justify-content-center">
                                        <div class="d-flex justify-content-end">
                                            <a href="{{ route('prospective-customers.create') }}"
                                                class="btn btn-primary mb-3"><i class="fas fa-plus"></i> Tambah</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table id="table" class="table table-bordered table-hover table-striped">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama</th>
                                                <th>Nomor KTP</th>
                                                <th>Bank</th>
                                                <th>KTP</th>
                                                <th>KK</th>
                                                <th>Nama Petugas</th>
                                                <th>Status</th>
                                                <th>Status Pesan</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
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
    <!-- Modal Select Officer -->
    <div class="modal fade" id="modal-proccess-customer">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Proses Calon Nasabah</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('prospective-customers.proccessProspectiveCustomer') }}" method="post">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="id" id="id">
                        <div class="form-group">
                            <label for="status">Status *</label>
                            <select name="status" id="status" class="form-control select2">
                                <option value="approved" {{ old('status') == 'approved' ? 'selected' : '' }}>
                                    Disetujui</option>
                                <option value="rejected" {{ old('status') == 'rejected' ? 'selected' : '' }}>
                                    Ditolak</option>
                            </select>

                            @error('status')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="status_message">Status Pesan *</label>
                            <textarea class="form-control" id="status_message" name="status_message" placeholder="Masukkan status pesan"
                                rows="3">{{ old('status_message') }}</textarea>
                            @error('status_message')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="name">Nama Calon Nasabah *</label>
                            <input type="text" class="form-control" id="name" name="name"
                                placeholder="Masukkan nama calon nasabah" value="{{ old('name') }}" readonly>
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="address">Alamat Calon Nasabah *</label>
                            <textarea class="form-control" id="address" name="address" placeholder="Masukkan alamat calon nasabah"
                                rows="3">{{ old('address') }}</textarea>
                            @error('address')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="no_ktp">Nomor KTP Calon Nasabah *</label>
                            <input type="text" class="form-control" id="no_ktp" name="no_ktp"
                                placeholder="Masukkan nomor ktp calon nasabah" value="{{ old('no_ktp') }}" readonly>
                            @error('no_ktp')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="address_status">Status Alamat Calon Nasabah</label>
                            <input type="text" class="form-control" id="address_status" name="address_status"
                                placeholder="Masukkan status alamat calon nasabah" value="{{ old('address_status') }}">
                            @error('address_status')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="phone_number">No Telepon Calon Nasabah</label>
                            <input type="text" class="form-control" id="phone_number" name="phone_number"
                                placeholder="Masukkan nomor telepon calon nasabah" value="{{ old('phone_number') }}">
                            @error('phone_number')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="npwp">NPWP Calon Nasabah</label>
                            <input type="text" class="form-control" id="npwp" name="npwp"
                                placeholder="Masukkan NPWP calon nasabah" value="{{ old('npwp') }}">
                            @error('npwp')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="user_id">Petugas</label>
                            <select name="user_id" id="user_id" class="form-control select2">
                                <option value="">Pilih Petugas</option>
                                @foreach ($users as $user)
                                    <option value="{{ old('user_id') ?? $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>

                            @error('user_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        @include('templates.form.required')
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
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
    <!-- InputMask -->
    <script src="{{ asset('adminlte') }}/plugins/moment/moment.min.js"></script>
    <script src="{{ asset('adminlte') }}/plugins/inputmask/jquery.inputmask.min.js"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="{{ asset('adminlte') }}/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
    <!-- Select2 -->
    <script src="{{ asset('adminlte') }}/plugins/select2/js/select2.full.min.js"></script>
    <script>
        $(function() {
            //Date range picker
            $('#start_date').datetimepicker({
                format: 'YYYY-MM-DD'
            });
            $('#end_date').datetimepicker({
                format: 'YYYY-MM-DD'
            });

            $('.select2').select2({
                theme: 'bootstrap4'
            });

            $("#table").DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "{{ route('prospective-customers.index') }}/fetch-data-table",
                    "type": "post",
                    "data": {
                        "_token": "{{ csrf_token() }}",
                        "start_date": "{{ $start_date }}",
                        "end_date": "{{ $end_date }}"
                    }
                },
                "responsive": true,
                // "lengthChange": false,
                "lengthMenu": [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "All"]
                ],
                "autoWidth": false,
                "columns": [{
                        "data": "DT_RowIndex"
                    },
                    {
                        "data": "name"
                    },
                    {
                        "data": "no_ktp"
                    },
                    {
                        "data": "bank"
                    },
                    {
                        "data": "ktp"
                    },
                    {
                        "data": "kk"
                    },
                    {
                        "data": "user"
                    },
                    {
                        "data": "status"
                    },
                    {
                        "data": "status_message"
                    },
                    {
                        "data": "action"
                    }
                ],
                "columnDefs": [{
                    "orderable": false,
                    "searchable": false,
                    "targets": [0, 7]
                }],
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
                "dom": `<<"d-flex justify-content-between"lf>Brt<"d-flex justify-content-between"ip>>`,
            });

            function toggleFormFields(status) {
                if (status === 'approved') {
                    $('.form-group').show(); // Tampilkan semua input
                } else if (status === 'rejected') {
                    $('.form-group').hide(); // Sembunyikan semua input
                    $('label[for="status"], #status').closest('.form-group').show(); // Tampilkan status
                    $('label[for="status_message"], #status_message').closest('.form-group')
                        .show(); // Tampilkan status pesan
                }
            }

            // Saat status berubah
            $('#status').on('change', function() {
                let selectedStatus = $(this).val();
                toggleFormFields(selectedStatus);
            });

            // Event listener saat modal akan ditampilkan
            $('#modal-proccess-customer').on('show.bs.modal', function(event) {
                let button = $(event.relatedTarget);
                let prospectiveCustomer = button.data('prospectivecustomer');

                if (prospectiveCustomer) {
                    $("#id").val(prospectiveCustomer.id);
                    $("#name").val(prospectiveCustomer.name);
                    $("#no_ktp").val(prospectiveCustomer.no_ktp);
                    $("#address").val(prospectiveCustomer.address);
                    $("#address_status").val(prospectiveCustomer.address_status);
                    $("#phone_number").val(prospectiveCustomer.phone_number);
                    $("#npwp").val(prospectiveCustomer.npwp);
                    $("#user_id").val(prospectiveCustomer.user_id).trigger('change');
                }

                // Panggil fungsi untuk menyesuaikan tampilan input berdasarkan status saat modal dibuka
                toggleFormFields($('#status').val());
            });
        });
    </script>
@endpush
