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
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
			$table->string('designation', 200)->nullable();
			$table->string('phone', 200)->nullable();
			$table->string('skype_id', 200)->nullable();
			$table->string('facebook_id', 200)->nullable();
			$table->text('address')->nullable();
			$table->string('city', 200)->nullable();
			$table->string('state', 200)->nullable();
			$table->string('zip_code', 100)->nullable();
			$table->string('photo', 255)->nullable();
			$table->text('url')->nullable();
			$table->string('bactive', 200)->nullable();
			$table->string('bkey', 200)->nullable();
			$table->integer('country_id')->nullable();
			$table->integer('active_id')->nullable();
			$table->integer('role_id')->nullable();
			$table->timestamp('creation_date')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
