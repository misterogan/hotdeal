<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMasterPartnersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('master_partners', function (Blueprint $table) {
            $table->id();
            $table->string('partner_name');
            $table->longText('description');
            $table->enum('status', ['active', 'inactive']);
            $table->string('image')->nullable();
            $table->boolean('show_in_footer');
            $table->string('partner_code');
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('token')->nullable();
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
        Schema::dropIfExists('master_partners');
    }
}
