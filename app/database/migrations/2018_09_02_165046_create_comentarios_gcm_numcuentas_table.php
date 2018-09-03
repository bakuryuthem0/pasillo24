<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComentariosGcmNumcuentasTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('numcuentas', function($table)
		{
		    $table->increments('id');
		    $table->integer('banco_id');
		    $table->string('num_cuenta',255);
		    $table->string('tipoCuenta',255);
		    
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
		Schema::dropIfExists('numcuentas');
	}

}
