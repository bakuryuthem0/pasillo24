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
				'publicaciones.descripcion',

				)
			);
			$habitual = Publicaciones::with('deparments')->where(function($query) use($id){
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
			$casual = Publicaciones::with('deparments')->where('tipo','=','Casual')
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
			$habitual = Publicaciones::with('deparments')->where(function($query){
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

			$casual = Publicaciones::with('deparments')->where('tipo','=','Casual')
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
		$paginatorFilter = "";
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
					$paginatorFilter .= '&filter='.$filter->id;
				}else
				{
					$filter = "";
				}
			}
			if (Input::has('min') || Input::has('max'))
			{
				$min = Input::get('min');
				$max = Input::get('max');

				$currency = Input::get('currency');
				if (!is_null($min) && !is_null($max) && !empty($min) && !empty($max)) {
					$minmax = array($min, $max);
					$paginatorFilter .= '&min='.$min.'&max='.$max.'&currency='.$currency;
					$auxLider =  $auxLider->where('moneda','=',$currency)->where('precio','>=',$min)->whereRaw('`publicaciones`.`precio` <= '.$max);
					$auxRes   =  $auxRes->where('publicaciones.precio','>=',$min)->whereRaw('`publicaciones`.`precio` <= '.$max)->where('publicaciones.moneda','=',$currency);
				}else{
					if(!is_null($max) && !empty($max)){
						$minmax = array('', $max);
						$paginatorFilter .= '&max='.$max.'&currency='.$currency;
						$auxLider =  $auxLider->where('moneda','=',$currency)->whereRaw('`publicaciones`.`precio` <= '.$max);
						$auxRes   =  $auxRes->whereRaw('`publicaciones`.`precio` <= '.$max)->where('publicaciones.moneda','=',$currency);
					}elseif(!is_null($min) && !empty($min)){
						$minmax = array($min, '');
						$paginatorFilter .= '&min='.$min.'&currency='.$currency;
						$auxLider =  $auxLider->where('moneda','=',$currency)->where('precio','>=',$min);
						$auxRes   =  $auxRes->where('publicaciones.moneda','=',$currency)->where('publicaciones.precio','>=',$min);
					}
				}
			}
			if (Input::has('rel')) {
				$rel = Input::get('rel');
				switch ($rel) {
					case 'rep':
						$auxLider =  $auxLider->leftJoin('usuario','usuario.id','=','publicaciones.user_id')->orderBy('usuario.reputation','DESC');
						$auxRes   =  $auxRes->leftJoin('usuario','usuario.id','=','publicaciones.user_id')->orderBy('usuario.reputation','DESC');
						$paginatorFilter .= '&rel=rep';
						break;
					case 'fin':
						$auxLider =  $auxLider->orderBy('publicaciones.fechFin','ASC')->orderBy('publicaciones.fechFinNormal','ASC');
						$auxRes   =  $auxRes->orderBy('publicaciones.fechFin','ASC')->orderBy('publicaciones.fechFinNormal','ASC');
						$paginatorFilter .= '&rel=fin';
						break;
					case 'ini':
						$auxLider =  $auxLider->orderBy('publicaciones.fechIni','DESC')->orderBy('publicaciones.fechIniNormal','DESC');
						$auxRes   =  $auxRes->orderBy('publicaciones.fechIni','DESC')->orderBy('publicaciones.fechIniNormal','DESC');
						$paginatorFilter .= '&rel=ini';
						break;
					default:
						break;
				}
			}
			if (Input::has('cond')) {
				$cond 			  = Input::get('cond');
				$paginatorFilter .= '&cond='.$cond;
				$auxRes   		  =  $auxRes->where('publicaciones.condicion','=',strtolower($cond));
			}
			if (Input::has('buss')) {
				$buss 			  = Input::get('buss');
				$paginatorFilter .= '&buss='.$buss;
				$auxLider =  $auxLider->where('publicaciones.bussiness_type','=',strtolower($buss));
				$auxRes   =  $auxRes->where('publicaciones.bussiness_type','=',strtolower($buss));
			}
			$lider = $auxLider->get(array('publicaciones.id','publicaciones.img_1','publicaciones.titulo','publicaciones.precio','publicaciones.moneda'));
			$res = $auxRes->paginate(5,array(
				'publicaciones.id',
				'publicaciones.img_1',
				'publicaciones.titulo',
				'publicaciones.precio',
				'publicaciones.moneda',
				'publicaciones.descripcion',
				'publicaciones.fechFin',
				'publicaciones.fechFinNormal',
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
			->with('departamento',$departamentos)
			->with('paginatorFilter',$paginatorFilter);
			if (isset($filter)) {
				$view = $view->with('filter',$filter);
			}
			if (isset($currency)) {
				$view = $view->with('minmax',$minmax)->with('currency',$currency);
			}
			if (isset($cond)) {
				$view = $view->with('cond',$cond);
			}
			if (isset($buss)) {
				$view = $view->with('buss',$buss);
			}
			if (isset($rel)) {
				$view = $view->with('rel',$rel);
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
					$paginatorFilter .= '&filter='.$filter->id;
				}else
				{
					$filter = "";
				}
			}
			if (Input::has('min') || Input::has('max'))
			{
				$min = Input::get('min');
				$max = Input::get('max');

				$currency = Input::get('currency');
				if (!is_null($min) && !is_null($max) && !empty($min) && !empty($max)) {
					$minmax = array($min, $max);
					$paginatorFilter .= '&min='.$min.'&max='.$max.'&currency='.$currency;
					$auxLider =  $auxLider->whereRaw('`publicaciones`.`precio` >= '.$min)->whereRaw('`publicaciones`.`precio` <= '.$max)->where('moneda','=',$currency);
					$auxRes   =  $auxRes->whereRaw('`publicaciones`.`precio` >= '.$min)->whereRaw('`publicaciones`.`precio` <= '.$max)->where('publicaciones.moneda','=',$currency);
				}else{
					if(!is_null($max) && !empty($max)){
						$minmax = array('', $max);
						$paginatorFilter .= '&max='.$max.'&currency='.$currency;
						$auxLider =  $auxLider->where('moneda','=',$currency)->whereRaw('`publicaciones`.`precio` <= '.$max);
						$auxRes   =  $auxRes->whereRaw('`publicaciones`.`precio` <= '.$max)->where('publicaciones.moneda','=',$currency);
					}elseif(!is_null($min) && !empty($min)){
						$minmax = array($min, '');
						$paginatorFilter .= '&min='.$min.'&currency='.$currency;
						$auxLider =  $auxLider->whereRaw('`publicaciones`.`precio` >= '.$min)->where('moneda','=',$currency);
						$auxRes   =  $auxRes->where('publicaciones.moneda','=',$currency)->whereRaw('`publicaciones`.`precio` >= '.$min);
					}
				}
			}
			if (Input::has('rel')) {
				$rel = Input::get('rel');
				switch ($rel) {
					case 'rep':
						$auxLider =  $auxLider->leftJoin('usuario','usuario.id','=','publicaciones.user_id')->orderBy('usuario.reputation','DESC');
						$auxRes   =  $auxRes->leftJoin('usuario','usuario.id','=','publicaciones.user_id')->orderBy('usuario.reputation','DESC');
						$paginatorFilter .= '&rel=rep';
						break;
					case 'fin':
						$auxLider =  $auxLider->orderBy('publicaciones.fechFin','ASC')->orderBy('publicaciones.fechFinNormal','ASC');
						$auxRes   =  $auxRes->orderBy('publicaciones.fechFin','ASC')->orderBy('publicaciones.fechFinNormal','ASC');
						$paginatorFilter .= '&rel=fin';
						break;
					case 'ini':
						$auxLider =  $auxLider->orderBy('publicaciones.fechIni','DESC')->orderBy('publicaciones.fechIniNormal','DESC');
						$auxRes   =  $auxRes->orderBy('publicaciones.fechIni','DESC')->orderBy('publicaciones.fechIniNormal','DESC');
						$paginatorFilter .= '&rel=ini';
						break;
					default:
						break;
				}
			}
			if (Input::has('cond')) {
				$cond 			  = Input::get('cond');
				$paginatorFilter .= '&cond='.$cond;
				$auxRes   		  =  $auxRes->where('publicaciones.condicion','=',strtolower($cond));
			}
			if (Input::has('buss')) {
				$buss 			  = Input::get('buss');
				$paginatorFilter .= '&buss='.$buss;
				$auxLider =  $auxLider->where('publicaciones.bussiness_type','=',strtolower($buss));
				$auxRes   =  $auxRes->where('publicaciones.bussiness_type','=',strtolower($buss));
			}
			$lider = $auxLider->get(array('publicaciones.id','publicaciones.img_1','publicaciones.titulo','publicaciones.precio','publicaciones.moneda'));
			$res = $auxRes->paginate(5,array(
				'publicaciones.id',
				'publicaciones.img_1',
				'publicaciones.titulo',
				'publicaciones.precio',
				'publicaciones.moneda',
				'publicaciones.descripcion',
				'publicaciones.fechFin',
				'publicaciones.fechFinNormal',
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
			->with('departamento',$departamentos)
			->with('paginatorFilter',$paginatorFilter);
			if (isset($filter)) {
				$view = $view->with('filter',$filter);
			}
			if (isset($currency)) {
				$view = $view->with('minmax',$minmax)->with('currency',$currency);
			}
			if (isset($cond)) {
				$view = $view->with('cond',$cond);
			}
			if (isset($buss)) {
				$view = $view->with('buss',$buss);
			}
			if (isset($rel)) {
				$view = $view->with('rel',$rel);
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