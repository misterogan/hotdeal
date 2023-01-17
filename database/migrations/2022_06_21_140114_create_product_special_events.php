<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductSpecialEvents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_special_events', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('special_event_id')->nullable();
            $table->bigInteger('product_id')->nullable();
            $table->enum('status', ['active', 'inactive','deleted']);
            $table->string('created_by');
            $table->string('updated_by');
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
        Schema::dropIfExists('product_special_events');
    }
}
