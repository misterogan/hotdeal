<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderDetailShipping extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_detail_shipping', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('order_details_id');
            $table->bigInteger('user_addresses_id')->nullable();
            $table->decimal('shipping_cost', 12, 0);
            $table->integer('shipment_services_id')->nullable();
            $table->integer('promotion_free_shipments_id')->nullable();
            $table->text('consignee');
            $table->text('consigner');
            $table->text('logistic_detail');
            $table->text('rate');
            $table->string('order_id')->nullable();
            $table->string('logistic_name')->nullable();
            $table->string('shipment_code')->nullable();
            $table->integer('min_estimate_arrived')->nullable();
            $table->integer('max_estimate_arrived')->nullable();
            $table->string('pickup_code' , 100)->nullable();
            $table->string('is_activate' , 100)->nullable();
            $table->string('pickup_time' , 100)->nullable();
            $table->string('label' , 255)->nullable();
            $table->string('awb_number' , 100)->nullable();
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
        Schema::dropIfExists('order_detail_shipping');
    }
}
