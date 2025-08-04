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
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('student_class_id')->nullable()->constrained()->onDelete('set null');
            $table->string('student_phone_number', 20);
            $table->char('nim', 9)->unique();
            $table->string('student_address')->nullable();
            // $table->string('student_signature')->nullable();
            $table->enum('status', ['active', 'graduated', 'dropout', 'resign', 'academic_leave'])->default('active');
            $table->date('inactive_at')->nullable();
            $table->integer('active_at_semester');
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
