<?php
// filepath: /home/dirham/Downloads/mip-backend/resources/lang/id/validation.php

return [
    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => ':attribute harus diterima.',
    'accepted_if' => ':attribute harus diterima ketika :other adalah :value.',
    'active_url' => ':attribute bukan URL yang valid.',
    'after' => ':attribute harus tanggal setelah :date.',
    'after_or_equal' => ':attribute harus tanggal setelah atau sama dengan :date.',
    'alpha' => ':attribute hanya boleh berisi huruf.',
    'alpha_dash' => ':attribute hanya boleh berisi huruf, angka, tanda hubung, dan garis bawah.',
    'alpha_num' => ':attribute hanya boleh berisi huruf dan angka.',
    'array' => ':attribute harus berupa array.',
    'ascii' => ':attribute hanya boleh berisi karakter alfanumerik dan simbol byte tunggal.',
    'before' => ':attribute harus tanggal sebelum :date.',
    'before_or_equal' => ':attribute harus tanggal sebelum atau sama dengan :date.',
    'between' => [
        'array' => ':attribute harus memiliki antara :min dan :max item.',
        'file' => ':attribute harus antara :min dan :max kilobyte.',
        'numeric' => ':attribute harus antara :min dan :max.',
        'string' => ':attribute harus antara :min dan :max karakter.',
    ],
    'boolean' => ':attribute harus bernilai benar atau salah.',
    'can' => ':attribute mengandung nilai yang tidak diizinkan.',
    'confirmed' => 'Konfirmasi :attribute tidak cocok.',
    'current_password' => 'Kata sandi salah.',
    'date' => ':attribute bukan tanggal yang valid.',
    'date_equals' => ':attribute harus tanggal yang sama dengan :date.',
    'date_format' => ':attribute tidak sesuai dengan format :format.',
    'decimal' => ':attribute harus memiliki :decimal tempat desimal.',
    'declined' => ':attribute harus ditolak.',
    'declined_if' => ':attribute harus ditolak ketika :other adalah :value.',
    'different' => ':attribute dan :other harus berbeda.',
    'digits' => ':attribute harus :digits digit.',
    'digits_between' => ':attribute harus antara :min dan :max digit.',
    'dimensions' => ':attribute memiliki dimensi gambar yang tidak valid.',
    'distinct' => ':attribute memiliki nilai duplikat.',
    'doesnt_end_with' => ':attribute tidak boleh diakhiri dengan salah satu dari berikut: :values.',
    'doesnt_start_with' => ':attribute tidak boleh diawali dengan salah satu dari berikut: :values.',
    'email' => ':attribute harus alamat email yang valid.',
    'ends_with' => ':attribute harus diakhiri dengan salah satu dari berikut: :values.',
    'enum' => ':attribute yang dipilih tidak valid.',
    'exists' => ':attribute yang dipilih tidak valid.',
    'extensions' => ':attribute harus memiliki salah satu ekstensi berikut: :values.',
    'file' => ':attribute harus berupa file.',
    'filled' => ':attribute harus memiliki nilai.',
    'gt' => [
        'array' => ':attribute harus memiliki lebih dari :value item.',
        'file' => ':attribute harus lebih besar dari :value kilobyte.',
        'numeric' => ':attribute harus lebih besar dari :value.',
        'string' => ':attribute harus lebih besar dari :value karakter.',
    ],
    'gte' => [
        'array' => ':attribute harus memiliki :value item atau lebih.',
        'file' => ':attribute harus lebih besar dari atau sama dengan :value kilobyte.',
        'numeric' => ':attribute harus lebih besar dari atau sama dengan :value.',
        'string' => ':attribute harus lebih besar dari atau sama dengan :value karakter.',
    ],
    'hex_color' => ':attribute harus warna heksadesimal yang valid.',
    'image' => ':attribute harus berupa gambar.',
    'in' => ':attribute yang dipilih tidak valid.',
    'in_array' => ':attribute tidak ada dalam :other.',
    'integer' => ':attribute harus berupa bilangan bulat.',
    'ip' => ':attribute harus alamat IP yang valid.',
    'ipv4' => ':attribute harus alamat IPv4 yang valid.',
    'ipv6' => ':attribute harus alamat IPv6 yang valid.',
    'json' => ':attribute harus string JSON yang valid.',
    'lowercase' => ':attribute harus huruf kecil.',
    'lt' => [
        'array' => ':attribute harus memiliki kurang dari :value item.',
        'file' => ':attribute harus kurang dari :value kilobyte.',
        'numeric' => ':attribute harus kurang dari :value.',
        'string' => ':attribute harus kurang dari :value karakter.',
    ],
    'lte' => [
        'array' => ':attribute tidak boleh memiliki lebih dari :value item.',
        'file' => ':attribute harus kurang dari atau sama dengan :value kilobyte.',
        'numeric' => ':attribute harus kurang dari atau sama dengan :value.',
        'string' => ':attribute harus kurang dari atau sama dengan :value karakter.',
    ],
    'mac_address' => ':attribute harus alamat MAC yang valid.',
    'max' => [
        'array' => ':attribute tidak boleh memiliki lebih dari :max item.',
        'file' => ':attribute tidak boleh lebih besar dari :max kilobyte.',
        'numeric' => ':attribute tidak boleh lebih besar dari :max.',
        'string' => ':attribute tidak boleh lebih besar dari :max karakter.',
    ],
    'max_digits' => ':attribute tidak boleh memiliki lebih dari :max digit.',
    'mimes' => ':attribute harus file dengan tipe: :values.',
    'mimetypes' => ':attribute harus file dengan tipe: :values.',
    'min' => [
        'array' => ':attribute harus memiliki setidaknya :min item.',
        'file' => ':attribute harus setidaknya :min kilobyte.',
        'numeric' => ':attribute harus setidaknya :min.',
        'string' => ':attribute harus setidaknya :min karakter.',
    ],
    'min_digits' => ':attribute harus memiliki setidaknya :min digit.',
    'missing' => ':attribute harus hilang.',
    'missing_if' => ':attribute harus hilang ketika :other adalah :value.',
    'missing_unless' => ':attribute harus hilang kecuali :other adalah :value.',
    'missing_with' => ':attribute harus hilang ketika :values ada.',
    'missing_with_all' => ':attribute harus hilang ketika :values ada.',
    'multiple_of' => ':attribute harus kelipatan dari :value.',
    'not_in' => ':attribute yang dipilih tidak valid.',
    'not_regex' => 'Format :attribute tidak valid.',
    'numeric' => ':attribute harus berupa angka.',
    'password' => [
        'letters' => ':attribute harus mengandung setidaknya satu huruf.',
        'mixed' => ':attribute harus mengandung setidaknya satu huruf besar dan satu huruf kecil.',
        'numbers' => ':attribute harus mengandung setidaknya satu angka.',
        'symbols' => ':attribute harus mengandung setidaknya satu simbol.',
        'uncompromised' => ':attribute yang diberikan telah muncul dalam kebocoran data. Silakan pilih :attribute yang berbeda.',
    ],
    'present' => ':attribute harus ada.',
    'present_if' => ':attribute harus ada ketika :other adalah :value.',
    'present_unless' => ':attribute harus ada kecuali :other adalah :value.',
    'present_with' => ':attribute harus ada ketika :values ada.',
    'present_with_all' => ':attribute harus ada ketika :values ada.',
    'prohibited' => ':attribute dilarang.',
    'prohibited_if' => ':attribute dilarang ketika :other adalah :value.',
    'prohibited_unless' => ':attribute dilarang kecuali :other adalah dalam :values.',
    'prohibits' => ':attribute melarang :other untuk ada.',
    'regex' => 'Format :attribute tidak valid.',
    'required' => ':attribute wajib diisi.',
    'required_array_keys' => ':attribute harus berisi entri untuk: :values.',
    'required_if' => ':attribute wajib diisi ketika :other adalah :value.',
    'required_if_accepted' => ':attribute wajib diisi ketika :other diterima.',
    'required_unless' => ':attribute wajib diisi kecuali :other adalah dalam :values.',
    'required_with' => ':attribute wajib diisi ketika :values ada.',
    'required_with_all' => ':attribute wajib diisi ketika :values ada.',
    'required_without' => ':attribute wajib diisi ketika :values tidak ada.',
    'required_without_all' => ':attribute wajib diisi ketika tidak ada :values yang ada.',
    'same' => ':attribute dan :other harus cocok.',
    'size' => [
        'array' => ':attribute harus mengandung :size item.',
        'file' => ':attribute harus :size kilobyte.',
        'numeric' => ':attribute harus :size.',
        'string' => ':attribute harus :size karakter.',
    ],
    'starts_with' => ':attribute harus diawali dengan salah satu dari berikut: :values.',
    'string' => ':attribute harus berupa string.',
    'timezone' => ':attribute harus zona waktu yang valid.',
    'unique' => ':attribute sudah digunakan.',
    'uploaded' => ':attribute gagal diunggah.',
    'uppercase' => ':attribute harus huruf besar.',
    'url' => ':attribute harus URL yang valid.',
    'ulid' => ':attribute harus ULID yang valid.',
    'uuid' => ':attribute harus UUID yang valid.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "rule.attribute" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [
        // Banks
        'name' => 'Nama',
        'branch_code' => 'Kode Bank',

        // Users
        'email' => 'Email',
        'password' => 'Kata Sandi',
        'password_confirmation' => 'Konfirmasi Kata Sandi',
        'bank_id' => 'Bank',

        // Detail Users
        'user_id' => 'Pengguna',
        'nik' => 'NIK',

        // Work Schedules
        'work_start_time' => 'Jam Mulai Kerja',
        'work_end_time' => 'Jam Selesai Kerja',
        'working_days' => 'Hari Kerja',

        // Annual Holidays
        'holiday_date' => 'Tanggal Libur',
        'description' => 'Deskripsi',

        // Attendances
        'code' => 'Kode Absensi',
        'date' => 'Tanggal',
        'time_check_in' => 'Waktu Check In',
        'time_check_out' => 'Waktu Check Out',
        'late_duration' => 'Durasi Terlambat',
        'early_leave_duration' => 'Durasi Pulang Awal',
        'type' => 'Tipe Absensi',
        'reason_late' => 'Alasan Terlambat',
        'reason_early_out' => 'Alasan Pulang Awal',
        'image_check_in' => 'Foto Check In',
        'image_check_out' => 'Foto Check Out',
        'location_check_in' => 'Lokasi Check In',
        'location_check_out' => 'Lokasi Check Out',

        // Leaves
        'start_date' => 'Tanggal Mulai',
        'end_date' => 'Tanggal Selesai',
        'status' => 'Status',
        'response' => 'Balasan',

        // Customers
        'no_contract' => 'Nomor Kontrak',
        'bank_account_number' => 'Nomor Rekening',
        'name_customer' => 'Nama Nasabah',
        'name_mother' => 'Nama Ibu',
        'phone_number' => 'Nomor Telepon',
        'margin_start' => 'Margin Awal',
        'os_start' => 'Outstanding Awal',
        'margin_remaining' => 'Margin Sisa',
        'installments' => 'Angsuran',
        'month_arrears' => 'Tunggakan Bulan',
        'arrears' => 'Tunggakan',
        'due_date' => 'Tanggal Jatuh Tempo',

        // Customer Address
        'customer_id' => 'Nasabah',
        'address' => 'Alamat',
        'village' => 'Desa',
        'subdistrict' => 'Kecamatan',

        // Customer Billings
        'bill_number' => 'Nomor Tagihan',

        // Billing Followups
        'customer_billing_id' => 'Tagihan Nasabah',
        'date_exec' => 'Tanggal Eksekusi',
        'proof' => 'Bukti',
        'promise_date' => 'Tanggal Janji Bayar',
        'payment_amount' => 'Jumlah Pembayaran',
        'signature_officer' => 'Tanda Tangan Petugas',
        'signature_customer' => 'Tanda Tangan Nasabah',

        // Prospective Customers
        'no_ktp' => 'Nomor KTP',
        'ktp' => 'Foto KTP',
        'kk' => 'Foto KK',
        'status_message' => 'Pesan Status',

        // Prospective Customer Surveys
        'prospective_customer_id' => 'Calon Nasabah',
        'number_ktp' => 'Nomor KTP',
        'address_status' => 'Status Alamat',
        'npwp' => 'NPWP',
        'job_type' => 'Jenis Pekerjaan',
        'company_name' => 'Nama Perusahaan',
        'job_level' => 'Jabatan',
        'employee_tenure' => 'Lama Kerja',
        'employee_status' => 'Status Karyawan',
        'salary' => 'Gaji',
        'other_business' => 'Usaha Lain',
        'monthly_living_expenses' => 'Biaya Hidup Bulanan',
        'children' => 'Anak',
        'wife' => 'Istri',
        'couple_jobs' => 'Pekerjaan Pasangan',
        'couple_business' => 'Usaha Pasangan',
        'couple_income' => 'Pendapatan Pasangan',
        'bank_debt' => 'Hutang Bank',
        'cooperative_debt' => 'Hutang Koperasi',
        'personal_debt' => 'Hutang Perorangan',
        'online_debt' => 'Hutang Online',
        'customer_character_analysis' => 'Analisa Karakter Nasabah',
        'financial_report_analysis' => 'Analisa Laporan Keuangan',
        'slik_result' => 'Hasil SLIK',
        'info_provider_name' => 'Nama Pemberi Info',
        'info_provider_position' => 'Jabatan Pemberi Info',
        'workplace_condition' => 'Kondisi Tempat Kerja',
        'employee_count' => 'Jumlah Karyawan',
        'business_duration' => 'Lama Usaha',
        'office_address' => 'Alamat Kantor',
        'office_phone' => 'Telepon Kantor',
        'loan_application' => 'Pengajuan Pinjaman',
        'recommendation_from_vendors' => 'Rekomendasi dari Vendor',
        'recommendation_from_treasurer' => 'Rekomendasi dari Bendahara',
        'recommendation_from_other' => 'Rekomendasi Lainnya',

        // Source Information 1
        'source_1_full_name' => 'Nama Lengkap Sumber 1',
        'source_1_gender' => 'Jenis Kelamin Sumber 1',
        'source_1_source_relationship' => 'Hubungan Sumber 1',
        'source_1_source_character' => 'Karakter Sumber 1',
        'source_1_knows_prospect_customer' => 'Sumber 1 Kenal Calon Nasabah',
        'source_1_prospect_lives_at_address' => 'Calon Nasabah Tinggal di Alamat (Sumber 1)',
        'source_1_length_of_residence' => 'Lama Tinggal (Sumber 1)',
        'source_1_house_ownership_status' => 'Status Kepemilikan Rumah (Sumber 1)',
        'source_1_prospect_status' => 'Status Calon Nasabah (Sumber 1)',
        'source_1_number_of_dependents' => 'Jumlah Tanggungan (Sumber 1)',
        'source_1_prospect_character' => 'Karakter Calon Nasabah (Sumber 1)',

        // Source Information 2
        'source_2_full_name' => 'Nama Lengkap Sumber 2',
        'source_2_gender' => 'Jenis Kelamin Sumber 2',
        'source_2_source_relationship' => 'Hubungan Sumber 2',
        'source_2_source_character' => 'Karakter Sumber 2',
        'source_2_knows_prospect_customer' => 'Sumber 2 Kenal Calon Nasabah',
        'source_2_prospect_lives_at_address' => 'Calon Nasabah Tinggal di Alamat (Sumber 2)',
        'source_2_length_of_residence' => 'Lama Tinggal (Sumber 2)',
        'source_2_house_ownership_status' => 'Status Kepemilikan Rumah (Sumber 2)',
        'source_2_prospect_status' => 'Status Calon Nasabah (Sumber 2)',
        'source_2_number_of_dependents' => 'Jumlah Tanggungan (Sumber 2)',
        'source_2_prospect_character' => 'Karakter Calon Nasabah (Sumber 2)',

        // Survey Details
        'recommendation_pt' => 'Rekomendasi PT',
        'description_survey' => 'Deskripsi Survei',
        'location_survey' => 'Lokasi Survei',
        'date_survey' => 'Tanggal Survei',
        'latitude' => 'Latitude',
        'longitude' => 'Longitude',
        'location_string' => 'String Lokasi',

        // Signatures
        'signature_couple' => 'Tanda Tangan Pasangan',

        // Images
        'workplace_image1' => 'Foto Tempat Kerja 1',
        'workplace_image2' => 'Foto Tempat Kerja 2',
        'customer_image' => 'Foto Nasabah',
        'ktp_image' => 'Foto KTP',
        'loan_guarantee_image1' => 'Foto Jaminan Pinjaman 1',
        'loan_guarantee_image2' => 'Foto Jaminan Pinjaman 2',
        'kk_image' => 'Foto Kartu Keluarga',
        'id_card_image' => 'Foto ID Card',
        'salary_slip_image1' => 'Foto Slip Gaji 1',
        'salary_slip_image2' => 'Foto Slip Gaji 2',

        // System Fields
        'created_by' => 'Dibuat Oleh',
        'updated_by' => 'Diperbarui Oleh',
        'deleted_by' => 'Dihapus Oleh',
        'created_at' => 'Dibuat Pada',
        'updated_at' => 'Diperbarui Pada',
        'deleted_at' => 'Dihapus Pada',
    ],
];
