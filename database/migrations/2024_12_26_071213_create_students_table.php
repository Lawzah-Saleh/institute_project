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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('student_name_en', 300)->index();
            $table->string('student_name_ar', 300)->index();
            $table->string("image", 500)->nullable();
            $table->json('phones')->nullable();
            $table->enum("gender", ["male", "female"]);
            $table->string("qualification");
            $table->date("birth_date");
            $table->string("birth_place");
            $table->string("address");
            $table->string("email")->nullable()->unique();
            $table->boolean("state")->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
