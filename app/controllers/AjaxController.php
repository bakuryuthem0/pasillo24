<?php

class AjaxController extends BaseController{
	public function getLoginApp()
	{
		/*Se reciven los datos del usuario*/

	    $username = Input::get("username"); 
	    $password = Input::get("password");
	    /*Se verifica si existe el usuario*/
		$user = User::where('username','=',$username)->first();
		if (count($user) > 0) {
			/*Se verifica si concuerdan las contraseñas*/
			if (Hash::check($password, $user->password)) 
			{
				/*Se toma el id del movil y se busca en la base de datos*/
				/*Si no se encuentra (primera vez que inicia en este movil) se crea un nuevo registro*/
				/*Se devuelven los datos en forma de json*/
				$username = $user->username;
				$user_id  = $user->id;
				if (is_null($user->auth_token)) {
					$user->auth_token = md5($user->id);
					$user->save();
				}
				$n = array(
					'type'	   => 'success',
					'msg'	   => 'Ha iniciado sesión satisfactoriamente',
					'userdata' => $user,
					'auth_token' => $user->auth_token);
				return Response::json($n);
			}else
			{
				return Response::json(array('type' =>'danger', 'msg' => 'Usuario o Contraseña invalida'));
			}
		}else
		{
			return Response::json(array('type' =>'danger', 'msg' => 'Usuario o Contraseña invalida'));
		}
	}
    public function postRegisterApp()
	{			
		/*Se reciven los datos y se validan*/
		/*required  = Campo obligatorio*/
		/*min 		= Minimo x caracteres*/
		/*unique    = Campo unico en la bd*/
		/*in 		= El valor debe ser uno de los preestablecidos*/
		/*email 	= Campo debe ser un email*/
		$input = Input::all();
		$rules = array(
			'username'					=> 'required|min:4|unique:usuario,username',
			'password'      			=> 'required|min:6|confirmed',
			'password_confirmation'  	=> 'required|min:6',
			'nombre'       				=> 'required|min:4',
			'apellido'					=> 'required|min:4',
			'email'						=> 'required|email|unique:usuario,email',
			'carnet'					=> 'required|min:5|unique:usuario,id_carnet',
			'sexo'						=> 'required|in:f,m',
			'department'	 			=> 'required',

		);
		$messages = array(
			'required' => ':attribute es obligatoria',
			'min'      => ':attribute debe ser mas largo',
			'email'    => 'Debe introducir un email válido',
			'unique'   => ':attribute ya existe',
			'confirmed'=> 'La contraseña no concuerdan',
			'in'	   => 'Sexo incorrecto.',
		);
		$custom = array(
			'username' 				=> 'EL nombre de usuario',
			'password'    	 		=> 'La contraseña',
			'nombre'            	=> 'El nombre',
			'lastname'          	=> 'El apellido',
			'email' 				=> 'El email',
			'carnet'				=> 'El carnet de identificacion',
			'sexo'					=> 'El sexo',
			'department'  			=> 'El departamento',
		);
		$validator = Validator::make($input, $rules, $messages,$custom);
		if ($validator->fails()) {
			/*Si la validacion falla, se devuelve un error y los datos en forma de json*/
			/*ej de la respuesta:
				{
					'danger' : 'Error al validar los datos'
					'data'	 : {
						'usuario' : 'El campo nombre de usuario es obligatorio',
						'email'	  : 'Debe introducir un email válido',
					}
				}
			*/
			return Response::json(array(
               'danger' => 'Error al validar los datos',
               'data' => $validator->getMessageBag()->toArray()
              	)
			);
		}
		/*Si todo es valido se crea un nuevo registro*/
		$user = new User;
		$user->username 	 = $input['username'];
		/*La contraseña se encrypta*/
		$user->password    	 = Hash::make($input['password']);
		$user->email    	 = $input['email'];
		$user->name     	 = $input['nombre'];
		$user->lastname 	 = $input['apellido'];
		$user->id_carnet	 = $input['carnet'];

		if (!empty($input['nombrempresa'])) {
			$user->nombEmp	     = $input['nombrempresa'];
		}
		if (!empty($input['nit'])) {
			$user->nit  		 = $input['nit'];
		}

		$user->state  		 = $input['department'];
		/*Se asigna role usuario*/
		$user->role          = 'Usuario';
		
		if ($user->save()) {
			$user->auth_token = md5($user->id);
			$user->save();
			/*Si se guarda se devuelve la respuesta*/
			$n = array(
				'type' => 'success', 
				'msg'  => 'Registro completo',
				'userdata' => $user,
				'auth_token' => $user->auth_token);
			return Response::json($n);
		}else
		{
			/*Si no se devuelve un error*/
			return Response::json(array(
				'type' => 'danger',
				'msg' => 'Error al registrar al usuario.'));
		}
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
			->orderBy('fechFin','desc')->get(array(
				'publicaciones.img_1',
				'publicaciones.titulo',
				'publicaciones.precio',
				'publicaciones.moneda',
				'publicaciones.id',
			));
			
			$casual = Publicaciones::where('tipo','=','Casual')
			->where('fechFin','>=',date('Y-m-d',time()))
			->where('status','=','Aprobado')
			->where('departamento','=',$id)
			->where('deleted','=',0)
			->get(array(
				'publicaciones.img_1',
				'publicaciones.titulo',
				'publicaciones.precio',
				'publicaciones.moneda',
				'publicaciones.id',
			));
		}else
		{
			$lider = Publicaciones::where('status','=','Aprobado')
			->where('ubicacion','=','Principal')
			->where('tipo','=','Lider')
			->where('pag_web','!=',"")
			->where('fechFin','>=',date('Y-m-d',time()))
			->where('deleted','=',0)
			->orderBy('fechFin','desc')->get(array(
				'publicaciones.img_1',
				'publicaciones.titulo',
				'publicaciones.precio',
				'publicaciones.moneda',
				'publicaciones.id',
			));
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
			->where('deleted','=',0)
			->orWhere(function($query){
				$query->where('tipo','=','Lider')
				->where('ubicacion','=','Principal')
				->where('pag_web','=',"")
				->where('status','=','Aprobado');

			})
			->orderBy('fechFin','desc')->get(array(
				'publicaciones.img_1',
				'publicaciones.titulo',
				'publicaciones.precio',
				'publicaciones.moneda',
				'publicaciones.id',
			));
			$casual = Publicaciones::where('tipo','=','Casual')
			->where('fechFin','>=',date('Y-m-d',time()))
			->where('status','=','Aprobado')
			->where('deleted','=',0)
			->get(array(
				'publicaciones.img_1',
				'publicaciones.titulo',
				'publicaciones.precio',
				'publicaciones.moneda',
				'publicaciones.id',
			));
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
		$departamentos  = Department::get();
        $publi 			= Publicidad::get();
		if (!is_null($id)) {
        	return Response::json(array(
        		'pubLider' 		=> $lider,
        		'pubHabitual' 	=> $habitual,
        		'pubCasual' 	=> $casual,	
        		'categorias' 	=> $categories,
        		'servicios'     => $servicios,
        		'departamentos' => $departamentos,
        		'publi' 		=> $publi,
        		'depFilter' 	=> $dep->id
          	));
        }else
        {
        	return Response::json(array(
        		'pubLider' 		=> $lider,
        		'pubHabitual' 	=> $habitual,
        		'pubCasual' 	=> $casual,	
        		'categorias' 	=> $categories,
        		'servicios'     => $servicios,
        		'departamentos' => $departamentos,
        		'publi' 		=> $publi,
          	));
        }
	}
	public function upload_image($carpeta)
	{
		$ruta 	 = "images/pubImages/".$carpeta."/";
		$file 	 = Input::file('file');
		if (file_exists($ruta.$file->getClientOriginalName())) {
			//guardamos la imagen en public/imgs con el nombre original
	        $i = 0;//indice para el while
	        //separamos el nombre de la img y la extensión
	        $info = explode(".",$file->getClientOriginalName());
	        //asignamos de nuevo el nombre de la imagen completo
	        $miImg = $file->getClientOriginalName();
	        //mientras el archivo exista iteramos y aumentamos i
	        while(file_exists($ruta.$miImg)){
	            $i++;
	            $miImg = $info[0]."(".$i.")".".".$info[1];              
	        }
	        //guardamos la imagen con otro nombre ej foto(1).jpg || foto(2).jpg etc
	        $file->move($ruta,$miImg);
	        $img = Image::make($ruta.$miImg);
	        $blank = Image::make('images/blank.jpg');
	         if ($img->width() > $img->height()) {
	        	$img->widen(1604);
	        }else
	        {
	        	$img->heighten(804);
	        }
	        if ($img->height() > 804) {
	        	$img->heighten(804);
	        }
	        $mark = Image::make('images/watermark.png')->widen(400);
	        $blank->insert($img,'center');
	        $blank->insert($mark,'center')
	       	->interlace()
	        ->save($ruta.$miImg);
		}else
		{
			$file->move($ruta,$file->getClientOriginalName());
			$img = Image::make($ruta.$file->getClientOriginalName());
	                    $blank = Image::make('images/blank.jpg');
	                    if ($img->width() > $img->height()) {
	        	      $img->widen(1604);
	                }else
	                {
	        	      $img->heighten(804);
	                }
	        if ($img->height() > 804) {
	        	$img->heighten(804);
	        }
	        $mark = Image::make('images/watermark.png')->widen(400);
	        $blank->insert($img,'center');
	        $blank->insert($mark,'center')
	       	->interlace()
	        ->save($ruta.$file->getClientOriginalName());
		}
		return Response::json(array('type' => 'success' , 'msg' => 'Se ha subido la imagen.'));
	}
	public function search()
	{
			$input = Input::all();
			if (Input::has('busq')) {
				if (Input::has('filter')) {
					$inp = Department::find(Input::get('filter'));
				}else
				{
					$inp = '';
				}
				if (!empty($inp)) {
					$lider = DB::select("SELECT `publicaciones`.`id`,`publicaciones`.`img_1`,`publicaciones`.`titulo` ,`publicaciones`.`precio`, `publicaciones`.`moneda` 
						FROM  `publicaciones` 
						LEFT JOIN  `categoria` ON  `categoria`.`id` =  `publicaciones`.`categoria` 
						WHERE `publicaciones`.`fechFin` >= '".date('Y-m-d',time())."' 
						AND `publicaciones`.`status` =  'Aprobado' AND (
							LOWER(  `publicaciones`.`titulo` ) LIKE  '%".strtolower($input['busq'])."%'
							OR LOWER( `publicaciones`.`pag_web` ) LIKE  '%".strtolower($input['busq'])."%'
							OR LOWER( `publicaciones`.`descripcion` ) LIKE  '%".strtolower($input['busq'])."%'
							OR LOWER( `categoria`.`desc` ) LIKE  '%".strtolower($input['busq'])."%'
						) 
						AND `publicaciones`.`departamento` = ".$inp->id."
						AND  `publicaciones`.`deleted` = 0
						AND  (`publicaciones`.`ubicacion` = 'Categoria' OR `publicaciones`.`ubicacion` = 'Ambos')");

					$res = Publicaciones::leftJoin('categoria','publicaciones.categoria','=','categoria.id')
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
					})
					->where('publicaciones.departamento','=',Input::get('filter'))
					->paginate(5,array('publicaciones.id',
						'publicaciones.img_1',
						'publicaciones.titulo',
						'publicaciones.precio',
						'publicaciones.moneda',
						'departamento.nombre as dep'));
				}else
				{
					$lider = DB::select("SELECT `publicaciones`.`id`,`publicaciones`.`img_1`,`publicaciones`.`titulo` ,`publicaciones`.`precio`, `publicaciones`.`moneda` 
						FROM  `publicaciones` 
						LEFT JOIN  `categoria` ON  `categoria`.`id` =  `publicaciones`.`categoria` 
						WHERE `publicaciones`.`fechFin` >= '".date('Y-m-d',time())."' 
						AND `publicaciones`.`status` =  'Aprobado' AND (
							LOWER(  `publicaciones`.`titulo` ) LIKE  '%".strtolower($input['busq'])."%'
							OR LOWER( `publicaciones`.`pag_web` ) LIKE  '%".strtolower($input['busq'])."%'
							OR LOWER( `publicaciones`.`descripcion` ) LIKE  '%".strtolower($input['busq'])."%'
							OR LOWER( `categoria`.`desc` ) LIKE  '%".strtolower($input['busq'])."%'
						) 
						AND  `publicaciones`.`deleted` = 0
						AND  (`publicaciones`.`ubicacion` = 'Categoria' OR `publicaciones`.`ubicacion` = 'Ambos')");
					$res = Publicaciones::leftJoin('categoria','publicaciones.categoria','=','categoria.id')
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
					})
					->paginate(5,array('publicaciones.id',
						'publicaciones.img_1',
						'publicaciones.titulo',
						'publicaciones.precio',
						'publicaciones.moneda',
						'departamento.nombre as dep'));
				}

				$categorias = Categorias::where('id','=',$input['busq'])->pluck('desc');
				if (!is_null($categorias)) {
					$busq = $categorias;
				}else
				{
					$busq = $input['busq'];
				}
				$departamentos = Department::get();
				return Response::json(array(
					'publicaciones' => $res,
					'busq' 			=> $busq,
					'lider' 		=> $lider,
					'departamento'  => $departamentos,
					'filter' 	    => $inp,
				));
			}elseif(Input::has('cat'))
			{
				if (Input::has('filter')) {
					$inp = Department::find(Input::get('filter'));
				}else
				{
					$inp = '';
				}
				if (!empty($inp)) {
					$lider = Publicaciones::where('status','=','Aprobado')
					->where('deleted','=',0)
					->where('departamento','=',$inp->id)
					->where(function($query){
						$query->where('ubicacion','=','Categoria')
						->orWhere('ubicacion','=','Ambos');
					})
					->where('fechFin','>=',date('Y-m-d'))
					->where('categoria','=',$input['cat'])
					->get(array('id','img_1','titulo','precio','moneda'));

					$res = Publicaciones::where('publicaciones.status','=','Aprobado')
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
					})		
					->paginate(5,array(
						'publicaciones.id',
						'publicaciones.img_1',
						'publicaciones.titulo',
						'publicaciones.precio',
						'publicaciones.moneda',
						'departamento.id as dep_id',
						'departamento.nombre as dep'));
				}else
				{
					$lider = Publicaciones::where('status','=','Aprobado')
					->where('deleted','=',0)
					->where(function($query){
						$query->where('ubicacion','=','Categoria')
						->orWhere('ubicacion','=','Ambos');
					})
					->where('fechFin','>=',date('Y-m-d'))
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
						'departamento.id as dep_id',
						'departamento.nombre as dep'));
				}
				$categorias = Categorias::where('id','=',$input['cat'])->pluck('id');
				if (!is_null($categorias)) {
					$busq = $categorias;
				}else
				{
					$busq = $input['cat'];
				}
				$departamentos = Department::get();
				return Response::json(array(
					'publicaciones' => $res,
					'busq' 			=> $busq,
					'lider' 		=> $lider,
					'departamento'  => $departamentos,
					'filter' 		=> $inp,
				));
			
			}
	}
	public function getCategories($id)
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
		->where('categoria','=',$id)
		->get(array('id','img_1','titulo','precio','moneda'));
		$publicaciones = Publicaciones::where('publicaciones.status','=','Aprobado')
		->where('categoria','=',$id)
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
		})		
		->paginate(5,array(
			'publicaciones.id',
			'publicaciones.img_1',
			'publicaciones.titulo',
			'publicaciones.precio',
			'publicaciones.moneda',
			'departamento.id as dep_id',
			'departamento.nombre as dep'));
		$departamentos = Department::get();
		return Response::json(array(
			'publicaciones' => $publicaciones,
			'lider' 		=> $lider,
			'departamento'  => $departamentos,
			'busq'          => $id,
		));
		
	}
	public function getPublicationCategory($id)
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
		->where('categoria','=',$id)
		->get(array('id','img_1','titulo','precio','moneda'));
		$publicaciones = Publicaciones::where('publicaciones.status','=','Aprobado')
		->where('categoria','=',$id)
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
		})		
		->get(array(
			'publicaciones.id',
			'publicaciones.img_1',
			'publicaciones.titulo',
			'publicaciones.precio',
			'publicaciones.moneda',
			'publicaciones.descripcion',
			'departamento.id as dep_id',
			'departamento.nombre as dep'
		));
		$departamentos = Department::get();
		return Response::json(array(
			'publicaciones' => $publicaciones,
			'lider' 		=> $lider,
			'departamento' 	=> $departamentos,
			'busq' 			=> $id,
		));
	}
	public function resetPassword()
	{
		$email = Input::get('email');
		$user = User::where('email','=',$email)->first();
		if (count($user) < 1) {
			return Response::json(array('type' => 'danger','msg' => 'Error, el email no existe.'));
		}else
		{

			$cadena = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
		    //Obtenemos la longitud de la cadena de caracteres
		    $longitudCadena=strlen($cadena);
		     
		    //Se define la variable que va a contener la contraseña
		    $pass = "";
		    //Se define la longitud de la contraseña, en mi caso 10, pero puedes poner la longitud que quieras
		    $longitudPass=8;
		    
		    //Creamos la contraseña
		    for($i=1 ; $i<=$longitudPass ; $i++){
		        //Definimos numero aleatorio entre 0 y la longitud de la cadena de caracteres-1
		        $pos=rand(0,$longitudCadena-1);
		     
		        //Vamos formando la contraseña en cada iteraccion del bucle, añadiendo a la cadena $pass la letra correspondiente a la posicion $pos en la cadena de caracteres definida.
		        $pass .= substr($cadena,$pos,1);
		    }
		    $user->password = Hash::make($pass);
		  	$data = array(
				'pass' => $pass,
				'texto' => 'Usted ha solicitado recuperar su contraseña',
				'title' => 'recuperar contraseña'
			);

		  	if ($user->save()) {
		  		Mail::send('emails.passNew', $data, function ($message) use ($pass,$email){
				    $message->subject('Correo de restablecimiento de contraseña pasillo24.com');
				    $message->to($email);
				});
				return Response::json(array('type' => 'success','msg' => 'Se enviara un email con una clave provisional. Esto puede tomar varios segundos.'));

		  	}else
		  	{
				return Response::json(array('type' => 'danger','msg' => 'Error, no se ha podido cambiar la contraseña, le agradecemos ponerse en contacto por medio de nuestro modulo de contacto.'));
		  	}
		    

		}	
	}
	public function postProfile()
	{
		$id = Input::get('id');
		$input = Input::all();
		$user = User::find($id);
		$email = $user->email;
		$rules = array(
			'name'       			 => 'min:4',
			'lastname'   			 => 'min:4',
			'id_carnet'         	 => 'min:5',
			'department' 			 => 'required'

		);
		$messages = array(
			'required' => ':attribute es obligatorio',
			'min'      => ':attribute debe ser mas largo'
		);
		$custom = array(
			'name'              => 'El nombre',
			'lastname'          => 'El apellido',
			'department'  		=> 'El departamento'
		);
		$validator = Validator::make($input, $rules, $messages,$custom);
		if ($validator->fails()) {
			return Response::json(array(
               'danger' => 'Error al validar los datos',
               'data' => $validator->getMessageBag()->toArray()
              	)
			);
		}
		if (!empty($input['name'])) {
			$user->name = $input['name'];
		}
		if (!empty($input['lastname'])) {
			$user->lastname = $input['lastname'];
		}
		if (!empty($input['id_carnet'])) {
			$user->id_carnet = $input['id_carnet'];
		}
		if (!empty($input['department'])) {
			$user->state = $input['department'];
		}
		if (!empty($input['phone'])) {
			$user->phone = $input['phone'];
		}
		if (!empty($input['pag_web'])) {
			$user->pag_web = $input['pag_web'];
		}
		if (!empty($input['postal_cod'])) {
			$user->postal_cod = $input['postal_cod'];
		}
		if  (!empty($input['direccion'])) {
			$user->dir = $input['direccion'];
		}
		if (!empty($input['nit'])) {
			$user->nit = $input['nit'];
		}
		if (!empty($input['pais'])) {
			$user->pais = $input['pais'];
		}
		if($user->save())
		{
			$data = array(
				'link' => 'pasillo24.com/inicio/contacto'
			);
			Mail::send('emails.modify', $data, function ($message) use ($input,$email){
			    $message->subject('Correo de cambio de perfil pasillo24.com');
			    $message->from('pasillo24@pasillo24.com');
			    $message->to($email);
			});
			return Response::json(array(
				'type' => 'success',
				'msg'  => 'Perfil cambiado satisfactoriamente. Se ha enviado un email a su correo.'
			));
		}else{
			return Response::json(array(
				'type' => 'danger',
				'msg'  => 'Error al modificar el perfil.'
			));
		}
	}
	public function postLider()
	{

		$msgBag = null;
		$input = Input::get('id');
		$input = Input::all();
		/* validar pagina web */
		if (!empty($input['pagina'])) {
			if (mb_stristr($input['pagina'],'http://') == false && mb_stristr($input['pagina'],'https://') == false) {
				$input['pagina'] = '';
				$msgBag = array('pagina' => 'La pagina web debe comenzar con http:// ó https://.');
			}
		}
		if (isset($input['fechIni'])) {
			$fecha = explode('-', $input['fechIni']);
			if (count($fecha)<3) {
				$input['fechIni'] = '';
				$msgBag = array('fechIni' => 'Formato de fecha incorrecto. formato valido: dd-mm-yyyy.');
			}
		}
		/* Validador general */
		define('CONST_SERVER_TIMEZONE', 'UTC');
		date_default_timezone_set('America/La_Paz');
		$rules = array(
			'ubication' => 'required',
			'namePub'   => 'required|min:4',
			'duration'  => 'required|min:0',
			'time'      => 'required|in:d,s,m,a',
			'fechIni'   => 'required|after:'.date('d-m-Y'),
			'id'   => 'required',
		);
		$msg = array(
			'required' => ':attribute es obligatorio',
			'min'      => ':attribute debe ser mas largo',
			'in'       => 'Debe seleccionar una opción',
			'after'    => 'Debe seleccionar una fecha posterior a hoy',
			'active_url'=> 'Debe utilizar un url válido'
		);
		$attribute = array(
			'ubication' => 'El campo ubicacion',
			'namePub' 	=> 'El campo titulo',
			'duration'	=> 'El campo duracion',
			'time'		=> 'El campo tiempo',
			'fechIni'   => 'El campo fecha de inicio',
			'id'	=> 'Id del usuario'

		);
		if ($input['ubication'] == 'Categoria'){
			$rules = $rules+array('cat'  => 'required');
			$attribute = array(
				'cat'	=> 'El campo categoria',
			);
		}
		$validator = Validator::make($input, $rules, $msg,$attribute);
		if ($validator->fails()) {
			if (!is_null($msgBag)) {
				return Response::json(array(
					'type' => 'danger',
					'data' => array_merge($validator->getMessageBag()->toArray(),$msgBag),
				));
				
			}else
			{
				return Response::json(array(
					'type' => 'danger',
					'data' => $validator->getMessageBag()->toArray(),
				));
			}
		}else
		{
			/* Validar duracion y montos */
			$dur = $input['duration'];
			if ($input['time'] == 'd') {
				
				$time = 86400;
				$monto = Precios::where('pub_type_id','=',1)->where('desc','=','dia')->pluck('precio');
			}elseif($input['time'] == 's')
			{
				$time = 604800;
				$monto = Precios::where('pub_type_id','=',1)->where('desc','=','semana')->pluck('precio');
			}elseif($input['time'] == 'm')
			{
				$time = 2629744;
				$monto = Precios::where('pub_type_id','=',1)->where('desc','=','mes')->pluck('precio');
			}
			$monto = $monto*$dur;

			/* Segundo validador de fecha */
		
			$timestamp = strtotime($input['fechIni'])+($time*$input['duration']);
			$fechaFin = date('d-m-Y',$timestamp);

			$timestamp = $input['duration']*$time;
			$date  = date('d-m-Y');
			$timestamp = strtotime($input['fechIni'])+$timestamp;
			$fechFin = date('Y-m-d',$timestamp);

			/* nueva publicacion */
			$publication = new Publicaciones;
			$publication->user_id   = $id;
			$publication->tipo 		= 'Lider';
			$publication->ubicacion = $input['ubication'];
			if ($publication->ubicacion == 'Categoria') {
				$publication->categoria = $input['cat'];
			}
			$publication->titulo    = $input['namePub'];
			if (isset($input['pagina']) && !empty($input['pagina'])) {
				$publication->pag_web   = $input['pagina'];
			}else
			{
				$publication->pag_web   = 'http://';
			}
			$publication->fechIni   = date('Y-m-d',strtotime($input['fechIni']));
			$publication->fechFin   = $fechFin;
			$publication->status    = 'Pendiente';
			if(isset($input['nomb']) && !empty($input['nomb'])){
				$publication->name = $input['nomb'];
			}
			if(isset($input['phone']) && !empty($input['phone'])){
				$publication->phone = $input['phone'];
			}
			if(isset($input['email']) && !empty($input['email'])){
				$publication->email = $input['email'];
			}
			if(isset($input['pag_web']) && !empty($input['pag_web'])){
				$publication->pag_web_hab = $input['pag_web'];
			}
			$publication->monto     = $monto;
			
			
			if ($publication->save()) {
				
				return Response::json(array(
					'type' 		=> 'success',
					'msg'  		=> 'Publcación creada satisfactoriamente.',
					'pub_id' 	=> $publication->id,
					'monto'	    => $publication->monto
				));
			}else
			{
				
				return Response::json(array(
					'type' 		=> 'danger',
					'msg'  		=> 'Error al crear la publicación.',
				));
			}
			
		}
	}
}
