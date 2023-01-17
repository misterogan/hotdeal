<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromotionVoucherVendors extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promotion_voucher_vendors', function (Blueprint $table) {
            $table->id();
            $table->integer('promotion_vouchers_id');
            $table->integer('vendor_id');
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
        Schema::dropIfExists('promotion_voucher_vendors');
    }
}
