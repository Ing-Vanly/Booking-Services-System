<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            // Add brand_category_id as an unsignedBigInteger
            $table->unsignedBigInteger('brand_category_id')->nullable()->after('price');
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            // Drop the brand_category_id column
            $table->dropColumn('brand_category_id');
        });
    }
};
