<?php

class DepartamentoTableSeeder extends Seeder {

    public function run()
    {
        Department::insert(array(
        	array(
	        	'id' => '1', 
	        	'nombre' => 'la paz'
        	),
			array(
				'id' => '2', 
				'nombre' => 'santa cruz'
			),
			array(
				'id' => '3', 
				'nombre' => 'cochabamba'
			),
			array(
				'id' => '4', 
				'nombre' => 'beni'
			),
			array(
				'id' => '5', 
				'nombre' => 'potosi'
			),
			array(
				'id' => '6', 
				'nombre' => 'tarija'
			),
			array(
				'id' => '7', 
				'nombre' => 'chuquisaca'
			),
			array(
				'id' => '8', 
				'nombre' => 'oruro'
			),
			array(
				'id' => '9', 
				'nombre' => 'pando'
			),

        ));
    }

}