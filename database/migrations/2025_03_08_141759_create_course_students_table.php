<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('course_students', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('course_id');
            $table->enum('study_time', ['8-10', '10-12', '12-2', '2-4', '4-6'])->nullable();
            $table->enum('status', ['waiting', 'cancelled'])->default('waiting');
            $table->timestamp('register_at')->useCurrent();
            $table->timestamps();

            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');

            $table->unique(['student_id', 'course_id']);

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_students');
    }
};
