<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComentariosGcmModeloTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('modelo', function($table)
		{
		    $table->increments('id');
		    $table->integer('marca_id');
		    $table->string('nombre',255);
		    $table->string('val',255);
		    
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
		Schema::dropIfExists('modelo');
	}

}
