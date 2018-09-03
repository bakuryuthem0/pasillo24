<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('categoria', function($table)
		{
		    $table->increments('id');
		    $table->string('nombre',255);
		    $table->string('desc',255)->default('');
		    $table->integer('deleted');
		    $table->integer('tipo');
		    
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
		Schema::dropIfExists('categoria');
	}

}
