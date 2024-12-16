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
                        {{ Breadcrumbs::render('billings') }}
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
                                        {{-- Reset selected data --}}
                                        <button type="button" class="btn btn-warning mb-3 mr-1" id="reset-selected"><i
                                            class="fas fa-redo"></i> Reset Data</button>
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
                                        {{-- Create to add new data --}}
                                        <a href="{{ route('billings.create') }}" class="btn btn-primary mb-3 mr-1"><i
                                                class="fas fa-plus"></i> Tambah</a>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table id="table" class="table table-bordered table-hover table-striped">
                                        <thead>
                                            <tr>
                                                <th class="text-center"><input type="checkbox" id="select-all"></th>
                                                <th>No</th>
                                                <th>Nomor Tagihan</th>
                                                <th>Nama Nasabah</th>
                                                <th>Nama Petugas</th>
                                                <th>Tanggal</th>
                                                <th>Tujuan Penagihan</th>
                                                <th>Bukti Kunjungan</th>
                                                <th>Keterangan (Kunjungan)</th>
                                                <th>Tanggal Janji Bayar</th>
                                                <th>Bukti Janji Bayar</th>
                                                <th>Keterangan (Janji Bayar)</th>
                                                <th>Jumlah Setoran</th>
                                                <th>Bukti Setoran</th>
                                                <th>Keterangan (Setoran)</th>
                                                <th>TTD Petugas</th>
                                                <th>TTD Nasabah</th>
                                                <th>Aksi</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {{-- @forelse ($data as $item => $value)
                                                <tr>
                                                    <td><input type="checkbox" class="checkbox" value="{{ $value->id }}"></td>
                                                    <td>{{ $item + 1 }}</td>
                                                    <td>{{ $value->user->name }}</td>
                                                    <td>{{ $value->code }}</td>
                                                    <td>{{ Carbon\Carbon::parse($value->date)->format('d-m-Y') }}</td>
                                                    <td>{{ Carbon\Carbon::parse($value->time_check_in)->format('H:i') }}</td>
                                                    <td>{{ $value->time_check_out ? Carbon\Carbon::parse($value->time_check_out)->format('H:i') : '-' }}</td>
                                                    <td>{{ $value->late_duration ?? '-'  }}</td>
                                                    <td>{{ $value->early_leave_duration ?? '-' }}</td>
                                                    <td>
                                                        @if ($value->type == 'present')
                                                            <span class="badge badge-success">{{ $value->type }}</span>
                                                        @elseif ($value->type == 'sick')
                                                            <span class="badge badge-info">{{ $value->type }}</span>
                                                        @elseif($value->type == 'permit')
                                                            <span class="badge badge-danger">{{ $value->type }}</span>
                                                        @else
                                                            <span class="badge badge-warning">{{ $value->type }}</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $value->reason_late ?? '-' }}</td>
                                                    <td>{{ $value->reason_early_out ?? '-' }}</td>
                                                    <td><a href="{{ asset('images/attendances/' . $value->image_check_in) }}" target="_blank">Masuk</a> {!! $value->image_check_out ? '| <a href="' . asset('images/attendances/' . $value->image_check_out) . '" target="_blank">Pulang</a>' : '| -' !!}</td>
                                                    @php
                                                        if (isset($value->location_check_in)) {
                                                            $location_check_in = explode(',', $value->location_check_in);
                                                        }
                                                        if (isset($value->location_check_out)) {
                                                            $location_check_out = explode(',', $value->location_check_out);
                                                        }

                                                        if (isset($value->location_check_in)) {
                                                            $latitude_check_in = $location_check_in[0];
                                                            $longitude_check_in = $location_check_in[1];
                                                        }
                                                        if (isset($value->location_check_out)) {
                                                            $latitude_check_out = $location_check_out[0];
                                                            $longitude_check_out = $location_check_out[1];
                                                        }
                                                    @endphp
                                                    <td>{!! $value->location_check_in ? '<a href="https://www.google.com/maps/search/?api=1&query=' . $latitude_check_in . ',' . $longitude_check_in .'" target="_blank">Masuk</a>' : '-' !!} {!! $value->location_check_out ? '| <a href="https://www.google.com/maps/search/?api=1&query=' . $latitude_check_out . ',' . $longitude_check_out .'" target="_blank">Pulang</a>' : '| -' !!}</td>
                                                    <td>
                                                        <div class="d-flex justify-content-center">
                                                            <a href="{{ route('billings.edit', $value->id) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i> Edit</a>
                                                            <form action="{{ route('billings.destroy', $value->id) }}" class="ml-1" method="post">
                                                                @csrf
                                                                @method('delete')
                                                                <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Delete</button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td class="text-center" colspan="14">Data tidak ditemukan</td>
                                                </tr>
                                            @endforelse --}}
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
                <form action="{{ route('billings.import') }}" method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        @csrf
                        <div class="form-group" style="margin-top: 10px;" id="form-import">
                            <label for="file">File Excel</label>

                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="file"
                                    name="file" value="{{ old('file') }}">
                                <label class="custom-file-label" for="file">Choose file</label>
                            </div>
                            @error('file')
                                <div class="alert alert-danger">{{ $message }}</div>
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
                {{-- <form action="{{ route('billings.massSelectOfficer') }}" method="post"> --}}
                <form id="form-mass-select-officer">
                    <div class="modal-body">
                        @csrf
                        <div class="form-group" id="form-mass-select-officer">
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
@endpush

@push('scripts')
    <!-- Select2 -->
    <script src="{{ asset('adminlte') }}/plugins/select2/js/select2.full.min.js"></script>
    <!-- bs-custom-file-input -->
    <script src="{{ asset('adminlte') }}/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
    <script>
        $(document).ready(function() {
            // File input
            bsCustomFileInput.init();
            // Initialize Select2
            $('#user_id').select2({
                theme: 'bootstrap4'
            });
            // DataTables
            var table = $("#table").DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "{{ route('billings.index') }}/fetch-data-table",
                    "type": "post",
                    "data": {
                        "_token": "{{ csrf_token() }}"
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
                "columns": [
                    { "data": "select" },
                    { "data": "DT_RowIndex" },
                    { "data": "no_billing" },
                    { "data": "customer" },
                    { "data": "user" },
                    { "data": "date" },
                    { "data": "destination" },
                    { "data": "image_visit" },
                    { "data": "description_visit" },
                    { "data": "promise_date" },
                    { "data": "image_promise" },
                    { "data": "description_promise" },
                    { "data": "amount" },
                    { "data": "image_amount" },
                    { "data": "description_amount" },
                    { "data": "signature_officer" },
                    { "data": "signature_customer" },
                    { "data": "action" },
                    { "data": "details" }
                ],
                "columnDefs": [{
                        "targets": 0,
                        "className": 'text-center',
                        "searchable": false,
                        "orderable": false,
                        "width": "10%",
                    },
                    {
                        "orderable": false,
                        "searchable": false,
                        "targets": 17
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
                            url: '{{ route('billings.massDelete') }}',
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
                            url: '{{ route('billings.massSelectOfficer') }}',
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
