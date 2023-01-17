<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RefundAccountBank extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('refund_account_banks', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('refund_id');
            $table->string('account_name');
            $table->string('account_number');
            $table->string('bank_name');
            $table->string('identity_image' , 1000);
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
        Schema::dropIfExists('refund_account_banks');
    }
}
