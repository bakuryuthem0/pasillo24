<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsuarioTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('usuario', function($table)
		{
		    $table->increments('id');
		    $table->string('username',255);
		    $table->string('password',255);
		    $table->string('email',255);
		    $table->string('name',255);
		    $table->string('lastname',255);
		    $table->string('id_carnet',255);
		    $table->string('nit',255);
		    $table->integer('state');
		    $table->string('pag_web',255);
		    $table->string('phone',255);
		    $table->string('postal_cod',255);
		    $table->integer('reputation')->default(0);
		    $table->integer('votes')->default(0);
		    $table->string('dir',255);
		    $table->string('pais',255);
		    $table->string('register_cod_active')->nullable();
		    $table->integer('user_suspended')->default(0);
		    $table->integer('user_deleted')->default(0);
		    $table->string('remember_token',255)->nullable();
		    $table->string('role',255);
		    $table->string('nombEmp',255)->nullable();
		    $table->string('auth_token',255)->nullable();
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
		Schema::dropIfExists('usuario');
	}

}
