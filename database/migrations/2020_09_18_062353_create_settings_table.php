<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
			$table->tinyInteger('bactive')->default(0);
			$table->text('company_name')->nullable();
			$table->text('company_title')->nullable();
			$table->text('logo')->nullable();
			$table->text('favicon')->nullable();
			$table->string('email', 150)->nullable();
			$table->string('tomailaddress', 150)->nullable();
			$table->string('timezone_id', 100)->nullable();
			$table->string('theme_color', 30)->nullable();
			$table->tinyInteger('recaptcha')->default(0);
			$table->text('sitekey')->nullable();
			$table->text('secretkey')->nullable();
			$table->tinyInteger('isnotification')->default(0);
			$table->text('siteurl')->nullable();
			$table->text('zoom_api_key')->nullable();
			$table->text('zoom_api_secret')->nullable();
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
        Schema::dropIfExists('settings');
    }
}
