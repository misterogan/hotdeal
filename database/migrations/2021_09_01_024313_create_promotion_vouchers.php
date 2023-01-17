<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromotionVouchers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promotion_vouchers', function (Blueprint $table) {
            $table->id();
            $table->enum('voucher_type', ['vendor', 'hotdeal']);
            $table->string('voucher_name', 100);
            $table->string('voucher_description');
            $table->integer('vendor_id')->nullable();
            $table->string('voucher_code');
            $table->string('image')->nullable();
            $table->decimal('minimum_payment',12,0);
            $table->decimal('maximum_promo',12,0)->nullable();
            $table->enum('discount_type', ['percent', 'nominal']);
            $table->decimal('value_discount',12,0);
            $table->boolean('apply_to_all_product')->default(false);
            $table->boolean('apply_to_all_user')->default(true);
            $table->integer('user_id')->nullable();
            $table->integer('category_promotion_id')->nullable();
            $table->enum('status', ['active', 'inactive', 'deleted']);
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->boolean('show_only')->default(false);
            $table->boolean('is_multiple')->default(true);
            $table->string('total')->nullable();
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
        Schema::dropIfExists('promotion_vouchers');
    }
}
