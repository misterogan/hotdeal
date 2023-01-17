<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMasterPaymentMethodDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('master_payment_method_details', function (Blueprint $table) {
            $table->id();
            $table->integer('payment_method_id');
            $table->string('name');
            $table->string('code');
            $table->boolean('is_activated')->default(1);
            $table->string('payment_gateway')->nullable();
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
        Schema::dropIfExists('master_payment_method_details');
    }
}
