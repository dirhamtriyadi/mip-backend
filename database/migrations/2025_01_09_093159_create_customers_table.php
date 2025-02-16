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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('no_contract')->comment('Nomor kontrak');
            $table->bigInteger('bank_account_number')->nullable()->comment('Nomor rekening nasabah');
            $table->string('name_customer')->comment('Nama nasabah');
            $table->string('name_mother')->nullable()->comment('Nama ibu');
            $table->string('phone_number')->nullable()->comment('Nomor telepon');
            $table->enum('status', ['paid', 'not_yet_paid'])->default('not_yet_paid')->comment('Status lunas');
            $table->foreignId('bank_id')->nullable()->constrained('banks')->onDelete('cascade')->comment('ID bank');
            // $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade')->comment('ID petugas');
            $table->bigInteger('os_start')->nullable()->default(0)->comment('Outstanding awal');
            $table->bigInteger('os_remaining')->nullable()->default(0)->comment('Outstanding sisa');
            $table->bigInteger('os_total')->nullable()->default(0)->comment('Total outstanding');
            $table->bigInteger('monthly_installments')->nullable()->default(0)->comment('Angsuran perbulan');
            $table->text('description')->nullable()->comment('Dekripsi nasabah: DU atau NON DU');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('deleted_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('customer_address', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade')->comment('ID nasabah');
            $table->text('address')->nullable()->comment('Alamat nasabah');
            $table->string('village')->nullable()->comment('Alamat desa nasabah');
            $table->string('subdistrict')->nullable()->comment('Alamat kecamatan nasabah');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
        Schema::dropIfExists('customer_address');
    }
};
