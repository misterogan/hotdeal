<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromotionDiscounts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promotion_discounts', function (Blueprint $table) {
            $table->id();
            $table->enum('promo_from', ['vendor', 'hotdeal']);
            $table->integer('vendor_id');
            $table->enum('type', ['percentage', 'amount']);
            $table->enum('status', ['active', 'inactive', 'deleted']);
            $table->dateTime('start_date');
            $table->dateTime('end_date');
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
        Schema::dropIfExists('promotion_discounts');
    }
}
