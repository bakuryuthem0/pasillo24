<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComentariosGcmPagosTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('pagos', function($table)
		{
		    $table->increments('id');
		    $table->integer('pub_id');
		    $table->integer('user_id');
		    $table->integer('banco_id');
		    $table->string('num_trans',255);
		    $table->string('banco_ext',255)->nullable();
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
		//
		Schema::dropIfExists('pagos');
	}

}
