<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('degrees', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id')->index();
            $table->unsignedBigInteger('course_session_id')->index();
            $table->decimal('practical_degree', 5, 2);
            $table->decimal('final_degree', 5, 2);
            $table->decimal('attendance_degree', 5, 2);
            $table->decimal('total_degree', 5, 2);
            $table->boolean('state')->default('0');
            $table->timestamps();

            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->foreign('course_session_id')->references('id')->on('course_sessions')->onDelete('cascade');
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('degrees');
    }
};
