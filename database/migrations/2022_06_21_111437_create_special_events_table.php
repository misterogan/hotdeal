<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpecialEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('special_events', function (Blueprint $table) {
            $table->id();
            $table->string('event_name');
            $table->string('banner_type')->default('image');
            $table->string('banner_image')->nullable();
            $table->string('banner_mobile')->nullable();
            $table->enum('status', ['active', 'inactive', 'deleted'])->nullable();
            $table->text('about')->nullable();
            $table->text('tnc')->nullable();
            $table->string('slug')->nullable();
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->dateTime('processed_date')->nullable();
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
        Schema::dropIfExists('special_events');
    }
}
