<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInviteRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invite_rules', function (Blueprint $table) {
            $table->id();
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->integer('count_from')->default(0);
            $table->integer('count_after')->default(0);
            $table->integer('percentage')->default(0);
            $table->integer('created_by')->default(0);
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
            $table->integer('updated_by')->default(0);
            $table->enum('status', ['active', 'inactive', 'deleted'])->default('inactive');
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
        Schema::dropIfExists('invite_rules');
    }
}
