@extends('templates.main')

@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Laporan Penagihan</h1>
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
                                <h3 class="card-title">Laporan Penagihan</h3>

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
                                        <form action="{{ route('billing-reports.index') }}" method="get">
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
                                        <form action="{{ route('billing-reports.export') }}" method="get">
                                            <input type="hidden" class="form-control" name="start_date" placeholder="Masukan Tanggal" value="{{ $start_date }}">
                                            <input type="hidden" class="form-control" name="end_date" placeholder="Masukan Tanggal" value="{{ $end_date }}">
                                            <button type="submit" class="btn btn-success mb-3 mr-1"><i class="fas fa-file-excel"></i> Export</button>
                                        </form>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table id="table" class="table table-bordered table-hover table-striped">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nomor Tagihan</th>
                                                <th>Nama Nasabah</th>
                                                <th>Nama Petugas</th>
                                                <th>Tanggal</th>
                                                <th>Status Tagihan</th>
                                                <th>Status Kunjungan</th>
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
@endsection

@push('styles')
    <!-- Moment -->
    <script src="{{ asset('adminlte') }}/plugins/moment/moment.min.js"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet"
        href="{{ asset('adminlte') }}/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
@endpush

@push('scripts')
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="{{ asset('adminlte') }}/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
    <script>
        $(document).ready(function() {
            //Date range picker
            $('#start_date').datetimepicker({
                format: 'YYYY-MM-DD'
            });
            $('#end_date').datetimepicker({
                format: 'YYYY-MM-DD'
            });
            // DataTables
            var table = $("#table").DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "{{ route('billing-reports.index') }}/fetch-data-table",
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
                "columns": [
                    { "data": "DT_RowIndex" },
                    { "data": "no_billing" },
                    { "data": "customer" },
                    { "data": "user" },
                    { "data": "date" },
                    { "data": "status" },
                    { "data": "billingStatuses.status" },
                    { "data": "billingStatuses.promise_date" },
                    { "data": "billingStatuses.payment_amount" },
                    { "data": "billingStatuses.evidence" },
                    { "data": "billingStatuses.description" },
                    { "data": "billingStatuses.signature_officer" },
                    { "data": "billingStatuses.signature_customer" },
                    { "data": "action" },
                    { "data": "details" }
                ],
                "columnDefs": [{
                        "targets": 0,
                        "className": 'text-center',
                        "searchable": false,
                    },
                    {
                        "orderable": false,
                        "searchable": false,
                        "targets": [ 9, 11, 12, 13, 14 ]
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
                "dom": `<<"d-flex justify-content-between"lf>Brt<"d-flex justify-content-between"ip>>`
            });
        });
    </script>
@endpush
