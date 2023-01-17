<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogUpdateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_update_products', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id');
            $table->text('before')->nullable();
            $table->text('after')->nullable();
            $table->text('compare')->nullable();
            $table->string('type')->default('edited');
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
        Schema::dropIfExists('log_update_products');
    }
}
