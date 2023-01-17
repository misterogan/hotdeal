<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('vendor_id');
            $table->integer('category_id');
            $table->integer('brand_id')->nullable();
            $table->string('brand' , 100)->nullable();
            $table->text('description');
            $table->string('short_desc');
            $table->decimal('admin_fee', 12, 0);
            $table->integer('weight');
            $table->integer('height');
            $table->integer('length');
            $table->integer('width');
            $table->decimal('rating',3,2)->nullable();
            $table->string('dimension' , 30)->nullable();
            $table->boolean('cod')->default(false);
            $table->boolean('for_order')->default(true);
            $table->string('sku');
            $table->string('slug' , 100);
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
        Schema::dropIfExists('products');
    }
}
