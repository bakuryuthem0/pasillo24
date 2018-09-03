<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComentariosComprasTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('compras', function($table)
		{
		    $table->increments('id');
		    $table->integer('user_id');
		    $table->integer('item_id');
		    $table->integer('valor_comp')->default(0);
		    $table->integer('valor_vend')->default(0);
		    $table->date('fechaVal')->nullable();
		    $table->integer('valorado_comp')->default(0);
		    $table->integer('valorado_vend')->default(0);
		    $table->integer('was_erased_comp')->default(0);
		    $table->integer('was_erased_vend')->default(0);
		    
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
		Schema::dropIfExists('compras');
	}

}
