<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFlashsaleProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flashsale_products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('img_url');
            $table->integer('normal_prize');
            $table->integer('discounted_prize');
            $table->integer('weight');
            $table->integer('quantity');
            $table->boolean('is_free_shipping');
            $table->enum('status', ['active', 'inactive']);
            $table->integer('shop_id');
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
        Schema::dropIfExists('flashdeal_products');
    }
}
