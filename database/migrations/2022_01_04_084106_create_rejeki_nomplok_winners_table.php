<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRejekiNomplokWinnersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rejeki_nomplok_winners', function (Blueprint $table) {
            $table->id();
            $table->integer('week_id');
            $table->integer('user_id');
            $table->integer('coupon_id');
            $table->decimal('value_hotpoint', 12, 0);
            $table->integer('product_id');
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
        Schema::dropIfExists('rejeki_nomplok_winners');
    }
}
