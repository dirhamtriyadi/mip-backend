@extends('templates.main')

@push('styles')
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet"
        href="{{ asset('adminlte') }}/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
@endpush

@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Detail Laporan Kehadiran</h1>
                    </div>
                    <div class="col-sm-6">
                        {{ Breadcrumbs::render('attendance-reports.show', $attendanceReport) }}
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
                                <h3 class="card-title">Detail Data Kehadiran</h3>

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
                                <div class="table-responsive">
                                    <table>
                                        <tr>
                                            <td><b>Nama</b></td>
                                            <td>:</td>
                                            <td>{{ $attendanceReport->name }}</td>
                                        </tr>
                                        <tr>
                                            <td><b>NIK</b></td>
                                            <td>:</td>
                                            <td>{{ $attendanceReport->detail_users->nik ?? '-' }}</td>
                                        </tr>
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
                        <!-- Default box -->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Detail Laporan Kehadiran</h3>

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
                                <div class="table-responsive">
                                    <table id="table" class="table table-bordered table-hover table-striped">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Tanggal</th>
                                                <th>Tipe Absensi</th>
                                                <th>Masuk Terlambat (menit)</th>
                                                <th>Pulang Lebih Awal (menit)</th>
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

@push('scripts')
    <script>
        // DataTables
        $("#table").DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "{{ route('attendance-reports.index') }}/fetch-data-table-by-user",
                "type": "post",
                "data": {
                    "_token": "{{ csrf_token() }}",
                    "id": "{{ $attendanceReport->id }}",
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
                    "data": "date"
                },
                {
                    "data": "type"
                },
                {
                    "data": "late_duration"
                },
                {
                    "data": "early_leave_duration"
                }
            ],
            "columnDefs": [{
                "orderable": false,
                "searchable": false,
                "targets": [0, 5]
            }],
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
            "dom": `<<"d-flex justify-content-between"lf>Brt<"d-flex justify-content-between"ip>>`,
        });
    </script>
@endpush
