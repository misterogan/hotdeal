<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHomepageBanners extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('img_url')->nullable();
            $table->string('video_url')->nullable();
            $table->string('url')->nullable();
            $table->enum('type', ['video', 'image'])->nullable();
            $table->string('placement')->nullable();
            $table->integer('sequence');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            $table->boolean('new_tab')->default(false)->nullable();
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
        Schema::dropIfExists('homepage_banners');
    }
}
