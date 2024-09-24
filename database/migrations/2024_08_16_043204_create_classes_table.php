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
        Schema::create('classes', function (Blueprint $table) {
            $table->id('class_id')->primary();
            $table->foreignId('program_id')->nullable();
            $table->foreign('program_id')->references('program_id')->on('programs');
            $table->foreignId('academic_advisor_id')->nullable();
            $table->foreign('academic_advisor_id')->references('lecturer_id')->on('lecturers');
            $table->string('class_name');
            $table->integer('academic_year');
            $table->enum('status', ['active', 'graduated'])->default('active');
            $table->date('graduated_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classes');
    }
};
