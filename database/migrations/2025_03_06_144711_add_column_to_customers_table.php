<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            if (Schema::hasColumn('customers', 'password')) {
                return;
            }
            if (Schema::hasColumn('customers', 'remember_token')) {
                return;
            }
            $table->string('password')->after('email')->nullable();
            $table->rememberToken()->after('password')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            if(!Schema::hasColumn('customers', 'password')) {
                return;
            }
            if(!Schema::hasColumn('customers', 'remember_token')) {
                return;
            }
            $table->dropColumn('password');
            $table->dropRememberToken();
        });
    }
}
