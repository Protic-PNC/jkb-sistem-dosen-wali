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
        Schema::create('warnings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->string('class_name', 10); //sebagai record ketika mhs cuti
            $table->year('entry_year'); //sebagai record ketika mhs cuti
            $table->enum('warning_type', ['SP 1', 'SP 2', 'SP 3']); //sp 1, 2 etc
            $table->text('reason')->nullable();
            $table->date('date')->nullable();
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
