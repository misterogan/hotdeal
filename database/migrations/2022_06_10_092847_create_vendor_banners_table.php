<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorBannersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_banners', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('vendor_id');
            $table->string('img_url');
            $table->enum('status', ['active', 'inactive', 'deleted']);
            $table->string('url')->nullable();
            $table->boolean('new_tab')->default(false);
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
        Schema::dropIfExists('vendor_banners');
    }
}
