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
        Schema::table('prospective_customers', function (Blueprint $table) {
            $table->foreignId('bank_id')->nullable()->constrained('banks')->onDelete('cascade')->comment('ID bank');
            $table->dropColumn('bank');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::table('prospective_customers', function (Blueprint $table) {
            $table->dropForeign('prospective_customers_bank_id_foreign'); // rumus nama {table}_{column}_foreign, supaya bisa drop lebih dari satu foreign bisa menggunakan array ['...', '...']
            $table->dropColumn('bank_id');
            $table->string('bank')->comment('Bank calon nasabah');
        });
    }
};
