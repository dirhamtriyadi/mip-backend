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
        Schema::create('billing_followups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_billing_id')->constrained('customer_billings')->onDelete('cascade')->comment('ID penagihan');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->comment('ID petugas');
            $table->enum('status', ['visit', 'promise_to_pay', 'pay'])->default('visit')->comment('Status kunjungan');
            $table->date('date_exec')->comment('Tanggal di eksekusi');
            $table->text('description')->comment('Deskripsi');
            $table->string('proof')->comment('Bukti berupa gambar');
            $table->date('promise_date')->nullable()->comment('Tanggal janji bayar');
            $table->bigInteger('payment_amount')->nullable()->comment('Jumlah pembayaran');
            $table->string('signature_officer')->nullable()->comment('Tanda tangan petugas');
            $table->string('signature_customer')->nullable()->comment('Tanda tangan pelanggan');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('billing_followups');
    }
};
