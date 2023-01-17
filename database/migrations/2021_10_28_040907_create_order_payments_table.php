<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('order_id');
            $table->string('external_id' , 255)->nullable();
            $table->string('bank_code' , 20)->nullable();
            $table->string('name')->nullable();
            $table->decimal('expected_amount' , 12 ,0)->nullable();
            $table->boolean('is_closed')->nullable();
            $table->string('expiration_date' , 30)->nullable();
            $table->boolean('is_single_use')->nullable();
            $table->string('status' , 20)->nullable();
            $table->string('currency' , 5)->nullable();
            $table->string('owner_id' , 50)->nullable();
            $table->integer('merchant_code')->nullable();
            $table->string('account_number' , 50)->nullable();
            $table->string('id_payment' , 100)->nullable();
            $table->string('payment_gate' , 100)->nullable();
            //$table->string('channel_code' , 50)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_payments');
    }
}
