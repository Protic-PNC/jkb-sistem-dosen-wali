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
        Schema::create('reports', function (Blueprint $table) {
            $table->id('report_id')->primary();

            $table->foreignId('class_id');
            $table->foreign('class_id')->references('class_id')->on('classes');
            
            // $table->foreignId('warning_id')->nullable();
            // $table->foreign('warning_id')->references('warning_id')->on('warnings');

            // $table->foreignId('gpa_id')->nullable();
            // $table->foreign('gpa_id')->references('gpa_id')->on('gpas');
            
            // $table->foreignId('guidance_id')->nullable();
            // $table->foreign('guidance_id')->references('guidance_id')->on('guidances');
            
            // $table->foreignId('achievement_id')->nullable();
            // $table->foreign('achievement_id')->references('achievement_id')->on('achievements');

            // $table->foreignId('scholarship_id')->nullable();
            // $table->foreign('scholarship_id')->references('scholarship_id')->on('scholarships');

            // $table->foreignId('student_resignation_id')->nullable();
            // $table->foreign('student_resignation_id')->references('student_resignation_id')->on('student_resignations');

            // $table->foreignId('tuition_arrear_id')->nullable();
            // $table->foreign('tuition_arrear_id')->references('tuition_arrear_id')->on('tuition_arrears');
            

            $table->integer('semester');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->boolean('has_acc_academic_advisor')->default(false);
            $table->boolean('has_acc_head_of_program')->default(false);            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
