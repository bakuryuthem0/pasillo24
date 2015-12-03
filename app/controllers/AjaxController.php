<?php

class AjaxController extends BaseController{
	public function getLoginApp()
	{
	    $nombre= Input::get("usuario"); 
	    $contra= Input::get("password");
		$user = User::where('username','=',$nombre)->first();
		if (count($user) > 0) {
			if (Hash::check($contra, $user->password)) 
			{
				$regId = Input::get('regId');
				$gcm = GcmDevices::where('gcm_regid','=',$regId)->where('usuario','=',$nombre)->first();
				if (count($gcm) < 1) {

					$g = new GcmDevices;
					$g->gcm_regid = $regId;
					$g->usuario   = $nombre;
					$g->save();
				}
				$cla= $user->id;
				$usu= $user->username;

				$n=array('usu' => $usu
					,'cla' => $cla);
				return Response::json($n);
			}else
			{
				return Response::json(array('danger' => 'Usuario o Contraseña invalida'));
			}
		}else
		{
			return Response::json(array('danger' => 'Usuario o Contraseña invalida'));
		}
	}
    public function postRegisterApp()
	{			
		$input = Input::all();
		$rules = array(
			'usuario'   			 => 'required|min:4|unique:usuario,username',
			 'contra'      		 	 => 'required|min:6',
			 'nombre'       			 => 'required|min:4',
			 'apellido'   			 => 'required|min:4',
			 'email'      			 => 'required|email|unique:usuario,email',
			'carnet'       			 => 'required|min:5|unique:usuario,id_carnet',
			 'sexo'       			 => 'required|in:f,m',
			'department'	 			 => 'required',

		);
		$messages = array(
			'required' => ':attribute es obligatoria',
			'min'      => ':attribute debe ser mas largo',
			'email'    => 'Debe introducir un email válido',
			'unique'   => ':attribute ya existe',
			'confirmed'=> 'La contraseña no concuerdan'
		);
		$custom = array(
			'usuario' 			=> 'EL nombre de usuario',
			'contra'    	 	=> 'La contraseña',
			'nombre'            => 'El nombre',
			'lastname'          => 'El apellido',
			'email' 			=> 'El email',
			'carnet'			=> 'El carnet de identificacion',
			'sexo'				=> 'El sexo',
			'department'  			=> 'El departamento',
		);
		$validator = Validator::make($input, $rules, $messages,$custom);
		if ($validator->fails()) {
			return Response::json(array(
               'danger' => 'Error al validar los datos',
               'data' => $validator->getMessageBag()->toArray()
              	)
			);
		}

		$user = new User;
		$user->username 	 = $input['usuario'];
		$user->password    	 = Hash::make($input['contra']);
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
		$user->role          = 'Usuario';
		
		if ($user->save()) {
			$n=array('ok' => 'Registro completo');
			return Response::json($n);
		}else
		{
			return Response::json(array('danger' => 'Error al registrar al usuario.'));
		}
	}
	public function showIndex($id = null)
	{
        if (!is_null($id)) {
			$dep = Department::find($id);
		}
		if (!is_null($id)) {
			$lider = Publicaciones::where('status','=','Aprobado')
			->where('ubicacion','=','Principal')
			->where('tipo','=','Lider')
			->where('pag_web','!=',"")
			->where('departamento','=',$id)
			->where('fechFin','>=',date('Y-m-d',time()))
			->where('deleted','=',0)
			->orderBy('fechFin','desc')
			->get(array(
				'id',
				'titulo',
				'img_1'
			));	
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
			->orderBy('fechFin','desc')->get(array(
				'id',
				'titulo',
				'precio',
				'moneda',
				'img_1'
			));
			
			$casual = Publicaciones::where('tipo','=','Casual')
			->where('fechFin','>=',date('Y-m-d',time()))
			->where('status','=','Aprobado')
			->where('departamento','=',$id)
			->where('deleted','=',0)
			->get(
			array(
				'id',
				'titulo',
				'precio',
				'moneda',
				'img_1'
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
				'id',
				'titulo',
				'img_1'
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
			->orderBy('fechFin','desc')->get(array(
				'id',
				'titulo',
				'precio',
				'moneda',
				'img_1'
			));
			$casual = Publicaciones::where('tipo','=','Casual')
			->where('fechFin','>=',date('Y-m-d',time()))
			->where('status','=','Aprobado')
			->where('deleted','=',0)
			->get(array(
				'id',
				'titulo',
				'precio',
				'moneda',
				'img_1'
			));
		}

		$categories = Categorias::where('deleted','=',0)->where('tipo','=',1)->orderBy('nombre')->get();
		$servicios  = Categorias::where('deleted','=',0)->where('tipo','=',2)->get();
		$departamentos = Department::get();
                $publi = Publicidad::get();
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
		return Response::json(array('success' => 1));
	}
	
}
