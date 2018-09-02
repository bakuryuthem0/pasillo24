<?php

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();

		$this->call('BancoTableSeeder');
		$this->call('DepartamentoTableSeeder');
		$this->call('MarcasTableSeeder');
		$this->call('ModeloTableSeeder');
		$this->call('PublicidadTableSeeder');
		$this->call('SubCategoriaTableSeeder');
		$this->call('TextosTableSeeder');
		$this->call('UsuarioTableSeeder');
	}

}
