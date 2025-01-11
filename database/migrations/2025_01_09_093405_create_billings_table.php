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
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade')->comment('ID Petugas');
            $table->enum('status', ['pending', 'process', 'success', 'cancel'])->default('pending')->comment('Status penagihan');
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
