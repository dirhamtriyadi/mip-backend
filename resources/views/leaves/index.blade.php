@extends('templates.main')

@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Cuti</h1>
                    </div>
                    <div class="col-sm-6">
                        {{ Breadcrumbs::render('leaves') }}
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
                                <h3 class="card-title">Cuti</h3>

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
                                    <a href="{{ route('leaves.create') }}" class="btn btn-primary mb-3"><i class="fas fa-plus"></i> Tambah</a>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover table-striped">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>No</th>
                                                <th>User</th>
                                                <th>Tanggal Mulai Cuti</th>
                                                <th>Tanggal Akhir Cuti</th>
                                                <th>Status</th>
                                                <th>Balasan Atasan</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($data as $item => $value)
                                                <tr>
                                                    <td>{{ $item + 1 }}</td>
                                                    <td>{{ $value->user->name }}</td>
                                                    <td>{{ Carbon\Carbon::parse($value->start_date)->format('d-m-Y') }}</td>
                                                    <td>{{ Carbon\Carbon::parse($value->end_date)->format('d-m-Y') }}</td>
                                                    <td>
                                                        @if ($value->status == 'pending')
                                                            <span class="badge badge-warning">{{ $value->status }}</span>
                                                        @elseif ($value->status == 'approved')
                                                            <span class="badge badge-success">{{ $value->status }}</span>
                                                        @elseif($value->status == 'rejected')
                                                            <span class="badge badge-danger">{{ $value->status }}</span>
                                                        @else
                                                            <span class="badge badge-danger">{{ $value->status }}</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $value->response }}</td>
                                                    <td>
                                                        <div class="d-flex justify-content-center">
                                                            @if ($value->status == 'pending')
                                                                <button type="button" class="btn btn-sm btn-default" data-toggle="modal" data-target="#modal-xl" data-leave_id="{{ $value->id }}">
                                                                    <i class="fas fa-reply"></i> Tanggapi
                                                                </button>
                                                            @endif
                                                            <a href="{{ route('leaves.edit', $value->id) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i> Edit</a>
                                                            <form action="{{ route('leaves.destroy', $value->id) }}" method="post">
                                                                @csrf
                                                                @method('delete')
                                                                <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Delete</button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td class="text-center" colspan="7">Data tidak ditemukan</td>
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

    {{-- Modal --}}
    <div class="modal fade" id="modal-xl">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Tanggapi</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="response-form" method="post">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="leave_id" id="leave_id">

                        <div class="form-group">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="form-control">
                                <option value="approved">Disetujui</option>
                                <option value="rejected">Ditolak</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="response">Balasan</label>
                            <textarea name="response" id="response" class="form-control" rows="5"></textarea>
                        </div>

                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#modal-xl').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget) // Button that triggered the modal
                var leaveId = button.data('leave_id') // Extract info from data-* attributes
                var modal = $(this)
                modal.find('.modal-body #leave_id').val(leaveId)
                var action = "{{ url('leaves') }}/" + leaveId + "/response";
                $('#response-form').attr('action', action);
            })
        });
    </script>
@endpush
