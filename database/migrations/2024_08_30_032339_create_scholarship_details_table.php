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
        Schema::create('scholarship_details', function (Blueprint $table) {
            $table->id('scholarship_detail_id')->primary();
            $table->foreignId('scholarship_id');
            $table->foreign('scholarship_id')->references('scholarship_id')->on('scholarships');
            $table->foreignId('student_id');
            $table->foreign('student_id')->references('student_id')->on('students');
            $table->string('scholarship_type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scholarship_details');
    }
};
