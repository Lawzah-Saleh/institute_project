<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSessionIdToCoursePricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('course_prices', function (Blueprint $table) {
            $table->unsignedBigInteger('session_id')->nullable()->after('id');
            $table->foreign('session_id')->references('id')->on('course_sessions')->onDelete('cascade'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('course_prices', function (Blueprint $table) {
            $table->dropForeign(['session_id']); // حذف المفتاح الأجنبي
            $table->dropColumn('session_id'); // حذف العمود
        });
    }
}
