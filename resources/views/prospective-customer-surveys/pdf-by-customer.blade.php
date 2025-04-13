<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDF Survey {{ $data->name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .header-table {
            width: 100%;
            border-bottom: 2px solid #000;
            margin-bottom: 20px;
        }

        .logo {
            width: 50px;
        }

        .company-name {
            font-size: 12px;
            font-weight: bold;
            text-align: right;
            width: 100%;
        }

        /* Garis bawah sampai ke ujung kanan */
        .value {
            width: 100%;
            border-bottom: 1px solid #000;
            display: inline-block;
        }

        .value1 {
            width: 90%;
            border-bottom: 1px solid #000;
            display: inline-block;
        }

        /* Agar tabel menyesuaikan isi */
        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: auto;
        }

        th,
        td {
            text-align: left;
            padding: 5px;
            /* border: 1px solid #ddd; */
            font-size: 11px;
        }

        .table-file th,
        .table-file td {
            text-align: left;
            padding: 5px;
            border: 1px solid #ddd;
            font-size: 11px;
        }

        /* Menyesuaikan kolom agar tidak terlalu lebar */
        th:first-child,
        td:first-child {
            /* Kolom "No" */
            width: 10px;
            white-space: nowrap;
        }

        th:nth-child(2),
        td:nth-child(2) {
            /* Kolom "Fitur" */
            width: 50px;
            white-space: nowrap;
        }

        .wawancara-container {
            width: 100%;
        }

        .wawancara-container td {
            width: 50%;
            vertical-align: top;
        }
    </style>
</head>

<body>
    <table class="header-table">
        <tr>
            <td style="width: 100px;">
                <img src="{{ public_path('assets/logo.png') }}" alt="Logo Perusahaan" class="logo">
            </td>
            <td class="company-name">MULTI INSAN PARAHYANGAN</td>
        </tr>
    </table>

    <table>
        <tr>
            <th>No</th>
            <th>Fitur</th>
            <th></th>
        </tr>
        <tr>
            <td><strong>1</strong></td>
            <td><strong>CIF</strong></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td>Nama</td>
            <td><span class="value">: {{ $data->name }}</span></td>
        </tr>
        <tr>
            <td></td>
            <td>Alamat</td>
            <td><span class="value">: {{ $data->address }}</span></td>
        </tr>
        <tr>
            <td></td>
            <td>No. KTP</td>
            <td><span class="value">: {{ $data->number_ktp }}</span></td>
        </tr>
        <tr>
            <td></td>
            <td>Status Alamat</td>
            <td><span class="value">: {{ $data->address_status }}</span></td>
        </tr>
        <tr>
            <td></td>
            <td>No. Telp</td>
            <td><span class="value">: {{ $data->phone_number }}</span></td>
        </tr>
        <tr>
            <td></td>
            <td>NPWP</td>
            <td><span class="value">: {{ $data->npwp }}</span></td>
        </tr>
        <tr>
            <td><strong>2</strong></td>
            <td><strong>Pendapatan</strong></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td>Jenis Pekerjaan</td>
            <td><span class="value">: {{ $data->job_type }}</span></td>
        </tr>
        <tr>
            <td></td>
            <td>Nama Perusahaan</td>
            <td><span class="value">: {{ $data->company_name }}</span></td>
        </tr>
        <tr>
            <td></td>
            <td>Jabatan</td>
            <td><span class="value">: {{ $data->job_level }}</span></td>
        </tr>
        <tr>
            <td></td>
            <td>Lama Kerja</td>
            <td><span class="value">: {{ $data->employee_tenure }}</span></td>
        </tr>
        <tr>
            <td></td>
            <td>Status Karyawan</td>
            <td><span class="value">: {{ $data->employee_status }}</span></td>
        </tr>
        <tr>
            <td></td>
            <td>Gaji</td>
            <td><span class="value">: {{ $data->salary }}</span></td>
        </tr>
        <tr>
            <td></td>
            <td>Usaha Tambahan</td>
            <td><span class="value">: {{ $data->other_business }}</span></td>
        </tr>
        <tr>
            <td></td>
            <td>Biaya hidup per bulan</td>
            <td><span class="value">: {{ $data->monthly_living_expenses }}</span></td>
        </tr>
        <tr>
            <td></td>
            <td><strong>Tanggungan</strong></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td>Anak</td>
            <td><span class="value">: {{ $data->children }}</span></td>
        </tr>
        <tr>
            <td></td>
            <td>Istri</td>
            <td><span class="value">: {{ $data->wife }}</span></td>
        </tr>
        <tr>
            <td></td>
            <td><strong>Pendapatan Pasangan</strong></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td>Pekerjaan Pasangan</td>
            <td><span class="value">: {{ $data->couple_jobs }}</span></td>
        </tr>
        <tr>
            <td></td>
            <td>Usaha Pasangan</td>
            <td><span class="value">: {{ $data->couple_business }}</span></td>
        </tr>
        <tr>
            <td></td>
            <td>Pendapatan Pasangan</td>
            <td><span class="value">: {{ $data->couple_income }}</span></td>
        </tr>
        <tr>
            <td><strong>3</strong></td>
            <td><strong>Hutang</strong></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td>Bank</td>
            <td><span class="value">: {{ $data->bank_debt }}</span></td>
        </tr>
        <tr>
            <td></td>
            <td>Koperasi</td>
            <td><span class="value">: {{ $data->cooperative_debt }}</span></td>
        </tr>
        <tr>
            <td></td>
            <td>Perorangan</td>
            <td><span class="value">: {{ $data->personal_debt }}</span></td>
        </tr>
        <tr>
            <td></td>
            <td>Online</td>
            <td><span class="value">: {{ $data->online_debt }}</span></td>
        </tr>
        <tr>
            <td><strong>4</strong></td>
            <td><strong>Scorring</strong></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td>Analisa Karakter Nasabah</td>
            <td><span class="value">: {{ $data->customer_character_analysis }}</span></td>
        </tr>
        <tr>
            <td></td>
            <td>Analisa Laporan Keuangan</td>
            <td><span class="value">: {{ $data->financial_report_analysis }}</span></td>
        </tr>
        <tr>
            <td></td>
            <td>Hasil Slik</td>
            <td><span class="value">: {{ $data->slik_result }}</span></td>
        </tr>
        <tr>
            <td><strong>5</strong></td>
            <td><strong>Informasi Tambahan dan Pengajuan</strong></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td>Nama Pemberi Informasi</td>
            <td><span class="value">: {{ $data->info_provider_name }}</span></td>
        </tr>
        <tr>
            <td></td>
            <td>Jabatan Pemberi Informasi</td>
            <td><span class="value">: {{ $data->info_provider_position }}</span></td>
        </tr>
        <tr>
            <td></td>
            <td>Kondisi Tempat Kerja</td>
            <td><span class="value">: {{ $data->workplace_condition }}</span></td>
        </tr>
        <tr>
            <td></td>
            <td>Banyak Karyawan</td>
            <td><span class="value">: {{ $data->employee_count }}</span></td>
        </tr>
        <tr>
            <td></td>
            <td>Lama Usaha Kantor</td>
            <td><span class="value">: {{ $data->business_duration }}</span></td>
        </tr>
        <tr>
            <td></td>
            <td>Alamat Kantor</td>
            <td><span class="value">: {{ $data->office_address }}</span></td>
        </tr>
        <tr>
            <td></td>
            <td>Telepon Kantor</td>
            <td><span class="value">: {{ $data->office_phone }}</span></td>
        </tr>
        <tr>
            <td><strong>6</strong></td>
            <td><strong>Rekomendasi dari</strong></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td>Vendor</td>
            <td><span class="value">: {{ $data->recommendation_from_vendors }}</span></td>
        </tr>
        <tr>
            <td></td>
            <td>Bendahara</td>
            <td><span class="value">: {{ $data->recommendation_from_treasurer }}</span></td>
        </tr>
        <tr>
            <td></td>
            <td>Lainnya</td>
            <td><span class="value">: {{ $data->recommendation_from_other }}</span></td>
        </tr>
    </table>
    <table class="wawancara-container">
        <tbody>
            <tr>
                <td><strong>7 Wawancara 1</strong></td>
                <td><strong>8 Wawancara 2</strong></td>
            </tr>
            <tr>
                <td>
                    <table>
                        <tr>
                            <td>Nama</td>
                            <td><span class="value">: {{ $data->source_1_full_name }}</span></td>
                        </tr>
                        <tr>
                            <td>Jenis Kelamin</td>
                            <td><span class="value">: {{ $data->source_1_gender }}</span></td>
                        </tr>
                        <tr>
                            <td>Hubungan Sumber Informasi</td>
                            <td><span class="value">: {{ $data->source_1_source_relationship }}</span></td>
                        </tr>
                        <tr>
                            <td>Karakter Sumber Informasi</td>
                            <td><span class="value">: {{ $data->source_1_source_character }}</span></td>
                        </tr>
                        <tr>
                            <td>Kenal Dengan Calon Nasabah?</td>
                            <td><span class="value">: {{ $data->source_1_knows_prospect_customer }}</span></td>
                        </tr>
                        <tr>
                            <td>Calon Nasabah Tinggal di Alamat tersebut?</td>
                            <td><span class="value">: {{ $data->source_1_prospect_lives_at_address }}</span></td>
                        </tr>
                        <tr>
                            <td>Lama Tinggal</td>
                            <td><span class="value">: {{ $data->source_1_length_of_residence }}</span></td>
                        </tr>
                        <tr>
                            <td>Status Kepemilikan Rumah</td>
                            <td><span class="value">: {{ $data->source_1_house_ownership_status }}</span></td>
                        </tr>
                        <tr>
                            <td>Status Calon Nasabah</td>
                            <td><span class="value">: {{ $data->source_1_prospect_status }}</span></td>
                        </tr>
                        <tr>
                            <td>Jumlah Tanggungan</td>
                            <td><span class="value">: {{ $data->source_1_number_of_dependents }}</span></td>
                        </tr>
                        <tr>
                            <td>Karakter Calon Nasabah</td>
                            <td><span class="value">: {{ $data->source_1_prospect_character }}</span></td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table>
                        <tr>
                            <td>Nama</td>
                            <td><span class="value">: {{ $data->source_2_full_name }}</span></td>
                        </tr>
                        <tr>
                            <td>Jenis Kelamin</td>
                            <td><span class="value">: {{ $data->source_2_gender }}</span></td>
                        </tr>
                        <tr>
                            <td>Hubungan Sumber Informasi</td>
                            <td><span class="value">: {{ $data->source_2_source_relationship }}</span></td>
                        </tr>
                        <tr>
                            <td>Karakter Sumber Informasi</td>
                            <td><span class="value">: {{ $data->source_2_source_character }}</span></td>
                        </tr>
                        <tr>
                            <td>Kenal Dengan Calon Nasabah?</td>
                            <td><span class="value">: {{ $data->source_2_knows_prospect_customer }}</span></td>
                        </tr>
                        <tr>
                            <td>Calon Nasabah Tinggal di Alamat tersebut?</td>
                            <td><span class="value">: {{ $data->source_2_prospect_lives_at_address }}</span></td>
                        </tr>
                        <tr>
                            <td>Lama Tinggal</td>
                            <td><span class="value">: {{ $data->source_2_length_of_residence }}</span></td>
                        </tr>
                        <tr>
                            <td>Status Kepemilikan Rumah</td>
                            <td><span class="value">: {{ $data->source_2_house_ownership_status }}</span></td>
                        </tr>
                        <tr>
                            <td>Status Calon Nasabah</td>
                            <td><span class="value">: {{ $data->source_2_prospect_status }}</span></td>
                        </tr>
                        <tr>
                            <td>Jumlah Tanggungan</td>
                            <td><span class="value">: {{ $data->source_2_number_of_dependents }}</span></td>
                        </tr>
                        <tr>
                            <td>Karakter Calon Nasabah</td>
                            <td><span class="value">: {{ $data->source_2_prospect_character }}</span></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <table>
        <tr>
            <td><strong>9</strong></td>
            <td><strong>Catatan Rekomendasi PT</strong></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td>Direkomendasikan</td>
            <td><span class="value">: {{ $data->recommendation_pt }}</span></td>
        </tr>
    </table>
    <table>
        <tr>
            <td style="height: 100px">{{ $data->descriptionSurvey }}</td>
        </tr>
    </table>
    <table>
        <tr>
            <td>Tempat - hari - tanggal</td>
            <td><span class="value">: {{ $data->locationSurvey ?? 'Lokasi kosong' }} -
                    {{ Carbon\Carbon::parse($data->dateSurvey)->translatedFormat('l - d F Y') }}</span></td>
        </tr>
        <tr>
            <td><strong>Latitude</strong></td>
            <td><span class="value">: {{ $data->latitude ?? '0' }}</span></td>
            <td><strong>Longitude</strong></td>
            <td><span class="value">: {{ $data->longitude ?? '0' }}</span></td>
        </tr>
    </table>
    <table style="width: 100%; border-collapse: collapse; table-layout: fixed; margin-top: 50px">
        <tr style="width: 33.33%;">
            <td style="text-align: center"><strong>Petugas</strong></td>
            <td style="text-align: center"><strong>Nasabah</strong></td>
            <td style="text-align: center"><strong>Pasangan/Penanggung Jawab</strong></td>
        </tr>
        <tr>
            @if (!empty($data->signature_officer))
                <td style="width: 33.33%; height: 150px; text-align: center; vertical-align: middle;">
                    <img src="{{ public_path('storage/' . $data->signature_officer) }}" alt="TTD Petugas"
                        style="width: 100px; height: 100px;">
                </td>
            @else
                <td style="width: 33.33%; height: 150px; text-align: center; vertical-align: middle;"></td>
            @endif

            @if (!empty($data->signature_customer))
                <td style="width: 33.33%; height: 150px; text-align: center; vertical-align: middle;">
                    <img src="{{ public_path('storage/' . $data->signature_customer) }}" alt="TTD Customer"
                        style="width: 100px; height: 100px;">
                </td>
            @else
                <td style="width: 33.33%; height: 150px; text-align: center; vertical-align: middle;"></td>
            @endif

            @if (!empty($data->signature_couple))
                <td style="width: 33.33%; height: 150px; text-align: center; vertical-align: middle;">
                    <img src="{{ public_path('storage/' . $data->signature_couple) }}" alt="TTD Couple"
                        style="width: 100px; height: 100px;">
                </td>
            @else
                <td style="width: 33.33%; height: 150px; text-align: center; vertical-align: middle;"></td>
            @endif
        </tr>
        <tr>
            <td style="height: 20px; text-align: center">( <span class="value1">{{ $data->user->name ?? '' }}</span> )
            </td>
            <td style="height: 20px; text-align: center">( <span class="value1">{{ $data->name ?? '' }}</span> )</td>
            <td style="height: 20px; text-align: center">( <span class="value1"></span> )</td>
        </tr>
    </table>
    <table class="table-file" style="width: 100%; border-collapse: collapse; table-layout: fixed;">
        <tr style="width: 33.33%;">
            <td style="width: 33.33%; height: 150px;">1. Latitude/Longitude dab Gedung</td>
            <td style="width: 33.33%; height: 150px;">
                @if (!empty($data->workplace_image1))
                    <img src="{{ public_path('storage/' . $data->workplace_image1) }}"
                        alt="{{ public_path('storage/' . $data->workplace_image1) }}"
                        style="width: 150px; height: 150px;">
                @endif
            </td>
            <td style="width: 33.33%; height: 150px;">
                @if (!empty($data->workplace_image2))
                    <img src="{{ public_path('storage/' . $data->workplace_image2) }}" alt="workplace_image2"
                        style="width: 150px; height: 150px;">
                @endif
            </td>
        </tr>
        <tr>
            <td style="width: 33.33%; height: 150px;">2. Foto Nasabah dan KTP</td>
            <td style="width: 33.33%; height: 150px;">
                @if (!empty($data->customer_image))
                    <img src="{{ public_path('storage/' . $data->customer_image) }}" alt="customer_image"
                        style="width: 150px; height: 150px;">
                @endif
            </td>
            <td style="width: 33.33%; height: 150px;">
                @if (!empty($data->ktp_image))
                    <img src="{{ public_path('storage/' . $data->ktp_image) }}" alt="ktp_image"
                        style="width: 150px; height: 150px;">
                @endif
            </td>
        </tr>
        <tr>
            <td style="width: 33.33%; height: 150px;">3. Foto Jaminan</td>
            <td style="width: 33.33%; height: 150px;">
                @if (!empty($data->loan_guarantee_image1))
                    <img src="{{ public_path('storage/' . $data->loan_guarantee_image1) }}"
                        alt="loan_guarantee_image1" style="width: 150px; height: 150px;">
                @endif
            </td>
            <td style="width: 33.33%; height: 150px;">
                @if (!empty($data->loan_guarantee_image2))
                    <img src="{{ public_path('storage/' . $data->loan_guarantee_image2) }}"
                        alt="loan_guarantee_image2" style="width: 150px; height: 150px;">
                @endif
            </td>
        </tr>
        <tr>
            <td style="width: 33.33%; height: 150px;">4. KK dan ID Card</td>
            <td style="width: 33.33%; height: 150px;">
                @if (!empty($data->kk_image))
                    <img src="{{ public_path('storage/' . $data->kk_image) }}" alt="kk_image"
                        style="width: 150px; height: 150px;">
                @endif
            </td>
            <td style="width: 33.33%; height: 150px;">
                @if (!empty($data->id_card_image))
                    <img src="{{ public_path('storage/' . $data->id_card_image) }}" alt="id_card_image"
                        style="width: 150px; height: 150px;">
                @endif
            </td>
        </tr>
        <tr>
            <td style="width: 33.33%; height: 150px;">5. Slip Gaji</td>
            <td style="width: 33.33%; height: 150px;">
                @if (!empty($data->salary_slip_image1))
                    <img src="{{ public_path('storage/' . $data->salary_slip_image1) }}" alt="salary_slip_image1"
                        style="width: 150px; height: 150px;">
                @endif
            </td>
            <td style="width: 33.33%; height: 150px;">
                @if (!empty($data->salary_slip_image2))
                    <img src="{{ public_path('storage/' . $data->salary_slip_image2) }}" alt="salary_slip_image2"
                        style="width: 150px; height: 150px;">
                @endif
            </td>
        </tr>
    </table>
</body>

</html>
