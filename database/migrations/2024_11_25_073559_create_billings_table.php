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
        Schema::create('billings', function (Blueprint $table) {
            $table->id();
            $table->string('no_billing')->unique()->comment('Nomor tagihan');
            $table->date('date')->comment('Tanggal penagihan');
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade')->comment('ID Customer');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->comment('ID Petugas');
            $table->enum('destination', ['visit', 'promise', 'pay'])->default('visit')->comment('Tujuan penagihan');
            $table->string('image_visit')->nullable()->comment('Bukti kunjungan');
            $table->string('description_visit')->nullable()->comment('Deskripsi kunjungan');
            $table->date('promise_date')->nullable()->comment('Tanggal janji bayar');
            $table->string('image_promise')->nullable()->comment('Bukti janji bayar');
            $table->string('description_promise')->nullable()->comment('Deskripsi janji bayar');
            $table->bigInteger('amount')->nullable()->comment('Jumlah setoran');
            $table->string('image_amount')->nullable()->comment('Bukti setoran');
            $table->string('description_amount')->nullable()->comment('Deskripsi pembayaran');
            $table->string('signature_officer')->nullable()->comment('Tanda tangan petugas');
            $table->string('signature_customer')->nullable()->comment('Tanda tangan pelanggan');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('deleted_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('billings');
    }
};
