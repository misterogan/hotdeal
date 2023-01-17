<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderDetailLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_detail_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('order_details_id');
            $table->integer('status')->nullable();
            $table->timestamps();
            $table->string('created_by')->nullable();
            $table->string('description')->nullable();
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
        Schema::dropIfExists('order_detail_logs');
    }
}
