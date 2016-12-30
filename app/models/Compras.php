<?php

class Compras extends Eloquent {

	protected $table = 'compras';

	public function publicacion()
	{
		return $this->belongsTo('Publicaciones','pub_id');
	}
	public function users()
	{
		return $this->belongsTo('User','user_id');
	}


}
