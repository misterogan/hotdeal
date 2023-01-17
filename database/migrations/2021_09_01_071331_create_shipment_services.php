<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShipmentServices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipment_services', function (Blueprint $table) {
            $table->id();
            $table->integer('shipment_services_shipper_id');
            $table->integer('shipment_services_id')->nullable();
            $table->string('service_name')->nullable();
            $table->string('type_name')->nullable();
            $table->string('volumetric', 255)->nullable();
            $table->integer('min_kg')->nullable();
            $table->integer('max_kg')->nullable();
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
        Schema::dropIfExists('shipment_services');
    }
}
