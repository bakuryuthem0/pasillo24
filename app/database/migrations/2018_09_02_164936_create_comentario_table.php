<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComentarioTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('comentario', function($table)
		{
		    $table->increments('id');
		    $table->integer('user_id');
		    $table->integer('pub_id');
		    $table->text('comentario');
		    $table->integer('respondido');
		    $table->integer('is_read');
		    $table->integer('deleted');
		    
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
		Schema::dropIfExists('comentario');
	}

}
