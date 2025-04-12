@extends('templates.main')

@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Survei</h1>
                    </div>
                    <div class="col-sm-6">
                        {{ Breadcrumbs::render('prospective-customer-surveys') }}
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
                                <h3 class="card-title">Survei</h3>

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
                                        {{-- Delete selected data --}}
                                        <button type="button" class="btn btn-danger mb-3 mr-1" id="delete-selected"><i
                                                class="fas fa-trash"></i> Hapus</button>
                                    </div>
                                    <div>
                                        {{-- Create to add new data --}}
                                        <a href="{{ route('prospective-customer-surveys.create') }}"
                                            class="btn btn-primary mb-3 mr-1"><i class="fas fa-plus"></i> Tambah</a>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table id="table" class="table table-bordered table-hover table-striped">
                                        <thead>
                                            <tr>
                                                <th><input type="checkbox" id="select-all" /></th>
                                                <th>No</th>
                                                <th>Petugas</th>
                                                <th>Status</th>
                                                <th>Nama</th>
                                                <th>Alamat</th>
                                                <th>No. KTP</th>
                                                <th>Status Alamat KTP</th>
                                                <th>No Telp</th>
                                                <th>NPWP</th>
                                                <th>Jenis Pekerjaan</th>
                                                <th>Nama Perusahaan</th>
                                                <th>Jabatan</th>
                                                <th>Lama Kerja</th>
                                                <th>Status Karyawan</th>
                                                <th>Gaji</th>
                                                <th>Usaha Tambahan</th>
                                                <th>Biaya hidup per bulan</th>
                                                <th>Tanggungan Anak</th>
                                                <th>Tanggungan Istri</th>
                                                <th>Pekerjaan Pasangan</th>
                                                <th>Usaha Pasangan</th>
                                                <th>Pendapatan Pasangan</th>
                                                <th>Hutang Bank</th>
                                                <th>Hutang Koperasi</th>
                                                <th>Hutang Perorangan</th>
                                                <th>Hutang Online</th>
                                                <th>Analisa Karakter Nasabah</th>
                                                <th>Analisa Laporan Keuangan</th>
                                                <th>Hasil Slik</th>
                                                <th>Nama Pemberi Informasi</th>
                                                <th>Jabatan Pemberi Informasi</th>
                                                <th>Kondisi Tempat Kerja</th>
                                                <th>Banyak Karyawan</th>
                                                <th>Lama Usaha Kantor</th>
                                                <th>Alamat Kantor</th>
                                                <th>Telepon Kantor</th>
                                                <th>pengajuan</th>
                                                <th>Nama Rekomendasi dari Vendor</th>
                                                <th>Nama Rekomendasi dari Bendahara</th>
                                                <th>Lainnya</th>
                                                <th>Nama Wawancara 1</th>
                                                <th>Jenis Kelamin Wawancara 1</th>
                                                <th>Hubungan Sumber Informasi Wawancara 1</th>
                                                <th>Karakter Sumber Informasi Wawancara 1</th>
                                                <th>Kenal Dengan Calon Nasabah Wawancara 1?</th>
                                                <th>Calon Nasabah Tinggal di Alamat Wawancara 1?</th>
                                                <th>Lama Tinggal Wawancara 1</th>
                                                <th>Status Kepemilikan Rumah Wawancara 1</th>
                                                <th>Status Calon Nasabah Wawancara 1</th>
                                                <th>Jumlah Tanggungan Wawancara 1</th>
                                                <th>Karakter Calon Nasabah Wawancara 1</th>
                                                <th>Nama Wawancara 2</th>
                                                <th>Jenis Kelamin Wawancara 2</th>
                                                <th>Hubungan Sumber Informasi Wawancara 2</th>
                                                <th>Karakter Sumber Informasi Wawancara 2</th>
                                                <th>Kenal Dengan Calon Nasabah Wawancara 2?</th>
                                                <th>Calon Nasabah Tinggal di Alamat Wawancara 2?</th>
                                                <th>Lama Tinggal Wawancara 2</th>
                                                <th>Status Kepemilikan Rumah Wawancara 2</th>
                                                <th>Status Calon Nasabah Wawancara 2</th>
                                                <th>Jumlah Tanggungan Wawancara 2</th>
                                                <th>Karakter Calon Nasabah Wawancara 2</th>
                                                <th>Catatan Rekomendasi PT</th>
                                                <th>Keterangan Rekomendasi PT</th>
                                                <th>Tempat</th>
                                                <th>Tanggal</th>
                                                <th>Lokasi</th>
                                                <th>TTD Petugas</th>
                                                <th>TTD Nasabah</th>
                                                <th>TTD Pasangan/Penanggung Jawab</th>
                                                <th>Foto Gedung</th>
                                                <th>Foto Nasabah dan KTP</th>
                                                <th>Foto Jaminan</th>
                                                <th>Foto KK dan ID Card</th>
                                                <th>Slip Gaji</th>
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
                {{-- <form action="{{ route('customer-billings.massSelectOfficer') }}" method="post"> --}}
                <form id="form-mass-select-officer">
                    <div class="modal-body">
                        @csrf
                        <div class="form-group">
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
            $('#bank_id').select2({
                theme: 'bootstrap4'
            });
            // DataTables
            var table = $("#table").DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "{{ route('prospective-customer-surveys.index') }}/fetch-data-table",
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
                "columns": [{
                        "data": "select"
                    },
                    {
                        "data": "DT_RowIndex"
                    },
                    {
                        "data": "user"
                    },
                    {
                        "data": "status"
                    },
                    {
                        "data": "name"
                    },
                    {
                        "data": "address"
                    },
                    {
                        "data": "number_ktp"
                    },
                    {
                        "data": "address_status"
                    },
                    {
                        "data": "phone_number"
                    },
                    {
                        "data": "npwp"
                    },
                    {
                        "data": "job_type"
                    },
                    {
                        "data": "company_name"
                    },
                    {
                        "data": "job_level"
                    },
                    {
                        "data": "employee_tenure"
                    },
                    {
                        "data": "employee_status"
                    },
                    {
                        "data": "salary"
                    },
                    {
                        "data": "other_business"
                    },
                    {
                        "data": "monthly_living_expenses"
                    },
                    {
                        "data": "children"
                    },
                    {
                        "data": "wife"
                    },
                    {
                        "data": "couple_jobs"
                    },
                    {
                        "data": "couple_business"
                    },
                    {
                        "data": "couple_income"
                    },
                    {
                        "data": "bank_debt"
                    },
                    {
                        "data": "cooperative_debt"
                    },
                    {
                        "data": "personal_debt"
                    },
                    {
                        "data": "online_debt"
                    },
                    {
                        "data": "customer_character_analysis"
                    },
                    {
                        "data": "financial_report_analysis"
                    },
                    {
                        "data": "slik_result"
                    },
                    {
                        "data": "info_provider_name"
                    },
                    {
                        "data": "info_provider_position"
                    },
                    {
                        "data": "workplace_condition"
                    },
                    {
                        "data": "employee_count"
                    },
                    {
                        "data": "business_duration"
                    },
                    {
                        "data": "office_address"
                    },
                    {
                        "data": "office_phone"
                    },
                    {
                        "data": "loan_application"
                    },
                    {
                        "data": "recommendation_from_vendors"
                    },
                    {
                        "data": "recommendation_from_treasurer"
                    },
                    {
                        "data": "recommendation_from_other"
                    },
                    {
                        "data": "source_1_full_name"
                    },
                    {
                        "data": "source_1_gender"
                    },
                    {
                        "data": "source_1_source_relationship"
                    },
                    {
                        "data": "source_1_source_character"
                    },
                    {
                        "data": "source_1_knows_prospect_customer"
                    },
                    {
                        "data": "source_1_prospect_lives_at_address"
                    },
                    {
                        "data": "source_1_length_of_residence"
                    },
                    {
                        "data": "source_1_house_ownership_status"
                    },
                    {
                        "data": "source_1_prospect_status"
                    },
                    {
                        "data": "source_1_number_of_dependents"
                    },
                    {
                        "data": "source_1_prospect_character"
                    },
                    {
                        "data": "source_2_full_name"
                    },
                    {
                        "data": "source_2_gender"
                    },
                    {
                        "data": "source_2_source_relationship"
                    },
                    {
                        "data": "source_2_source_character"
                    },
                    {
                        "data": "source_2_knows_prospect_customer"
                    },
                    {
                        "data": "source_2_prospect_lives_at_address"
                    },
                    {
                        "data": "source_2_length_of_residence"
                    },
                    {
                        "data": "source_2_house_ownership_status"
                    },
                    {
                        "data": "source_2_prospect_status"
                    },
                    {
                        "data": "source_2_number_of_dependents"
                    },
                    {
                        "data": "source_2_prospect_character"
                    },
                    {
                        "data": "recommendation_pt"
                    },
                    {
                        "data": "descriptionSurvey"
                    },
                    {
                        "data": "locationSurvey"
                    },
                    {
                        "data": "dateSurvey"
                    },
                    {
                        "data": "locationString"
                    },
                    {
                        "data": "signature_officer"
                    },
                    {
                        "data": "signature_customer"
                    },
                    {
                        "data": "signature_couple"
                    },
                    {
                        "data": "workplace_image"
                    },
                    {
                        "data": "customer_and_ktp_image"
                    },
                    {
                        "data": "loan_guarantee_image"
                    },
                    {
                        "data": "kk_and_id_card_image"
                    },
                    {
                        "data": "salary_slip_image"
                    },
                    {
                        "data": "action"
                    },
                    {
                        "data": "details"
                    }
                ],
                "columnDefs": [{
                        "targets": 0,
                        "searchable": false,
                        "orderable": false,
                    },
                    {
                        "orderable": false,
                        "searchable": false,
                        "targets": [11, 13, 14, 15, 16]
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
                            url: '{{ route('prospective-customer-surveys.massDelete') }}',
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
                            url: '{{ route('prospective-customer-surveys.massSelectOfficer') }}',
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
