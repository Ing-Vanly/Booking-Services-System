<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDiscountAndGrandTotalToCartsTable extends Migration
{
    public function up()
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->decimal('discount', 10, 2)->default(0); // Discount field
            $table->decimal('grand_total', 10, 2)->default(0); // Grand total field
        });
    }
    
    public function down()
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->dropColumn('discount');
            $table->dropColumn('grand_total');
        });
    }
    
}
