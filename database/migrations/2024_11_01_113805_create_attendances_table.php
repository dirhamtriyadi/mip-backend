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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->string('code')->comment('Kode absensi');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->date('date')->comment('Tanggal absensi');
            $table->time('time_check_in')->nullable()->comment('Waktu absensi masuk');
            $table->time('time_check_out')->nullable()->comment('Waktu absensi pulang');
            $table->integer('late_duration')->nullable(); // durasi terlambat
            $table->integer('early_leave_duration')->nullable(); // durasi pulang lebih awal
            $table->enum('type', ['present', 'sick', 'permit'])->comment('Tipe absensi');
            $table->string('description_check_in')->nullable()->comment('Deskripsi absensi');
            $table->string('description_check_out')->nullable()->comment('Deskripsi absensi');
            $table->string('image_check_in')->nullable()->comment('Bukti absensi masuk');
            $table->string('image_check_out')->nullable()->comment('Bukti absensi keluar');
            $table->string('location_check_in')->nullable()->comment('Lokasi absensi');
            $table->string('location_check_out')->nullable()->comment('Lokasi absensi');
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
        Schema::dropIfExists('attendances');
    }
};
