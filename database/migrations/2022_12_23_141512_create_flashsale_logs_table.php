<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFlashsaleLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flashsale_logs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('flashsale_id');
            $table->longText('before');
            $table->longText('after');
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('type')->nullable();
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
        Schema::dropIfExists('flashsale_logs');
    }
}
