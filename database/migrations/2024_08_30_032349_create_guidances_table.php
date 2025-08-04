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
        Schema::create('guidances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->string('class_name', 10); //sebagai record ketika mhs cuti
            $table->year('entry_year'); //sebagai record ketika mhs cuti
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->text('problem')->nullable();
            $table->text('solution')->nullable();
            $table->date('problem_date')->nullable();
            $table->date('solution_date')->nullable();
            $table->integer('is_validated')->nullable();
            $table->text('validation_note')->nullable();
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
