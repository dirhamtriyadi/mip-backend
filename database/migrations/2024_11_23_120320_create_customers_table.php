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
            $table->bigInteger('no')->comment('Nomor kontrak/rekening');
            $table->string('name_customer')->comment('Nama pelanggan');
            $table->string('phone_number')->comment('Nomor telepon');
            $table->string('address')->comment('Alamat');
            $table->string('name_bank')->comment('Nama bank');
            $table->date('date')->comment('Tanggal membuat kontrak');
            $table->bigInteger('total_bill')->default(0)->comment('Total tagihan');
            $table->bigInteger('installment')->default(0)->comment('Angsuran per bulan');
            // $table->bigInteger('remaining_installment')->default(0)->comment('Sisa angsuran');
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
        Schema::dropIfExists('customers');
    }
};
