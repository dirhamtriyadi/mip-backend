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
        Schema::create('annual_holidays', function (Blueprint $table) {
            $table->id();
            $table->date('holiday_date')->comment('Tanggal libur');
            $table->text('description')->comment('Deskripsi hari libur, misalnya "Hari Kemerdekaan"');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('annual_holidays');
    }
};
