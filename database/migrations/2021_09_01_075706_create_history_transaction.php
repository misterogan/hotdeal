<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoryTransaction extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('history_transaction', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('product_id');
            $table->string('sku');
            $table->integer('quantity');
            $table->integer('voucher_id');
            $table->decimal('price', 12, 0);
            $table->string('courier');
            $table->dateTime('delivered_date');
            $table->decimal('delivery_cost', 12, 0);
            $table->enum('status', ['active', 'inactive']);
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
        Schema::dropIfExists('history_transaction');
    }
}
