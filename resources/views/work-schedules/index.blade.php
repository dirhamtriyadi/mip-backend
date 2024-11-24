@extends('templates.main')

@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Jam dan Hari Kerja</h1>
                    </div>
                    <div class="col-sm-6">
                        {{  Breadcrumbs::render('work-schedules') }}
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
                                <h3 class="card-title">Jam dan Hari Kerja</h3>

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
                                {{-- <div class="d-flex justify-content-end">
                                    <a href="{{ route('work-schedules.create') }}" class="btn btn-primary mb-3"><i class="fas fa-plus"></i> Tambah</a>
                                </div> --}}
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover table-striped">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>No</th>
                                                <th>Jam Mulai Kerja</th>
                                                <th>Jam Selesai Kerja</th>
                                                <th>Hari Kerja</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($data as $item => $value)
                                                <tr>
                                                    <td>{{ $item + 1 }}</td>
                                                    <td>{{ Carbon\Carbon::parse($value->work_start_time)->format('H:i') }}</td>
                                                    <td>{{ Carbon\Carbon::parse($value->work_end_time)->format('H:i') }}</td>
                                                    <td>{{ json_encode($value->working_days) }}</td>
                                                    <td>
                                                        <div class="d-flex justify-content-center">
                                                            <a href="{{ route('work-schedules.edit', $value->id) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i> Edit</a>
                                                            {{-- <form action="{{ route('work-schedules.destroy', $value->id) }}" method="post">
                                                                @csrf
                                                                @method('delete')
                                                                <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Delete</button>
                                                            </form> --}}
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td class="text-center" colspan="5">Data tidak ditemukan</td>
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
