<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->enum('registration_source', ['hotdeal', 'facebook', 'google'])->default('hotdeal');
            $table->timestamp('email_verified_at')->nullable();
            $table->boolean('is_email_verified')->default(false);
            $table->string('password')->nullable();
            $table->string('image')->nullable();
            $table->string('phone', 15)->nullable();
            $table->string('pin', 6)->nullable();
            $table->string('province')->nullable();
            $table->decimal('point', 12, 0)->default(0)->nullable();
            $table->string('district')->nullable();
            $table->string('address')->nullable();
            $table->string('postal_code')->nullable();
            $table->integer('payment_id')->nullable();
            $table->date('dob')->nullable();
            $table->enum('gender',['male','female'])->nullable();
            $table->boolean('is_vendor')->default(false);
            $table->enum('status', ['active', 'inactive', 'ban3', 'ban7'])->default('active');
            $table->rememberToken();
            $table->timestamps();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamp('phone_number_verified_at')->nullable();
            $table->boolean('is_phone_verified')->default(false);
            $table->string('referal_code')->nullable();
            $table->bigInteger('parent_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
