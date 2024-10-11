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
        Schema::create('students', function (Blueprint $table) {
            $table->id('student_id')->primary();
            $table->foreignId('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreignId('class_id')->nullable();
            $table->foreign('class_id')->references('class_id')->on('classes');
            $table->string('student_phone_number');
            $table->char('nim');
            $table->string('student_name');
            $table->string('student_address')->nullable();
            $table->string('student_signature')->nullable();
            $table->enum('status', ['active', 'non-active'])->default('active');
            $table->date('inactive_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
