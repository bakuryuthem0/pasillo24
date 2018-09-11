<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComentariosPublicacionesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('publicaciones', function($table)
		{
		    $table->increments('id');
		    $table->integer('user_id');
		    $table->string('tipo',255);
		    $table->string('ubicacion',255);
		    $table->integer('departamento');
		    $table->integer('categoria');
		    $table->string('titulo',255);
		    $table->string('ciudad',255);
		    $table->string('pag_web',255);
		    $table->integer('typeCat');
		    $table->integer('marca');
		    $table->integer('modelo');
		    $table->integer('anio');
		    $table->integer('precio');
		    $table->integer('kilometraje');
		    $table->string('cilindraje',255);
		    $table->string('transmision',255);
		    $table->string('combustible',255);
		    $table->string('documentos',255);
		    $table->integer('traccion');
		    $table->string('moneda',255);
		    $table->string('extension',255);
		    $table->text('descripcion');
		    $table->string('transaccion',255);
		    $table->integer('duracion');
		    $table->integer('duracionNormal');
		    $table->integer('monto');
		    $table->string('status');
		    $table->string('motivo');
		    $table->date('fechIni');
		    $table->date('fechFin');
		    $table->date('fechIniNormal');
		    $table->date('fechFinNormal');
		    $table->integer('deleted')->default(0);
		    $table->string('name',255);
		    $table->string('lastname',255);
		    $table->string('phone',255);
		    $table->string('email',255);
		    $table->string('pag_web_hab',255);
		    $table->string('img_1',255);
		    $table->string('img_2',255);
		    $table->string('img_3',255);
		    $table->string('img_4',255);
		    $table->string('img_5',255);
		    $table->string('img_6',255);
		    $table->string('img_7',255);
		    $table->string('img_8',255);
		    $table->string('condicion',255);
		    $table->string('bussiness_type',255);
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
		Schema::dropIfExists('publicaciones');
	}

}
