<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMailtextTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mailtext', function (Blueprint $table) {
            $table->id();
			$table->string('subject_key', 191)->unique();
			$table->longText('subject_value')->nullable();
			$table->string('body_key', 191)->unique();
			$table->longText('body_value')->nullable();				
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
        Schema::dropIfExists('mailtext');
    }
}
