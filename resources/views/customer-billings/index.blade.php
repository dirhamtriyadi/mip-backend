@extends('templates.main')

@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Penagihan</h1>
                    </div>
                    <div class="col-sm-6">
                        {{ Breadcrumbs::render('customer-billings') }}
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
                                <h3 class="card-title">Penagihan</h3>

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
                                    <div>
                                        {{-- Create to select officer --}}
                                        <button type="button" class="btn btn-info mb-3 mr-1" data-toggle="modal"
                                            data-target="#modal-mass-select-officer">
                                            <i class="fas fa-user"></i> Pilih Petugas
                                        </button>
                                        {{-- Delete selected data --}}
                                        <button type="button" class="btn btn-danger mb-3 mr-1" id="delete-selected"><i
                                                class="fas fa-trash"></i> Hapus</button>
                                    </div>
                                    <div>
                                        {{-- Create to import data from excel --}}
                                        <button type="button" class="btn btn-success mb-3 mr-1" data-toggle="modal"
                                            data-target="#modal-import">
                                            <i class="fas fa-file-excel"></i> Import
                                        </button>
                                        {{-- Create to download template import excel --}}
                                        <a href="{{ route('customer-billings.templateImport') }}"
                                            class="btn btn-info mb-3 mr-1"><i class="fas fa-download"></i> Template
                                            Import</a>
                                        {{-- Create to add new data --}}
                                        <a href="{{ route('customer-billings.create') }}"
                                            class="btn btn-primary mb-3 mr-1"><i class="fas fa-plus"></i> Tambah</a>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <div class="form-group" style="margin-top: 10px;">
                                        <label for="start_date">Rentang Tanggal</label>
                                        <form action="{{ route('customer-billings.index') }}" method="get">
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
                                        {{-- Create to import data from excel --}}
                                        {{-- <form action="{{ route('customer-billing-reports.export') }}" method="get">
                                            <input type="hidden" class="form-control" name="start_date"
                                                placeholder="Masukan Tanggal" value="{{ $start_date }}">
                                            <input type="hidden" class="form-control" name="end_date"
                                                placeholder="Masukan Tanggal" value="{{ $end_date }}">
                                            <button type="submit" class="btn btn-success mb-3 mr-1"><i
                                                    class="fas fa-file-excel"></i> Export</button>
                                        </form> --}}
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table id="table" class="table table-bordered table-hover table-striped">
                                        <thead>
                                            <tr>
                                                <th><input type="checkbox" id="select-all" /></th>
                                                <th>No</th>
                                                <th>Nomor Tagihan</th>
                                                <th>Nomor Kontrak</th>
                                                <th>Nama Nasabah</th>
                                                <th>Nama Petugas</th>
                                                <th>Bank</th>
                                                <th>Status Penagihan</th>
                                                <th>Tanggal Penagihan</th>
                                                <th>Tanggal Janji Bayar</th>
                                                <th>Jumlah Bayar</th>
                                                <th>Bukti (Kunjungan/Janji Bayar/Bayar)</th>
                                                <th>Keterangan Kunjungan</th>
                                                <th>TTD Petugas</th>
                                                <th>TTD Nasabah</th>
                                                <th>Aksi</th>
                                                <th></th>
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

    <!-- Modal Import -->
    <div class="modal fade" id="modal-import">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Import Penagihan</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('customer-billings.import') }}" method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        @csrf
                        <div class="form-group">
                            <label for="bank_id">Bank *</label>
                            <select name="bank_id" id="bank_id" class="form-control">
                                <option value="">Pilih Bank</option>
                                @foreach ($banks as $bank)
                                    <option value="{{ old('bank_id') ?? $bank->id }}">{{ $bank->name }}</option>
                                @endforeach
                            </select>

                            @error('bank_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="user_id">Petugas</label>
                            <select name="user_id" id="user_id" class="form-control">
                                <option value="">Pilih Petugas</option>
                                @foreach ($users as $user)
                                    <option value="{{ old('user_id') ?? $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>

                            @error('user_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group" style="margin-top: 10px;" id="form-import">
                            <label for="file">File Excel</label>

                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="file" name="file"
                                    value="{{ old('file') }}">
                                <label class="custom-file-label" for="file">Choose file</label>
                            </div>
                            @error('file')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        @include('templates.form.required')
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

    <!-- Modal Select Officer -->
    <div class="modal fade" id="modal-mass-select-officer">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Pilih Petugas</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                {{-- <form action="{{ route('customer-billings.massSelectOfficer') }}" method="post"> --}}
                <form id="form-mass-select-officer">
                    <div class="modal-body">
                        @csrf
                        <div class="form-group">
                            <label for="user_id">Petugas</label>
                            <select name="user_id" id="user_id" class="form-control">
                                <option value="">Pilih Petugas</option>
                                @foreach ($users as $user)
                                    <option value="{{ old('user_id') ?? $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>

                            @error('user_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
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
    <!-- bs-custom-file-input -->
    <script src="{{ asset('adminlte') }}/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
    <script>
        $(document).ready(function() {
            //Date range picker
            $('#start_date').datetimepicker({
                format: 'YYYY-MM-DD'
            });
            $('#end_date').datetimepicker({
                format: 'YYYY-MM-DD'
            });
            // File input
            bsCustomFileInput.init();
            // Initialize Select2
            $('#user_id').select2({
                theme: 'bootstrap4'
            });
            $('#bank_id').select2({
                theme: 'bootstrap4'
            });
            // DataTables
            var table = $("#table").DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "{{ route('customer-billings.index') }}/fetch-data-table",
                    "type": "post",
                    "data": {
                        "_token": "{{ csrf_token() }}",
                        "start_date": "{{ $start_date }}",
                        "end_date": "{{ $end_date }}"
                    }
                },
                "responsive": {
                    details: {
                        type: 'column',
                        target: -1
                    }
                },
                // "lengthChange": false,
                "lengthMenu": [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "All"]
                ],
                "autoWidth": false,
                "columns": [{
                        "data": "select"
                    },
                    {
                        "data": "DT_RowIndex"
                    },
                    {
                        "data": "bill_number"
                    },
                    {
                        "data": "no_contract"
                    },
                    {
                        "data": "customer"
                    },
                    {
                        "data": "user"
                    },
                    {
                        "data": "bank"
                    },
                    {
                        "data": "status"
                    },
                    {
                        "data": "date_exec"
                    },
                    {
                        "data": "promise_date"
                    },
                    {
                        "data": "payment_amount"
                    },
                    {
                        "data": "proof"
                    },
                    {
                        "data": "description"
                    },
                    {
                        "data": "signature_officer"
                    },
                    {
                        "data": "signature_customer"
                    },
                    {
                        "data": "action"
                    },
                    {
                        "data": "details"
                    }
                ],
                "columnDefs": [{
                        "targets": 0,
                        "searchable": false,
                        "orderable": false,
                    },
                    {
                        "orderable": false,
                        "searchable": false,
                        "targets": [11, 13, 14, 15, 16]
                    },
                    {
                        "targets": -1,
                        "className": 'dtr-control arrow-right',
                        "searchable": false,
                        "orderable": false,
                        "width": "10%",
                    },
                ],
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
                "dom": `<<"d-flex justify-content-between"lf>Brt<"d-flex justify-content-between"ip>>`,
                "drawCallback": function(settings) {
                    $('#select-all').prop('checked', false);
                }
            });

            // Prevent checkbox click from triggering row expansion
            $('#table').on('click', 'input[type="checkbox"]', function(e) {
                e.stopPropagation();
            });

            // Select all checkboxes
            $('#select-all').click(function() {
                if (this.checked) {
                    $('.checkbox').each(function() {
                        this.checked = true;
                    });
                } else {
                    $('.checkbox').each(function() {
                        this.checked = false;
                    });
                }
            });

            // If all checkbox checkboxes are checked, check the select-all checkbox
            $(document).on('change', '.checkbox', function() {
                if ($('.checkbox:checked').length === $('.checkbox').length) {
                    $('#select-all').prop('checked', true);
                } else {
                    $('#select-all').prop('checked', false);
                }
            });

            // Delete selected items
            $('#delete-selected').click(function() {
                var selected = [];
                $('.checkbox:checked').each(function() {
                    selected.push($(this).val());
                });

                if (selected.length > 0) {
                    if (confirm('Are you sure you want to delete the selected items?')) {
                        $.ajax({
                            url: '{{ route('customer-billings.massDelete') }}',
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                ids: selected
                            },
                            success: function(response) {
                                // table.ajax.reload();
                                location.reload();
                            }
                        });
                    }
                } else {
                    alert('Please select at least one item to delete.');
                }
            });

            // Mass select officer
            $('#form-mass-select-officer').submit(function(e) {
                e.preventDefault();

                var user_id = $('#user_id').val();
                var selected = [];
                $('.checkbox:checked').each(function() {
                    selected.push($(this).val());
                });

                if (selected.length > 0) {
                    if (user_id) {
                        $.ajax({
                            url: '{{ route('customer-billings.massSelectOfficer') }}',
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                ids: selected,
                                user_id: user_id
                            },
                            success: function(response) {
                                // table.ajax.reload();
                                location.reload();
                            }
                        });
                    } else {
                        alert('Please select an officer.');
                    }
                } else {
                    alert('Please select at least one item to assign an officer.');
                }
            });
        });
    </script>
@endpush
