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
        Schema::create('institutes', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Name of the institute
            $table->text('description')->nullable(); // Description of the institute
            $table->string('logo_url')->nullable(); // URL for the logo
            $table->string('about_us')->nullable(); // URL for the logo
            $table->string('about_image')->nullable(); // URL for the logo
            $table->string('institute_servicies')->nullable(); // URL for the logo
            $table->string('address')->nullable(); // Address
            $table->string('phone')->nullable(); // Phone number
            $table->string('email')->nullable(); // Email

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('institutes');
    }
};
