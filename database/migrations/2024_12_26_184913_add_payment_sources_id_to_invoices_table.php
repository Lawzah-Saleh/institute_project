<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->unsignedBigInteger('payment_sources_id')->nullable()->after('id');
            $table->foreign('payment_sources_id')->references('id')->on('payment_sources')->onDelete('cascade');         });
    }


    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign(['payment_sources_id']); // حذف المفتاح الأجنبي
            $table->dropColumn('payment_sources_id'); // حذف العمود
             });
    }
};
