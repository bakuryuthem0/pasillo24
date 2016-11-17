<?php
 
class Publicaciones extends Eloquent {


	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'publicaciones';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	public function deparments()
	{
		return $this->belongsTo('Department','departamento');
	}

}
