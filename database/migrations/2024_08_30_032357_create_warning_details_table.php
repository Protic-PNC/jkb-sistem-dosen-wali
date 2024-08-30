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
        Schema::create('warning_details', function (Blueprint $table) {
            $table->id('warning_detail_id')->primary();
            $table->foreignId('student_id');
            $table->foreign('student_id')->references('student_id')->on('students');
            $table->string('warning_type'); //sp 1, 2 etc
            $table->text('reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warning_details');
    }
};
