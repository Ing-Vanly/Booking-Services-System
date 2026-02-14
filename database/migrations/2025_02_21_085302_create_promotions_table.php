<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromotionsTable extends Migration
{

    public function up()
    {
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('discount_type', ['fix', 'percent']);
            $table->decimal('discount_value', 10, 2);
            $table->enum('promotion_type', ['product', 'category']);
            $table->date('start_date');
            $table->date('end_date');
            $table->string('image')->nullable();
            $table->string('default_key')->nullable();
            $table->timestamps();
        });
    }

  
    public function down()
    {
        Schema::dropIfExists('promotions');
    }
}
