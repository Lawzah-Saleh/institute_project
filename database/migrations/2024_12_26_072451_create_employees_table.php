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
            $table->json('phones')->nullable();
            $table->string("address");
            $table->enum("gender", ["male", "female"]);
            $table->date("birth_date");
            $table->string("birth_place");
            $table->string ("image")->nullable();
            $table->string("email")->nullable()->unique();
            $table->string('emptype');
            $table->boolean('state')->default(0);
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
