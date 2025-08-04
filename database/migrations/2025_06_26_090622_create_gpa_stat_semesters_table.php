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
        Schema::create('gpa_stat_semesters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gpa_stat_id')->constrained()->cascadeOnDelete();
            $table->integer('semester');
            $table->float('avg')->nullable();
            $table->float('min')->nullable();
            $table->float('max')->nullable();
            $table->integer('below_3')->nullable();
            $table->float('below_3_percent')->nullable();
            $table->integer('above_equal_3')->nullable();
            $table->float('above_equal_3_percent')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gpa_stat_semesters');
    }
};
