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
        Schema::create('prospective_customers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Nama calon nasabah');
            $table->string('no_ktp')->unique()->comment('Nomor KTP calon nasabah');
            $table->string('bank')->comment('Bank calon nasabah');
            $table->string('ktp')->comment('Foto KTP calon nasabah');
            $table->string('kk')->comment('Foto KK calon nasabah');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->comment('Status calon nasabah');
            $table->text('status_message')->nullable()->comment('Status pesan calon nasabah');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade')->comment('ID petugas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prospective_customers');
    }
};
