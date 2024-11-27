@extends('templates.main')

@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Akun Bank</h1>
                    </div>
                    <div class="col-sm-6">
                        {{  Breadcrumbs::render('bank-accounts') }}
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
                                <h3 class="card-title">Akun Bank</h3>

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
                                    <a href="{{ route('bank-accounts.create') }}" class="btn btn-primary mb-3"><i class="fas fa-plus"></i> Tambah</a>
                                </div>
                                <div class="table-responsive">
                                    <table id="table" class="table table-bordered table-hover table-striped">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nomor Rekening</th>
                                                <th>Nama Nasabah</th>
                                                <th>Alamat</th>
                                                <th>Nama Bank</th>
                                                <th>Total Tagihan</th>
                                                <th>Angsuran</th>
                                                {{-- <th>Sisa Angsuran</th> --}}
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {{-- @forelse ($data as $item => $value)
                                                <tr>
                                                    <td>{{ $item + 1 }}</td>
                                                    <td>{{ $value->no }}</td>
                                                    <td>{{ $value->name_customer }}</td>
                                                    <td>{{ $value->address }}</td>
                                                    <td>{{ $value->name_bank }}</td>
                                                    <td class="total-bill">{{ $value->total_bill }}</td>
                                                    <td class="installment">{{ $value->installment }}</td>
                                                    <td>{{ $value->remaining_installment }}</td>
                                                    <td>
                                                        <div class="d-flex justify-content-center">
                                                            <a href="{{ route('bank-accounts.edit', $value->id) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i> Edit</a>
                                                            <form action="{{ route('bank-accounts.destroy', $value->id) }}" class="ml-1" method="post">
                                                                @csrf
                                                                @method('delete')
                                                                <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Delete</button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td class="text-center" colspan="9">Data tidak ditemukan</td>
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

@push('scripts')
    <!-- InputMask -->
    <script src="{{ asset('adminlte') }}/plugins/inputmask/jquery.inputmask.min.js"></script>

    <script>
        $(document).ready(function() {
            // Format currency to Rupiah
            $('.total-bill, .installment').each(function() {
                var value = $(this).text();
                var formattedValue = new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                }).format(value);
                $(this).text(formattedValue);
            });

            // DataTables
            $("#table").DataTable({
                "processing":true,
                "serverSide":true,
                "ajax": {
                    "url": "{{ route('bank-accounts.index') }}/fetch-data-table",
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
                    { "data": "no" },
                    { "data": "name_customer" },
                    { "data": "address" },
                    { "data": "name_bank" },
                    { "data": "total_bill", "render": $.fn.dataTable.render.number('.', ',', 0, 'Rp ') },
                    { "data": "installment", "render": $.fn.dataTable.render.number('.', ',', 0, 'Rp ') },
                    { "data": "action" },
                ],
                "columnDefs": [
                    { "orderable": false, "targets": [0, 7] },
                ],
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
                "dom": `<<"d-flex justify-content-between"lf>Brt<"d-flex justify-content-between"ip>>`,
            });
        });
    </script>
@endpush
