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
        Schema::create('gpa_semesters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gpa_cumulative_id')->constrained()->cascadeOnDelete();
            $table->integer('semester');
            $table->decimal('semester_gpa', 3, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gpa_semesters');
    }
};
