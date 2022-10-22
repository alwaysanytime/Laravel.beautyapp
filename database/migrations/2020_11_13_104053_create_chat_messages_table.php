<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
			$table->integer('user_id')->nullable();
			$table->integer('me_id')->nullable();
			$table->text('chat_mes_text')->nullable();
			$table->text('chat_mes_file')->nullable();
			$table->text('chat_mes_img')->nullable();
			$table->integer('is_delete')->nullable();
			$table->integer('is_seen')->nullable();
			$table->integer('is_me_id')->nullable();
			$table->integer('is_group')->nullable();
			$table->timestamp('chat_datetime')->nullable();
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
        Schema::dropIfExists('chat_messages');
    }
}
