<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogVoucherUsagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_voucher_usages', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('promotion_voucher_id');
            $table->bigInteger('user_id');
            $table->string('voucher_code');
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
        Schema::dropIfExists('log_voucher_usages');
    }
}
