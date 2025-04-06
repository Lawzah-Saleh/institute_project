<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('session_id');
            $table->unsignedBigInteger('employee_id');
            $table->dateTime('attendance_date')->useCurrent();
            $table->boolean('status')->default(0);
            $table->timestamps();

            $table->unique(['student_id', 'session_id','attendance_date'], 'student_unique_record');

            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->foreign('session_id')->references('id')->on('course_sessions')->onDelete('cascade');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
