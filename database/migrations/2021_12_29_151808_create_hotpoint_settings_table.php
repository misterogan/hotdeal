<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHotpointSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hotpoint_settings', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('password');
            $table->string('tries')->nullable();
            $table->string('otp')->nullable();
            $table->string('try_again_in')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamp('requested_at')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->string('created_by');
            $table->string('updated_by');
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
        Schema::dropIfExists('hotpoint_settings');
    }
}
