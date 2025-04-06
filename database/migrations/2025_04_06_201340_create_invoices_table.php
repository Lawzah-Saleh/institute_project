<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('payment_id'); // ربط الحافظة بسجل الدفع الأساسي
            $table->unsignedBigInteger('payment_sources_id')->nullable();

            $table->unsignedBigInteger('amount');
            $table->boolean('status')->default(0);
            $table->string('invoice_number')->unique();
            $table->string('invoice_details');
            $table->date('due_date')->nullable(); // تاريخ استحقاق الدفع
            $table->dateTime('paid_at')->nullable(); // تاريخ الدفع

            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->foreign('payment_id')->references('id')->on('payments')->onDelete('cascade');
            $table->foreign('payment_sources_id')->references('id')->on('payment_sources')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
