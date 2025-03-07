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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('session_id');
            $table->unsignedBigInteger('user_id');
            $table->dateTime('attendance_date')->default(now());

            $table->boolean('status');
            $table->timestamps();

            // المفاتيح الأجنبية
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->foreign('session_id')->references('id')->on('course_sessions')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
