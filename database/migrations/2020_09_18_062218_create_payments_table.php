<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
			$table->string('invoice_no', 100)->nullable();
			$table->text('title')->nullable();
			$table->dateTime('deadline');
			$table->double('amount')->nullable();
			$table->integer('project_id')->nullable();
			$table->integer('payment_status_id')->nullable();
			$table->string('payment_method', 150)->nullable();
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
        Schema::dropIfExists('payments');
    }
}
