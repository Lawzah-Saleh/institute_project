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
        Schema::create('payment_sources', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // اسم الجهة (مثل: البنك، البريد، إلخ)
            $table->enum('status', ['active', 'inactive'])->default('active'); // حالة الجهة (نشطة أو غير نشطة)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_sources');
    }
};
