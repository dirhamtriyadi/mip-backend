@extends('templates.main')

@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Kehadiran</h1>
                    </div>
                    <div class="col-sm-6">
                        {{ Breadcrumbs::render('attendances') }}
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
                                <h3 class="card-title">Kehadiran</h3>

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
                                <div class="d-flex justify-content-end">
                                    <a href="{{ route('attendances.create') }}" class="btn btn-primary mb-3"><i class="fas fa-plus"></i> Tambah</a>
                                </div>
                                <div class="table-responsive">
                                    <table id="table" class="table table-bordered table-hover table-striped">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>User</th>
                                                <th>Kode Absen</th>
                                                <th>Tanggal</th>
                                                <th>Jam Masuk</th>
                                                <th>Jam Pulang</th>
                                                <th>Masuk Terlambat (menit)</th>
                                                <th>Pulang Lebih Awal (menit)</th>
                                                <th>Tipe Absensi</th>
                                                <th>Keterangan Masuk</th>
                                                <th>Keterangan Pulang</th>
                                                <th>Gambar</th>
                                                <th>Lokasi</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {{-- @forelse ($data as $item => $value)
                                                <tr>
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
                                                            <a href="{{ route('attendances.edit', $value->id) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i> Edit</a>
                                                            <form action="{{ route('attendances.destroy', $value->id) }}" class="ml-1" method="post">
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
@endsection

@push('styles')

@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            // DataTables
            $("#table").DataTable({
                "processing":true,
                "serverSide":true,
                "ajax": {
                    "url": "{{ route('attendances.index') }}/fetch-data-table",
                    "type": "post",
                    "data": {
                        "_token": "{{ csrf_token() }}"
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
                    { "data": "user" },
                    { "data": "code" },
                    { "data": "date" },
                    { "data": "time_check_in" },
                    { "data": "time_check_out" },
                    { "data": "late_duration" },
                    { "data": "early_leave_duration" },
                    { "data": "type" },
                    { "data": "reason_late" },
                    { "data": "reason_early_out" },
                    { "data": "image" },
                    { "data": "location" },
                    { "data": "action" }
                ],
                "columnDefs": [
                    { "orderable": false, "searchable": false, "targets": [0, 13] }
                ],
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
                "dom": `<<"d-flex justify-content-between"lf>Brt<"d-flex justify-content-between"ip>>`,
            });
        });
    </script>
@endpush
