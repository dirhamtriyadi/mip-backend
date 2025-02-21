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
                        <h1>Laporan Petugas</h1>
                    </div>
                    <div class="col-sm-6">
                        {{ Breadcrumbs::render('officer-reports') }}
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
                                <h3 class="card-title">Laporan Petugas</h3>

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
                                        <form action="{{ route('attendance-reports.index') }}" method="get">
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
                                        <form action="{{ route('attendance-reports.export') }}" method="get">
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
                                                <th>Nama</th>
                                                <th>Visit</th>
                                                <th>Janji Bayar</th>
                                                <th>Bayar</th>
                                                <th>Total Bayar</th>
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
    {{-- Modal XL --}}
    <div class="modal fade" id="modal-xl">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Extra Large Modal</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>One fine body&hellip;</p>
                </div>
                <div class="modal-footer justify-content-end">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
                {{-- <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div> --}}
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
@endsection

@push('scripts')
    <!-- InputMask -->
    <script src="{{ asset('adminlte') }}/plugins/moment/moment.min.js"></script>
    <script src="{{ asset('adminlte') }}/plugins/inputmask/jquery.inputmask.min.js"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="{{ asset('adminlte') }}/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
    <script>
        $(function() {
            //Date range picker
            $('#start_date').datetimepicker({
                format: 'YYYY-MM-DD'
            });
            $('#end_date').datetimepicker({
                format: 'YYYY-MM-DD'
            });
            // Attach event listener to all buttons with id 'btn-detail'
            // Attach event listener to all buttons with id 'btn-detail'
            // Attach event listener to all buttons with id 'btn-detail'
            $(document).on('click', '#btn-detail', function() {
                let data = $(this).data('attendance');
                // reset modal title and body
                $('.modal-title').text('');
                $('.modal-body').html('');
                // open modal xl
                $('#modal-xl').modal('show');
                // set modal title
                $('.modal-title').text(data.name);
                // set modal body
                let body = '';
                body += '<div class="table-responsive">';
                body += '<table class="table table-bordered table-hover table-striped">';
                body += '<thead class="table-dark">';
                body += '<tr>';
                body += '<th>No</th>';
                body += '<th>Kode Absen</th>';
                body += '<th>Tanggal</th>';
                body += '<th>Jam Masuk</th>';
                body += '<th>Jam Pulang</th>';
                body += '<th>Masuk Terlambat (menit)</th>';
                body += '<th>Pulang Lebih Awal (menit)</th>';
                body += '<th>Tipe Absen</th>';
                body += '<th>Keterangan Masuk</th>';
                body += '<th>Keterangan Pulang</th>';
                body += '<th>Gambar</th>';
                body += '<th>Lokasi</th>';
                body += '</tr>';
                body += '</thead>';
                body += '<tbody>';
                data.attendances.forEach((item, index) => {
                    body += '<tr>';
                    body += '<td>' + (index + 1) + '</td>';
                    body += '<td>' + item.code + '</td>';
                    body += '<td>' + moment(item.date).format('DD-MM-YYYY') + '</td>';
                    body += '<td>' + moment(item.time_check_in, 'HH:mm:ss').format('HH:mm') + '</td>';
                    body += '<td>' + item.late_duration + '</td>'
                    body += '<td>' + (item.early_leave_duration ?? '-') + '</td>'
                    body += '<td>' + (item.time_check_out ? moment(item.time_check_out, 'HH:mm:ss').format('HH:mm') : '-') + '</td>';
                    body += '<td>' + (item.type == 'present' ? '<span class="badge badge-success">' + item.type + '</span>' : (item.type == 'sick' ? '<span class="badge badge-info">' + item.type + '</span>' : '<span class="badge badge-danger">' + item.type + '</span>')) + '</td>';
                    body += '<td>' + (item.reason_late == null ? '-' : item.reason_late) + '</td>';
                    body += '<td>' + (item.reason_early_out == null ? '-' : item.reason_early_out) + '</td>';
                    body += `<td>` + (item.image_check_in ? `<a href='{{ asset('images/attendances/`+item.image_check_in+`') }}' target='_blank'>Masuk</a>` : '-') + (item.image_check_out ? ` | <a href='{{ asset('images/attendances/`+item.image_check_out+`') }}' target='_blank'>Pulang</a>` : '-') + `</td>`;
                    let locationCheckIn = item.location_check_in ? item.location_check_in.split(',') : null;
                    let locationCheckOut = item.location_check_out ? item.location_check_out.split(',') : null;
                    if (locationCheckIn) {
                        let latitudeCheckIn = locationCheckIn[0];
                        let longitudeCheckIn = locationCheckIn[1];
                        body += `<td><a href='https://www.google.com/maps/search/?api=1&query=` + latitudeCheckIn + ',' + longitudeCheckIn + `' target='_blank'>Masuk</a>`;
                    } else {
                        body += '<td>-';
                    }
                    if (locationCheckOut) {
                        let latitudeCheckOut = locationCheckOut[0];
                        let longitudeCheckOut = locationCheckOut[1];
                        body += ` | <a href='https://www.google.com/maps/search/?api=1&query=` + latitudeCheckOut + ',' + longitudeCheckOut + `' target='_blank'>Pulang</a></td>`;
                    } else {
                        body += '<br>-</td>';
                    }
                    body += '</tr>';
                });
                body += '</tbody>';
                body += '</table>';
                body += '</div>';
                $('.modal-body').html(body);
            });

            // Reset modal content when closed
            $('#modal-xl').on('hidden.bs.modal', function () {
                $('.modal-title').text('');
                $('.modal-body').html('');
            });

            // DataTables
            $("#table").DataTable({
                "processing":true,
                "serverSide":true,
                "ajax": {
                    "url": "{{ route('officer-reports.index') }}/fetch-data-table",
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
                "columns": [
                    { "data": "DT_RowIndex" },
                    { "data": "name" },
                    { "data": "visit" },
                    { "data": "promise_to_pay" },
                    { "data": "pay" },
                    { "data": "total_pay" },
                    { "data": "action" }
                ],
                "columnDefs": [
                    { "orderable": false, "searchable": false, "targets": [0, 6] }
                ],
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
                "dom": `<<"d-flex justify-content-between"lf>Brt<"d-flex justify-content-between"ip>>`,
            });
        });
    </script>
@endpush
