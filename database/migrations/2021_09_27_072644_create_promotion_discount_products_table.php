<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromotionDiscountProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promotion_discount_products', function (Blueprint $table) {
            $table->id();
            $table->integer('promotion_discounts_id');
            $table->integer('vendor_id');
            $table->integer('product_id');
            $table->enum('type', ['percentage', 'amount']);
            $table->decimal('value_discount', 12, 0);
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
        Schema::dropIfExists('promotion_discount_products');
    }
}
