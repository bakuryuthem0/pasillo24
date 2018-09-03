<?php


class GcmDevices extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'gcm_devices';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */

	function users()
	{
		return $this->belongsTo('User','user_id');
	}

}