<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRejekiNomplokWeeksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rejeki_nomplok_weeks', function (Blueprint $table) {
            $table->id();
            $table->integer('week');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->integer('ihsg')->nullable();
            $table->enum('status', ['active', 'inactive']);
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
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
        Schema::dropIfExists('rejeki_nomplok_weeks');
    }
}
