<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRejekiNomplokCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rejeki_nomplok_coupons', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('rejeki_nomplok_week_id');
            $table->integer('order_details_id');
            $table->integer('product_id');
            $table->integer('coupon_number');
            $table->boolean('is_winner')->default(false)->nullable();
            $table->boolean('has_send_point')->default(false)->nullable();
            $table->decimal('point_sent' , 12 ,0)->default(0);
            $table->decimal('value_transaction' , 12 ,0)->default(0);
            $table->enum('status', ['active', 'expired', 'cancel']);
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
        Schema::dropIfExists('rejeki_nomplok_coupons');
    }
}
