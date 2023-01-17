<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHotpointsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hotpoints', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->enum('type', ['use', 'earn']);
            $table->decimal('value', 12, 0);
            $table->decimal('before', 12, 0);
            $table->decimal('after', 12, 0);
            $table->char('code', 10);
            $table->string('detail', 255);
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
        Schema::dropIfExists('hotpoints');
    }
}
