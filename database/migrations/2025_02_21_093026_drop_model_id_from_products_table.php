<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            // Drop the model_id column
            $table->dropColumn('model_id');
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            // Add the column back
            $table->unsignedBigInteger('model_id')->nullable();
        });
    }
};
