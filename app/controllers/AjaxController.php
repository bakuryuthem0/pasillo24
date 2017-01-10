<?php

class AjaxController extends BaseController{
	protected $toReturn = array(
		'publicaciones.id',
		'publicaciones.img_1',
		'publicaciones.img_2',
		'publicaciones.img_3',
		'publicaciones.img_4',
		'publicaciones.img_5',
		'publicaciones.img_6',
		'publicaciones.img_7',
		'publicaciones.img_8',
		'publicaciones.titulo',
		'publicaciones.precio', 
		'publicaciones.moneda',
		'publicaciones.descripcion',
		'usuario.email',
		'usuario.auth_token',
		'usuario.dir',
		'usuario.id as user_i',
		'usuario.id_carnet',
		'usuario.lastname',
		'usuario.name',
		'usuario.nit',
		'usuario.nombEmp',
		'usuario.pag_web',
		'usuario.pais',
		'usuario.password',
		'usuario.phone',
		'usuario.postal_cod',
		'usuario.register_cod',
		'usuario.register_cod_active',
		'usuario.reputation',
		'usuario.role',
		'usuario.state',
		'usuario.user_deleted',
		'usuario.user_suspended',
		'usuario.username',
		'usuario.votes',
		'departamento.nombre as dep_desc' 
	);
	public function upload_images($file, $user)
	{
		$filename = $file->getClientOriginalName();
		if (file_exists('images/pubImages/'.$user->username.'/'.$filename)) {
			//guardamos la imagen en public/imgs con el nombre original
            $i = 0;//indice para el while
            //separamos el nombre de la img y la extensión
            $info = explode(".",$filename);
            //asignamos de nuevo el nombre de la imagen completo
            $miImg = $filename;
            //mientras el archivo exista iteramos y aumentamos i
            while(file_exists('images/pubImages/'.$user->username.'/'. $miImg)){
                $i++;
                $miImg = $info[0]."(".$i.")".".".$info[1];              
            }
            //guardamos la imagen con otro nombre ej foto(1).jpg || foto(2).jpg etc
            $file->move("images/pubImages/".$user->username,$miImg);
            $img = Image::make('images/pubImages/'.$user->username.'/'.$miImg);
	        $mark = Image::make('images/watermark.png')->widen(400);
	        $img->insert($mark,'center')
           	->interlace()
            ->save('images/pubImages/'.$user->username.'/'.$miImg);
            if($miImg != $filename){
				return $user->username.'/'.$miImg;
            }
		}else
		{
			$file->move("images/pubImages/".$user->username,$filename);
			$img = Image::make('images/pubImages/'.$user->username.'/'.$filename);
	        $mark = Image::make('images/watermark.png')->widen(400);
	        $img->insert($mark,'center')
           	->interlace()
            ->save("images/pubImages/".$user->username.'/'.$filename);
            return $user->username.'/'.$filename;
		}
	}
	/*---------------------------Login-------------------------------------------------*/
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*---------------------------Login-------------------------------------------------*/
	public function getGcm()
	{
		$regId = Input::get('gcm_token');
		$id = Input::get('id');
		$aux = GcmDevices::where('gcm_regid','=',$regId)->where('user_id','=',$id)->first();
		if (is_null($aux) || empty($aux)) {
			$new   = new GcmDevices;
			$new->gcm_regid = $regId;
			$new->user_id   = $id;
			$new->save();
		}
		return Response::json(array(
			'type' => 'success',
		));
	}
	public function getLoginApp()
	{

	    $username = Input::get("username"); 
	    $password = Input::get("password");
	    /*Se verifica si existe el usuario*/
		$user = User::where('username','=',$username)->first();
		if (count($user) > 0) {
			/*Se verifica si concuerdan las contraseñas*/
			if (Hash::check($password, $user->password)) 
			{
				/*Se toma el id del movil y se busca en la base de datos*/
				if (Input::has('gcm_token')) {
					$regId = Input::get('gcm_token');
					$aux = GcmDevices::where('gcm_regid','=',$regId)->where('id','=',$user->id)->count();
					if (is_null($aux) || empty($aux) || $aux < 1) {
						$new   = new GcmDevices;
						$new->gcm_regid = $regId;
						$new->user_id   = $user->id;
						$new->save();
					}
					/*Si no se encuentra (primera vez que inicia en este movil) se crea un nuevo registro*/
					/*Se devuelven los datos en forma de json*/
				}
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
					'auth_token' => $user->auth_token
				);
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
	/*---------------------------Resetar/perfil----------------------------------------*/
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*---------------------------Resetar/perfil----------------------------------------*/
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
	public function postChangePass()
	{
		$id = Input::get('id');
		$input = Input::all();
		$user = User::find($id);
		if (!Hash::check($input['old'], $user->password)) {
			return Response::json(array(
				'type' => 'danger',
				'msg' => 'la contraseña anterior es incorrecta'
			));
		}else
		{
			$rules = array(
				'old' 					=> 'required',
				'password' 				=> 'required|min:8|confirmed',
				'password_confirmation' => 'required'
			);		
			$messages = array(
				'required' => ':attribute es obligatorio',
				'min'	   => ':attribute debe tener al menos 8 caracteres',
				'confirmed'	=> ':attribute no coincide'
			);
			$attributes = array(
				'old'  					 => 'El campo contraseña vieja',
				'password'  			 => 'El campo contraseña nueva',
				'password_confirmation'  => 'El campo repetir contraseña'
			);
			$validator = Validator::make($input, $rules, $messages, $attributes);
			if ($validator->fails()) {
				Response::json(array(
					'type' => 'danger',
					'msg'  => 'Error al validar lo datos',
					'data' => $validator->getMessageBag()->toArray(),
				));
			}

			$user->password = Hash::make($input['password']);
			if ($user->save()) {
				$data = array(
					'pass' => $input['password'],
					'texto' => 'Usted ha solicitado un cambio de contraseña',
					'title' => 'Cambio de contraseña'
				);
				$newPass = $input['password'];
				$email = $user->email;
				Mail::send('emails.passNew', $data, function ($message) use ($newPass,$email){
					    $message->subject('Correo de cambio de contraseña pasillo24.com');
					    $message->from('pasillo24@pasillo24.com');
					    $message->to($email);
					});
				return Response::json(array(
					'type' => 'success',
					'msg' => 'Contraseña modificada correctamente'
				));
			}else
			{
				return Response::json(array(
					'type' => 'error',
					'msg' => 'la contraseña no se pudo cambiar.'
				));
			}
		}
			
	}
	/*---------------------------Index-------------------------------------------------*/
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*---------------------------Index-------------------------------------------------*/
	public function showIndex($id = null)
	{
        if (!is_null($id)) {
			$dep = Department::find($id);
		}
		$title ="Inicio | pasillo24.com";
		if (!is_null($id)) {
			$lider = Publicaciones::join('usuario','usuario.id','=','publicaciones.user_id')
			->leftJoin('departamento','publicaciones.departamento','=','departamento.id')
			->where('publicaciones.status','=','Aprobado')
			->where('publicaciones.ubicacion','=','Principal')
			->where('publicaciones.tipo','=','Lider')
			->where('publicaciones.pag_web','!=',"")
			->where('publicaciones.fechFin','>=',date('Y-m-d',time()))
			->where('publicaciones.deleted','=',0)
			->orderBy('publicaciones.fechFin','desc')->get($this->toReturn);
			$habitual = Publicaciones::join('usuario','usuario.id','=','publicaciones.user_id')
			->leftJoin('departamento','publicaciones.departamento','=','departamento.id')
			->where(function($query) use($id){
				/*Busco las habituales*/
				$query->where('publicaciones.tipo','=','Habitual')
				->where(function($query){
					/*Que vayan en la principal*/
					$query->where('publicaciones.ubicacion','=','Principal')
					->orWhere('publicaciones.ubicacion','=','Ambos')
					->where('publicaciones.status','=','Aprobado');

				})
				->where(function($query){
					/*y que sigan activas*/
					$query->where('publicaciones.fechFin','>=',date('Y-m-d',time()))
					->where('publicaciones.status','=','Aprobado');

				})
				->where('publicaciones.departamento','=',$id);
			})
			->where('publicaciones.deleted','=',0)
			->orWhere(function($query) use($id){
				$query->where('publicaciones.tipo','=','Lider')
				->where('publicaciones.ubicacion','=','Principal')
				->where('publicaciones.pag_web','=',"")
				->where('publicaciones.status','=','Aprobado')
				->where('publicaciones.departamento','=',$id);

			})
			->orderBy('publicaciones.fechFin','desc')->get($this->toReturn);
			
			$casual = Publicaciones::join('usuario','usuario.id','=','publicaciones.user_id')
			->leftJoin('departamento','publicaciones.departamento','=','departamento.id')
			->where('publicaciones.tipo','=','Casual')
			->where('publicaciones.fechFin','>=',date('Y-m-d',time()))
			->where('publicaciones.status','=','Aprobado')
			->where('publicaciones.departamento','=',$id)
			->where('publicaciones.deleted','=',0)
			->get($this->toReturn);
		}else
		{
			$lider = Publicaciones::join('usuario','usuario.id','=','publicaciones.user_id')
			->leftJoin('departamento','publicaciones.departamento','=','departamento.id')
			->where('publicaciones.status','=','Aprobado')
			->where('publicaciones.ubicacion','=','Principal')
			->where('publicaciones.tipo','=','Lider')
			->where('publicaciones.pag_web','!=',"")
			->where('publicaciones.fechFin','>=',date('Y-m-d',time()))
			->where('publicaciones.deleted','=',0)
			->orderBy('publicaciones.fechFin','desc')->get($this->toReturn);
			$habitual = Publicaciones::join('usuario','usuario.id','=','publicaciones.user_id')
			->leftJoin('departamento','publicaciones.departamento','=','departamento.id')
			->where(function($query){
				/*Busco las habituales*/
				$query->where('publicaciones.tipo','=','Habitual')
				->where(function($query){
					/*Que vayan en la principal*/
					$query->where('publicaciones.ubicacion','=','Principal')
					->orWhere('publicaciones.ubicacion','=','Ambos')
					->where('publicaciones.status','=','Aprobado');

				})
				->where(function($query){
					/*y que sigan activas*/
					$query->where('publicaciones.fechFin','>=',date('Y-m-d',time()))
					->where('publicaciones.status','=','Aprobado');

				});
			})
			->where('publicaciones.deleted','=',0)
			->orWhere(function($query){
				$query->where('publicaciones.tipo','=','Lider')
				->where('publicaciones.ubicacion','=','Principal')
				->where('publicaciones.pag_web','=',"")
				->where('publicaciones.status','=','Aprobado');

			})
			->orderBy('publicaciones.fechFin','desc')->get($this->toReturn);
			$casual = Publicaciones::join('usuario','usuario.id','=','publicaciones.user_id')
			->leftJoin('departamento','publicaciones.departamento','=','departamento.id')
			->where('publicaciones.tipo','=','Casual')
			->where('publicaciones.fechFin','>=',date('Y-m-d',time()))
			->where('publicaciones.status','=','Aprobado')
			->where('publicaciones.deleted','=',0)
			->get($this->toReturn);
		}
		/*
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
        $publi 			= Publicidad::get();*/
		if (!is_null($id)) {
        	return Response::json(array(
        		'pubLider' 		=> $lider,
        		'pubHabitual' 	=> $habitual,
        		'pubCasual' 	=> $casual,	
        		//'categorias' 	=> $categories,
        		//'servicios'     => $servicios,
        		//'departamentos' => $departamentos,
        		//'publi' 		=> $publi,
        		//'depFilter' 	=> $dep->id
          	));
        }else
        {
        	return Response::json(array(
        		'pubLider' 		=> $lider,
        		'pubHabitual' 	=> $habitual,
        		'pubCasual' 	=> $casual,	
        		//'categorias' 	=> $categories,
        		//'servicios'     => $servicios,
        		//'departamentos' => $departamentos,
        		//'publi' 		=> $publi,
          	));
        }
	}
	public function publicationSelf()
	{
		$id = Input::get('pub_id');
		$pub = Publicaciones::find($id);

		if ($pub->tipo == 'Lider') {
			$url = URL::to('publicacion/lider/'.base64_encode($id));
			
		}elseif($pub->tipo == "Habitual")
		{
			$url = URL::to('publicacion/habitual/'.base64_encode($id));

		}elseif($pub->tipo == "Casual")
		{
			$url = URL::to('publicacion/casual/'.base64_encode($id));
			
		}
		$user = User::find($pub->user_id);
		if ($pub->user_id != 21) {
			$otrasPub = Publicaciones::where('user_id','=',$pub->user_id)
			->where('id','!=',$id)
			->where('status','=','Aprobado')
			->where(function($query)
			{
				$query->where('tipo','=','Lider')
				->where('fechFin','>=',date('Y-m-d',time()))
				->orWhere(function($query)
				{
					$query->where(function($query){
						/*Busco las habituales*/
						$query->where('tipo','=','Habitual')
						->where(function($query){
							/*y que sigan activas*/
							$query->where('fechFin','>=',date('Y-m-d',time()))
							->orWhere('fechFinNormal','>=',date('Y-m-d',time()));

						});
					})
					->orWhere(function($query){
						$query->where('tipo','=','Casual')
						->where('fechFin','>=',date('Y-m-d',time()));

					});
				});
			})
			->where('deleted','=',0)
			->orderBy('id','desc')
			->take(16)
			->get();
		}else
		{
			$otrasPub = array();
		}
		if ($pub->tipo == "Lider") 
		{
			$publication = Publicaciones::leftJoin('locations','locations.pub_id','=','publicaciones.id')
			->join('usuario','usuario.id','=','publicaciones.user_id')
			->where('publicaciones.id','=',$id)
			->first(array(
				'locations.longitude',
				'locations.latitude',
				'publicaciones.titulo',
				'publicaciones.tipo',
				'publicaciones.precio',
				'publicaciones.moneda',
				'publicaciones.img_1',
				'publicaciones.img_2',
				'publicaciones.pag_web',
				'publicaciones.name as name_pub',
				'publicaciones.lastname as lastname_pub',
				'publicaciones.email as email_pub',
				'publicaciones.phone as phone_pub',
				'publicaciones.pag_web_hab',
				'usuario.id',
				'usuario.name',
				'usuario.lastname',
				'usuario.email',
				'usuario.phone',
				'usuario.reputation'
			));
		return Response::json($publication);
			
		}elseif($pub->tipo == "Habitual")
		{
			if ($pub->categoria == 34) {
				$publication = DB::table('publicaciones')
				->leftJoin('locations','locations.pub_id','=','publicaciones.id')
				->join('marcas','marcas.id','=','publicaciones.marca_id')
				->join('modelo','modelo.id','=','publicaciones.modelo_id')
				->join('departamento','departamento.id','=','publicaciones.departamento')
				->join('usuario','usuario.id','=','publicaciones.user_id')
				->where('publicaciones.id','=',$id)
				->first(array(
					'locations.longitude',
					'locations.latitude',
					'usuario.id',
					'usuario.name as table_name',
					'usuario.lastname as table_lastname',
					'usuario.email as table_email',
					'usuario.phone as table_phone',
					'usuario.pag_web as table_pag_web',
					'usuario.reputation',
					'departamento.nombre as dep',
					'marcas.nombre as marca',
					'modelo.nombre as modelo',
					'publicaciones.*'
				));
			}else {
				$publication = DB::table('publicaciones')
				->leftJoin('locations','locations.pub_id','=','publicaciones.id')
				->join('usuario','usuario.id','=','publicaciones.user_id')
				->join('departamento','departamento.id','=','publicaciones.departamento')
				->where('publicaciones.id','=',$id)
				->first(array(
					'locations.longitude',
					'locations.latitude',
					'usuario.id as user_id',
					'usuario.reputation',
					'publicaciones.*',
					'departamento.nombre as dep'
				));
			}		
			
		}elseif($pub->tipo == 'Casual')
		{
			$publication = Publicaciones::leftJoin('locations','locations.pub_id','=','publicaciones.id')
			->join('categoria','categoria.id','=','publicaciones.categoria')
			->join('usuario','usuario.id','=','publicaciones.user_id')
			->join('departamento','departamento.id','=','publicaciones.departamento')
			->where('publicaciones.id','=',$id)
			->where('publicaciones.tipo','=','Casual')
			->first(array(
				'locations.longitude',
				'locations.latitude',
				'usuario.id as user_id',
				'usuario.reputation',
				'categoria.desc as cat',
				'publicaciones.titulo',
				'publicaciones.precio',
				'publicaciones.moneda',
				'publicaciones.id',
				'publicaciones.img_1',
				'publicaciones.img_2',
				'publicaciones.descripcion',
				'publicaciones.tipo',
				'departamento.nombre'
			));
		}
		$comentarios = DB::table('comentario')
		->join('usuario','usuario.id','=','comentario.user_id')
		->where('comentario.pub_id','=',$id)
		->get(array('comentario.id','comentario.comentario','comentario.created_at','usuario.username'));
		$resp = Respuestas::where('pub_id','=',$id)->get();
		return Response::json(array(
			'publication' 	=> $publication,/*Toda la info esta pub*/
			'comentarios' 	=> $comentarios, /*comentarios de esta pub*/
			'id' 			=> $id,
			'respuestas' 	=> $resp, /*respuesta a los comentarios de esta pub*/
			'otrasPub' 		=> $otrasPub,/*Otras pub de los usuarios*/
			'username' 		=> $user->username,
			'url' 			=> $url
		));
	}
	/*---------------------------Busqueda----------------------------------------------*/
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*---------------------------Busqueda----------------------------------------------*/
	public function search()
	{
		
		$paginatorFilter = "";
		if (Input::has('busq')) {
			$busq = Input::get('busq');
			$auxLider = Publicaciones::leftJoin('categoria','categoria.id','=','publicaciones.categoria')
			->leftJoin('usuario','usuario.id','=','publicaciones.user_id')
			->leftJoin('departamento','publicaciones.departamento','=','departamento.id')
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
			->leftJoin('usuario','usuario.id','=','publicaciones.user_id')
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
					$auxLider =  $auxLider->where('publicaciones.moneda','=',$currency)->where('publicaciones.precio','>=',$min)->whereRaw('`publicaciones`.`precio` <= '.$max);
					$auxRes   =  $auxRes->where('publicaciones.precio','>=',$min)->whereRaw('`publicaciones`.`precio` <= '.$max)->where('publicaciones.moneda','=',$currency);
				}else{
					if(!is_null($max) && !empty($max)){
						$minmax = array('', $max);
						$paginatorFilter .= '&max='.$max.'&currency='.$currency;
						$auxLider =  $auxLider->where('publicaciones.moneda','=',$currency)->whereRaw('`publicaciones`.`precio` <= '.$max);
						$auxRes   =  $auxRes->whereRaw('`publicaciones`.`precio` <= '.$max)->where('publicaciones.moneda','=',$currency);
					}elseif(!is_null($min) && !empty($min)){
						$minmax = array($min, '');
						$paginatorFilter .= '&min='.$min.'&currency='.$currency;
						$auxLider =  $auxLider->where('publicaciones.moneda','=',$currency)->where('precio','>=',$min);
						$auxRes   =  $auxRes->where('publicaciones.moneda','=',$currency)->where('publicaciones.precio','>=',$min);
					}
				}
			}
			if (Input::has('rel')) {
				$rel = Input::get('rel');
				switch ($rel) {
					case 'rep':
						$auxLider =  $auxLider->orderBy('usuario.reputation','DESC');
						$auxRes   =  $auxRes->orderBy('usuario.reputation','DESC');
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
			$lider = $auxLider->get(array_merge($this->toReturn));
			$res = $auxRes->get(array_merge($this->toReturn,array('publicaciones.fechFin','publicaciones.fechFinNormal')));
			$categorias = Categorias::where('id','=',$busq)->pluck('desc');
			if (!is_null($categorias)) {
				$busq = $categorias;
			}else
			{
				$busq = $busq;
			}
			$departamentos = Department::get();
			$view = array(
				'publicaciones' 	=> $res,
				'busq' 				=> $busq,
				'lider' 			=> $lider,
				'departamento' 		=> $departamentos,
				'paginatorFilter' 	=> $paginatorFilter,
			);
			if (isset($filter)) {
				$view = $view + array('filter' => $filter);
			}
			if (isset($currency)) {
				$view = $view + array('minmax' => $minmax, 'currency' => $currency);
			}
			if (isset($cond)) {
				$view = $view + array('cond' => $cond);
			}
			if (isset($buss)) {
				$view = $view + array('buss' => $buss);
			}
			if (isset($rel)) {
				$view = $view + array('rel' => $rel);
			}
			return Response::json($view);
		}elseif(Input::has('cat'))
		{
			$id = Input::geT('cat');
			/*Query inicial*/
			$auxLider = Publicaciones::leftJoin('usuario','usuario.id','=','publicaciones.user_id')
			->leftJoin('departamento','publicaciones.departamento','=','departamento.id')
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
			->where('publicaciones.categoria','=',$id);
			//->get(array('id','img_1','titulo','precio','moneda'));
			$auxRes = Publicaciones::leftJoin('departamento','publicaciones.departamento','=','departamento.id')
			->leftJoin('usuario','usuario.id','=','publicaciones.user_id')
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
			$lider = $auxLider->get($this->toReturn);
			$res = $auxRes->get(array_merge($this->toReturn,array('publicaciones.fechFin','publicaciones.fechFinNormal')));

			$categorias = Categorias::where('id','=',$id)->pluck('id');
			if (!is_null($categorias)) {
				$busq = $categorias;
			}else
			{
				$busq = $id;
			}
			$departamentos = Department::get();	
			$view = array(
				'publicaciones' 	=> $res,
				'busq' 				=> $busq,
				'lider' 			=> $lider,
				'departamento' 		=> $departamentos,
				'paginatorFilter' 	=> $paginatorFilter,
			);
			if (isset($filter)) {
				$view = $view + array('filter' => $filter);
			}
			if (isset($currency)) {
				$view = $view + array('minmax' => $minmax, 'currency' => $currency);
			}
			if (isset($cond)) {
				$view = $view + array('cond' => $cond);
			}
			if (isset($buss)) {
				$view = $view + array('buss' => $buss);
			}
			if (isset($rel)) {
				$view = $view + array('rel' => $rel);
			}
			return Response::json($view);
		}
	}
	/*---------------------------categorias----------------------------------------------*/

	public function getCategories($id)
	{
		$lider = Publicaciones::join('usuario','usuario.id','=','publicaciones.user_id')
		->leftJoin('departamento','publicaciones.departamento','=','departamento.id')
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
		->where('publicaciones.categoria','=',$id)
		->get($this->toReturn);
		$publicaciones = Publicaciones::join('usuario','usuario.id','=','publicaciones.user_id')
		->leftJoin('departamento','publicaciones.departamento','=','departamento.id')
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
		})		
		->get($this->toReturn);
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
		$lider = Publicaciones::leftJoin('usuario','usuario.id','=','publicaciones.user_id')
		->leftJoin('departamento','departamento.id','=','publicaciones.departamento')
		->where('publicaciones.status','=','Aprobado')
		->where('deleted','=',0)
		->where(function($query){
			$query->where('publicaciones.ubicacion','=','Categoria')
			->orWhere('publicaciones.ubicacion','=','Ambos');
		})
		->where(function($query){
			$query->where('publicaciones.fechFin','>=',date('Y-m-d'))
			->orWhere('publicaciones.fechFinNormal','>=',date('Y-m-d'));
			
		})
		->where('publicaciones.categoria','=',$id)
		->get($this->toReturn);
		$publicaciones = Publicaciones::leftJoin('usuario','usuario.id','=','publicaciones.user_id')
		->leftJoin('departamento','publicaciones.departamento','=','departamento.id')
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
		})		
		->get($this->toReturn);
		$departamentos = Department::get();
		return Response::json(array(
			'publicaciones' => $publicaciones,
			'lider' 		=> $lider,
			'departamento' 	=> $departamentos,
			'busq' 			=> $id,
		));
	}
	public function getLider()
	{
		$lider = Publicaciones::join('usuario','usuario.id','=','publicaciones.user_id')
			->leftJoin('departamento','publicaciones.departamento','=','departamento.id')
			->where('publicaciones.status','=','Aprobado')
			->where('publicaciones.tipo','=','Lider')
			->where('publicaciones.fechFin','>=',date('Y-m-d',time()))
			->where('publicaciones.deleted','=',0)
			->orderBy('publicaciones.fechFin','desc')->get($this->toReturn);
		return Response::json(array(
			'type' => 'success',
			'data' => $lider, 
		));
	}
	/*---------------------------Subir Imagenes----------------------------------------*/
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*---------------------------Subir Imagenes----------------------------------------*/
	public function upload_image($carpeta,$file)
	{
		$ruta 	 = "images/pubImages/".$carpeta."/";
		$extension = File::extension($file->getClientOriginalName());
		$time = time();
		$miImg = $time.'.'.$extension;
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
        return $carpeta.'/'.$miImg;
	}
	/*---------------------------Publicar----------------------------------------------*/
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*---------------------------Publicar----------------------------------------------*/
	/*------------------------------Lider----------------------------------------------*/
	
	public function postLider()
	{

		$msgBag = null;
		$id = Input::get('id');
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
			'ubication'  => 'required|in:Principal,Categoria',
			'namePub'    => 'required|min:4',
			'duration'   => 'required|min:0',
			'time'       => 'required|in:d,s,m,a',
			'fechIni'    => 'required|after:'.date('d-m-Y'),
			'id'   		 => 'required',
			'negocioType' => 'required',
			'dep'		  => 'required|exists:departamento,id',
			//'img_1'		 => 'required|image',
		);
		$msg = array(
			'required' => ':attribute es obligatorio',
			'min'      => ':attribute debe ser mas largo',
			'in'       => 'Debe seleccionar una opción',
			'after'    => 'Debe seleccionar una fecha posterior a hoy',
			'active_url'=> 'Debe utilizar un url válido',
			'image'		=> 'El archivo :attribute debe ser una imagen',
		);
		$attribute = array(
			'ubication' => 'El campo ubicacion',
			'namePub' 	=> 'El campo titulo',
			'duration'	=> 'El campo duracion',
			'time'		=> 'El campo tiempo',
			'fechIni'   => 'El campo fecha de inicio',
			'id'		=> 'Id del usuario',
			'negocioType'=> 'El campo tipo de negocio',
			'img_1'		=> 'Imagen principal',
			'dep'		=> 'El campo departamento'

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
			$publication->bussiness_type = $input['negocioType'];
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
			$publication->departamento = $input['dep'];
			$publication->monto     = $monto;
			$user = User::find($id);
			if (Input::hasFile('img_1')) {
				$file1 = Input::file('img_1');
				$publication->img_1 = $this->upload_image($user->username,$file1);
			}
			if (Input::hasFile('img_2')) {
				$file2 = Input::file('img_2');
				$publication->img_1 = $this->upload_image($user->username,$file2);
			}
			
			if ($publication->save()) {
				if (Input::has('longitud') && Input::has('latitud')) {
					$lon = Input::get('longitud');
					$lat = Input::get('latitud');
					if (!empty($lon) && !empty($lat)) {
						$loc = new Location;
						$loc->latitude  = Input::get('latitud');
						$loc->longitude = Input::get('longitud');
						$loc->pub_id    = $publication->id;
						$loc->save();
					}
				}
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
	/*------------------------------Habitual----------------------------------------------*/
	public function postHabitual()
	{
		$id = Input::get('id');
		if (!Input::has('cat_id')) {
			return Response::json(array(
				'type' => 'danger',
				'msg'  => 'Error, no se consigue el id de la categoria',
			));
		}
		$user = User::find($id);
		$input = Input::all();
		$rules = array(

			'departamento'	=> 'required',
			'ciudad'		=> 'required',
			'cat_id'		=> 'required',
			'subCat'	    => 'required',
			'title' 		=> 'required|min:4',
			'input'			=> 'required|min:4',
			'moneda'		=> 'required',
			'precio'		=> 'required_if:tipoTransac,venta,alquiler,Aticretico,otro',
			'img1'			=> 'required|image|max:3000',
			'tipoTransac'	=> 'required',
			'negocioType'   => 'required',
		);
		$messages = array(
			'required' 	=> ':attribute es obligatorio',
			'required_if' => ':attribute es obligatorio',
			'min'		=> ':attribute debe ser mas largo',
			'image'		=> ':attribute debe ser una imagen',
			'numeric'	=> ':attribute debe ser numerico',
			'image'		=> 'El archivo :attribute debe ser una imagen',
		);
		$customAttributes = array(
			'precio'	 	=> 'El campo precio',

			'departamento'  => 'El campo departamento',
			'title'		 	=> 'El campo titulo',
			'input' 	 	=> 'El campo descripcion',
			'img1'		 	=> 'El campo imagen de portada',
			'moneda'		=> 'El campo moneda',
			'tipoTransac'	=> 'El campo tipo de operación',
			'subCat'		=> 'El campo sub-categoria',
			'cat_id'		=> 'La categoria',

		);
		$aux = Input::get('cat_id');
		$aux = Categorias::find($aux);
		if ($aux->tipo == 1) {
			$rules = $rules+array(
				'condition' 	=> 'required',
			);
			$customAttributes = $customAttributes+array(
				'condition'		=> 'El campo condición',
			);
		}
		if ($input['cat_id'] == 34) {
			$rules = $rules+array(
				'marca' 	=> 'required',
				'modelo'	=> 'required',
				'anio'		=> 'required|numeric',
				'kilo'		=> 'required|numeric',
				'doc'		=> 'required'
			);
			$customAttributes = $customAttributes+array(
				'marca'		=> 'El campo marca',
				'modelo'	=> 'El campo modelo',
				'anio'		=> 'El campo año',
				'kilo' 		=> 'El campo kilometraje',
				'doc'		=> 'El campo documentos'
			);

		}elseif($input['cat_id'] == 20)
		{
			$rules = $rules+array(
				'ext' 	=> 'required'
			);
			$customAttributes = $customAttributes+array(
				'ext'		=> 'El campo extensión'
			);
		}

		$validator = Validator::make($input, $rules, $messages, $customAttributes);
		if ($validator->fails()) {
			return Response::json(array(
				'type' => 'danger',
				'msg'  => 'Error al validar los datos',
				'data' => $validator->getMessageBag()->toArray(),
			));
		}
		$pub 				= new Publicaciones;
		$pub->user_id 		= $id;
		$pub->titulo 		= $input['title'];
		$pub->categoria 	= $input['cat_id'];
		if(!empty($input['subCat']))
		{
			$pub->typeCat 		= $input['subCat'];
		}else
		{
			$pub->typeCat = 0;
		}
		$pub->bussiness_type = $input['negocioType'];
		if (Input::has('condition')) {
			$pub->condicion	    = $input['condition'];
		}
		$pub->departamento  = $input['departamento'];
		$pub->ciudad 		= $input['ciudad'];
		$pub->descripcion	= $input['input'];
		$pub->precio 		= $input['precio'];
		$pub->monto 		= 40;
		$pub->status  		= 'Pendiente';
		$pub->moneda 		= $input['moneda'];
		$pub->tipo 			= 'Habitual';
		$pub->transaccion	= $input['tipoTransac'];

		if (isset($input['nomb']) && !empty($input['nomb'])) {
			$pub->name 			= $input['nomb'];
		}
		if (isset($input['phone']) && !empty($input['phone'])) {
			$pub->phone 		= $input['phone'];
		}
		if (isset($input['email']) && !empty($input['email'])) {
			$pub->email 		= $input['email'];
		}
		if (isset($input['pag_web']) && !empty($input['pag_web'])) {
			$pub->pag_web_hab = $input['pag_web'];
		}
		if ($input['cat_id'] == 34) {
			$pub->marca_id		= $input['marca'];
			$pub->modelo_id  	= $input['modelo'];
			$pub->anio 			= $input['anio'];
			$pub->precio 		= $input['precio'];
			$pub->kilometraje	= $input['kilo'];
			$pub->documentos = $input['doc'];
			if (isset($input['cilin']) && !empty($input['cilin'])) {
				$pub->cilindraje = $input['cilin'];
			}
			if (isset($input['trans']) && !empty($input['trans'])) {
				$pub->transmision = $input['trans'];
			}
			if (isset($input['comb']) && !empty($input['comb'])) {
				$pub->combustible = $input['comb'];
			}
			if (isset($input['trac']) && !empty($input['trac'])) {
				$pub->traccion = $input['trac'];
			}
		}elseif($input['cat_id'] == 20)
		{
			$pub->extension     = $input['ext'];
		}
		if (Input::hasFile('img_1')) {
			$file1 = Input::file('img_1');
			$pub->img_1 = $this->upload_image($user->username,$file1);
		}
		if (Input::hasFile('img_2')) {
			$file2 = Input::file('img_2');
			$pub->img_2 = $this->upload_image($user->username,$file2);
		}
		if (Input::hasFile('img_3')) {
			$file3 = Input::file('img_3');
			$pub->img_3 = $this->upload_image($user->username,$file3);
		}
		if (Input::hasFile('img_4')) {
			$file4 = Input::file('img_4');
			$pub->img_4 = $this->upload_image($user->username,$file4);
		}
		if (Input::hasFile('img_5')) {
			$file5 = Input::file('img_5');
			$pub->img_5 = $this->upload_image($user->username,$file5);
		}
		if (Input::hasFile('img_6')) {
			$file6 = Input::file('img_6');
			$pub->img_6 = $this->upload_image($user->username,$file6);
		}
		if (Input::hasFile('img_7')) {
			$file7 = Input::file('img_7');
			$pub->img_7 = $this->upload_image($user->username,$file7);
		}
		if (Input::hasFile('img_8')) {
			$file8 = Input::file('img_8');
			$pub->img_8 = $this->upload_image($user->username,$file8);
		}
		if($pub->save())
		{
			if (Input::has('longitud') && Input::has('latitud')) {
				$lon = Input::get('longitud');
				$lat = Input::get('latitud');
				if (!empty($lon) && !empty($lat)) {
					$loc = new Location;
					$loc->latitude  = Input::get('latitud');
					$loc->longitude = Input::get('longitud');
					$loc->pub_id    = $pub->id;
					$loc->save();
				}
			}
			return Response::json(array(
				'type' 		=> 'success',
				'msg'  		=> 'Publcación creada satisfactoriamente.',
				'pub_id' 	=> $pub->id,
				'monto'	    => $pub->monto
			));
		}
	}
	public function getHabitualPreview($id)
	{
		$pub = Publicaciones::find($id);
		if ($pub->categoria == 34) {
			$pub = Publicaciones::join('marcas','marcas.id','=','publicaciones.marca_id')
			->leftJoin('departamento','publicaciones.departamento','=','departamento.id')
			->leftJoin('categoria','categoria.id','=','publicaciones.categoria')
			->leftJoin('subcategoria','subcategoria.id','=','publicaciones.typeCat')
			->join('modelo','modelo.id','=','publicaciones.modelo_id')
			->join('usuario','usuario.id','=','publicaciones.user_id')
			->where('publicaciones.id','=',$id)
			->get(array(
					'marcas.nombre as marca',
					'modelo.nombre as modelo',
					'publicaciones.*',
					'departamento.nombre as dep',
					'categoria.nombre as cat',
					'subcategoria.desc as subCat',
					));
		}else
		{
			if ($pub->typeCat != 0) {
				$pub = Publicaciones::join('departamento','publicaciones.departamento','=','departamento.id')
				->join('categoria','categoria.id','=','publicaciones.categoria')
				->join('subcategoria','subcategoria.id','=','publicaciones.typeCat')
				->where('publicaciones.id','=',$id)
				->get(array(
						'subcategoria.desc as subCat',
						'departamento.nombre as dep',
						'categoria.nombre as cat',
						'publicaciones.*'
						));
			}else
			{
				$pub = Publicaciones::join('departamento','publicaciones.departamento','=','departamento.id')
				->join('categoria','categoria.id','=','publicaciones.categoria')
				->where('publicaciones.id','=',$id)
				->get(array(
						'departamento.nombre as dep',
						'categoria.nombre as cat',
						'publicaciones.*'
						));
			}
			
		}
		
		if (isset($pub[0])) {
			$publication = $pub[0];
		}else
		{
			$publication = $pub;
		}
		return Response::json(array(
			'publication'	=> $publication,
			'id'			=> $id,
		));
	}
	public function changePosition()
	{
		$arr = Input::get('arr');
		$id  = Input::get('pub_id');
		$pub = Publicaciones::find($id);
		$i = 0;
		$arr2 = array();
		foreach ($arr as $a) {
			switch ($a) {
				case 'img_1':
					$arr2[$i] = $pub->img_1;
					break;
				case 'img_2':
					$arr2[$i] = $pub->img_2;
					break;
				case 'img_3':
					$arr2[$i] = $pub->img_3;
					break;
				case 'img_4':
					$arr2[$i] = $pub->img_4;
					break;
				case 'img_5':
					$arr2[$i] = $pub->img_5;
					break;
				case 'img_6':
					$arr2[$i] = $pub->img_6;
					break;
				case 'img_7':
					$arr2[$i] = $pub->img_7;
					break;
				case 'img_8':
					$arr2[$i] = $pub->img_8;
					break;
				default:
					break;
			}
			$i++;
		}

		$j = 0;
		foreach ($arr2 as $a) {
			switch ($j) {
				case '0':
					$pub->img_1 = $arr2[$j];
					break;
				case '1':
					$pub->img_2 = $arr2[$j];
					break;
				case '2':
					$pub->img_3 = $arr2[$j];
					break;
				case '3':
					$pub->img_4 = $arr2[$j];
					break;
				case '4':
					$pub->img_5 = $arr2[$j];
					break;
				case '5':
					$pub->img_6 = $arr2[$j];
					break;
				case '6':
					$pub->img_7 = $arr2[$j];
					break;
				case '7':
					$pub->img_8 = $arr2[$j];
					break;
				default:
					break;
			}
			$j++;
		}
		if ($pub->save()) {
			return Response::json(array('type' => 'success','msg' => 'Imagenes cambiadas de posición satisfactoriamente.'));
		}else
		{
			return Response::json(array('type' => 'danger','msg' => 'Error al guardar los cambios.'));
		}
	}
	/*---------------------------Incremento habitual-----------------------------------*/
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*---------------------------Incremento habitual-----------------------------------*/


	public function postHabitualAdd()
	{
		$pub_id 	 = Input::get('pub_id');
		$input  	 = Input::all();
		$publication = Publicaciones::find($pub_id);
		$precio 	 = Precios::all();
		$solo = $precio[0];

		if (!empty($input['durationPrin']) && !empty($input['periodoPrin']) && !empty($input['durationCat']) && !empty($input['periodoCat'])) {

			if ($input['periodoPrin'] == $precio[0]->precio) {
				$publication->duracion	= ($input['durationPrin']*86400);
				$publication->monto = ($input['durationPrin']*$precio[0]->precio)+$publication->monto;
			}elseif($input['periodoPrin'] == $precio[1]->precio)
			{
				$publication->duracion	= ($input['durationPrin']*604800);
				$publication->monto 	= ($input['durationPrin']*$precio[1]->precio)+$publication->monto;
			}elseif($input['periodoPrin'] == $precio[2]->precio)
			{
				$publication->duracion	= ($input['durationPrin']*2629744);
				$publication->monto 	= ($input['durationPrin']*$precio[2]->precio)+$publication->monto;
			}

			if ($input['periodoCat'] == $precio[3]->precio) {
				$publication->duracionNormal	= ($input['durationCat']*86400)+6048000;
				$publication->monto 	= ($input['durationCat']*$precio[3]->precio)+$publication->monto;
			}elseif($input['periodoCat'] == $precio[4]->precio)
			{
				$publication->duracionNormal	= ($input['durationCat']*604800)+6048000;
				$publication->monto 	= ($input['durationCat']*$precio[4]->precio)+$publication->monto;
			}elseif($input['periodoCat'] == $precio[5]->precio)
			{
				$publication->duracionNormal	= ($input['durationCat']*2629744)+6048000;
				$publication->monto 	= ($input['durationCat']*$precio[5]->precio)+$publication->monto;
			}
			$publication->ubicacion = "Ambos";

		}elseif(!empty($input['durationPrin']) && !empty($input['periodoPrin']))
		{
			if ($input['periodoPrin'] == $precio[0]->precio) {

				$publication->duracion	= ($input['durationPrin']*86400);
				$publication->monto = ($input['durationPrin']*$precio[0]->precio)+$publication->monto;

			}elseif($input['periodoPrin'] == $precio[1]->precio)
			{

				$publication->duracion	= ($input['durationPrin']*604800);
				$publication->monto 	= ($input['durationPrin']*$precio[1]->precio)+$publication->monto;

			}elseif($input['periodoPrin'] == $precio[2]->precio)
			{

				$publication->duracion	= ($input['durationPrin']*2629744);
				$publication->monto 	= ($input['durationPrin']*$precio[2]->precio)+$publication->monto;

			}
			$publication->ubicacion = 'Principal';
		}elseif(!empty($input['durationCat']) && !empty($input['periodoCat']))
		{
			if ($input['periodoCat'] == $precio[3]->precio) {

				$publication->duracionNormal	= ($input['durationCat']*86400)+6048000;
				$publication->monto 	= ($input['durationCat']*$precio[3]->precio)+$publication->monto;

			}elseif($input['periodoCat'] == $precio[4]->precio)
			{

				$publication->duracionNormal	= ($input['durationCat']*604800)+6048000;
				$publication->monto 	= ($input['durationCat']*$precio[4]->precio)+$publication->monto;

			}elseif($input['periodoCat'] == $precio[5]->precio)
			{

				$publication->duracionNormal	= ($input['durationCat']*2629744)+6048000;
				$publication->monto 	= ($input['durationCat']*$precio[5]->precio)+$publication->monto;

			}
			$publication->ubicacion = 'Categoria';

		}else
		{
			$publication->duracionNormal = 6048000;
			$publication->ubicacion = 'Categoria';
		}
				
		$publication->save();
		return Response::json(array(
			'type' => 'success',
			'msg'  => 'Se ha guardado su publicación.',
			'pub_id' => $pub_id,
		));
	}
	/*---------------------------Casual -----------------------------------------------*/
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*---------------------------Casual -----------------------------------------------*/
	public function postCasual()
	{
		$id = Input::get('id');
		$input = Input::all();
		/*$pub = Publicaciones::where('user_id','=',$id)
		->where('tipo','=','Casual')
		->where('fechRepub','>',date('Y-m-d',time()))
		->orderBy('fechRepub','desc')
		->first();
		if (count($pub)>0 && $id != 21) {
			
			return Response::json(array(
				'type' => 'error', 
				'msg'  => 'Usted ha consumido el máximo de publicaciones casuales. Inténtelo nuevamente cuando su última publicación casual expire.'
			));
		}*/
		if (strlen(strip_tags($input['input']))>400) {
			return Response::json(array(
				'type' => 'danger',
				'msg'  => 'La descripción debe tener maximo 400 caracteres',
			));
		}
		$rules = array(
			'input' 		=> 'required',
			'precio'		=> 'required|numeric',
			'moneda'		=> 'required',
			'departamento' 	=> 'required',
			'categoria'		=> 'required',
			'titulo'		=> 'required',
			'negocioType' 	=> 'required',
			'img_1'			=> 'required|image|max:3000',
		);
		$messages = array(
			'required' => ':attribute es requerido',
			'numeric'  => ':attribute debe ser numerico',
			'image'	   => 'El archivo :attribute debe ser una imagen',
		);
		$customAttributes = array(
			'input' 		=> 'El campo descripcion',
			'precio'		=> 'El precio',
			'moneda'		=> 'El campo moneda',
			'departamento' 	=> 'El campo departamento',
			'categoria'		=> 'El campo categoria',
			'titulo'		=> 'El campo titulo',
			'negocioType' => 'El campo clase de negocio',
			'img_1'			=> 'Imagen principal'
		);
		
		$validator = Validator::make($input, $rules, $messages, $customAttributes);
		if ($validator->fails()) {
			return Response::json(array(
				'type' => 'danger',
				'msg'  => 'Error al validar los datos',
				'data' => $validator->getMessageBag()->toArray()
			));
		}
		$pub = new Publicaciones;
		$pub->user_id 	  = $id;
		$pub->tipo 		  = 'Casual';
		$pub->titulo      = $input['titulo'];
		$pub->departamento= $input['departamento'];
		$pub->categoria   = $input['categoria'];
		$pub->ubicacion   = 'Principal';
		$pub->precio 	  = $input['precio'];
		$pub->moneda 	  = $input['moneda'];
		$pub->descripcion = $input['input'];
		$pub->fechIni 	  = date('Y-m-d',time());
		$pub->fechFin	  = date('Y-m-d',time()+604800);
		$pub->fechRepub	  = date('Y-m-d',time()+2543400);
		$pub->bussiness_type = $input['negocioType'];
		$pub->status 	  = 'Procesando';
		$user = User::find($id);
		if (Input::hasFile('img_1')) {
			$file1 = Input::file('img_1');
			$pub->img_1 = $this->upload_image($user->username,$file1);
		}
		if (Input::hasFile('img_2')) {
			$file2 = Input::file('img_2');
			$pub->img_2 = $this->upload_image($user->username,$file2);
		}
		$pub->save();
		if (Input::has('longitud') && Input::has('latitud')) {
			$lon = Input::get('longitud');
			$lat = Input::get('latitud');
			if (!empty($lon) && !empty($lat)) {
				$loc = new Location;
				$loc->latitude  = Input::get('latitud');
				$loc->longitude = Input::get('longitud');
				$loc->pub_id    = $pub->id;
				$loc->save();
			}
		}
		return Response::json(array(
			'type' => 'success',
			'msg'  => 'publicación creada satisfactoriamente.',
			'pub_id' => $pub->id,
		));
	}
	/*----------------------------Reactivar--------------------------------------------*/
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*----------------------------Reactivar--------------------------------------------*/
	public function postReactivate($id)
	{
		if(is_null($id))
		{
			return Response::json(array(
				'type' => 'danger',
				'msg'  => 'Id de la publicación no encontrado'
			));
		}
		$pub = Publicaciones::find($id);
		if($pub->tipo == 'Lider')
		{
			$data = Input::all();
			$rules = array(
				'duration' => 'required|in:d,s,m',
				'time'     => 'required|integer|min:1',
				'ubication'=> 'required|in:Principal,Categoria',
				'cat'	   => 'required_if:ubication,Categoria',
				'fechIni'  => 'required|date|after:'.date('d-m-Y',time()).'|date_format:d-m-Y',
			);
			$msg = array();
			$attr = array(
				'duration' => 'duración',
				'time'	   => 'período',
				'fechIni'  => 'fecha de inicio',
			);
			$validator = Validator::make($data, $rules, $msg, $attr);
			if ($validator->fails()) {
				return Response::json(array(
					'type' => 'danger',
					'msg'  => 'Error, datos invalidos',
					'data' => $validator->getMessageBag()
				));
			}
			/* Validar duracion y montos */
			$dur = $data['time'];
			$monto = 0;
			if ($data['duration'] == 'd') {
				
				$time = 86400;
				$prices = Precios::find(1);
			}elseif($data['duration'] == 's')
			{
				$time = 604800;
				$prices = Precios::find(2);
			}elseif($data['duration'] == 'm')
			{
				$time = 2629744;
				$prices = Precios::find(3);
			}
			$monto = $prices->precio*$dur;
			/* Segundo validador de fecha */
			$fecha = explode('-', $data['fechIni']);
			$timestamp = strtotime($data['fechIni'])+($time*$data['duration']);
			$fechaFin = date('d-m-Y',$timestamp);

			$timestamp = $data['duration']*$time;
			$date  = date('d-m-Y');
			$timestamp = strtotime($data['fechIni'])+$timestamp;
			$fechFin = date('Y-m-d',$timestamp);
			$pub = Publicaciones::find($id);
			$pub->ubicacion = $data['ubication'];
			if ($data['ubication'] == 'Categoria') {
				$pub->categoria = $data['cat'];
			}
			$pub->monto = $monto;
			$pub->fechIni   = date('Y-m-d',strtotime($data['fechIni']));
			$pub->fechFin   = $fechFin;
			$pub->status    = 'Pendiente';
			$pub->save();
		}else
		{
			$input = Input::all();
			$precio = Precios::all();
			$solo = $precio[0];

			if (!empty($input['durationPrin']) && !empty($input['periodoPrin']) && !empty($input['durationCat']) && !empty($input['periodoCat'])) {

				if ($input['periodoPrin'] == $precio[0]->precio) {
					$pub->duracion	= ($input['durationPrin']*86400);
					$pub->monto = ($input['durationPrin']*$precio[0]->precio)+$pub->monto;
				}elseif($input['periodoPrin'] == $precio[1]->precio)
				{
					$pub->duracion	= ($input['durationPrin']*604800);
					$pub->monto 	= ($input['durationPrin']*$precio[1]->precio)+$pub->monto;
				}elseif($input['periodoPrin'] == $precio[2]->precio)
				{
					$pub->duracion	= ($input['durationPrin']*2629744);
					$pub->monto 	= ($input['durationPrin']*$precio[2]->precio)+$pub->monto;
				}

				if ($input['periodoCat'] == $precio[3]->precio) {
					$pub->duracionNormal	= ($input['durationCat']*86400)+6048000;
					$pub->monto 	= ($input['durationCat']*$precio[3]->precio)+$pub->monto;
				}elseif($input['periodoCat'] == $precio[4]->precio)
				{
					$pub->duracionNormal	= ($input['durationCat']*604800)+6048000;
					$pub->monto 	= ($input['durationCat']*$precio[4]->precio)+$pub->monto;
				}elseif($input['periodoCat'] == $precio[5]->precio)
				{
					$pub->duracionNormal	= ($input['durationCat']*2629744)+6048000;
					$pub->monto 	= ($input['durationCat']*$precio[5]->precio)+$pub->monto;
				}
				$pub->ubicacion = "Ambos";

			}elseif(!empty($input['durationPrin']) && !empty($input['periodoPrin']))
			{
				if ($input['periodoPrin'] == $precio[0]->precio) {

					$pub->duracion	= ($input['durationPrin']*86400);
					$pub->monto = ($input['durationPrin']*$precio[0]->precio)+$pub->monto;

				}elseif($input['periodoPrin'] == $precio[1]->precio)
				{

					$pub->duracion	= ($input['durationPrin']*604800);
					$pub->monto 	= ($input['durationPrin']*$precio[1]->precio)+$pub->monto;

				}elseif($input['periodoPrin'] == $precio[2]->precio)
				{

					$pub->duracion	= ($input['durationPrin']*2629744);
					$pub->monto 	= ($input['durationPrin']*$precio[2]->precio)+$pub->monto;

				}
				$pub->ubicacion = 'Principal';
			}elseif(!empty($input['durationCat']) && !empty($input['periodoCat']))
			{
				if ($input['periodoCat'] == $precio[3]->precio) {

					$pub->duracionNormal	= ($input['durationCat']*86400)+6048000;
					$pub->monto 	= ($input['durationCat']*$precio[3]->precio)+$pub->monto;

				}elseif($input['periodoCat'] == $precio[4]->precio)
				{

					$pub->duracionNormal	= ($input['durationCat']*604800)+6048000;
					$pub->monto 	= ($input['durationCat']*$precio[4]->precio)+$pub->monto;

				}elseif($input['periodoCat'] == $precio[5]->precio)
				{

					$pub->duracionNormal	= ($input['durationCat']*2629744)+6048000;
					$pub->monto 	= ($input['durationCat']*$precio[5]->precio)+$pub->monto;

				}
				$pub->ubicacion = 'Categoria';

			}else
			{
				$pub->duracionNormal = 6048000;
				$pub->ubicacion = 'Categoria';
			}
					
			$pub->save();
		}
		$obj = new StdClass;
		$obj->pub_id = $pub->id;
		$obj->monto  = $pub->monto;

		return Response::json(array(
			'type'  => 'success',
			'msg'	=> 'Datos enviados sactisfactoriamente',
			'data'  => $obj
		));
	}
	public function postElimPub()
	{
		$id     = Input::get('id');
		$pub_id = Input::get('pub_id');
		$pub = Publicaciones::find($pub_id);
		$titulo = $pub->titulo;
		$comment = Comentarios::where('pub_id','=',$id)->delete();
		$resp    = Respuestas::where('pub_id','=',$id)->delete();
		$user = User::find($id);
		$subject = "Correo de Aviso";
		if (!empty($pub->img_1)) {
			File::delete('images/pubImages/'.$user->id.'/'.$pub->img_1);
		}
		if (!empty($pub->img_2)) {
			File::delete('images/pubImages/'.$user->id.'/'.$pub->img_2);
		}
		if (!empty($pub->img_3)) {
			File::delete('images/pubImages/'.$user->id.'/'.$pub->img_3);
		}
		if (!empty($pub->img_4)) {
			File::delete('images/pubImages/'.$user->id.'/'.$pub->img_4);
		}
		if (!empty($pub->img_5)) {
			File::delete('images/pubImages/'.$user->id.'/'.$pub->img_5);
		}
		if (!empty($pub->img_6)) {
			File::delete('images/pubImages/'.$user->id.'/'.$pub->img_6);
		}
		if (!empty($pub->img_7)) {
			File::delete('images/pubImages/'.$user->id.'/'.$pub->img_7);
		}
		if (!empty($pub->img_8)) {
			File::delete('images/pubImages/'.$user->id.'/'.$pub->img_8);
		}
		$pub->delete();
		$data = array(
			'subject' => $subject,
			'publicacion' => $titulo,
			'motivo'	  => 'Eliminado por el usuario'
		);
		$to_Email = $user->email;
		Mail::send('emails.elimPubUser', $data, function($message) use ($titulo,$to_Email,$subject)
		{
			$message->to($to_Email)->from('pasillo24.com@pasillo24.com')->subject($subject);
		});


		return Response::json(array(
			'type' => 'success',
			'msg'  => 'Publicación eliminada satisfactoriamente. Hemos enviado un email al correo.'
		));
	}
	/*---------------------------Pago total--------------------------------------------*/
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*---------------------------Pago total--------------------------------------------*/
	public function postPublicationPayment(){
		$id 	= Input::get('id');
		if (!Input::has('pub_id')) {
			return Response::json(array(
				'type' => 'danger',
				'msg'  => 'No se encontro el id de la publicación.',
			));
		}
		$input 	= Input::all();
		$rules 	= array(
			'transNumber' => 'required',
			'fecha'		  => 'required|date|date_format:Y-m-d',
			'banco'		  => 'required'
		);
		$messages = array(
			'required' => 'El campo es obligatorio.'
		);
		$validator = Validator::make($input, $rules, $messages);
		if ($validator->fails()) {
			return Response::json(
				array(
					'type' => 'danger',
					'msg'  => 'Error al validar los campos',
					'data' => $validator->getMessageBag()->toArray(),
				)
			);
		}
		$pub_id = Input::get('pub_id');
		$pago = new Pagos;
		$pago->user_id   = $id;
		$pago->pub_id    = $pub_id;
		$pago->num_trans = $input['transNumber'];
		$pago->banco_id  = $input['banco'];
		if(isset($input['emisor']) && !empty($input['emisor']))
		{
				$pago->banco_ext = $input['emisor'];
		}

		$pago->fech_trans= $input['fecha'];

		if ($pago->save()) {
			$publicacion = Publicaciones::find($pub_id);
			$monto = $publicacion->monto;
			$moneda = $publicacion->moneda;
			$publicacion->status = "Procesando";
			$publicacion->save();
			$user = User::find($id);
			$subject = "Correo de administrador";
			$data = array(
				'subject' => $subject,
				'createBy'=> $user->username,
				'monto'   => $monto,
				'moneda'  => $moneda,
				'num_trans' => $input['transNumber']
			);
			$to_Email = 'gestor@pasillo24.com';
			Mail::send('emails.newPost', $data, function($message) use ($input,$to_Email,$subject)
			{
				$message->to($to_Email)->from('pasillo24.com@pasillo24.com')->subject($subject);
			});
			return Response::json(array(
				'type' => 'success',
				'msg'  => 'Información enviada, pronto procesaremos su pago'
			));
		}else
		{
			return Response::json(array(
				'type' => 'danger',
				'msg'  => 'Error al guardar el pago'
			));
		}
	}
	/*---------------------------Mis publicaciones  -----------------------------------*/
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*---------------------------Mis publicaciones  -----------------------------------*/
	public function getMyPublicationsType($type)
	{
		$id = Input::get('id');
		if (strtolower($type) == "lider") {
			$publications = Publicaciones::where('user_id','=',$id)
			->leftJoin('categoria','categoria.id','=','publicaciones.categoria')
			->leftJoin('departamento','departamento.id','=','publicaciones.departamento')
			->where('publicaciones.tipo','=',ucfirst(strtolower($type)))
			->where('publicaciones.deleted','=',0)
			->get(array('publicaciones.*','categoria.nombre as categoria','departamento.nombre as dep_desc'));	
		}elseif (strtolower($type) == "habitual") {
			$publications = Publicaciones::join('categoria','categoria.id','=','publicaciones.categoria')
			->leftJoin('departamento','departamento.id','=','publicaciones.departamento')
			->where('user_id','=',$id)
			->where('publicaciones.tipo','=','Habitual')
			->where('publicaciones.deleted','=',0)
			->get(array('publicaciones.*','categoria.nombre as categoria','departamento.nombre as dep_desc'));	
		}elseif(strtolower($type) == "casual")
		{
			$publications = Publicaciones::join('categoria','categoria.id','=','publicaciones.categoria')
			->leftJoin('departamento','departamento.id','=','publicaciones.departamento')
			->where('publicaciones.user_id','=',$id)
			->where('publicaciones.tipo','=','Casual')
			->where('publicaciones.deleted','=',0)
			->get(array(
				'publicaciones.*',
				'categoria.nombre as categoria'
			));
			$rePub = Publicaciones::where('publicaciones.user_id','=',$id)
			->where('publicaciones.tipo','=','Casual')
			->where('publicaciones.deleted','=',0)
			->orderBy('fechRepub','desc')
			->first(array('fechRepub'));

		}
		if (strtolower($type) == "casual") {
			return Response::json(array(
				'publications' 	=> $publications,
				'type' 			=> strtolower($type),
				'rePub'	 		=> $rePub,
			));
		}
		return Response::json(array(
			'publications'  => $publications,
			'type' 			=> strtolower($type),
		));
	}

	/*---------------------------Reputacion--------------------------------------------*/
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*---------------------------Reputacion--------------------------------------------*/
	public function getMyCart()
	{
		$id = Input::get('id');
		$compras = Compras::with('users')
		->with(array('publicacion' => function($query){
			$query->with('deparments');
		}))
		->where('compras.user_id','=',$id)
		->where('compras.valor_vend','=',0)
		->get();

		return Response::json(array(
			'compras' 	=>$compras,

		));
	}
	public function getMySell()
	{
		$id = Input::get('id');
		$compras = Compras::with(array('publicacion' => function($query){
			$query->with('deparments')->with('users');
		}))
		->whereHas('publicacion',function($query) use($id){
			$query->where('user_id','=',$id);
		})
		->where('compras.valor_comp','=',0)
		->get();
		$hoy = date('Y-m-d');
		return Response::json(array(
			'compras' 	=> $compras,
			'hoy' 		=> $hoy,
		));
	}
	public function postValorVend()
	{
		$id = Input::get('id');
		$compra_id   = Input::get('compra_id');
		$tipo = Input::get('tipo');
		$pub = Compras::find($compra_id);
		if (is_null($pub) || empty($pub)) {
			return Response::json(array('type' => 'danger','msg' => 'Error al valorar la publicación, no se encontro la venta'));	
		}

		$valor = 0;
		if($tipo != "pos" && $tipo != 'neg' && $tipo != 'neutro')
		{
			return Response::json(array('type' => 'danger','msg' => 'Error al valorar la publicación, valor invalido'));	
		}else
		{
			if ($tipo == 'pos') {
				$valor = 1;
				$valMsg = "te han valorado positivamente.";
			}elseif ($tipo == 'neg') {
				$valor = -1;
				$valMsg = "te han valorado negativamente.";
			}elseif($tipo == 'neutro')
			{
				$valor = 0;
				$valMsg = "te han valorado neutralmente.";
			}
		}
		$comp = Compras::find($compra_id);
		$user = User::where('id','=',$id)->first();
		$user->reputation = $user->reputation + $valor;
		if (!$user->save()) {
			return Response::json(array('type' => 'danger','msg' => 'Error al valorar la publicación'));	
		}
		$comp->valor_vend = $valor;
		if ($comp->save()) {
			$msg = 'El usuario '.$user->name.' '+$user->lastname.' '.$valMsg;
			$publication = Publicaciones::find($comp->pub_id);
			$user = User::with('gcmdevices')->find($publication->user_id);
			foreach($user->gcmdevices as $gcm) {
				$regId = $gcm->gcm_regid;
				$data = [
					"type"		=> "rating",
					"pub_id"	=> $comp->pub_id,
					"message"	=> $msg,
					"title"		=> "Te han valorado como vendedor",
				];
				$doGcm = new Gcm;
				$response = $doGcm->send_notification($data,$regId);
			}
			return Response::json(array('type' => 'success','msg' => 'Publicación valorada correctamente.'));
		}else
		{
			return Response::json(array('type' => 'danger','msg' => 'Error al valorar la publicación'));
		}
	}
	public function postValorComp()
	{
		$id = Input::get('id');
		$venta_id   = Input::get('venta_id');
		$tipo = Input::get('tipo');
		if (is_null($pub) || empty($pub)) {
			return Response::json(array('type' => 'danger','msg' => 'Error al valorar la publicación, no se encontro la compra'));	
		}
		$valor = 0;
		if($tipo != "pos" && $tipo != 'neg' && $tipo != 'neutro')
		{
			return Response::json(array('type' => 'danger','msg' => 'Error al valorar la publicación, valor invalido'));	
		}else
		{
			if ($tipo == 'pos') {
				$valor = 1;
				$valMsg = "te han valorado positivamente.";
			}elseif ($tipo == 'neg') {
				$valor = -1;
				$valMsg = "te han valorado negativamente.";
			}elseif($tipo == 'neutro')
			{
				$valor = 0;
				$valMsg = "te han valorado neutralmente.";
			}
		}
		$comp = Compras::find($venta_id);
		$user = User::find($id);
		$user->reputation = $user->reputation + $valor;
		if (!$user->save()) {
			return Response::json(array('type' => 'danger','msg' => 'Error al valorar la publicación'));	
		}
		$comp->valor_comp = $valor;
		if ($comp->save()) {
			$msg = 'El usuario '.$user->name.' '+$user->lastname.' '.$valMsg;
			$user = User::with('gcmdevices')->find($comp->user_id);
			foreach($user->gcmdevices as $gcm) {
				$regId = $gcm->gcm_regid;
				$data = array(
					"type"		=> "rating",
					"pub_id"	=> $comp->pub_id,
					"message"	=> $msg,
					"title"		=> "Te han valorado como vendedor",
				);
				$doGcm = new Gcm;
				$response = $doGcm->send_notification($data,$regId);
			}
			return Response::json(array('type' => 'success','msg' => 'Publicación valorada correctamente.'));
		}else
		{
			return Response::json(array('type' => 'danger','msg' => 'Error al valorar la publicación'));
		}
	}
	public function getMyReputation()
	{
		$id = Input::get('id');
		$compras = Compras::join('publicaciones','publicaciones.id','=','compras.pub_id')
		->join('usuario','usuario.id','=','publicaciones.user_id')
		->where('compras.user_id','=',$id)
		->get(array(
			'compras.id',
			'compras.valor_vend',
			'compras.valor_comp',
			'publicaciones.titulo',
			'publicaciones.id as pub_id',
			'publicaciones.name as name_pub',
			'usuario.name',
			'usuario.lastname'
		));
		$ventas = Compras::join('publicaciones','publicaciones.id','=','compras.pub_id')
		->join('usuario','usuario.id','=','compras.user_id')
		->where('publicaciones.user_id','=',$id)
		->get(array(
			'compras.id',
			'compras.valor_vend',
			'compras.valor_comp',
			'publicaciones.titulo',
			'usuario.id as user_id',
			'usuario.name',
			'usuario.lastname'
		));
		$comprasC = Compras::join('publicaciones','publicaciones.id','=','compras.pub_id')
		->join('usuario','usuario.id','=','publicaciones.user_id')
		->where('compras.user_id','=',$id)
		->get(array(
			'compras.id',
			'compras.valor_vend',
			'compras.valor_comp',
			'publicaciones.titulo',
			'publicaciones.id as pub_id',
			'usuario.name',
			'usuario.lastname'
		));
		$ventasC = Compras::join('publicaciones','publicaciones.id','=','compras.pub_id')
		->join('usuario','usuario.id','=','compras.user_id')
		->where('publicaciones.user_id','=',$id)
		->get(array(
			'compras.id',
			'compras.valor_vend',
			'compras.valor_comp',
			'publicaciones.titulo',
			'usuario.id as user_id',
			'usuario.name',
			'usuario.lastname'
		));
		$comp_pos = 0;
		$comp_neg = 0;
		$vent_pos = 0;
		$vent_neg = 0;
		foreach ($ventasC as $v) {
			if ($v->valor_vend>0) {
				$vent_pos++; 
			}elseif ($v->valor_vend < 0)
			{
				$vent_neg++;
			}
			
		}
		foreach ($comprasC as $c) {
			if ($c->valor_comp > 0) {
				$comp_pos++;
			}elseif($c->valor_comp < 0)
			{
				$comp_neg++;
			}
		}
		return Response::json(array(
		'compras'	=> $compras,
		'ventas' 	=> $ventas,
		'vend_pos' 	=> $vent_pos,
		'vend_neg' 	=> $vent_neg,
		'comp_pos' 	=> $comp_pos,
		'comp_neg' 	=> $comp_neg,

		));
	}

	/*---------------------------Comentarios-------------------------------------------*/
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*---------------------------Comentarios-------------------------------------------*/
	public function getMyComment()
	{
		$id = Input::get('id');
		$comment = Comentarios::leftJoin('publicaciones','publicaciones.id','=','comentario.pub_id')
		->where('publicaciones.user_id','=',$id)
		->where('comentario.is_read','=',0)
		->where('publicaciones.deleted','=',0)
		->where('comentario.deleted','=',0)
		->update(array('comentario.is_read' => 1));


		$responses = Respuestas::where('user_id','=',$id)
		->where('is_read','=',0)
		->where('deleted','=',0)
		->update(array('is_read' => 1));
		$recividos = Comentarios::leftJoin('publicaciones','publicaciones.id','=','comentario.pub_id')
		->where('publicaciones.user_id','=',$id)
		->where('comentario.respondido','=',0)
		->where('comentario.deleted','=',0)
		->get(array(
			'publicaciones.titulo',
			'comentario.id',
			'comentario.pub_id',
			'comentario.comentario',
			'comentario.created_at',
			'comentario.deleted'
		));
		
		$hechos 	= Comentarios::leftJoin('publicaciones','publicaciones.id','=','comentario.pub_id')
		->leftJoin('respuestas','respuestas.comentario_id','=','comentario.id')
		->where('comentario.user_id','=',$id)
		->where('comentario.deleted','=',0)
		->get(array(
			'publicaciones.titulo',
			'comentario.id',
			'comentario.comentario',
			'comentario.created_at',
			'comentario.deleted',
			'respuestas.respuesta'
		));
		return Response::json(array(
			'hechos' 	=> $hechos,
			'recividos' => $recividos,
		));
	}
	/*---------------------------Modificar pub ----------------------------------------*/
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*---------------------------Modificar pub ----------------------------------------*/
	public function postModifyPub($type)
	{
		$data = Input::all();
		if (!Input::has('pub_id')) {
			return Response::json(array(
				'type' => 'danger',
				'msg'  => 'Error, no se encontro la publicación'
			));
		}
		$id = $data['pub_id'];
		$pub = Publicaciones::find($id);
		if ($pub->tipo == 'Lider') {
			$rules = array(
				'title' 	=> 'required|min:4|max:100',
				'cat'		=> 'exists:categoria,id',
				'url'		=> 'url',
				'img1'		=> 'image|max:3000',
				'img2'		=> 'image|max:3000', 
				'name'		=> 'min:4|max:50',
				'phone' 	=> 'min:7|max:15',
				'email'		=> 'email',
				'pag_web'	=> 'url'
			);
			$msg = array();
			$attr = array(
				'title' 	=> 'titulo',
				'cat'		=> 'categoría',
				'url'		=> 'URL',
				'img1'		=> 'imagen principal',
				'img2'		=> 'imagen secundaria',
				'name'		=> 'nombre',
				'phone'		=> 'telefono',
				'email'		=> 'email',
				'pag_web' 	=> 'pagina web'
			);
			$validator = Validator::make($data, $rules, $msg, $attr);
			if ($validator->fails()) {
				return Response::json(array(
					'type' => 'danger',
					'msg'  => 'Error, datos invalidos',
					'data' => $validator->getMessageBag()
				));
			}
			if ($pub->ubicacion != "Principal" && isset($data['cat'])) {
				$pub->categoria = $data['cat'];
			}
			if (Input::hasFile('img1')) {
				$img1 = Input::file('img1');
				$user = User::find($pub->user_id);
				$pub->img_1 = $this->upload_images($img1,$user);
			}
			if (Input::hasFile('img2')) {
				$img2 = Input::file('img2');
				$user = User::find($pub->user_id);
				$pub->img_2 = $this->upload_images($img2,$user);
			}
			if ($data['title'] != $pub->pag_web && !empty($data['title'])) {
				$pub->titulo = $data['title'];
			}
			if ($data['url'] != $pub->pag_web && !empty($data['url'])) {
				$pub->pag_web = $data['url'];
			}
			if ($data['name'] != $pub->name && !empty($data['name'])) {
				$pub->name = $data['name'];
			}
			if ($data['phone'] != $pub->phone && !empty($data['phone'])) {
				$pub->phone = $data['phone'];
			}
			if ($data['email'] != $pub->email && !empty($data['email'])) {
				$pub->email = $data['email'];
			}
			if ($data['pag_web'] != $pub->pag_web_hab && !empty($data['pag_web'])) {
				$pub->pag_web_hab = $data['pag_web'];
			}
			if (!empty($data['namePub'])) {
				$pub->titulo = $data['namePub'];
			}
			
		}elseif($pub->tipo == 'Habitual')
		{
			$rules = array(
				'title' 		=> 'required|min:4|max:100',
				'cat'			=> 'required|exists:categoria,id',
				'subcat'		=> 'exists:subcategoria,id',
				'price'			=> 'required|numeric|integer|min:1',
				'currency'		=> 'required|in:Usd,Bs',
				'department'	=> 'required|exists:departamento,id',
				'city'			=> 'required|max:50',
				'brand'			=> 'required_if:cat,34|exists:marcas,id',
				'models'		=> 'required_if:cat,34|exists:modelo,id',
				'year'			=> 'required_if:cat,34|numeric|min:1885|max:'.date('Y'),
				'document'		=> 'required_if:cat,34|max:100',
				'kilo'			=> 'required_if:cat,34|min:0|max:999999',
				'tipoTransac'	=> 'required|in:venta,alquiler,Aticretico,otro',
				'description'	=> 'required',
				'negocioType'   => 'required|in:fiscal,virtual,independiente,otro',
				'img1'			=> 'image|max:3000',
				'img2'			=> 'image|max:3000', 
				'img3'			=> 'image|max:3000', 
				'img4'			=> 'image|max:3000', 
				'img5'			=> 'image|max:3000', 
				'img6'			=> 'image|max:3000', 
				'img7'			=> 'image|max:3000', 
				'img8'			=> 'image|max:3000', 
				'name'			=> 'min:4|max:50',
				'phone' 		=> 'min:7|max:15',
				'email'			=> 'email',
				'pag_web'		=> 'url'
			);
			$msg = array();
			$attr = array(
				'title' 		=> 'titulo',
				'cat'			=> 'categoría',
				'subcat'		=> 'sub-categoria',
				'price'			=> 'precio',
				'currency'		=> 'moneda',
				'department'	=> 'departamento',
				'city'			=> 'ciudad',
				'brand'			=> 'marca',
				'models'		=> 'modelo',
				'year'			=> 'año',
				'document'		=> 'documentos',
				'kilo'			=> 'kilometraje',
				'tipoTransac' 	=> 'tipo de transacción',
				'description' 	=> 'descripción',
				'img1'			=> 'imagen principal',
				'img2'			=> 'imagen adicional 1', 
				'img3'			=> 'imagen adicional 2', 
				'img4'			=> 'imagen adicional 3', 
				'img5'			=> 'imagen adicional 4', 
				'img6'			=> 'imagen adicional 5', 
				'img7'			=> 'imagen adicional 6', 
				'img8'			=> 'imagen adicional 7', 
				'name'			=> 'nombre',
				'phone'			=> 'telefono',
				'email'			=> 'email',
				'pag_web' 		=> 'pagina web'
			);
			$validator = Validator::make($data, $rules, $msg, $attr);
			if ($validator->fails()) {
				return Response::json(array(
					'type' => 'danger',
					'msg'  => 'Error, datos invalidos',
					'data' => $validator->getMessageBag()
				));
			}
			$pub->titulo 		= $data['title'];
			$pub->categoria 	= $data['cat'];
			$pub->typeCat		= $data['subcat'];
			$pub->precio 		= $data['price'];
			$pub->moneda 		= strtolower($data['currency']);
			$pub->departamento 	= $data['department'];
			$pub->ciudad 		= $data['city'];
			
			$pub->transaccion 	= $data['tipoTransac'];
			$pub->descripcion 	= $data['description'];
			$user = User::find($pub->user_id);
			if (Input::hasFile('img1')) {
				$img1 = Input::file('img1');
				$pub->img_1 = $this->upload_images($img1, $user);
			}
			if (Input::hasFile('img2')) {
				$img2 = Input::file('img2');
				$pub->img_2 = $this->upload_images($img2, $user);
			}
			if (Input::hasFile('img3')) {
				$img3 = Input::file('img3');
				$pub->img_3 = $this->upload_images($img3, $user);
			}
			if (Input::hasFile('img4')) {
				$img4 = Input::file('img4');
				$pub->img_4 = $this->upload_images($img4, $user);
			}
			if (Input::hasFile('img5')) {
				$img5 = Input::file('img5');
				$pub->img_5 = $this->upload_images($img5, $user);
			}
			if (Input::hasFile('img6')) {
				$img6 = Input::file('img6');
				$pub->img_6 = $this->upload_images($img6, $user);
			}
			if (Input::hasFile('img7')) {
				$img7 = Input::file('img7');
				$pub->img_7 = $this->upload_images($img7, $user);
			}
			if (Input::hasFile('img8')) {
				$img8 = Input::file('img8');
				$pub->img_8 = $this->upload_images($img8, $user);
			}
			if (Input::has('pagina')) {
				$pub->pag_web = $data['pagina'];
			}
			if (Input::has('name')) {
				$pub->name = $data['name'];
			}
			if (Input::has('phone')) {
				$pub->phone = $data['phone'];
			}
			if (Input::has('email')) {
				$pub->email = $data['email'];
			}
			if (Input::has('pagina')) {
				$pub->pag_web_hab = $data['pagina'];
			}
			if ($pub->categoria == 34) {
				$pub->marca_id 		= $data['brand'];
				$pub->modelo_id 	= $data['models'];
				$pub->anio 			= $data['year'];
				$pub->documentos 	= $data['document'];
				$pub->kilometraje 	= $data['kilo'];
				if (Input::has('cilin')) {
					$pub->cilindraje = $input['cilin'];
				}
				if (Input::has('trans')) {
					$pub->transmision = $input['trans'];
				}
				if (Input::has('comb')) {
					$pub->combustible = $input['comb'];
				}
				if (Input::has('trac')) {
					$pub->traccion = $input['trac'];
				}
			}elseif($pub->categoria == 20)
			{
				if (Input::has('ext')) {
					$pub->extension = $input['ext'];
				}
			}
			
			if (!empty($input['input'])) {
				$pub->descripcion = $input['input'];
			}
		}
		
		if($pub->save())
		{
			return Response::json(array(
				'type' => 'success', 
				'msg'  => 'Publicación modificada satisfactoriamente.'
			));
			return Redirect::back();
		}else
		{
			return Response::json(array(
				'type' => 'danger', 
				'msg'  => 'Error al modificar el usuario.'
			));
			return Redirect::back();
		}
	}
	/*---------------------------Comentarios-------------------------------------------*/
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*---------------------------Comentarios-------------------------------------------*/
	public function postComment(){
		$id = Input::get('id');
		$pub_id = Input::get('pub_id');
		if (!Input::has('pub_id')) {
			return Response::json(array(
				'type' => 'danger', 
				'msg' => 'No se encontro el id de la publicación.',
			));
		}
		$comentario = Input::get('comment');
		if (strlen($comentario)<4) {
			return Response::json(array(
				'type' => 'danger', 
				'msg' => 'El comentario es muy corto',
			));
		}
		$publication = Publicaciones::find($pub_id);
		$comentarios = new Comentarios;
		$comentarios->user_id 	 = $id;
		$comentarios->pub_id  	 = $pub_id;
		$comentarios->comentario = $comentario;
		$comentarios->updated_at = date('Y-m-d',time());
		$comentarios->created_at = date('Y-m-d',time());
		$comentarios->save();
		$commenter = User::find($id);
		$msg = 'El usuario '.$commenter->name.' '+$commenter->lastname.' ha comentado tu publicación '.$publication->titulo;
		$user = User::with('gcmdevices')->find($publication->user_id);
		foreach($user->gcmdevices as $gcm) {
			$regId = $gcm->gcm_regid;
			$data = array(
				"type"		=> "comment",
				"pub_id"	=> $pub_id,
				"message"	=> $msg,
				"title"		=> "Han comentado tu publicación"
			);
			$doGcm = new Gcm;
			$response = $doGcm->send_notification($data,$regId);
		}
		return Response::json(array(
			'type' => 'success', 
			'msg' => 'Comentario Guardado Sactisfactoriamente',

			'date' => date('d-m-Y',strtotime($comentarios->created_at))
		));
	}
	public function postResponse()
	{
		$id = Input::get('id');
		$input = Input::all();
		$rules = array(
			'comment_id' => 'required|numeric',
			'respuesta'  => 'required',
			'pub_id' 	 => 'required|numeric'
		);
		$validator = Validator::make($input, $rules);
		if ($validator->fails()) {
			return Response::json(array(
				'type' => 'danger',
				'msg'  => 'Error al validar los datos.',
				'data' => $validator->getMessageBag()->toArray(),
			));
		}
		$com = Comentarios::find($input['comment_id']);
		if ($com->respondido == 1) {
			return Response::json(array(
				'type' => 'danger',
				'msg' => 'El comantario ya fue respondido.'
			));	
		}
		if ($com->deleted == 1) {
			$com->deleted = 0;
		}


		$resp = new Respuestas;
		$resp->comentario_id = $input['comment_id'];
		$resp->respuesta 	 = $input['respuesta'];
		$resp->pub_id 		 = $input['pub_id'];
		$resp->user_id		 = $com->user_id;
		$resp->created_at 	 = date('Y-m-d',time());
		$resp->updated_at 	 = date('Y-m-d',time());
		$user = User::find($resp->user_id);
		$to_Email = $user->email;
		$subject  = "han respondido tu comentario";
		$data = array(
			'email' => $to_Email
		);
		Mail::send('emails.respuesta', $data, function($message) use ($to_Email,$subject)
		{
			$message->to($to_Email)->from('pasillo24.com@pasillo24.com')->subject($subject);
		});
		if ($resp->save()) {
			
			$publication = Publicaciones::find($input['pub_id']);
			$msg = 'El usuario '.Auth::user()->name.' '+Auth::user()->lastname.' ha respondido tu comentario en la publicación '.$publication->titulo;
			$user = User::with('gcmdevices')->find($publication->user_id);
			foreach($user->gcmdevices as $gcm) {
				$regId = $gcm->gcm_regid;
				$data = array(
					"type"		=> "comment",
					"pub_id"	=> $publication->id,
					"message"	=> $msg,
					"title"		=> "Han respondido tu comentario"
				);
				$doGcm = new Gcm;
				$response = $doGcm->send_notification($data,$regId);
			}

			$com->respondido = 1;
			$com->save();
			return Response::json(array(
				'type' => 'success',
				'msg' => 'Respuesta guardada satisfactoriamente'
			));
		}else
		{
			return Response::json(array(
				'type' => 'danger',
				'msg' => 'Error al guardar la respuesta'
			));
		}
		return Response::json($input);
	}
	public function postElimComment()
	{
		$id = Input::get('id');

		$comment = Comentarios::find($id);
		$comment->deleted = 1;
		if ($comment->save()) {
			return Response::json(array(
				'type' => 'success',
				'msg'  => 'Comentario borrado sactisfactoriamente.'
			));
		}else
		{
			return Response::json(array(
				'type' => 'danger',
				'msg'  => 'Error al borrar el comentario.'
			));
		}
	}
	public function postElimCommentrecividos()
	{
		$comment_id = Input::get('comment_id');
		$comment    = Comentarios::find($comment_id);
		$comment->respondido = 1;
		if ($comment->save()) {
			return Response::json(array(
				'type' => 'success',
				'msg'  => 'Comentario marcado como respondido sactisfactoriamente.'
			));
		}else
		{
			return Response::json(array(
				'type' => 'danger',
				'msg'  => 'Error al marcar el comentario.'
			));
		}
	}

	/*---------------------------favoritos---------------------------------------------*/
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*---------------------------favoritos---------------------------------------------*/
	public function addFav($id)
	{
		
		$user_id = Input::get('id');
		$aux = Favorito::where('user_id','=',$user_id)->where('pub_id','=',$id)->first();
		if (is_null($aux) || empty($aux)) 
		{
			$fav = new Favorito;
			$fav->user_id = $user_id;
			$fav->pub_id  = $id;
			$fav->save();
			$response = array(
				'type' 		=> 'success',
				'msg'  		=> 'Se ha agregado esta publicación a favoritos.',
				'data'		=> $fav->id,
			);
		}else
		{
			$response = array(
				'type' 		=> 'danger',
				'msg'  		=> 'Ya agregaste esta publicación a favoritos.',
			);
		}
		return Response::json($response);
	}
	public function removeFav($id)
	{
		$fav = Favorito::find($id);
		if (is_null($fav) || empty($fav)) {
			return Response::json(array(
				'type' 	 => 'danger',
				'msg'    => 'Favorito no existe o ya se ha eliminado.',
			));	
		}
		$pub = $fav->pub_id;
		$fav->delete();
		return Response::json(array(
			'type' 	 => 'success',
			'msg'    => 'Se ha removido la publicación de sus favoritos.',
		));
	}
	public function getMyFav()
	{
		$toReturn = array_merge($this->toReturn,array(
			'favoritos.id as fav_id',
		));
		$id = Input::get('id');
		$fav = Publicaciones::leftJoin('favoritos','favoritos.pub_id','=','publicaciones.id')
		->join('usuario','usuario.id','=','publicaciones.user_id')
		->leftJoin('departamento','departamento.id','=','publicaciones.departamento')
		->where(function($query){
			$query->where('publicaciones.fechFin','>=',date('Y-m-d',time()))
			->orWhere('publicaciones.fechFinNormal','>=',date('Y-m-d',time()));
		})	
		->where('publicaciones.deleted','=',0)
		->where('favoritos.user_id','=',$id)
		->get($toReturn);
		return Response::json(array(
			'pub'  => $fav,
			'type' => 'success',
		));
	}

	/*---------------------------Compras-----------------------------------------------*/
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*---------------------------Compras-----------------------------------------------*/
	public function getCompra()
	{
		$id = Input::get('id');
		$pub_id = Input::get('pub_id');
		$aux = Compras::where('pub_id','=',$pub_id)
		->where('user_id','=',$pub_id)
		->where(function($query){
			$query->where('valor_vend','=',0)
			->orWhere('fechVal','=',date('Y-m-d',time()+172800));
		})
		->first();

		if (!empty($aux)) {
			return Response::json(array(
				'type' => 'danger',
				'msg'  => 'Usted ya ha contactado este usuario y aun no se ha valorado'));
		}
		$comp = new Compras;
		$comp->pub_id  	  = $pub_id;
		$comp->user_id 	  = $id;
		$comp->valor_comp = 0;
		$comp->valor_vend = 0;
		$comp->fechVal 	  = date('Y-m-d',time()+172800);
		if ($comp->save()) {
			$pub = Publicaciones::find($pub_id);
			$user = User::with('gcmdevices')->find($pub->user_id);
			$msg = 'El usuario '.Auth::user()->name.' '+Auth::user()->lastname.' lo ha contactado para compra';
			foreach($user->gcmdevices as $gcm) {
				$regId = $gcm->gcm_regid;
				$data = array(
					"type"		=> "contact",
					"pub_id"	=> $comp->pub_id,
					"message"	=> $msg,
					"title"		=> "Te han contactado"
				);
				$doGcm = new Gcm;
				$response = $doGcm->send_notification($data,$regId);
			}
			$userdata = array();
			if (empty($pub->name) || empty($pub->lastname)) {
				$userdata = array_merge($userdata,array('name' => $user->name));
				$userdata = array_merge($userdata,array('lastname' => $user->lastname));
			}
			if (empty($pub->phone)) {
				$userdata = array_merge($userdata,array('phone' => $user->phone));
			}
			if (empty($pub->email)) {
				$userdata = array_merge($userdata,array('email' => $user->email));
			}
			if (empty($pub->pag_web_hab) && $pub->pag_web == "http://") {
				$userdata = array_merge($userdata,array('pag_web' => $user->pag_web));
			}
			return Response::json(array(
				'type' => 'success',
				'msg'  => 'Se ha generado una compra',
				'compra_id' => $comp->id,
				'pub' => $pub,
				'userdata' => $userdata
			));
		}
	}
	/*---------------------------Rutas Globales----------------------------------------*/
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*                                                                                 */
	/*---------------------------Rutas Globales----------------------------------------*/
	public function getDate()
	{
		define('CONST_SERVER_TIMEZONE', 'UTC');
		date_default_timezone_set('America/La_Paz');
		$input = Input::all();
		$fecha = explode('-', $input['fecha']);
		if (count($fecha)<3) {
			return Response::json(array('type' => 'danger', 'msg' => 'El formato de la fecha debe ser dd-mm-yyyy'));
		}else
		{
			if ($fecha < date('d-m-Y')) {
				return Response::json(array('type' => 'danger', 'msg' => 'La fecha debe ser anterior a hoy.'));
			}
			if ($input['per'] == 'd') {
				$prec = Precios::find(1);
				$times = 86400;
			}elseif ($input['per'] == 's') {
				$prec = Precios::find(2);
				$times = 604800;
			}elseif ($input['per'] == 'm') {
				$prec = Precios::find(3);
				$times = 2629744;
			}
			if ($input['dur'] < 1) {
				$timestamp = strtotime($input['fecha'])+$times;
				$costo = $prec->precio;
			}else
			{
				$timestamp = strtotime($input['fecha'])+$times*$input['dur'];
				$costo = $prec->precio*$input['dur'];
			}
			$fech = date('d-m-Y',$timestamp);
			return Response::json(array('fecha' => $fech,'costo' => $costo));
			
		}
	}
	public function getCategory()
	{
		$aux = Categorias::where('tipo','=',1)->where('deleted','=',0)->where(function($query){
			$query->where('nombre','!=','otros')->orWhere('nombre','!=','Otros');
		})->orderBy('desc')->get(array('id','nombre as desc'));
		$cat = array();
		foreach ($aux as $i => $c) {
			$cat[$i] = $c;
		}
		$aux2 = Categorias::where('tipo','=',2)->where('deleted','=',0)->where(function($query){
			$query->where('nombre','!=','otros')->orWhere('nombre','!=','Otros');
		})->orderBy('desc')->get(array('id','nombre as desc'));
		$ser = array();
		foreach ($aux2 as $i => $s) {
			$ser[$i] = $s;
		}
		$otros = Categorias::where('tipo','=',1)->where('deleted','=',0)->where(function($query){
			$query->where('nombre','=','otros')->orWhere('nombre','=','Otros');
		})->first(array('id','nombre as desc'));
		$cat = array_merge($cat,array($otros));
		$otros2 = Categorias::where('tipo','=',2)->where('deleted','=',0)->where(function($query){
			$query->where('nombre','=','otros')->orWhere('nombre','=','Otros');
		})->first(array('id','nombre as desc'));
		$ser = array_merge($ser,array($otros2));

		return Response::json(array(
			'type' => 'success',
			'categorias' => $cat,
			'servicios'  => $ser, 
		));
	}
	public function getSubCategory()
	{
		$id = Input::get('cat_id');
		$subCat = SubCat::where('categoria_id','=',$id)
		->where('deleted','=',0)
		->orderBy('desc')
		->get();
		return Response::json(array(
			'type' => 'success',
			'data' => $subCat,
		));
	}
	public function getDepartments()
	{
		$dep = Department::get();
		return Response::json(array(
			'type' => 'success',
			'departamentos' => $dep,
		));
	}
	public function getBrand()
	{
		$brand = Marcas::get();
		return Response::json(array(
			'type' => 'success',
			'marcas' => $brand,
		));
	}
	public function getModel()
	{
		$id = Input::get('marca_id');
		$brand = Modelo::where('marca_id','=',$id)->get();
		return Response::json(array(
			'type' => 'success',
			'marcas' => $brand,
		));
	}
	public function getUserData()
	{
		$id = Input::get('id');
		$user = User::where('id','=',$id)->with('favoritos')->first();
		return Response::json(array(
			'type' => 'success',
			'data' => $user,
		));
	}
	public function getTest()
	{
		$regId = Input::get('regId');
		$data = [
			"type"		=> "rating",
			"message"	=> 'holis',
			"title"		=> "Te han valorado como vendedor",
		];
		$doGcm = new Gcm;
		$response = $doGcm->send_notification($data,$regId);
		return Response::json(array(
			'type' => 'success',
			'msg'	=> 'se envio'
		));
	}
	public function getPrice()
	{
		$price = Precios::get();
		return $price;
	}
}
