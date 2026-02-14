<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdditionalInfoToTransactionsTable extends Migration
{

    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->text('additional_info')->nullable();  
        });
    }

    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('additional_info');
        });
    }
}
