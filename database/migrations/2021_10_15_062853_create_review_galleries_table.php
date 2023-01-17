<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReviewGalleriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('review_galleries', function (Blueprint $table) {
            $table->id();
            $table->integer('review_id');
            $table->enum('type',['image','video']);
            $table->enum('status',['active','inactive']);
            $table->string('url_source');
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
        Schema::dropIfExists('review_galleries');
    }
}
