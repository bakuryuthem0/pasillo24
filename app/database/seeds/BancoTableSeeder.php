<?php

class BancoTableSeeder extends Seeder {

    public function run()
    {
        Bancos::insert(array(
        	array(
	        	'id' => '1', 
	        	'nombre' => 'Banco Nacional de Bolivia',
	        	'created_at' => '0000-00-00',
	        	'updated_at' => '0000-00-00'
        	),
        	array(
				'id' => '2', 
				'nombre' => 'Banco Union',
				'created_at' => '0000-00-00',
	        	'updated_at' => '0000-00-00'
        	),
			array(
				'id' => '3', 
				'nombre' => 'Banco de Crédito',
				'created_at' => '0000-00-00',
	        	'updated_at' => '0000-00-00'
			),
			array(
				'id' => '4', 
				'nombre' => 'Banco Mercantil Santa Cruz',
				'created_at' => '0000-00-00',
	        	'updated_at' => '0000-00-00'
			),
			array(
				'id' => '6', 
				'nombre' => 'Banco Bisa',
				'created_at' => '0000-00-00',
	        	'updated_at' => '0000-00-00'
			),
			array(
				'id' => '9', 
				'nombre' => 'PayPal',
				'created_at' => '0000-00-00',
	        	'updated_at' => '0000-00-00'
			),
        ));
    }

}