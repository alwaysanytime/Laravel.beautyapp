<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
			$table->text('task_name')->nullable();
			$table->longText('description')->nullable();
			$table->integer('task_group_id')->nullable();
			$table->integer('project_id')->nullable();
			$table->dateTime('task_date')->nullable();
			$table->integer('bOrder')->nullable();
			$table->tinyInteger('complete_task')->default(0);
			$table->dateTime('creation_date',0)->nullable();
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
        Schema::dropIfExists('tasks');
    }
}
