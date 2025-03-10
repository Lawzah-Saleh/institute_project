<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id'); // مفتاح أجنبي لجدول الطلاب
            $table->unsignedBigInteger('session_id')->nullable();
            $table->unsignedBigInteger('invoice_id');
            $table->enum('status', ['pending', 'completed', 'failed']);
            $table->date('payment_date');
            $table->decimal('amount', 10, 2);

            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->foreign('session_id')->references('id')->on('course_sessions')->onDelete('cascade');
            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
