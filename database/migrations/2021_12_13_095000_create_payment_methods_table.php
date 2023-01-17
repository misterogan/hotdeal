<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentMethodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('payment_gateway' , 25);
            $table->string('code' ,30);
            $table->string('label' ,30);
            $table->string('channel' ,25);
            $table->string('icon' ,255);
            $table->enum('status',['active','inactive','deleted']);
            $table->timestamps();
            $table->decimal('min_transaction' ,12, 0);
            $table->decimal('max_transaction' ,12, 0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_methods');
    }
}
