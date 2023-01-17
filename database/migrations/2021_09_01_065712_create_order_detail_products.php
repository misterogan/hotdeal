<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderDetailProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_detail_products', function (Blueprint $table) {
            $table->id();
            $table->integer('order_detail_id');
            $table->integer('product_detail_id');
            $table->integer('quantity');
            $table->decimal('price', 12, 0);
            $table->decimal('discount_price', 12, 0);
            $table->decimal('fix_price', 12, 0);
            $table->decimal('admkin_fee', 12, 0);
            $table->integer('promotion_vouchers_id')->nullable();
            $table->integer('promotion_discount_id')->nullable();
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
        Schema::dropIfExists('order_detail_products');
    }
}
