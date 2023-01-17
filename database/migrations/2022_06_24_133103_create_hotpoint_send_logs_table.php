<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHotpointSendLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hotpoint_send_logs', function (Blueprint $table) {
            $table->id();
            $table->decimal('amount', 12, 0);
            $table->string('email', 100);
            $table->text('description')->nullable();
            $table->string('created_by', 100);
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
        Schema::dropIfExists('hotpoint_send_logs');
    }
}
