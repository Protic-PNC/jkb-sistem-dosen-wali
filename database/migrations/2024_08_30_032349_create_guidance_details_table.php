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
        Schema::create('guidance_details', function (Blueprint $table) {
            $table->id('guidance_detail_id')->primary();
            $table->foreignId('student_id');
            $table->foreign('student_id')->references('student_id')->on('students');
            $table->text('problem')->nullable();
            $table->text('solution')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guidance_details');
    }
};
