<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('coupon_name');
            $table->dateTime('start_date');
            $table->dateTime('expired_date');
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->integer('total_coupon')->nullable();
            $table->string('status',30)->nullable();
            $table->integer('partner_id')->nullable();
            $table->string('serial_code',50)->nullable();
            $table->integer('length_code')->nullable();
            $table->decimal('hotpoint', 12, 0)->nullable();
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
        Schema::dropIfExists('coupons');
    }
}
