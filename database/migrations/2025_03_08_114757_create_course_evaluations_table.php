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
        Schema::create('course_evaluations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('course_session_id');
            $table->integer('rating');
            $table->text('feedback')->nullable();
            $table->date('date')->default(now());
            $table->unique(['student_id', 'course_session_id']);// الطالب ما يقدر يقيم الكورس الا مرة واحدة




            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->foreign('course_session_id')->references('id')->on('course_sessions')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_evaluations');
    }
};
