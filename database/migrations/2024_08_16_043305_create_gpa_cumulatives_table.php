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
        Schema::create('gpa_cumulatives', function (Blueprint $table) {
            $table->id('gpa_cumulative_id')->primary();
            $table->foreignId('gpa_id');
            $table->foreign('gpa_id')->references('gpa_id')->on('gpas');
            $table->foreignId('student_id');
            $table->foreign('student_id')->references('student_id')->on('students');
            $table->decimal('cumulative_gpa', 3, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gpa_cumulatives');
    }
};
