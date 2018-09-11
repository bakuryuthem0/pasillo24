<?php

class DepartamentoTableSeeder extends Seeder {

    public function run()
    {
        Department::insert(array(
        	array(
	        	'id' => '1', 
	        	'nombre' => 'la paz',
	        	'created_at' => '2018-01-01',
	        	'updated_at' => '2018-01-01'
        	),
			array(
				'id' => '2', 
				'nombre' => 'santa cruz',
				'created_at' => '2018-01-01',
	        	'updated_at' => '2018-01-01'
			),
			array(
				'id' => '3', 
				'nombre' => 'cochabamba',
				'created_at' => '2018-01-01',
	        	'updated_at' => '2018-01-01'
			),
			array(
				'id' => '4', 
				'nombre' => 'beni',
				'created_at' => '2018-01-01',
	        	'updated_at' => '2018-01-01'
			),
			array(
				'id' => '5', 
				'nombre' => 'potosi',
				'created_at' => '2018-01-01',
	        	'updated_at' => '2018-01-01'
			),
			array(
				'id' => '6', 
				'nombre' => 'tarija',
				'created_at' => '2018-01-01',
	        	'updated_at' => '2018-01-01'
			),
			array(
				'id' => '7', 
				'nombre' => 'chuquisaca',
				'created_at' => '2018-01-01',
	        	'updated_at' => '2018-01-01'
			),
			array(
				'id' => '8', 
				'nombre' => 'oruro',
				'created_at' => '2018-01-01',
	        	'updated_at' => '2018-01-01'
			),
			array(
				'id' => '9', 
				'nombre' => 'pando',
				'created_at' => '2018-01-01',
	        	'updated_at' => '2018-01-01'
			),

        ));
    }

}