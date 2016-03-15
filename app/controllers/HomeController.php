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
		$title = "Búsqueda | pasillo24.com";

		if (Input::has('busq')) {
			$busq = Input::get('busq');
			$auxLider = Publicaciones::leftJoin('categoria','categoria.id','=','publicaciones.categoria')
			->where('publicaciones.status','=','Aprobado')
			->where('publicaciones.deleted','=',0)
			->where(function($query){
				$query->where('publicaciones.ubicacion','=','Categoria')
				->orWhere('publicaciones.ubicacion','=','Ambos');
			})
			->where(function($query){
				$query->where('publicaciones.fechFin','>=',date('Y-m-d'))
				->orWhere('publicaciones.fechFinNormal','>=',date('Y-m-d'));
			})
			->where(function($query) use($busq)
			{
				$query->whereRaw("LOWER(`publicaciones`.`titulo`) LIKE  '%".strtolower($busq)."%'")
				->orWhereRaw("LOWER(`publicaciones`.`pag_web`) LIKE  '%".strtolower($busq)."%'")
				->orWhereRaw("LOWER(`categoria`.`desc`) LIKE  '%".strtolower($busq)."%'");
			});

			$auxRes = Publicaciones::leftJoin('categoria','publicaciones.categoria','=','categoria.id')
			->leftJoin('departamento','publicaciones.departamento','=','departamento.id')
			->where(function($query) use ($busq){
				$query->whereRaw("LOWER(`publicaciones`.`titulo`) LIKE  '%".strtolower($busq)."%'")
				->orWhereRaw("LOWER(`departamento`.`nombre`) LIKE  '%".strtolower($busq)."%'")
				->orWhereRaw("LOWER(`categoria`.`desc`) LIKE  '%".strtolower($busq)."%'");
			})
			->where(function($query)
			{
				$query->where('publicaciones.fechFin','>=',date('Y-m-d'))
				->orWhere('publicaciones.fechFinNormal','>=',date('Y-m-d'));
			})
			->where('publicaciones.tipo','!=','Lider')
			->where('publicaciones.status','=','Aprobado')
			->where('publicaciones.deleted','=',0);
			/*Se agrega*/
			if (Input::has('filter')) {
				$filter = Input::get('filter');
				if ($filter != -1) {
					$filter = Department::find(Input::get('filter'));
					$auxRes = $auxRes->where('publicaciones.departamento','=',$filter->id);
				}else
				{
					$filter = "";
				}
			}
			if (Input::has('min') || Input::has('max'))
			{
				$currency = Input::get('currency');
				if (Input::has('min') && Input::has('max')) {
					$min = Input::get('min');
					$max = Input::get('max');
					$minmax = array($min, $max);
					$filterPrice = '&min='.$min.'&max='.$max.'&currency='.$currency;
					$auxLider =  $auxLider->where('moneda','=',$currency)->where('precio','>=',$min)->where('precio','<=',$max);
					$auxRes   =  $auxRes->where('publicaciones.moneda','=',$currency)->where('publicaciones.precio','>=',$min)->where('publicaciones.precio','<=',$max);
				}else{
					if(Input::has('max')){
						$max = Input::get('max');
						$minmax = array('', $max);
						$filterPrice = '&max='.$max.'&currency='.$currency;
						$auxLider =  $auxLider->where('moneda','=',$currency)->where('precio','<=',$max);
						return $auxRes->get(array('publicaciones.id','publicaciones.precio'));
						$auxRes   =  $auxRes->where('publicaciones.moneda','=',$currency)->where('publicaciones.precio','<=',$max);
					}elseif(Input::has('min')){
						$min = Input::get('min');
						$minmax = array($min, '');
						$filterPrice = '&min='.$min.'&currency='.$currency;
						$auxLider =  $auxLider->where('moneda','=',$currency)->where('precio','>=',$min);
						$auxRes   =  $auxRes->where('publicaciones.moneda','=',$currency)->where('publicaciones.precio','>=',$min);
					}
				}
			}
			
			$lider = $auxLider->get(array('publicaciones.id','publicaciones.img_1','publicaciones.titulo','publicaciones.precio','publicaciones.moneda'));
			$res = $auxRes->paginate(5,array(
				'publicaciones.id',
				'publicaciones.img_1',
				'publicaciones.titulo',
				'publicaciones.precio',
				'publicaciones.moneda',
				'publicaciones.descripcion',
				'departamento.id as dep_id',
				'departamento.nombre as dep'));
			$categorias = Categorias::where('id','=',$busq)->pluck('desc');
			if (!is_null($categorias)) {
				$busq = $categorias;
			}else
			{
				$busq = $busq;
			}
			$departamentos = Department::get();

			$view = View::make('publications.busq')
			->with('publicaciones',$res)
			->with('title',$title)
			->with('busq',$busq)
			->with('lider',$lider)
			->with('departamento',$departamentos);
			if (isset($filter)) {
				$view = $view->with('filter',$filter);
			}
			if (isset($filterPrice)) {
				$view = $view->with('filterPrice',$filterPrice)->with('minmax',$minmax)->with('currency',$currency);
			}
			return $view;

		}elseif(Input::has('cat'))
		{
			$id = Input::geT('cat');
			/*Query inicial*/
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
			->where('categoria','=',$id);
			//->get(array('id','img_1','titulo','precio','moneda'));
			$auxRes = Publicaciones::leftJoin('departamento','publicaciones.departamento','=','departamento.id')
			->where('publicaciones.status','=','Aprobado')
			->where('publicaciones.categoria','=',$id)
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
			/*Se agrega*/

			if (Input::has('filter')) {
				$filter = Input::get('filter');
				if ($filter != -1) {
					$filter = Department::find(Input::get('filter'));
					$auxRes = $auxRes->where('publicaciones.departamento','=',$filter->id);
				}else
				{
					$filter = "";
				}
			}
			if (Input::has('min') || Input::has('max'))
			{
				$currency = Input::get('currency');
				if (Input::has('min') && Input::has('max')) {
					$min = Input::get('min');
					$max = Input::get('max');
					$minmax = array($min, $max);
					$filterPrice = '&min='.$min.'&max='.$max.'&currency='.$currency;
					$auxLider =  $auxLider->where('moneda','=',$currency)->where('precio','>=',$min)->where('precio','<=',$max);
					$auxRes   =  $auxRes->where('publicaciones.moneda','=',$currency)->where('publicaciones.precio','>=',$min)->where('publicaciones.precio','<=',$max);
				}else{
					if(Input::has('max')){
						$max = Input::get('max');
						$minmax = array('', $max);
						$filterPrice = '&max='.$max.'&currency='.$currency;
						$auxLider =  $auxLider->where('moneda','=',$currency)->where('precio','<=',$max);
						$auxRes   =  $auxRes->where('publicaciones.moneda','=',$currency)->where('publicaciones.precio','<=',$max);

					}elseif(Input::has('min')){
						$min = Input::get('min');
						$minmax = array($min, '');
						$filterPrice = '&min='.$min.'&currency='.$currency;
						$auxLider =  $auxLider->where('moneda','=',$currency)->where('precio','>=',$min);
						$auxRes   =  $auxRes->where('publicaciones.moneda','=',$currency)->where('publicaciones.precio','>=',$min);
					}
				}
			}
			$lider = $auxLider->get(array('id','img_1','titulo','precio','moneda'));
			$res = $auxRes->paginate(5,array(
				'publicaciones.id',
				'publicaciones.img_1',
				'publicaciones.titulo',
				'publicaciones.precio',
				'publicaciones.moneda',
				'publicaciones.descripcion',
				'departamento.id as dep_id',
				'departamento.nombre as dep'));

			$categorias = Categorias::where('id','=',$id)->pluck('id');
			if (!is_null($categorias)) {
				$busq = $categorias;
			}else
			{
				$busq = $id;
			}
			$departamentos = Department::get();
			$view = View::make('publications.categories')
			->with('publicaciones',$res)
			->with('title',$title)
			->with('busq',$busq)
			->with('lider',$lider)
			->with('departamento',$departamentos);
			if(isset($filter))
			{
				$view = $view->with('filter',$filter);
			}
			if (isset($filterPrice)) {
				$view = $view->with('filterPrice',$filterPrice)->with('minmax',$minmax)->with('currency',$currency);
			}
				return $view;
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