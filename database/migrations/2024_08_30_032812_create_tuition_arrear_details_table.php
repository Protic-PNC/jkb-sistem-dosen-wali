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
        Schema::create('tuition_arrear_details', function (Blueprint $table) {
            $table->id('tuition_arrear_id')->primary();
            $table->foreignId('student_id');
            $table->foreign('student_id')->references('student_id')->on('students');
            $table->decimal('amount', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tuition_arrear_details');
    }
};
