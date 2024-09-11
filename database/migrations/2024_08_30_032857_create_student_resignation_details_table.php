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
        Schema::create('student_resignation_details', function (Blueprint $table) {
            $table->id('student_resignation_detail_id')->primary();
            $table->foreignId('student_resignation_id');
            $table->foreign('student_resignation_id')->references('student_resignation_id')->on('student_resignations');
            $table->foreignId('student_id');
            $table->foreign('student_id')->references('student_id')->on('students');
            $table->string('resignation_type'); //undur diri / DO
            $table->string('decree_number'); //surat keputusan
            $table->text('reason');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_resignation_details');
    }
};
