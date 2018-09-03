<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComentariosPubTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('comentarios_pub', function($table)
		{
		    $table->increments('id');
		    $table->integer('user_id');
		    $table->integer('item_id');
		    $table->integer('status');
		    $table->string('name',100);
		    $table->text('comment');
		    $table->timestamps()->default('0000-00-00');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
		Schema::dropIfExists('comentarios_pub');
	}

}
