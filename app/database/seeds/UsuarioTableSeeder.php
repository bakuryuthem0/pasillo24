<?php

class UsuarioTableSeeder extends Seeder {

    public function run()
    {
        User::insert(array(
        	'id' => '1', 
        	'username' => 'admin', 
        	'password' => Hash::make('Temporal*1'), 
        	'email' => 'admin@project.com', 
        	'name' => 'carlos',
        	'lastname' => 'salazar', 
        	'id_carnet' => '20990979',
        	'nit' =>  '111111', 
        	'state' => '2', 
        	'phone' => '5644646', 
        	'pag_web' => 'http://hola.com', 
        	'postal_cod' => '2122',
        	'dir' => 'adfdafdaf',
        	'pais' => 'adfafaf', 
        	'register_cod_active' => 'afdfad', 
        	'user_suspended' => '1', 
        	'role' => 'Administrador'

        ));
    }

}