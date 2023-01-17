<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReboundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('refund_return_confirmations', function (Blueprint $table) {
            $table->id();
            $table->string('consignor');
            $table->integer('refund_id');
            $table->string('receipt_number', 50);
            $table->string('shipping_name', 50);
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
        Schema::dropIfExists('refund_return_confirmations');
    }
}
