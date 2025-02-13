@extends('templates.main')

@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Nasabah</h1>
                    </div>
                    <div class="col-sm-6">
                        {{ Breadcrumbs::render('customers') }}
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
                                <h3 class="card-title">Nasabah</h3>

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
                                    <a href="{{ route('customers.create') }}" class="btn btn-primary mb-3"><i
                                            class="fas fa-plus"></i> Tambah</a>
                                </div>
                                <div class="table-responsive">
                                    <table id="table" class="table table-bordered table-hover table-striped">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nomor Kontrak</th>
                                                <th>Nomor Rekening</th>
                                                <th>Nama Nasabah</th>
                                                <th>No HP</th>
                                                <th>Alamat</th>
                                                <th>Desa</th>
                                                <th>Kecamatan</th>
                                                <th>Nama Bank</th>
                                                <th>Nama Petugas</th>
                                                <th>Outstanding Awal</th>
                                                <th>Outstanding Sisa</th>
                                                <th>Outstanding Total</th>
                                                <th>Angsuran</th>
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
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "{{ route('customers.index') }}/fetch-data-table",
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
                "columns": [{
                        "data": "DT_RowIndex"
                    },
                    {
                        "data": "no_contract"
                    },
                    {
                        "data": "bank_account_number"
                    },
                    {
                        "data": "name_customer"
                    },
                    {
                        "data": "phone_number"
                    },
                    {
                        "data": "address"
                    },
                    {
                        "data": "village"
                    },
                    {
                        "data": "subdistrict"
                    },
                    {
                        "data": "name_bank"
                    },
                    {
                        "data": "name_officer"
                    },
                    {
                        "data": "os_start",
                        "render": $.fn.dataTable.render.number('.', ',', 0, 'Rp. ')
                    },
                    {
                        "data": "os_remaining",
                        "render": $.fn.dataTable.render.number('.', ',', 0, 'Rp. ')
                    },
                    {
                        "data": "os_total",
                        "render": $.fn.dataTable.render.number('.', ',', 0, 'Rp. ')
                    },
                    {
                        "data": "monthly_installments",
                        "render": $.fn.dataTable.render.number('.', ',', 0, 'Rp. ')
                    },
                    {
                        "data": "action"
                    },
                ],
                "columnDefs": [{
                    "orderable": false,
                    "searchable": false,
                    "targets": [0, 13]
                }, ],
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
                "dom": `<<"d-flex justify-content-between"lf>Brt<"d-flex justify-content-between"ip>>`,
            });
        });
    </script>
@endpush
