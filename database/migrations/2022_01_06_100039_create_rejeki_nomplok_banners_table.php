<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRejekiNomplokBannersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rejeki_nomplok_banners', function (Blueprint $table) {
            $table->id();
            $table->string('banner')->nullable();
            $table->string('banner_mobile')->nullable();
            $table->enum('status', ['active', 'inactive']);
            $table->string('created_by');
            $table->string('updated_by');
            $table->timestamps();
            $table->enum('status', ['active', 'inactive']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rejeki_nomplok_banners');
    }
}
