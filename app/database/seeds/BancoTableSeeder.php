<?php

class BancoTableSeeder extends Seeder {

    public function run()
    {
        Banco::create(array(
        	array(
	        	'id' => '1', 
	        	'nombre' => 'Banco Nacional de Bolivia'
        	),
        	array(
				'id' => '2', 
				'nombre' => 'Banco Union'
        	),
			array(
				'id' => '3', 
				'nombre' => 'Banco de Crédito'
			),
			array(
				'id' => '4', 
				'nombre' => 'Banco Mercantil Santa Cruz'
			),
			array(
				'id' => '6', 
				'nombre' => 'Banco Bisa'
			),
			array(
				'id' => '9', 
				'nombre' => 'PayPal'
			),
        ));
    }

}