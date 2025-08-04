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
        Schema::create('student_classes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('academic_advisor_id')->nullable()->constrained('lecturers')->onDelete('set null');
            $table->string('academic_advisor_decree', 50)->nullable();
            $table->string('class_name', 10);
            $table->year('entry_year');
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
