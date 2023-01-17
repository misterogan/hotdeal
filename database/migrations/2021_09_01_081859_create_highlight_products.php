<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHighlightProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('highlight_products', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id');
            $table->integer('highlight_type');
            $table->enum('status', ['active', 'inactive']);
            $table->integer('sequence');
            $table->string('img_square' ,255)->nullable();
            $table->string('img_portrait' ,255)->nullable();
            $table->string('img_landscape' ,255)->nullable();
            $table->enum('img_type' ,['video' ,'image'])->nullable();
            $table->string('deep_link');
            $table->boolean('new_tab')->default('false');
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
        Schema::dropIfExists('highlight_products');
    }
}
