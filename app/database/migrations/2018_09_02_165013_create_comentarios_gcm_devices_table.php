<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComentariosGcmDevicesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('gcm_devices', function($table)
		{
		    $table->increments('id');
		    $table->string('gcm_regid',255);
		    $table->integer('user_id');
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
		Schema::dropIfExists('gcm_devices');
	}

}
