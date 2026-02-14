<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->date('start_date')->nullable();   
            $table->date('end_date')->nullable();   
            $table->integer('total_date')->nullable(); 
        });
    }

    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['start_date', 'end_date', 'total_date']);
        });
    }
};
