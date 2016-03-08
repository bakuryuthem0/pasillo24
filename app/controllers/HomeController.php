<?php

class HomeController extends BaseController {
	public function showFront()
	{

		$title = "pasillo24.com el portal de comercio  creado por bolivianos para bolivianos";
		return View::make('portada')->with('title',$title)->with('portada','portada');;
	}
	public function showIndex($id = null)
	{
        if (!is_null($id)) {
			$dep = Department::find($id);
		}
		$title ="Inicio | pasillo24.com";
		if (!is_null($id)) {
			$lider = Publicaciones::where('status','=','Aprobado')
			->where('ubicacion','=','Principal')
			->where('tipo','=','Lider')
			->where('publicaciones.pag_web','!=',"")
			->where('fechFin','>=',date('Y-m-d',time()))
			->where('publicaciones.deleted','=',0)
			->orderBy('fechFin','desc')->get(array(
				'publicaciones.img_1',
				'publicaciones.titulo',
				'publicaciones.precio',
				'publicaciones.moneda',
				'publicaciones.id',
				)
			);
			$habitual = Publicaciones::where(function($query) use($id){
				/*Busco las habituales*/
				$query->where('tipo','=','Habitual')
				->where(function($query){
					/*Que vayan en la principal*/
					$query->where('ubicacion','=','Principal')
					->orWhere('ubicacion','=','Ambos')
					->where('status','=','Aprobado');

				})
				->where(function($query){
					/*y que sigan activas*/
					$query->where('fechFin','>=',date('Y-m-d',time()))
					->where('status','=','Aprobado');

				})
				->where('departamento','=',$id);
			})
			->where('deleted','=',0)
			->orWhere(function($query) use($id){
				$query->where('tipo','=','Lider')
				->where('ubicacion','=','Principal')
				->where('pag_web','=',"")
				->where('status','=','Aprobado')
				->where('departamento','=',$id);

			})
			->orderBy('fechFin','desc')->get();
			
			$casual = Publicaciones::where('tipo','=','Casual')
			->where('fechFin','>=',date('Y-m-d',time()))
			->where('status','=','Aprobado')
			->where('departamento','=',$id)
			->where('deleted','=',0)
			->get();
		}else
		{
			$lider = Publicaciones::where('status','=','Aprobado')
			->where('ubicacion','=','Principal')
			->where('tipo','=','Lider')
			->where('pag_web','!=',"")
			->where('fechFin','>=',date('Y-m-d',time()))
			->where('deleted','=',0)
			->orderBy('fechFin','desc')->get();
			$habitual = Publicaciones::where(function($query){
				/*Busco las habituales*/
				$query->where('tipo','=','Habitual')
				->where(function($query){
					/*Que vayan en la principal*/
					$query->where('ubicacion','=','Principal')
					->orWhere('ubicacion','=','Ambos')
					->where('status','=','Aprobado');

				})
				->where(function($query){
					/*y que sigan activas*/
					$query->where('fechFin','>=',date('Y-m-d',time()))
					->where('status','=','Aprobado');

				});
			})
			->orWhere(function($query){
				$query->where('tipo','=','Lider')
				->where('ubicacion','=','Principal')
				->where('pag_web','=',"")
				->where('status','=','Aprobado');

			})
			->where('deleted','=',0)
			->orderBy('fechFin','desc')->get();
			$casual = Publicaciones::where('tipo','=','Casual')
			->where('fechFin','>=',date('Y-m-d',time()))
			->where('status','=','Aprobado')
			->where('deleted','=',0)
			->get();
		}

		$categories = Categorias::where('deleted','=',0)->where('tipo','=',1)->orderBy('nombre')->get();
		$otros = new StdClass;
		foreach ($categories as $c) {
			if (strtolower($c->nombre) == 'otros') {
				$otros->id 		= $c->id;
				$otros->nombre	= $c->nombre;			
			}
		}
		if(!isset($otros->id))
		{
			$otros->id = '1000';
			$otros->nombre = 'Otros';
		}
		$servicios  = Categorias::where('deleted','=',0)->where('tipo','=',2)->orderBy('nombre')->get();
		$otros2 = new StdClass;
		foreach ($servicios as $c) {
			if (strtolower($c->nombre) == 'otros') {
				$otros2->id 		= $c->id;
				$otros2->nombre	= $c->nombre;			
			}
		}
		if(!isset($otros2->id))
		{
			$otros2->id = '1000';
			$otros2->nombre = 'Otros';
		}
		$departamentos = Department::get();
                $publi = Publicidad::get();
		if (!is_null($id)) {
        	return View::make('index')
		->with('title',$title)
                ->with('publi',$publi)
				->with('lider',$lider)
				->with('categories',$categories)
				->with('departamentos',$departamentos)
				->with('habitual',$habitual)
				->with('casual',$casual)
				->with('otros',$otros)
				->with('otros2',$otros2)
				->with('servicios',$servicios)
				->with('depFilter',$dep->id);
        }else
        {
			return View::make('index')
			->with('title',$title)
	                ->with('publi',$publi)
			->with('lider',$lider)
			->with('categories',$categories)
			->with('departamentos',$departamentos)
			->with('habitual',$habitual)
			->with('casual',$casual)
			->with('otros',$otros)
			->with('otros2',$otros2)
			->with('servicios',$servicios);
        }
	}
	public function getContact()
	{
		$title ="Contáctenos";
		return View::make('contactUs')->with('title',$title);
	}
	public function getTermsAndConditions()
	{
		$title = "Términos y condiciones | pasillo24.com";
		return View::make('termsAndCond')->with('title',$title);
	}
	public function getMision()
	{
		$title = "Misión y visión | pasillo24.com";
		return View::make('mision')
		->with('title',$title);
	}
	public function getPolitics()
	{
		$title = "Política de privacidad | pasillo24.com";
		return View::make('politics')
		->with('title',$title);
	}
	public function getSearch()
	{

		$input = Input::all();
		$title = "Búsqueda | pasillo24.com";

		if (Input::has('busq')) {
			if (Input::has('filter')) {
				$inp = Department::find(Input::get('filter'));
			}else
			{
				$inp = '';
			}
			if (!empty($inp)) {
				$sql = "SELECT `publicaciones`.`id`,`publicaciones`.`img_1`,`publicaciones`.`titulo` ,`publicaciones`.`precio`, `publicaciones`.`moneda` 
					FROM  `publicaciones` 
					LEFT JOIN  `categoria` ON  `categoria`.`id` =  `publicaciones`.`categoria` 
					WHERE `publicaciones`.`fechFin` >= '".date('Y-m-d',time())."' 
					AND `publicaciones`.`status` =  'Aprobado' AND (
						LOWER(  `publicaciones`.`titulo` ) LIKE  '%".strtolower($input['busq'])."%'
						OR LOWER( `publicaciones`.`pag_web` ) LIKE  '%".strtolower($input['busq'])."%'
						OR LOWER( `categoria`.`desc` ) LIKE  '%".strtolower($input['busq'])."%'
					) 
					AND `publicaciones`.`departamento` = ".$inp->id."
					AND  `publicaciones`.`deleted` = 0
					AND  (`publicaciones`.`ubicacion` = 'Categoria' OR `publicaciones`.`ubicacion` = 'Ambos')";


				$auxRes = Publicaciones::leftJoin('categoria','publicaciones.categoria','=','categoria.id')
				->leftJoin('departamento','publicaciones.departamento','=','departamento.id')
				->where(function($query) use ($input){
					$query->whereRaw("LOWER(`publicaciones`.`titulo`) LIKE  '%".strtolower($input['busq'])."%'")
					->orWhereRaw("LOWER(`departamento`.`nombre`) LIKE  '%".strtolower($input['busq'])."%'")
					->orWhereRaw("LOWER(`categoria`.`desc`) LIKE  '%".strtolower($input['busq'])."%'");
				})->where('publicaciones.tipo','!=','Lider')
				->where('publicaciones.status','=','Aprobado')
				->where('publicaciones.departamento','=',$inp->id)
				->where('publicaciones.deleted','=',0)
				->where(function($query)
				{
					$query->where('publicaciones.fechFin','>=',date('Y-m-d'))
					->orWhere('publicaciones.fechFinNormal','>=',date('Y-m-d'));
				});
				
				if (Input::has('min') || Input::has('max'))
				{
					if (Input::has('min') && Input::has('max')) {
						$min = Input::get('min');
						$max = Input::get('max');
						$minmax = array($min, $max);
						$currency = Input::get('currency');
						$filterPrice = '&min='.$min.'&max='.$max.'&currency='.$currency;
						$sql = $sql." AND (`publicaciones`.`precio` >= ".$min." AND `publicaciones`.`precio` <= ".$max.")"." AND `publicaciones`.`moneda` = '".$currency."'";
						$res = $auxRes->where('publicaciones.precio','>=',$min)->where('publicaciones.precio','<=',$max)->where('publicaciones.moneda','=',$curency)
						->paginate(5,array('publicaciones.id',
							'publicaciones.img_1',
							'publicaciones.titulo',
							'publicaciones.precio',
							'publicaciones.moneda',
							'publicaciones.descripcion',
							'departamento.nombre as dep'));
					}else{
						if(Input::has('max')){
							$max = Input::get('max');
							$minmax = array('', $max);
							$currency = Input::get('currency');
							$filterPrice = '&max='.$max.'&currency='.$currency;
							$sql = $sql." AND `publicaciones`.`precio` <= ".$max." AND `publicaciones`.`moneda` = '".$currency."'";
							$res = $auxRes->where('publicaciones.precio','<=',$max)->where('publicaciones.moneda','=',$currency)
							->paginate(5,array('publicaciones.id',
								'publicaciones.img_1',
								'publicaciones.titulo',
								'publicaciones.precio',
								'publicaciones.moneda',
								'publicaciones.descripcion',
								'departamento.nombre as dep'));
						}elseif(Input::has('min')){
							$min = Input::get('min');
							$minmax = array($min, '');
							$currency = Input::get('currency');
							$filterPrice = '&min='.$min.'&currency='.$currency;
							$sql = $sql." AND precio >= ".$min." AND `publicaciones`.`moneda` = '".$currency."'";
							$res = $auxRes->where('publicaciones.precio','>=',$min)->where('publicaciones.moneda','=',$curency)
							->paginate(5,array('publicaciones.id',
								'publicaciones.img_1',
								'publicaciones.titulo',
								'publicaciones.precio',
								'publicaciones.moneda',
								'publicaciones.descripcion',
								'departamento.nombre as dep'));
						}
					}
				}else
				{
					$res = $auxRes->paginate(5,array('publicaciones.id',
					'publicaciones.img_1',
					'publicaciones.titulo',
					'publicaciones.precio',
					'publicaciones.moneda',
					'publicaciones.descripcion',
					'departamento.nombre as dep'));	
				}
			}else
			{
				$sql = "SELECT `publicaciones`.`id`,`publicaciones`.`img_1`,`publicaciones`.`titulo` ,`publicaciones`.`precio`, `publicaciones`.`moneda` 
					FROM  `publicaciones` 
					LEFT JOIN  `categoria` ON  `categoria`.`id` =  `publicaciones`.`categoria` 
					WHERE `publicaciones`.`fechFin` >= '".date('Y-m-d',time())."' 
					AND `publicaciones`.`status` =  'Aprobado' AND (
						LOWER(  `publicaciones`.`titulo` ) LIKE  '%".strtolower($input['busq'])."%'
						OR LOWER( `publicaciones`.`pag_web` ) LIKE  '%".strtolower($input['busq'])."%'
						OR LOWER( `categoria`.`desc` ) LIKE  '%".strtolower($input['busq'])."%'
					) 
					AND  `publicaciones`.`deleted` = 0
					AND  (`publicaciones`.`ubicacion` = 'Categoria' OR `publicaciones`.`ubicacion` = 'Ambos')";

				$auxRes = Publicaciones::leftJoin('categoria','publicaciones.categoria','=','categoria.id')
				->leftJoin('departamento','publicaciones.departamento','=','departamento.id')
				->where(function($query) use ($input){
					$query->whereRaw("LOWER(`publicaciones`.`titulo`) LIKE  '%".strtolower($input['busq'])."%'")
					->orWhereRaw("LOWER(`departamento`.`nombre`) LIKE  '%".strtolower($input['busq'])."%'")
					->orWhereRaw("LOWER(`categoria`.`desc`) LIKE  '%".strtolower($input['busq'])."%'");
				})->where('publicaciones.tipo','!=','Lider')
				->where('publicaciones.status','=','Aprobado')
				->where('publicaciones.deleted','=',0)
				->where(function($query)
				{
					$query->where('publicaciones.fechFin','>=',date('Y-m-d'))
					->orWhere('publicaciones.fechFinNormal','>=',date('Y-m-d'));
				});
				if (Input::has('min') || Input::has('max')){
					if (Input::has('min') && Input::has('max')) {
						$min = Input::get('min');
						$max = Input::get('max');
						$minmax = array($min, $max);
						$currency = Input::get('currency');
						$filterPrice = '&min='.$min.'&max='.$max.'&currency='.$currency;
						$sql = $sql." AND (`publicaciones`.`precio` >= ".$min." AND `publicaciones`.`precio` <= ".$max.") AND `publicaciones`.`moneda` = '".$currency."'";
						$res = $auxRes->where('publicaciones.precio','>=',$min)->where('publicaciones.precio','<=',$max)->where('publicaciones.moneda','=',$curency)
						->paginate(5,array('publicaciones.id',
							'publicaciones.img_1',
							'publicaciones.titulo',
							'publicaciones.precio',
							'publicaciones.moneda',
							'publicaciones.descripcion',
							'departamento.nombre as dep'));
					}else{
						if(Input::has('max')){
							$max = Input::get('max');
							$minmax = array('', $max);
							$currency = Input::get('currency');
							$filterPrice = '&max='.$max.'&currency='.$currency;
							$sql = $sql." AND `publicaciones`.`precio` <= ".$max." AND `publicaciones`.`moneda` = '".$currency."'";
							$res = $auxRes->where('publicaciones.precio','<=',$max)->where('publicaciones.moneda','=',$currency)
							->paginate(5,array('publicaciones.id',
								'publicaciones.img_1',
								'publicaciones.titulo',
								'publicaciones.precio',
								'publicaciones.moneda',
								'publicaciones.descripcion',
								'departamento.nombre as dep'));
						}elseif(Input::has('min')){
							$min = Input::get('min');
							$minmax = array($min, '');
							$currency = Input::get('currency');
							$filterPrice = '&min='.$min.'&currency='.$currency;
							$sql = $sql." AND precio >= ".$min." AND `publicaciones`.`moneda` = '".$currency."'";
							$res = $auxRes->where('publicaciones.precio','>=',$min)->where('publicaciones.moneda','=',$curency)
							->paginate(5,array('publicaciones.id',
								'publicaciones.img_1',
								'publicaciones.titulo',
								'publicaciones.precio',
								'publicaciones.moneda',
								'publicaciones.descripcion',
								'departamento.nombre as dep'));
						}
					}
				}else
				{
					$res = $auxRes->paginate(5,array('publicaciones.id',
					'publicaciones.img_1',
					'publicaciones.titulo',
					'publicaciones.precio',
					'publicaciones.moneda',
					'publicaciones.descripcion',
					'departamento.nombre as dep'));
				}
				
			}
			$lider = DB::select($sql);

			$categorias = Categorias::where('id','=',$input['busq'])->pluck('desc');
			if (!is_null($categorias)) {
				$busq = $categorias;
			}else
			{
				$busq = $input['busq'];
			}
			$departamentos = Department::get();

			$view = View::make('publications.busq')
			->with('publicaciones',$res)
			->with('title',$title)
			->with('busq',$busq)
			->with('lider',$lider)
			->with('departamento',$departamentos)
			->with('filter',$inp);
			if (isset($filterPrice)) {
				return $view->with('filterPrice',$filterPrice)->with('minmax',$minmax)->with('currency',$currency);
			}else
			{
				return $view;
			}
		}elseif(Input::has('cat'))
		{
			$cat = Input::get('cat');
			if (Input::has('filter')) {
				$inp = Department::find(Input::get('filter'));
			}else
			{
				$inp = '';
			}
			if (!empty($inp)) {
				$auxLider = Publicaciones::where('status','=','Aprobado')
				->where('deleted','=',0)
				->where(function($query){
					$query->where('ubicacion','=','Categoria')
					->orWhere('ubicacion','=','Ambos');
				})
				->where(function($query){
					$query->where('fechFin','>=',date('Y-m-d'))
					->orWhere('fechFinNormal','>=',date('Y-m-d'));
					
				})
				->where('categoria','=',$input['cat'])
				->where('departamento','=',$inp->id);
				

				$auxRes = Publicaciones::where('publicaciones.status','=','Aprobado')
				->where('categoria','=',$input['cat'])
				->where('publicaciones.departamento','=',$inp->id)
				->leftJoin('departamento','publicaciones.departamento','=','departamento.id')
				->where('publicaciones.tipo','!=','Lider')
				->where('publicaciones.deleted','=',0)
				->where(function($query){
					$query->where('publicaciones.ubicacion','=','Categoria')
					->orWhere('publicaciones.ubicacion','=','Ambos');
				})
				->where(function($query){
					$query->where('publicaciones.fechFin','>=',date('Y-m-d',time()))
					->orWhere('publicaciones.fechFinNormal','>=',date('Y-m-d',time()));
				});
				if (Input::has('min') || Input::has('max'))
				{
					if (Input::has('min') && Input::has('max')) {
						$min = Input::get('min');
						$max = Input::get('max');
						$minmax = array($min, $max);
						$currency = Input::get('currency');
						$filterPrice = '&min='.$min.'&max='.$max.'&currency='.$currency;
						$lider =  $auxLider->where('moneda','=',$currency)->where('precio','>=',$min)->where('precio','<=',$max)->get(array('id','img_1','titulo','precio','moneda'));
						$res   =  $auxRes->where('publicaciones.moneda','=',$currency)->where('publicaciones.precio','>=',$min)->where('publicaciones.precio','<=',$max)
						->paginate(5,array(
							'publicaciones.id',
							'publicaciones.img_1',
							'publicaciones.titulo',
							'publicaciones.precio',
							'publicaciones.moneda',
							'publicaciones.descripcion',
							'departamento.id as dep_id',
							'departamento.nombre as dep'
						));
						
					}else{
						if(Input::has('max')){
							$max = Input::get('max');
							$minmax = array('', $max);
							$currency = Input::get('currency');
							$filterPrice = '&max='.$max.'&currency='.$currency;
							$lider =  $auxLider->where('moneda','=',$currency)->where('precio','<=',$max)->get(array('id','img_1','titulo','precio','moneda'));
							$res   =  $auxRes->where('publicaciones.moneda','=',$currency)->where('publicaciones.precio','<=',$max)
							->paginate(5,array(
								'publicaciones.id',
								'publicaciones.img_1',
								'publicaciones.titulo',
								'publicaciones.precio',
								'publicaciones.moneda',
								'publicaciones.descripcion',
								'departamento.id as dep_id',
								'departamento.nombre as dep'
							));
						}elseif(Input::has('min')){
							$min = Input::get('min');
							$minmax = array($min, '');
							$currency = Input::get('currency');
							$filterPrice = '&min='.$min.'&currency='.$currency;
							$lider =  $auxLider->where('moneda','=',$currency)->where('precio','>=',$min)->get(array('id','img_1','titulo','precio','moneda'));
							$res   =  $auxRes->where('publicaciones.moneda','=',$currency)->where('publicaciones.precio','>=',$min)
							->paginate(5,array(
								'publicaciones.id',
								'publicaciones.img_1',
								'publicaciones.titulo',
								'publicaciones.precio',
								'publicaciones.moneda',
								'publicaciones.descripcion',
								'departamento.id as dep_id',
								'departamento.nombre as dep'
							));
						}
					}
				}else
				{
					$lider = $auxLider->get(array('id','img_1','titulo','precio','moneda'));	
					$res   = $auxRes->paginate(5,array(
						'publicaciones.id',
						'publicaciones.img_1',
						'publicaciones.titulo',
						'publicaciones.precio',
						'publicaciones.moneda',
						'publicaciones.descripcion',
						'departamento.id as dep_id',
						'departamento.nombre as dep'));
				}

			}else
			{
				$filter = Input::get('filter');
				if ($filter == -1) {
					return Redirect::to('publicaciones/categorias/'.$cat);
				}
				/*Filtro por precio*/
				if (Input::has('min') || Input::has('max')) {
					$auxLider = Publicaciones::where('status','=','Aprobado')
					->where('deleted','=',0)
					->where(function($query){
						$query->where('ubicacion','=','Categoria')
						->orWhere('ubicacion','=','Ambos');
					})
					->where(function($query){
						$query->where('fechFin','>=',date('Y-m-d'))
						->orWhere('fechFinNormal','>=',date('Y-m-d'));
						
					})
					->where('categoria','=',$input['cat']);
					$auxRes = Publicaciones::where('publicaciones.status','=','Aprobado')
					->where('categoria','=',$input['cat'])
					->leftJoin('departamento','publicaciones.departamento','=','departamento.id')
					->where('publicaciones.tipo','=','Habitual')
					->where('publicaciones.deleted','=',0)
					->where(function($query){
						$query->where('publicaciones.ubicacion','=','Categoria')
						->orWhere('publicaciones.ubicacion','=','Ambos');
					})
					->where(function($query){
						$query->where('publicaciones.fechFin','>=',date('Y-m-d',time()))
						->orWhere('publicaciones.fechFinNormal','>=',date('Y-m-d',time()));
					});
					if (Input::has('min') && Input::has('max')) {
						$min = Input::get('min');
						$max = Input::get('max');
						$minmax = array($min, $max);
						$currency = Input::get('currency');
						$filterPrice = '&min='.$min.'&max='.$max.'&currency='.$currency;
						$lider =  $auxLider->where('moneda','=',$currency)->where('precio','>=',$min)->where('precio','<=',$max)->get(array('id','img_1','titulo','precio','moneda'));
						$res   =  $auxRes->where('publicaciones.moneda','=',$currency)->where('publicaciones.precio','>=',$min)->where('publicaciones.precio','<=',$max)
						->paginate(5,array(
							'publicaciones.id',
							'publicaciones.img_1',
							'publicaciones.titulo',
							'publicaciones.precio',
							'publicaciones.moneda',
							'publicaciones.descripcion',
							'departamento.id as dep_id',
							'departamento.nombre as dep'
						));
					}else{
						if(Input::has('max')){
							$max = Input::get('max');
							$minmax = array('', $max);
							$currency = Input::get('currency');
							$filterPrice = '&max='.$max.'&currency='.$currency;
							$lider =  $auxLider->where('moneda','=',$currency)->where('precio','<=',$max)->get(array('id','img_1','titulo','precio','moneda'));
							$res   =  $auxRes->where('publicaciones.moneda','=',$currency)->where('publicaciones.precio','<=',$max)
							->paginate(5,array(
								'publicaciones.id',
								'publicaciones.img_1',
								'publicaciones.titulo',
								'publicaciones.precio',
								'publicaciones.moneda',
								'publicaciones.descripcion',
								'departamento.id as dep_id',
								'departamento.nombre as dep'
							));
						}elseif(Input::has('min')){
							$min = Input::get('min');
							$minmax = array($min, '');
							$currency = Input::get('currency');
							$filterPrice = '&min='.$min.'&currency='.$currency;
							$lider =  $auxLider->where('moneda','=',$currency)->where('precio','>=',$min)->get(array('id','img_1','titulo','precio','moneda'));
							$res   =  $auxRes->where('publicaciones.moneda','=',$currency)->where('publicaciones.precio','>=',$min)
							->paginate(5,array(
								'publicaciones.id',
								'publicaciones.img_1',
								'publicaciones.titulo',
								'publicaciones.precio',
								'publicaciones.moneda',
								'publicaciones.descripcion',
								'departamento.id as dep_id',
								'departamento.nombre as dep'
							));
						}
					}
				}else
				{
					$lider = Publicaciones::where('status','=','Aprobado')
					->where('deleted','=',0)
					->where(function($query){
						$query->where('ubicacion','=','Categoria')
						->orWhere('ubicacion','=','Ambos');
					})
					->where(function($query){
						$query->where('fechFin','>=',date('Y-m-d'))
						->orWhere('fechFinNormal','>=',date('Y-m-d'));
						
					})
					->where('categoria','=',$input['cat'])
					->get(array('id','img_1','titulo','precio','moneda'));

					$res = Publicaciones::where('publicaciones.status','=','Aprobado')
					->where('categoria','=',$input['cat'])
					->leftJoin('departamento','publicaciones.departamento','=','departamento.id')
					->where('publicaciones.tipo','=','Habitual')
					->where('publicaciones.deleted','=',0)
					->where(function($query){
						$query->where('publicaciones.ubicacion','=','Categoria')
						->orWhere('publicaciones.ubicacion','=','Ambos');
					})
					->where(function($query){
						$query->where('publicaciones.fechFin','>=',date('Y-m-d',time()))
						->orWhere('publicaciones.fechFinNormal','>=',date('Y-m-d',time()));
					})		
					->paginate(5,array(
						'publicaciones.id',
						'publicaciones.img_1',
						'publicaciones.titulo',
						'publicaciones.precio',
						'publicaciones.moneda',
						'publicaciones.descripcion',
						'departamento.id as dep_id',
						'departamento.nombre as dep'));
				}
			}
			$categorias = Categorias::where('id','=',$input['cat'])->pluck('id');
			if (!is_null($categorias)) {
				$busq = $categorias;
			}else
			{
				$busq = $input['cat'];
			}
			$departamentos = Department::get();
			$view = View::make('publications.categories')
			->with('publicaciones',$res)
			->with('title',$title)
			->with('busq',$busq)
			->with('lider',$lider)
			->with('departamento',$departamentos)
			->with('filter',$inp);
			if (isset($filterPrice)) {
				return $view->with('filterPrice',$filterPrice)->with('minmax',$minmax)->with('currency',$currency);
			}else
			{
				return $view;
			}
		}
	}
	public function getVerifyComment()
	{
		if (Request::ajax()) {
			$comment = Comentarios::leftJoin('publicaciones','publicaciones.id','=','comentario.pub_id')
			->where('publicaciones.user_id','=',Auth::user()->id)
			->where('comentario.is_read','=',0)
			->where('publicaciones.deleted','=',0)
			->where('comentario.deleted','=',0)
			->count();
			$responses = Respuestas::where('user_id','=',Auth::user()->id)
			->where('is_read','=',0)
			->where('deleted','=',0)
			->count();
			return $comment+$responses;
		}
	}
}