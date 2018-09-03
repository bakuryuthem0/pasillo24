<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComentariosRespuestasTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('respuestas', function($table)
		{
		    $table->increments('id');
		    $table->integer('comentario_id');
		    $table->integer('pub_id');
		    $table->integer('user_id');
		    $table->string('respuesta', 255);
		    $table->integer('is_read')->default(0);
		    $table->integer('deleted')->default(0);
		    
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
		Schema::dropIfExists('respuestas');
	}

}
