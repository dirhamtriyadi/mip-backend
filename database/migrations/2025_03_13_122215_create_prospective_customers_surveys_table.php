<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('prospective-customer-surveys', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prospective_customer_id')->nullable()->constrained('prospective_customers')->comment('ID calon nasabah');
            $table->enum('status', ['pending', 'ongoing', 'done'])->default('pending')->comment('Status survei');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade')->comment('ID petugas');
            $table->string('name')->comment('Nama calon nasabah');
            $table->text('address')->comment('Alamat calon nasabah');
            $table->string('number_ktp')->unique()->comment('Nomor KTP calon nasabah');
            $table->string('address_status')->comment('Status alamat calon nasabah');
            $table->string('phone_number')->comment('Nomor telepon calon nasabah');
            $table->string('npwp')->nullable()->comment('NPWP calon nasabah');
            $table->string('job_type')->nullable()->comment('Jenis pekerjaan');
            $table->string('company_name')->nullable()->comment('Nama perusahaan');
            $table->string('job_level')->nullable()->comment('Jabatan');
            $table->string('employee_tenure')->nullable()->comment('Lama kerja');
            $table->string('employee_status')->nullable()->comment('Status karyawan');
            $table->bigInteger('salary')->nullable()->default(0)->comment('Gaji');
            $table->bigInteger('other_business')->nullable()->default(0)->comment('Pendapatan usaha tambahan');
            $table->bigInteger('monthly_living_expenses')->nullable()->default(0)->comment('Biaya hidup per bulan');
            $table->string('children')->default(0)->comment('Tanggungan anak');
            $table->string('wife')->default(0)->comment('Tanggungan istri');
            $table->string('couple_jobs')->nullable()->comment('Pekerjaan pasangan');
            $table->string('couple_business')->nullable()->comment('Usaha pasangan');
            $table->bigInteger('couple_income')->nullable()->default(0)->comment('Pendapatan pasangan');
            $table->bigInteger('bank_debt')->nullable()->default(0)->comment('Hutang bank');
            $table->bigInteger('cooperative_debt')->nullable()->default(0)->comment('Hutang koperasi');
            $table->bigInteger('personal_debt')->nullable()->default(0)->comment('Hutang perorangan');
            $table->bigInteger('online_debt')->nullable()->default(0)->comment('Hutang online');
            $table->text('customer_character_analysis')->nullable()->comment('Analisa karakter nasabah');
            $table->text('financial_report_analysis')->nullable()->comment('Analisa laporan keuangan');
            $table->string('slik_result')->nullable()->comment('Hasil Slik');
            $table->string('info_provider_name')->nullable()->comment('Nama pemberi informasi');
            $table->string('info_provider_position')->nullable()->comment('Jabatan pemberi informasi');
            $table->text('workplace_condition')->nullable()->comment('Kondisi tempat kerja');
            $table->string('employee_count')->nullable()->comment('Banyak karyawan');
            $table->string('business_duration')->nullable()->comment('Lama usaha kantor');
            $table->text('office_address')->nullable()->comment('Alamat kantor');
            $table->string('office_phone')->nullable()->comment('Telepon kantor');
            $table->bigInteger('loan_application')->nullable()->default(0)->comment('Pengajuan pinjaman');
            $table->text('recommendation_from_vendors')->nullable()->comment('Rekomendasi dari vendor?');
            $table->text('recommendation_from_treasurer')->nullable()->comment('Rekomendasi dari bendahara?');
            $table->text('recommendation_from_other')->nullable()->comment('Rekomendasi dari lainnya?');

            // Sumber informasi pertama
            $table->string('source_1_full_name')->nullable()->comment('Nama lengkap sumber informasi pertama');
            $table->string('source_1_gender')->nullable()->comment('Jenis kelamin sumber informasi pertama');
            $table->string('source_1_source_relationship')->nullable()->comment('Hubungan sumber informasi pertama');
            $table->string('source_1_source_character')->nullable()->comment('Karakteristik sumber informasi pertama');
            $table->string('source_1_knows_prospect_customer')->nullable()->comment('Apakah sumber pertama kenal dengan calon nasabah?');
            $table->string('source_1_prospect_lives_at_address')->nullable()->comment('Apakah calon nasabah tinggal di alamat alamat tersebut?');
            $table->string('source_1_length_of_residence')->nullable()->comment('Berapa lama calon nasabah tinggal di alamat tersebut?');
            $table->string('source_1_house_ownership_status')->nullable()->comment('Status kepemilikan rumah sumber informasi pertama');
            $table->string('source_1_prospect_status')->nullable()->comment('Status calon nasabah sumber informasi pertama');
            $table->string('source_1_number_of_dependents')->nullable()->comment('Jumlah tanggungan sumber informasi pertama');
            $table->string('source_1_prospect_character')->nullable()->comment('Karakter calon nasabah sumber informasi pertama');

            // Sumber informasi kedua
            $table->string('source_2_full_name')->nullable()->comment('Nama lengkap sumber informasi kedua');
            $table->string('source_2_gender')->nullable()->comment('Jenis kelamin sumber informasi kedua');
            $table->string('source_2_source_relationship')->nullable()->comment('Hubungan sumber informasi kedua');
            $table->string('source_2_source_character')->nullable()->comment('Karakteristik sumber informasi kedua');
            $table->string('source_2_knows_prospect_customer')->nullable()->comment('Apakah sumber kedua kenal dengan calon nasabah?');
            $table->string('source_2_prospect_lives_at_address')->nullable()->comment('Apakah calon nasabah tinggal di alamat alamat tersebut?');
            $table->string('source_2_length_of_residence')->nullable()->comment('Berapa lama calon nasabah tinggal di alamat tersebut?');
            $table->string('source_2_house_ownership_status')->nullable()->comment('Status kepemilikan rumah sumber informasi kedua');
            $table->string('source_2_prospect_status')->nullable()->comment('Status calon nasabah sumber informasi kedua');
            $table->string('source_2_number_of_dependents')->nullable()->comment('Jumlah tanggungan sumber informasi kedua');
            $table->string('source_2_prospect_character')->nullable()->comment('Karakter calon nasabah sumber informasi kedua');

            $table->enum('recommendation_pt', ['yes', 'no'])->nullable()->comment('Apakah direkomendasikan?');
            $table->text('descriptionSurvey')->nullable()->comment('Keterangan rekomendasi PT');
            $table->text('locationSurvey')->nullable()->comment('Tempat lokasi survei');
            $table->date('dateSurvey')->nullable()->comment('Tanggal survei');
            $table->string('latitude')->nullable()->comment('Latitude');
            $table->string('longitude')->nullable()->comment('Longitude');
            $table->string('locationString')->nullable()->comment('Lokasi survei');

            // Tanda tangan
            $table->text('signature_officer')->nullable()->comment('Tanda tangan officer');
            $table->text('signature_customer')->nullable()->comment('Tanda tangan customer');
            $table->text('signature_couple')->nullable()->comment('Tanda tangan pasangan');

            // Gambar
            $table->text('workplace_image1')->nullable()->comment('Gambar tempat kerja 1');
            $table->text('workplace_image2')->nullable()->comment('Gambar tempat kerja 2');
            $table->text('customer_image')->nullable()->comment('Gambar customer');
            $table->text('ktp_image')->nullable()->comment('Gambar KTP');
            $table->text('loan_guarantee_image1')->nullable()->comment('Gambar garansi pinjaman 1');
            $table->text('loan_guarantee_image2')->nullable()->comment('Gambar garansi pinjaman 2');
            $table->text('kk_image')->nullable()->comment('Gambar KK');
            $table->text('id_card_image')->nullable()->comment('Gambar ID card');
            $table->text('salary_slip_image1')->nullable()->comment('Gambar bukti gaji 1');
            $table->text('salary_slip_image2')->nullable()->comment('Gambar bukti gaji 2');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prospective-customer-surveys');
    }
};
