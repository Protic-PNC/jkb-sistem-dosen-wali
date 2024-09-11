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
        Schema::create('lecturers', function (Blueprint $table) {
            $table->id('lecturer_id')->primary();
            $table->char('nidn');
            $table->char('nip');
            $table->string('lecturer_name');
            $table->string('lecturer_phone_number');
            $table->text('lecturer_address');
            $table->string('lecturer_signature')->nullable();
            $table->foreignId('position_id')->nullable();
            $table->foreign('position_id')->references('position_id')->on('positions');
            $table->foreignId('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lecturers');
    }
};
