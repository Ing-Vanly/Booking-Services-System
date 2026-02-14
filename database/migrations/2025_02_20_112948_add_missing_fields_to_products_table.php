<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMissingFieldsToProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {

            if (!Schema::hasColumn('products', 'status')) {
                $table->integer('status')->default(1);
            }

            if (!Schema::hasColumn('products', 'model_id')) {
                $table->unsignedInteger('model_id');
            }

            if (!Schema::hasColumn('products', 'number_available')) {
                $table->integer('number_available');
            }

            if (!Schema::hasColumn('products', 'description')) {
                $table->text('description')->nullable();
            }

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('model_id');
            $table->dropColumn('number_available');
            $table->dropColumn('description');
        });
    }
}
