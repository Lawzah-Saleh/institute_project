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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string("name_en",300);
            $table->string("name_ar",300);
            $table->unsignedInteger("phone");
            $table->string("address");
            $table->string("gender");
            $table->date("Day_birth");
            $table->string("place_birth");
            $table->string ("image")->nullable();
            $table->string("email")->nullable();
            $table->string("emptype");
            $table->string("state");
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
