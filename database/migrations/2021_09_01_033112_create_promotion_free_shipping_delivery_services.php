<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromotionFreeShippingDeliveryServices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promotion_free_shipping_delivery_services', function (Blueprint $table) {
            $table->id();
            $table->integer('promotion_free_shipping_id');
            $table->integer('shipment_service_id');
            $table->enum('status', ['active', 'inactive', 'deleted']);
            $table->timestamps();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('promotion_free_shipping_delivery_services');
    }
}
