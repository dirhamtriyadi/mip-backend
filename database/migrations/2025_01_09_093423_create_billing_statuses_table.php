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
        Schema::create('billing_statuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('billing_id')->constrained('billings')->onDelete('cascade');
            $table->enum('status', ['visit', 'promise_to_pay', 'pay'])->default('visit')->comment('Status kunjungan');
            $table->date('status_date')->comment('Tanggal status');
            $table->text('description')->nullable()->comment('Keterangan');
            $table->string('evidence')->nullable()->comment('Bukti');
            $table->date('promise_date')->nullable()->comment('Tanggal janji bayar');
            $table->bigInteger('payment_amount')->nullable()->comment('Jumlah setoran');
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
        Schema::dropIfExists('billing_statuses');
    }
};
