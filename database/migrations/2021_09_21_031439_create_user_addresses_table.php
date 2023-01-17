<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_addresses', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->string('recipient_name');
            $table->string('phone_number');
            $table->text('address');
            $table->integer('city_id');
            $table->integer('province_id');
            $table->integer('regency_id');
            $table->integer('area_id');
            $table->string('lat');
            $table->string('lng');
            $table->string('zip_code');
            $table->string('label_name');
            $table->boolean('is_primary_address')->nullable();
            $table->enum('status', ['active', 'inactive']);
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
        Schema::dropIfExists('user_addresses');
    }
}
