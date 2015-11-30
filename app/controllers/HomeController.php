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
			->where('pag_web','!=',"")
			->where('departamento','=',$id)
			->where('fechFin','>=',date('Y-m-d',time()))
			->where('deleted','=',0)
			->orderBy('fechFin','desc')->get();	
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
					->orWhere('fechFinNormal','>=',date('Y-m-d',time()))
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
					->orWhere('fechFinNormal','>=',date('Y-m-d',time()))
					->where('status','=','Aprobado');

				});
			})
			->where('deleted','=',0)
			->orWhere(function($query){
				$query->where('tipo','=','Lider')
				->where('ubicacion','=','Principal')
				->where('pag_web','=',"")
				->where('status','=','Aprobado');

			})
			->orderBy('fechFin','desc')->get();
			$casual = Publicaciones::where('tipo','=','Casual')
			->where('fechFin','>=',date('Y-m-d',time()))
			->where('status','=','Aprobado')
			->where('deleted','=',0)
			->get();
		}

		$categories = Categorias::where('deleted','=',0)->where('tipo','=',1)->orderBy('nombre')->get();
		$servicios  = Categorias::where('deleted','=',0)->where('tipo','=',2)->get();
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
		
		
		$lider = DB::select("SELECT `publicaciones`.`id`,`publicaciones`.`img_1`,`publicaciones`.`titulo` 
			FROM  `publicaciones` 
			LEFT JOIN  `categoria` ON  `categoria`.`id` =  `publicaciones`.`categoria` 
			WHERE `publicaciones`.`status` =  'Aprobado' AND (
				LOWER(  `publicaciones`.`titulo` ) LIKE  '%".strtolower($input['busq'])."%'
				OR LOWER( `publicaciones`.`pag_web` ) LIKE  '%".strtolower($input['busq'])."%'
				OR LOWER( `publicaciones`.`descripcion` ) LIKE  '%".strtolower($input['busq'])."%'
				OR LOWER( `categoria`.`desc` ) LIKE  '%".strtolower($input['busq'])."%'
			) 
			AND  `publicaciones`.`deleted` = 0
			AND  (`publicaciones`.`tipo` =  'Lider'
            OR   `publicaciones`.`ubicacion` = 'Ambos')");

		if (Input::has('filter')) {
			$inp = Department::find(Input::get('filter'));
		}else
		{
			$inp = '';
		}
		if (!empty($inp)) {
			$res = Publicaciones::leftJoin('categoria','publicaciones.categoria','=','categoria.id')
			->leftJoin('departamento','publicaciones.departamento','=','departamento.id')
			->where(function($query) use ($input){
				$query->whereRaw("LOWER(`publicaciones`.`titulo`) LIKE  '%".strtolower($input['busq'])."%'")
				->orWhereRaw("LOWER(`departamento`.`nombre`) LIKE  '%".strtolower($input['busq'])."%'")
				->orWhereRaw("LOWER(`categoria`.`desc`) LIKE  '%".strtolower($input['busq'])."%'");
			})->where('publicaciones.tipo','!=','Lider')
			->where('publicaciones.status','=','Aprobado')
			->where('publicaciones.deleted','=',0)
			->where('publicaciones.departamento','=',Input::get('filter'))
			->paginate(5,array('publicaciones.id',
				'publicaciones.img_1',
				'publicaciones.titulo',
				'publicaciones.precio',
				'publicaciones.moneda',
				'departamento.nombre as dep'));
		}else
		{
			$res = Publicaciones::leftJoin('categoria','publicaciones.categoria','=','categoria.id')
			->leftJoin('departamento','publicaciones.departamento','=','departamento.id')
			->where(function($query) use ($input){
				$query->whereRaw("LOWER(`publicaciones`.`titulo`) LIKE  '%".strtolower($input['busq'])."%'")
				->orWhereRaw("LOWER(`departamento`.`nombre`) LIKE  '%".strtolower($input['busq'])."%'")
				->orWhereRaw("LOWER(`categoria`.`desc`) LIKE  '%".strtolower($input['busq'])."%'");
			})->where('publicaciones.tipo','!=','Lider')
			->where('publicaciones.status','=','Aprobado')
			->where('publicaciones.deleted','=',0)
			->paginate(5,array('publicaciones.id',
				'publicaciones.img_1',
				'publicaciones.titulo',
				'publicaciones.precio',
				'publicaciones.moneda',
				'departamento.nombre as dep'));
		}
		$departamentos = Department::get();
                $categorias = Categorias::where('id','=',$input['busq'])->pluck('desc');
		if (!is_null($categorias)) {
			$busq = $categorias;
		}else
		{
			$busq = $input['busq'];
		}
		return View::make('publications.busq')
		->with('publicaciones',$res)
		->with('title',$title)
		->with('busq',$busq)
		->with('lider',$lider)
		->with('departamento',$departamentos)
		->with('filter',$inp);
	}
	public function getVerifyComment()
	{
		if (Request::ajax()) {
			$comment = Comentarios::join('publicaciones','publicaciones.id','=','comentario.pub_id')
			->where('publicaciones.user_id','=',Auth::id())
			->where('comentario.respondido','=',0)
			->count();
			return $comment;
		}
	}
}