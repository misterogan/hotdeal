<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFlashSales extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flash_sales', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('banner_type', ['image', 'video']);
            $table->enum('status', ['active', 'inactive']);
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->string('banner')->nullable();
            $table->string('banner_mobile')->nullable();
            $table->string('slug' , 255)->nullable();
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
        Schema::dropIfExists('flash_sales');
    }
}
