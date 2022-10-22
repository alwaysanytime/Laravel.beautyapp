<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
			$table->text('comment')->nullable();
			$table->text('attachment')->nullable();
			$table->dateTime('comments_date', 0)->nullable();
			$table->integer('task_id')->nullable();
			$table->integer('staff_id')->nullable();
			$table->integer('project_id')->nullable();
			$table->tinyInteger('battach')->default(0);
			$table->tinyInteger('editable')->default(0);
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
        Schema::dropIfExists('comments');
    }
}
