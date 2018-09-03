<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComentariosPublicidadTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('publicidad', function($table)
		{
		    $table->increments('id');
		    $table->string('image',255);
		    $table->string('pos',255);
		    $table->string('pag_web',255);
		    $table->integer('activo');
		    
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
		Schema::dropIfExists('publicidad');
	}

}
