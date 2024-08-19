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
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('class_id')->constrained()->onDelete('cascade');
            $table->foreignId('warning_id')->constrained()->onDelete('cascade');
            $table->foreignId('gpas_id')->constrained()->onDelete('cascade');
            $table->foreignId('guidance_id')->constrained()->onDelete('cascade');
            $table->foreignId('achievement_id')->constrained()->onDelete('cascade');
            $table->foreignId('scholarship_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_withdrawal_id')->constrained()->onDelete('cascade');
            $table->foreignId('tuition_arrears_id')->constrained()->onDelete('cascade');
            //$table->enum('status', ['pending', 'approved', 'rejected']);
            $table->boolean('has_acc_academic_advisor');
            $table->boolean('has_acc_head_of_program');
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
