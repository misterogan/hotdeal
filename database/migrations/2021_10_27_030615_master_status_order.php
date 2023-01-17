<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MasterStatusOrder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('master_status_order', function (Blueprint $table) {
            $table->id();
            $table->string('status_code' , 30);
            $table->string('description' , 255);
            $table->string('description_vendor' , 255)->nullable();
            $table->enum('status', ['active', 'inactive']);
            $table->integer('period')->nullable();
            $table->string('created_by' , 100)->nullable();
            $table->string('updated_by' , 100)->nullable();
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
        Schema::dropIfExists('master_status_order');
    }
}
