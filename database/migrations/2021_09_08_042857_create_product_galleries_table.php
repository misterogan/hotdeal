<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductGalleriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * type 1 for image
         * type 2 for video
         */
        Schema::create('product_galleries', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id');
            $table->integer('product_detail_id')->nullable();
            $table->enum('type', ['1', '2']);
            $table->string('link', 255);
            $table->enum('status', ['active', 'inactive', 'deleted']);
            $table->timestamps();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->boolean('is_primary')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_galleries');
    }
}
