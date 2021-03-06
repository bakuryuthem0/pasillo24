<?php

class UserController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/

	public function getProfile()
	{
		if(Auth::check()){
			$title ="Cambiar Perfil";
			$department = Department::all();
			$user = User::where('id','=',Auth::id())->first();
			
			$id = $user->state;
			$userDep = Department::where('id','=',$id)->pluck('nombre');
			if (!empty($user) && $user != "" && !is_null($user) ) {
				return View::make('user.profile')
				->with('title',$title)
				->with('user',$user)
				->with('department',$department)
				->with('userDep',$userDep);
			}		
		}else
		{
			Session::flash('error', 'Debe iniciar sesión para acceder a esta área');
			return Redirect::to('inicio/login');
		}
	}
	public function postProfile()
	{
		$input = Input::all();
		$user = User::find(Auth::id());
		$email = $user->email;
		$rules = array(
			'name'       			 => 'min:4',
			'lastname'   			 => 'min:4',
			'id'         			 => 'min:5',
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
			return Redirect::to('usuario/perfil')->withErrors($validator)->withInput();
		}
		if (!empty($input['name'])) {
			$user->name = $input['name'];
		}
		if (!empty($input['lastname'])) {
			$user->lastname = $input['lastname'];
		}
		if (!empty($input['id'])) {
			$user->id_carnet = $input['id'];
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
			Session::flash('success', 'Datos Cambiados correctamente. Le hemos enviado un correo electrónico como seguridad.');
			return Redirect::to('usuario/perfil');
		}else{
			Session::flash('error','Error no se pudo modificar los datos');
			return Redirect::to('usuario/perfil');
		}
	}
	public function getPublication()
	{

		$title ="Publicación | pasillo24.com";
		return View::make('publications.publicar')->with('title',$title);
	}
	public function getPublicationLider()
	{
		$title ="Publicación Lider | pasillo24.com";
		$department = Department::all();
		$categoria  = Categorias::where('tipo','=',1)->where('deleted','=',0)->orderBy('nombre')->get();
		$otros = new StdClass;
		foreach ($categoria as $c) {
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
		$servicios  = Categorias::where('tipo','=',2)->where('deleted','=',0)->orderBy('nombre')->get();
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
		$url = 'usuario/publicacion/lider/enviar';
		$textos = Textos::where('id','=',1)->first();
		$precio = Precios::where('pub_type_id','=',1)->get();
		$dep = Department::get();
		return View::make('publications.publicacion')
		->with('title',$title)
		->with('tipo','lider')
		->with('department',$department)
		->with('url',$url)->with('categorias',$categoria)
		->with('texto',$textos)
		->with('precio',$precio)
		->with('servicios',$servicios)
		->with('otros',$otros)
		->with('otros2',$otros2)
		->with('dep',$dep);
	}
	public function postPublicationLider()
	{
		function chequear($publication,$img1,$donde)
		{
			if (file_exists('images/pubImages/'.Auth::user()['username'].'/'.$img1->getClientOriginalName())) {
				//guardamos la imagen en public/imgs con el nombre original
	            $i = 0;//indice para el while
	            //separamos el nombre de la img y la extensión
	            $info = explode(".",$img1->getClientOriginalName());
	            //asignamos de nuevo el nombre de la imagen completo
	            $miImg = $img1->getClientOriginalName();
	            //mientras el archivo exista iteramos y aumentamos i
	            while(file_exists('images/pubImages/'.Auth::user()['username'].'/'. $miImg)){
	                $i++;
	                $miImg = $info[0]."(".$i.")".".".$info[1];              
	            }
	            //guardamos la imagen con otro nombre ej foto(1).jpg || foto(2).jpg etc
	            $img1->move("images/pubImages/".Auth::user()['username'],$miImg);
	            $img = Image::make('images/pubImages/'.Auth::user()['username'].'/'.$miImg);
	            /*$blank = Image::make('images/blank.jpg');
	            if ($img->width() > $img->height()) {
		        	$img->widen(1604);
		        }else
		        {
		        	$img->heighten(804);
		        }
		        if ($img->height() > 804) {
		        	$img->heighten(804);
		        }*/
		        $mark = Image::make('images/watermark.png')->widen(400);
		        $img->insert($mark,'center')
	           	->interlace()
	            ->save('images/pubImages/'.Auth::user()['username'].'/'.$miImg);
	            if($miImg != $img1->getClientOriginalName()){
	            	if($donde == 1)
	            	{
		                $publication->img_1 = Auth::user()['username'].'/'.$miImg;
	            	}elseif($donde == 2)
	            	{
	            		$publication->img_2 = Auth::user()['username'].'/'.$miImg;
	            	}
	            }	
			}else
			{
				$img1->move("images/pubImages/".Auth::user()['username'],$img1->getClientOriginalName());
				$img = Image::make('images/pubImages/'.Auth::user()['username'].'/'.$img1->getClientOriginalName());
	            /*$blank = Image::make('images/blank.jpg');
	             if ($img->width() > $img->height()) {
		        	$img->widen(1604);
		        }else
		        {
		        	$img->heighten(804);
		        }
		        if ($img->height() > 804) {
		        	$img->heighten(804);
		        }*/
		        $mark = Image::make('images/watermark.png')->widen(400);
		        $img->insert($mark,'center')
	           	->interlace()
	            ->save('images/pubImages/'.Auth::user()['username'].'/'.$img1->getClientOriginalName());
			}
		}

		
		$input = Input::all();
		/* validar que exista al menos una imagen*/
		if (!Input::hasFile('portada') && !Input::hasFile('portada2'))
		{
		    Session::flash('error', 'Debe ingresar al menos una imagen.');
			return Redirect::to('usuario/publicacion/lider')->withInput();
		}

		if (Input::hasFile('portada')) {
			$img1  = Input::file('portada');
			$rules = array('img1' => 'image');
			$validator = Validator::make(array('img1' => $img1), $rules);
			if ($validator->fails()) {
				Session::flash('error','Error, el archivo '.$img1->getClientOriginalName().' debe ser una imagen en formato: jpg, png o gif');
				return Redirect::back()->withInput();
			}
			$tam = getimagesize($img1);
			$width = $tam[0];
		}

		if (Input::hasFile('portada2')) {
			$img2  = Input::file('portada2');
			$rules = array('img2' => 'image');
			$validator = Validator::make(array('img2' => $img2), $rules);
			if ($validator->fails()) {
				Session::flash('error','Error, el archivo '.$img2->getClientOriginalName().' debe ser una imagen en formato: jpg, png o gif');
				return Redirect::back();
			}
			$tam2 = getimagesize($img2);
			$width2 = $tam2[0];
		}

		/* validar pagina web */
		if (!empty($input['pagina'])) {
			if (mb_stristr($input['pagina'],'http://') == false && mb_stristr($input['pagina'],'https://') == false) {
				Session::flash('error', 'La pagina web debe comenzar con http:// ó https://.');
				return Redirect::to('usuario/publicacion/lider')->withInput();
			}
		}
		
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
		}else
		{
			Session::flash('error', 'Debe elegir un periodo válido');
			return Redirect::to('usuario/publicacion/lider');
		}
		$monto = $monto*$dur;
		/* Validador general */
		define('CONST_SERVER_TIMEZONE', 'UTC');
		date_default_timezone_set('America/La_Paz');
		$rules = array(
			'ubication' => 'required',
			'namePub'   => 'required|min:4',
			'duration'  => 'required|min:0',
			'time'      => 'required|in:d,s,m,a',
			'fechIni'   => 'required|after:'.date('d-m-Y'),
			'dep'		=> 'required',
			'negocioType' => 'required',
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
			'dep' 		=> 'El campo departamento',
			'negocioType'=> 'El campo tipo de negocio',

		);
		if ($input['ubication'] == 'Categoria'){
			$rules = $rules+array('cat'  => 'required');
			$attribute = array(
			'cat'	=> 'El campo categoria',
			
		);
		}
		$validator = Validator::make($input, $rules, $msg,$attribute);
		if ($validator->fails()) {
			Session::flash('error', 'Todos los campos son requeridos');
			return Redirect::to('usuario/publicacion/lider')->withErrors($validator)->withInput();
		}else
		{

			/* Segundo validador de fecha */
			$fecha = explode('-', $input['fechIni']);
			if (count($fecha)<3) {
				Session::flash('error', 'Formato de fecha incorrecta');
				return Redirect::to('usuario/publicacion/lider')->withInput();
			}else
			{
				$timestamp = strtotime($input['fechIni'])+($time*$input['duration']);
				$fechaFin = date('d-m-Y',$timestamp);

				$timestamp = $input['duration']*$time;
				$date  = date('d-m-Y');
				$timestamp = strtotime($input['fechIni'])+$timestamp;
				$fechFin = date('Y-m-d',$timestamp);
			}

			/* nueva publicacion */
			$publication = new Publicaciones;
			$publication->user_id   = Auth::id();
			$publication->tipo 		= 'Lider';
			$publication->departamento 	= $input['dep'];

			$publication->ubicacion = $input['ubication'];
			if ($publication->ubicacion == 'Categoria') {
				$publication->categoria = $input['cat'];
			}
			$publication->titulo    = $input['namePub'];
			$publication->pag_web   = $input['pagina'];
			$publication->fechIni   = date('Y-m-d',strtotime($input['fechIni']));
			$publication->fechFin   = $fechFin;
			$publication->status    = 'Pendiente';
			$publication->bussiness_type = $input['negocioType'];
			if(!empty($input['nomb'])){
				$publication->name = $input['nomb'];
			}
			if(!empty($input['phone'])){
				$publication->phone = $input['phone'];
			}
			if(!empty($input['email'])){
				$publication->email = $input['email'];
			}
			if(!empty($input['pag_web'])){
				$publication->pag_web_hab = $input['pag_web'];
			}

			if (Input::hasFile('portada')) {
				$publication->img_1		= Auth::user()['username'].'/'.$img1->getClientOriginalName();
				chequear($publication,$img1,1);
				if (Input::hasFile('portada2')) {
					$publication->img_2		= Auth::user()['username'].'/'.$img2->getClientOriginalName();
					chequear($publication,$img2,2);
				}						
			}else
			{
				$publication->img_1		= Auth::user()['username'].'/'.$img2->getClientOriginalName();
				chequear($publication,$img2,1);
				if (Input::hasFile('portada')) {
					$publication->img_2		= Auth::user()['username'].'/'.$img1->getClientOriginalName();
					chequear($publication,$img1,2);
				}	
			}
			

			$publication->monto     = $monto;
			
			
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
				Session::flash('success', 'Su publicación fue creada sactisfactoriamente.');
				return Redirect::to('usuario/publicacion/pagar/'.$publication->id);
			}else
			{
				Session::flash('error', 'Error al guardar los datos');
				return Redirect::to('usuario/publicacion/lider')->withInput();
			}
			
		}

	}
	public function getDate()
	{
		define('CONST_SERVER_TIMEZONE', 'UTC');
		date_default_timezone_set('America/La_Paz');
		$input = Input::all();
		$fecha = explode('-', $input['fecha']);
		if (count($fecha)<3) {
			return Response::json(array('code' => 0));
		}else
		{
			
			if ($fecha < date('d-m-Y')) {
				return Response::json(array('code' => 1));
			}
			$timestamp = strtotime($input['fecha'])+$input['timestamp'];
			$fech = date('d-m-Y',$timestamp);
			if ($input['period'] == 'd') {
				$prec = Precios::find(1);
			}elseif ($input['period'] == 's') {
				$prec = Precios::find(2);
			}elseif ($input['period'] == 'm') {
				$prec = Precios::find(3);
			}
			$costo = $prec->precio*$input['duration'];
			return Response::json(array('fecha' => $fech,'costo' => $costo));
			
		}
	}
	public function getPublicationNormal()
	{
		$title ="Publicación HABITUAL | pasillo24.com";
		$marcas = Marcas::all();
		$url = 'usuario/publication/estandar/enviar';
		$departamento = Department::all();
		$categorias = Categorias::where('deleted','=',0)->where('tipo','=',1)->orderBy('nombre')->get();
		$otros = new StdClass;
		foreach ($categorias as $c) {
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
		$textos = Textos::where('id','=',2)->first();

		return View::make('publications.publicacion')
		->with('title',$title)
		->with('tipo','normal')
		->with('marcas',$marcas)
		->with('url',$url)
		->with('categorias',$categorias)
		->with('texto',$textos)
		->with('departamento',$departamento)
		->with('servicios',$servicios)
		->with('otros',$otros)
		->with('otros2',$otros2);
	}
	public function getModelo()
	{
		$id = Input::get('id');
		$modelos = Modelo::where('marca_id','=',$id)->orderBy('nombre')->get();
		return $modelos;
	}
	
	public function postDelete()
	{

		if (Request::ajax()){
			$input = Input::all();
			$prod = Productos::find($input['id']);
			$prod->deleted = 1;
			if($prod->save()) {
				return Response::json(array(
					'success' => true,
					'msg'     => 'Artículo eliminado correctamente'
				));
			}else
			{
				return Response::json(array(
					'success' => false,
					'msg'     => 'Error al eliminar artículo'
				));
			}
		}
	}
	public function getMyPublications()
	{
		$title ="Mis publicaciones";
		$publications = Publicaciones::where('user_id','=',Auth::id())->where('deleted','=',0)->get();
		return View::make('user.publications')->with('title',$title)->with('publications',$publications);
	}
	public function getPublicationPayment($id = null)
	{
		if (!is_null($id)) {
			$pub_id = $id;
		}else
		{
			Session::flash('error', 'Debe seleccionar una publicación para enviar el pago');
			return Redirect::to('usuario/publicaciones/mis-publicaciones');
		}
		$pub = Publicaciones::find($pub_id);
		$title = "Pago de publicación";
		$bancos     = Bancos::all();
		$numCuentas = NumCuentas::all();
		return View::make('publications.payments')
		->with('title',$title)
		->with('pub_id',$pub_id)
		->with('bancos',$bancos)
		->with('numCuentas',$numCuentas)
		->with('pub',$pub);
	}
	public function postPublicationPayment(){
		$input = Input::all();
		$id = $input['enviarPago'];
		$rules = array(
			'transNumber' => 'required',
			'fecha'		  => 'required',
			'banco'		  => 'required'
		);
		$messages = array(
			'required' => 'El campo es obligatorio.'
		);
		$validator = Validator::make($input, $rules, $messages);
		if ($validator->fails()) {
			return Redirect::to('usuario/publicaciones/pago/'.$input['enviarPago'])->withErrors($validator)->withInput();
		}
		$pago = new Pagos;
		$pago->user_id   = Auth::id();
		$pago->pub_id    = $id;
		$pago->num_trans = $input['transNumber'];
		$pago->banco_id  = $input['banco'];
		if(!empty($input['emisor']))
		{
				$pago->banco_ext = $input['emisor'];
		}

		$pago->fech_trans= $input['fecha'];

		if ($pago->save()) {
			$publicacion = Publicaciones::find($id);
			$monto = $publicacion->monto;
			$moneda = $publicacion->moneda;
			$publicacion->status = "Procesando";
			$publicacion->save();
			$subject = "Correo de administrador";
			$data = array(
				'subject' => $subject,
				'createBy'=> Auth::user()['username'],
				'monto'   => $monto,
				'moneda'  => $moneda,
				'num_trans' => $input['transNumber']
			);
			$to_Email = 'gestor@pasillo24.com';
			Mail::send('emails.newPost', $data, function($message) use ($input,$to_Email,$subject)
			{
				$message->to($to_Email)->from('pasillo24.com@pasillo24.com')->subject($subject);
			});
			Session::flash('success', 'Información enviada, pronto procesaremos su pago');
			return Redirect::to('usuario/publicaciones/mis-publicaciones');
		}else
		{
			Session::flash('error', 'Error al guardar el pago');
			return Redirect::to('usuario/publicaciones/mis-publicaciones');
		}
	}
	public function getChangePass()
	{
		$title ="Cambiar contraseña | pasillo24.com";
		return View::make('user.changePass')->with('title',$title);
	}
	public function postChangePass()
	{
		$input = Input::all();
		$user = User::find(Auth::id());
		if (!Hash::check($input['old'], $user->password)) {
			Session::flash('error', 'la contraseña anterior es incorrecta');
			return Redirect::to('usuario/cambiar-clave');
		}else
		{
			$rules = array(
				'old' => 'required',
				'new' => 'required|min:8',
				'rep' => 'required|same:new'
			);		
			$messages = array(
				'required' => ':attribute es obligatorio',
				'min'	   => ':attribute debe tener al menos 8 caracteres',
				'same'	   => ':attribute no coincide'
			);
			$attributes = array(
				'old'  => 'El campo contraseña vieja',
				'new'  => 'El campo contraseña nueva',
				'rep'  => 'El campo repetir contraseña'
			);
			$validator = Validator::make($input, $rules, $messages, $attributes);
			if ($validator->fails()) {
				return Redirect::to('usuario/cambiar-clave')->withErrors($validator);
			}

			$user->password = Hash::make($input['new']);
			if ($user->save()) {
				$data = array(
					'pass' => $input['new'],
					'texto' => 'Usted ha solicitado un cambio de contraseña',
					'title' => 'Cambio de contraseña'
				);
				$newPass = $input['new'];
				$email = Auth::user()['email'];
				Mail::send('emails.passNew', $data, function ($message) use ($newPass,$email){
					    $message->subject('Correo de cambio de contraseña pasillo24.com');
					    $message->from('pasillo24@pasillo24.com');
					    $message->to($email);
					});
				Session::flash('success', 'Contraseña modificada correctamente');
				return Redirect::to('usuario/cambiar-clave');
			}else
			{
				Session::flash('error', 'la contraseña no se pudo cambiar.');
				return Redirect::to('usuario/cambiar-clave');
			}
		}
			
	}
	public function getMyCart()
	{
		$title = "Mis compras | pasillo24.com";
		
		$compras = Compras::join('publicaciones','publicaciones.id','=','compras.pub_id')
		->join('usuario','usuario.id','=','publicaciones.user_id')
		->where('compras.user_id','=',Auth::id())
		->where('compras.valorado_vend','=',0)
		->get(array(
			'compras.id',
			'compras.fechVal',
			'publicaciones.titulo',
			'publicaciones.id as pub_id',
			'publicaciones.name as pName',
			'publicaciones.phone as pPhone',
			'publicaciones.email as pEmail',
			'publicaciones.pag_web_hab as pPag_web',
			'usuario.name',
			'usuario.lastname',
			'usuario.phone',
			'usuario.pag_web',
			'usuario.email'
		));

		return View::make('user.myCart')
		->with('title',$title)
		->with('compras',$compras);
	}
	public function getMySell()
	{
		$title = "Mis ventas | pasillo24.com";
		$compras = Compras::join('publicaciones','publicaciones.id','=','compras.pub_id')
		->join('usuario','usuario.id','=','compras.user_id')
		->where('publicaciones.user_id','=',Auth::id())
		->where('compras.valorado_comp','=',0)
		->get(array(
			'compras.id',
			'compras.valor_vend',
			'compras.valor_comp',
			'compras.fechVal',
			'publicaciones.titulo',
			'usuario.id as user_id',
			'usuario.name',
			'usuario.lastname',
		));
		$hoy = date('Y-m-d');
		return View::make('user.mySell')
		->with('title',$title)
		->with('compras',$compras)
		->with('hoy',$hoy);
	}
	public function postValorVend()
	{
		if (Request::ajax()) {
			$id = Input::get('id');
			$tipo = Input::get('tipo');
			$pub = Compras::find($id);
			if (is_null($pub) || empty($pub)) {
				return Response::json(array('type' => 'danger','msg' => 'Error al valorar la publicación'));	
			}

			$valor = 0;
			if($tipo != "pos" && $tipo != 'neg' && $tipo != "neutro")
			{
				return Response::json(array('type' => 'danger','msg' => 'Error al valorar la publicación'));	
			}else
			{
				if ($tipo == 'pos') {
					$valor = 1;
					$msg = "Te han valorado positivamente.";
				}elseif ($tipo == 'neg') {
					$valor = -1;
					$msg = "Te han valorado negativamente.";
				}elseif($tipo == 'neutro')
				{
					$valor = 0;
					$msg = "Te han valorado neutralmente.";
				}
			}
			$comp = Compras::find($id);
			$pub = Publicaciones::find($comp->pub_id);
			$user = User::find($pub->user_id);
			$user->reputation 	 = $user->reputation + $valor;

			if (!$user->save()) {
				return Response::json(array('type' => 'danger','msg' => 'Error al valorar al vendedor.'));	
			}
			$comp->valor_vend = $valor;
			$comp->valorado_vend = 1;
			$data = array(
				"type"		=> "rating",
				"pub_id"	=> $comp->pub_id,
				"message"	=> $msg,
				"title"		=> "Te han valorado",
			);
			$user = User::with('gcmdevices')->find($pub->user_id);
			foreach($user->gcmdevices as $gcm) {
				$regId = $gcm->gcm_regid;
				$doGcm = new Gcm;
				$response = $doGcm->send_notification($data,$regId);
			}
			if ($comp->save()) {
				return Response::json(array('type' => 'success','msg' => 'Vendedor valorado correctamente.'));
			}else
			{
				return Response::json(array('type' => 'danger','msg' => 'Error al valorar al vendedor.'));
			}
		}
	}
	public function postValorComp()
	{
		if (Request::ajax()) {
			$id = Input::get('id');
			$tipo = Input::get('tipo');

			$valor = 0;
			if($tipo != "pos" && $tipo != 'neg' && $tipo != 'neutro')
			{
				return Response::json(array('type' => 'danger','msg' => 'Error al valorar al comprador.'));	
			}else
			{
				if ($tipo == 'pos') {
					$valor = 1;
					$msg = "Te han valorado positivamente.";
				}elseif ($tipo == 'neg') {
					$valor = -1;
					$msg = "Te han valorado negativamente.";
				}elseif($tipo == 'neutro')
				{
					$valor = 0;
					$msg = "Te han valorado neutralmente.";
				}
			}
			$comp = Compras::find($id);
			$user = User::find($comp->user_id);
			$user->reputation = $user->reputation + $valor;
			if (!$user->save()) {
				return Response::json(array('type' => 'danger','msg' => 'Error al valorar al comprador.'));	
			}
			$comp->valor_comp 	 = $valor;
			$comp->valorado_comp = 1;
			$data = array(
				"type"		=> "rating",
				"pub_id"	=> $comp->pub_id,
				"message"	=> $msg,
				"title"		=> "Te han valorado",
			);
			$user = User::with('gcmdevices')->find($comp->user_id);
			foreach($user->gcmdevices as $gcm) {
				$regId = $gcm->gcm_regid;
				$doGcm = new Gcm;
				$response = $doGcm->send_notification($data,$regId);
			}
			if ($comp->save()) {
				return Response::json(array('type' => 'success','msg' => 'Comprador valorado correctamente.'));
			}else
			{
				return Response::json(array('type' => 'danger','msg' => 'Error al valorar al comprador.'));
			}
		}
	}
	public function getMyReputation()
	{
		$title = "Mi reputación|pasillo24.com";
		$compras = Compras::join('publicaciones','publicaciones.id','=','compras.pub_id')
		->join('usuario','usuario.id','=','publicaciones.user_id')
		->where('compras.user_id','=',Auth::id())
		->where('was_erased_comp','=',0)
		->paginate(10,array(
			'compras.id',
			'compras.valor_vend',
			'compras.valor_comp',
			'compras.valorado_vend',
			'compras.valorado_comp',
			'publicaciones.titulo',
			'publicaciones.id as pub_id',
			'publicaciones.name as name_pub',
			'usuario.name',
			'usuario.lastname'
		));
		$ventas = Compras::join('publicaciones','publicaciones.id','=','compras.pub_id')
		->join('usuario','usuario.id','=','compras.user_id')
		->where('publicaciones.user_id','=',Auth::id())
		->where('was_erased_vend','=',0)
		->paginate(10,array(
			'compras.id',
			'compras.valor_vend',
			'compras.valor_comp',
			'compras.valorado_vend',
			'compras.valorado_comp',
			'publicaciones.titulo',
			'usuario.id as user_id',
			'usuario.name',
			'usuario.lastname'
		));
		$comprasC = Compras::join('publicaciones','publicaciones.id','=','compras.pub_id')
		->join('usuario','usuario.id','=','publicaciones.user_id')
		->where('compras.user_id','=',Auth::id())
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
		->where('publicaciones.user_id','=',Auth::id())
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
		return View::make('user.myReputation')
		->with('title',$title)
		->with('compras',$compras)
		->with('ventas',$ventas)
		->with('vend_pos',$vent_pos)
		->with('vend_neg',$vent_neg)
		->with('comp_pos',$comp_pos)
		->with('comp_neg',$comp_neg);
	}
	public function getPayments($id)
	{
		$numCuentas = NumCuentas::all();
		$bancos     = Bancos::all();
		$title = 'Pago de publicación';
		$pub = Publicaciones::find($id);
		return View::make('publications.payments')
		->with('title',$title)
		->with('pub_id',$id)
		->with('numCuentas',$numCuentas)
		->with('bancos',$bancos)
		->with('pub',$pub);
	}
	public function postElimComment()
	{
		if (Request::ajax()) {
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
	}
	public function postElimCommentrecividos()
	{
		if (Request::ajax()) {
			$id = Input::get('id');
			$comment = Comentarios::find($id);
			$comment->respondido = 1;
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
	}
	public function addFav($id)
	{
		if (Auth::check()) {
			$user_id = Auth::id();
		}elseif(Input::has('id'))
		{
			$user_id = Input::get('id');
		}
		$aux = Favorito::where('user_id','=',$user_id)->where('pub_id','=',$id)->first();
		if (is_null($aux) || empty($aux)) 
		{
			$fav = new Favorito;
			$fav->user_id = $user_id;
			$fav->pub_id  = $id;
			$fav->save();
			$response = array(
				'type' 		=> 'success',
				'action'    => 'add',
				'msg'  		=> 'Se ha agregado esta publicación a favoritos.',
				'popover' 	=> 'Remover de favoritos',
				'url'   	=> URL::to('usuario/publicaciones/remover-favorito/'.$fav->id),
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
		$pub = $fav->pub_id;
		$fav->delete();
		return Response::json(array(
			'type' 	 => 'success',
			'action' => 'remove',
			'msg'    => 'Se removio esta publicación de sus favoritos.',
			'url'    => URL::to('usuario/publicaciones/agregar-favorito/'.$pub),
			'popover'=> 'Agregar a favoritos.',

		));
	}
	public function getMyFav()
	{
		$title = "Mis publicacioens favoritas | pasillo24.com";
		$fav = Publicaciones::join('favoritos','favoritos.pub_id','=','publicaciones.id')
		->leftJoin('departamento','departamento.id','=','publicaciones.departamento')
		->where(function($query){
			$query->where('publicaciones.fechFin','>=',date('Y-m-d',time()))
			->orWhere('publicaciones.fechFinNormal','>=',date('Y-m-d',time()));
		})	
		->where('publicaciones.deleted','=',0)
		->where('favoritos.user_id','=',Auth::id())
		->paginate(5,array(
			'publicaciones.id',
			'publicaciones.img_1',
			'publicaciones.tipo',
			'publicaciones.titulo',
			'publicaciones.precio',
			'publicaciones.moneda',
			'publicaciones.descripcion',
			'departamento.nombre as dep_desc',
			'favoritos.id as fav_id'
		));
		return View::make('user.myFav')
		->with('title',$title)
		->with('fav',$fav);
	}
	public function getReactivate($id)
	{
		$title = "Reactivar publicación | Pasillo24";
		$pub = Publicaciones::find($id);
		$pub->duracion  = 0;
		
		if ($pub->tipo == 'Lider') {
			$cat = Categorias::where('tipo','=',1)->where('deleted','=',0)->orderBy('nombre')->get();
			$otros = new StdClass;
			foreach ($cat as $c) {
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
			$serv  = Categorias::where('tipo','=',2)->where('deleted','=',0)->orderBy('nombre')->get();
			$otros2 = new StdClass;
			foreach ($serv as $c) {
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
			$pub->monto 	= 0;
			$pub->save();
			return View::make('publications.reactivate')
			->with('title',$title)
			->with('pub',$pub)
			->with('cat',$cat)
			->with('serv',$serv)
			->with('otros',$otros)
			->with('otros2',$otros2);
		}else
		{
			$precioLider = Precios::where('pub_type_id','=',1)->get();
			$precioCat 	 = Precios::where('pub_type_id','=',2)->get();
			$pub->monto 	= 40;
			$pub->save();
			return View::make('publications.reactivate')
			->with('title',$title)
			->with('pub',$pub)
			->with('precioLider',$precioLider)
			->with('precioCat',$precioCat);

		}
	}
	public function postReactivate($id)
	{
		$pub = Publicaciones::find($id);
		if($pub->tipo == 'Lider')
		{
			$data = Input::all();
			$rules = array(
				'duration' => 'required',
				'time'     => 'required',
				'ubication'=> 'required',
				'cat'	   => 'required_if:ubication,Categoria',
				'fechIni'  => 'required|after:'.date('d-m-Y',time()).'|date_format:d-m-Y',
			);
			$msg = array();
			$attr = array(
				'duration' => 'duración',
				'time'	   => 'período',
				'fechIni'  => 'fecha de inicio',
			);
			$validator = Validator::make($data, $rules, $msg, $attr);
			if ($validator->fails()) {
				return Redirect::back()->withErrors($validator);
			}

			/* Validar duracion y montos */
			$dur = $data['duration'];
			if ($data['time'] == 'd') {
				
				$time = 86400;
				$monto = Precios::where('pub_type_id','=',1)->where('desc','=','dia')->pluck('precio');
			}elseif($data['time'] == 's')
			{
				$time = 604800;
				$monto = Precios::where('pub_type_id','=',1)->where('desc','=','semana')->pluck('precio');
			}elseif($data['time'] == 'm')
			{
				$time = 2629744;
				$monto = Precios::where('pub_type_id','=',1)->where('desc','=','mes')->pluck('precio');
			}
			$monto = $monto*$dur;
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
			$publication = Publicaciones::find($input['enviarId']);
			Session::flash('success', 'Publicación creada sactisfactoriamente');
			return Redirect::to('usuario/publicaciones/pago/'.$publication->id);
		}
		
		Session::flash('success', 'Su publicación se actualizo sactisfactoriamente.');
		return Redirect::to('usuario/publicaciones/pago/'.$pub->id);
	}
	public function removeComp()
	{
		$id = Input::get('id');
		$action = Compras::find($id);
		$action->was_erased_comp = 1;
		$action->save();
		return Response::json(array(
			'type' => 'success',
			'msg'  => 'El elemento seleccionado fue eliminado sactisfactoriamente.',
		));
	}
	public function removeVend()
	{
		$id = Input::get('id');
		$action = Compras::find($id);
		$action->was_erased_vend = 1;
		$action->save();
		return Response::json(array(
			'type' => 'success',
			'msg'  => 'El elemento seleccionado fue eliminado sactisfactoriamente.',
		));
	}
}