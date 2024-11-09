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
        Schema::create('leaves', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->date('start_date')->comment('Tanggal mulai cuti');
            $table->date('end_date')->comment('Tanggal selesai cuti');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->comment('Status cuti');
            $table->string('response')->nullable()->comment('Balasan dari atasan');
            // $table->string('description')->nullable()->comment('Deskripsi cuti');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leaves');
    }
};
