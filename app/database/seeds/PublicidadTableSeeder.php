<?php

class PublicidadTableSeeder extends Seeder {

    public function run()
    {
        Publicidad::create(array(
        	array(
		        'id' => '1', 
		        'image' => 'gif.gif', 
		        'pos' => 'top', 
		        'pag_web' => 'http://ffasil.com', 
		        'activo' => 0
        	),
			array(
				'id' => '2', 
				'image' => 'gif(1).gif', 
				'pos' => 'izq', 
				'pag_web' => 'http://ffasil.com', 
				'activo' => 0
			),
			array(
				'id' => '3', 
				'image' => 'c', 
				'pos' => 'der', 
				'pag_web' => 'http://', 
				'activo' => 0
			),

        ));
    }

}