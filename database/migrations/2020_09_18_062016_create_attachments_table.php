<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attachments', function (Blueprint $table) {
            $table->id();
			$table->text('attach_title')->nullable();
			$table->text('attachment')->nullable();
			$table->dateTime('attach_date', 0)->nullable();
			$table->integer('comments_id')->nullable();
			$table->integer('task_id')->nullable();
			$table->integer('staff_id')->nullable();			
			$table->integer('project_id')->nullable();			
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
        Schema::dropIfExists('attachments');
    }
}
