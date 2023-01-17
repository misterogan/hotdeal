<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupon_details', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('coupon_id');
            $table->string('code');
            $table->string('email')->nullable();
            $table->dateTime('claim_date')->nullable();
            $table->dateTime('buy_date');
            $table->boolean('isActive');
            $table->enum('status', ['redeem', 'buy', 'available'])->default('available');
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
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
        Schema::dropIfExists('coupon_details');
    }
}
