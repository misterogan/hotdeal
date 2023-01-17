<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_details', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id');
            $table->integer('stock');
            $table->decimal('price', 12, 0);
            $table->string('variant_key_1' , 30)->nullable();
            $table->string('variant_value_1', 30)->nullable();
            $table->string('variant_key_2',30)->nullable();
            $table->string('variant_value_2',30)->nullable();
            $table->string('variation_code',30)->nullable();
            $table->string('child_sku',20)->nullable();
            $table->integer('product_galleries_id')->nullable();
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
        Schema::dropIfExists('product_details');
    }
}
