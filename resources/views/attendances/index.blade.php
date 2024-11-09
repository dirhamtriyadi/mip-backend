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
                        {{-- <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item"><a href="#">Layout</a></li>
                            <li class="breadcrumb-item active">Fixed Layout</li>
                        </ol> --}}
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
                                    <table class="table table-bordered table-hover table-striped">
                                        <thead class="table-dark">
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
                                            @forelse ($data as $item => $value)
                                                <tr>
                                                    <td>{{ $item + 1 }}</td>
                                                    <td>{{ $value->user->name }}</td>
                                                    <td>{{ $value->code }}</td>
                                                    {{-- format date to Senin, 27 Oktober 2024 --}}
                                                    {{-- <td>{{ $value->date->format('l, d F Y') }}</td> --}}
                                                    <td>{{ Carbon\Carbon::parse($value->date)->format('d-m-Y') }}</td>
                                                    {{-- <td>{{ $value->date }}</td> --}}
                                                    {{-- <td class="time-mask">{{ $value->time }}</td> --}}
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
                                                    {{-- redirect to new tab see image --}}
                                                    <td><a href="{{ asset('images/attendances/' . $value->image_check_in) }}" target="_blank">Masuk</a> {!! $value->image_check_out ? '| <a href="' . asset('images/attendances/' . $value->image_check_out) . '" target="_blank">Pulang</a>' : '| -' !!}</td>
                                                    {{-- redirect to gmpas with latitude and longititude and split string $value->image --}}
                                                    @php
                                                        $location_check_in = explode(',', $value->location_check_in);
                                                        if (isset($value->location_check_out)) {
                                                            $location_check_out = explode(',', $value->location_check_out);
                                                        }

                                                        $latitude_check_in = $location_check_in[0];
                                                        $longitude_check_in = $location_check_in[1];
                                                        if (isset($value->location_check_out)) {
                                                            $latitude_check_out = $location_check_out[0];
                                                            $longitude_check_out = $location_check_out[1];
                                                        }
                                                    @endphp
                                                    <td><a href="https://www.google.com/maps/search/?api=1&query={{ $latitude_check_in }},{{ $longitude_check_in }}" target="_blank">Masuk</a> {!! $value->location_check_out ? '| <a href="https://www.google.com/maps/search/?api=1&query=' . $latitude_check_out . ',' . $longitude_check_out .'" target="_blank">Pulang</a>' : '| -' !!}</td>
                                                    <td class="d-flex justify-content-center">
                                                        <a href="{{ route('attendances.edit', $value->id) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i> Edit</a>
                                                        <form action="{{ route('attendances.destroy', $value->id) }}" method="post">
                                                            @csrf
                                                            @method('delete')
                                                            <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Delete</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td class="text-center" colspan="14">Data tidak ditemukan</td>
                                                </tr>
                                            @endforelse
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
