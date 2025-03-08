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
        Schema::create('course_session_students', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('course_session_id')->nullable(); // السماح بالتسجيل بدون كورس متاح
            $table->timestamp('register_at')->useCurrent();
            $table->enum('study_time', ['8-10', '10-12','12-2', '2-4', '4-6'])->default('8-10'); // وقت الدراسة المفضل
            $table->enum('status', ['pending', 'in_progress', 'completed', 'dropped'])->default('pending');
            $table->unique(['student_id', 'course_id']); // منع تسجيل نفس الطالب في نفس الكورس أكثر من مرة

            // العلاقات (Foreign Keys)
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
            $table->foreign('course_session_id')->references('id')->on('course_sessions')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_session_students');
    }
};
