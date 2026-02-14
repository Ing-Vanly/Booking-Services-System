<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('customer_id');
            $table->text('guest_info');
            $table->decimal('sub_total', 10, 2);
            $table->decimal('final_total', 10, 2);
            $table->string('invoice_no')->unique();
            $table->date('booking_date');
            $table->string('payment_method');
            $table->enum('payment_status', ['paid', 'unpaid']);
            $table->enum('status', ['request', 'confirmed', 'cancel']);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};
