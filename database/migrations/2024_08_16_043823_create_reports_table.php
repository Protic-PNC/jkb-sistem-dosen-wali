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
            $table->id();
            
            $table->foreignId('student_class_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('academic_advisor_id')->nullable()->constrained('lecturers')->nullOnDelete();

            // Salinan snapshot sebagai data historis
            $table->string('class_name', 10); 
            $table->year('entry_year');  
            $table->string('academic_advisor_name', 100); 
            $table->string('academic_advisor_decree', 50); 

            $table->integer('semester');
            $table->string('academic_year', 9);
            $table->enum('status', ['draft', 'submitted', 'approved'])->default('draft');
            $table->date('submitted_at')->nullable();
            $table->date('approved_at')->nullable();

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
