<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendors extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->bigInteger('user_id');
            $table->string('image', 255)->nullable();
            $table->integer('country_id')->nullable();
            $table->integer('province_id')->nullable();
            $table->integer('city_id')->nullable();
            $table->integer('area_id')->nullable();
            $table->string('lat')->nullable();
            $table->string('lng')->nullable();
            $table->integer('suburb_id')->nullable();
            $table->string('address', 255)->nullable();
            $table->enum('status', ['active', 'inactive', 'deleted']);
            $table->text('description')->nullable();
            $table->string('pic' , 255)->nullable();
            $table->decimal('rating')->nullable();
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
        Schema::dropIfExists('vendors');
    }
}
