<?php

class PublicationController extends BaseController {

	public function upload_images($file)
	{
		if (file_exists('images/pubImages/'.Auth::user()['username'].'/'.$file->getClientOriginalName())) {
			//guardamos la imagen en public/imgs con el nombre original
            $i = 0;//indice para el while
            //separamos el nombre de la img y la extensión
            $info = explode(".",$file->getClientOriginalName());
            //asignamos de nuevo el nombre de la imagen completo
            $miImg = $file->getClientOriginalName();
            //mientras el archivo exista iteramos y aumentamos i
            while(file_exists('images/pubImages/'.Auth::user()['username'].'/'. $miImg)){
                $i++;
                $miImg = $info[0]."(".$i.")".".".$info[1];              
            }
            //guardamos la imagen con otro nombre ej foto(1).jpg || foto(2).jpg etc
            $file->move("images/pubImages/".Auth::user()['username'],$miImg);
            $img = Image::make('images/pubImages/'.Auth::user()['username'].'/'.$miImg);
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
	            ->save('images/pubImages/'.Auth::user()['username'].'/'.$miImg);
            if($miImg != $file->getClientOriginalName()){
				return Auth::user()['username'].'/'.$miImg;
            }
		}else
		{
			$file->move("images/pubImages/".Auth::user()['username'],$file->getClientOriginalName());
			$img = Image::make('images/pubImages/'.Auth::user()['username'].'/'.$file->getClientOriginalName());
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
	            ->save("images/pubImages/".Auth::user()['username'].'/'.$file->getClientOriginalName());
	            return Auth::user()['username'].'/'.$file->getClientOriginalName();
		}
	}
	
	public function getHabitualForm($type)
	{
		$url = 'usuario/publicacion/habitual/'.$type.'/enviar';
		$title = "Publicacion Habitual / ".ucwords($type)." | pasillo24.com";
		$departamentos = Department::get();
		$marcas = Marcas::get();
		
		return View::make('publications.habitualForm')
		->with('title',$title)
		->with('type',$type)
		->with('url',$url)
		->with('departamento',$departamentos)
		->with('marcas',$marcas)
		->with('categorias',$categorias);
	}
	

	public function getPublicationNormalPayment($id)
	{
		$id = $id;
		$title ="Pago publicación habitual";
		$precioLider = Precios::where('pub_type_id','=',1)->get();
		$pub = Publicaciones::find($id);
		$precioCat 	 = Precios::where('pub_type_id','=',2)->get();
		$numCuentas = NumCuentas::all();
		return View::make('publications.paymentsNormal')
		->with('title',$title)
		->with('id',$id)
		->with('precLid',$precioLider)
		->with('precCat',$precioCat)
		->with('pub',$pub)
		->with('numCuentas',$numCuentas);
	}

	public function getMyPublicationsType($type)
	{
		$title ="Mis publicaciones";
		if (strtolower($type) == "lider") {
			$publications = Publicaciones::where('user_id','=',Auth::id())
			->leftJoin('categoria','categoria.id','=','publicaciones.categoria')
			->where('publicaciones.tipo','=',ucfirst(strtolower($type)))
			->where('publicaciones.deleted','=',0)
			->get(array('publicaciones.*','categoria.nombre as categoria'));	
		}elseif (strtolower($type) == "habitual") {
			$publications = Publicaciones::join('categoria','categoria.id','=','publicaciones.categoria')
			->where('user_id','=',Auth::id())
			->where('publicaciones.tipo','=','Habitual')
			->where('publicaciones.deleted','=',0)
			->get(array('publicaciones.*','categoria.nombre as categoria'));	
		}elseif(strtolower($type) == "casual")
		{
			$publications = Publicaciones::join('categoria','categoria.id','=','publicaciones.categoria')
			->where('publicaciones.user_id','=',Auth::id())
			->where('publicaciones.tipo','=','Casual')
			->where('publicaciones.deleted','=',0)
			->get(array(
				'publicaciones.*',
				'categoria.nombre as categoria'
			));
			$rePub = Publicaciones::where('publicaciones.user_id','=',Auth::id())
			->where('publicaciones.tipo','=','Casual')
			->where('publicaciones.deleted','=',0)
			->orderBy('fechRepub','desc')
			->first(array('fechRepub'));

		}
		if (strtolower($type) == "casual") {
			return View::make('user.publications')
			->with('title',$title)
			->with('publications',$publications)
			->with('type',strtolower($type))
			->with('rePub',$rePub);
		}
		return View::make('user.publications')
		->with('title',$title)
		->with('publications',$publications)
		->with('type',strtolower($type));
	}
	public function getPublication($id)
	{
		$pub = Publicaciones::find(base64_decode($id));
		$user = User::find($pub->user_id);
		if ($pub->user_id != 24) {
			$otrasPub = Publicaciones::where('user_id','=',$pub->user_id)
			->where('id','!=',base64_decode($id))
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
		if ($pub->tipo == "Lider") {
			$pub = Publicaciones::join('usuario','usuario.id','=','publicaciones.user_id')
			->where('publicaciones.id','=',base64_decode($id))
			->get(array(
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
			$volver = 'administrador/publicacion/lider';
			$publication = $pub[0];
			
		}elseif($pub->tipo == "Habitual")
		{
			if ($pub->categoria == 34) {
				$pub = DB::table('publicaciones')
				->join('marcas','marcas.id','=','publicaciones.marca_id')
				->join('modelo','modelo.id','=','publicaciones.modelo_id')
				->join('departamento','departamento.id','=','publicaciones.departamento')
				->join('usuario','usuario.id','=','publicaciones.user_id')
				->where('publicaciones.id','=',base64_decode($id))
				->get(array(
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
				$pub = DB::table('publicaciones')
				->join('usuario','usuario.id','=','publicaciones.user_id')
				->join('departamento','departamento.id','=','publicaciones.departamento')
				->where('publicaciones.id','=',base64_decode($id))
				->get(array(
				'usuario.id as user_id',
				'usuario.reputation',
				'publicaciones.*',
				'departamento.nombre as dep'
				));
			}		
			
			
			$publication = $pub[0];
			$volver = 'administrador/publicacion/habitual';

		}elseif($pub->tipo == 'Casual')
		{
			$pub = Publicaciones::join('categoria','categoria.id','=','publicaciones.categoria')
			->join('usuario','usuario.id','=','publicaciones.user_id')
			->join('departamento','departamento.id','=','publicaciones.departamento')
			->where('publicaciones.id','=',base64_decode($id))
			->where('publicaciones.tipo','=','Casual')
			->get(array(
				'usuario.id as user_id',
				'usuario.reputation',
				'categoria.desc as desc',
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
			$publication = $pub[0];
			$volver = 'administrador/publicacion/casual';
		}
		$comentarios = DB::table('comentario')
		->join('usuario','usuario.id','=','comentario.user_id')
		->where('comentario.pub_id','=',base64_decode($id))
		->get(array('comentario.id','comentario.comentario','comentario.created_at','usuario.username'));

		$resp = Respuestas::where('pub_id','=',$publication->id)->get();
		$title = $publication->titulo." | pasillo24.com";
		return View::make('publications.publicationSelf')
		->with('title',$title)
		->with('publication',$publication)
		->with('comentarios',$comentarios)
		->with('id',base64_decode($id))
		->with('respuestas',$resp)
		->with('otrasPub',$otrasPub)
		->with('username',$user->username)
		->with('volver',$volver);
	}

	public function postPublicationNormalPayment()
	{
		$input = Input::all();
		$publication = Publicaciones::find($input['enviarId']);
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
	public function postComment(){
		if (Request::ajax()) {
			$id =Input::get('id');
			$comentario = Input::get('comment');
			if (strlen($comentario)<4) {
				return 'El comentario es muy corto';
			}
			$publication = Publicaciones::find($id);
			$comentarios = new Comentarios;
			
			$comentarios->user_id 	 = Auth::id();
			$comentarios->pub_id  	 = $id;
			$comentarios->comentario = $comentario;
			$comentarios->updated_at = date('Y-m-d',time());
			$comentarios->created_at = date('Y-m-d',time());
			$comentarios->save();
			$msg = "Han comentado tu publicacion: ".$publication->titulo;
			$user = User::find($publication->user_id);
			$data = array(
				'message' 		=> $msg,
				'title'   		=> $msg,
				'msgcnt'  		=> null,
				'timeToLive' 	=> 3000,
			);
			$gcm = GcmDevices::where('usuario','=',$user->username)->orderBy('id','DESC')->get(array('gcm_regid'));
			$regId = array();
			$i = 0;
			foreach($gcm as $g)
			{
				$regId[$i] = $g['gcm_regid'];
				$i++;
			}
			$doGcm = new Gcm;
			$response = $doGcm->send_notification($regId,$data);
			return Response::json(array('type' => 'success', 'msg' => 'Comentario Guardado Sactisfactoriamente','date' => date('d-m-Y',strtotime($comentarios->created_at))));
		}
		
	}
	public function getMyComment()
	{
		$title = "Comentarios | pasillo24.com";

		$comment = Comentarios::leftJoin('publicaciones','publicaciones.id','=','comentario.pub_id')
		->where('publicaciones.user_id','=',Auth::user()->id)
		->where('comentario.is_read','=',0)
		->where('publicaciones.deleted','=',0)
		->where('comentario.deleted','=',0)
		->update(array('comentario.is_read' => 1));


		$responses = Respuestas::where('user_id','=',Auth::user()->id)
		->where('is_read','=',0)
		->where('deleted','=',0)
		->update(array('is_read' => 1));
		$recividos = Comentarios::leftJoin('publicaciones','publicaciones.id','=','comentario.pub_id')
		->where('publicaciones.user_id','=',Auth::user()['id'])
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
		->where('comentario.user_id','=',Auth::id())
		->where('comentario.deleted','=',0)
		->get(array(
			'publicaciones.titulo',
			'comentario.id',
			'comentario.comentario',
			'comentario.created_at',
			'comentario.deleted',
			'respuestas.respuesta'
		));

		return View::make('publications.myComments')
		->with('title',$title)
		->with('hechos',$hechos)
		->with('recividos',$recividos);
	}

	public function getPublicationCasual()
	{
		$pub = Publicaciones::where('user_id','=',Auth::id())
		->where('tipo','=','Casual')
		->where('fechRepub','>',date('Y-m-d',time()))
		->orderBy('fechRepub','desc')
		->first();
		if (count($pub)>0) {
			Session::flash('error', 'Usted ha consumido el máximo de publicaciones casuales. Inténtelo nuevamente cuando su última publicación casual expire.');
			return Redirect::to('usuario/publicar');
		}
		$title = "Publicación CASUAL | pasillo24.com";
		$url = 'usuario/publicacion/casual/enviar';
		$departamentos = Department::get();
		$categorias = Categorias::where('tipo','=',1)->where('deleted','=',0)->orderBy('nombre')->get();
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
		$textos = Textos::where('id','=',3)->first();
		return View::make('publications.publicacion')
		->with('tipo','casual')
		->with('url',$url)
		->with('title',$title)
		->with('departamento',$departamentos)
		->with('categorias',$categorias)
		->with('texto',$textos)
		->with('servicios',$servicios)
		->with('otros',$otros)
		->with('otros2',$otros2);
	}
	public function getPublicationCategory($id)
	{
		$title = "Búsqueda por categorías | pasillo24.com";
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
			'publicaciones.descripcion',
			'departamento.id as dep_id',
			'departamento.nombre as dep'));
		$departamentos = Department::get();
		return View::make('publications.categories')
		->with('title',$title)
		->with('publicaciones',$publicaciones)
		->with('lider',$lider)
		->with('departamento',$departamentos)
		->with('busq',$id);
		
	}
	public function getPublicationDepartment($id)
	{
		$title = "Búsqueda por departamento | pasillo24.com";
		if ($id != 'todos') {
			$lider = Publicaciones::where('publicaciones.departamento','=',$id)
			->where('tipo','=','Lider')
			->where(function($query){
				$query->where('fechFin','>=',date('Y-m-d',time()))
				->orWhere('fechFinNormal','>=',date('Y-m-d',time()));
			})		
			->get(array('id','img_1','titulo','precio'));
			$publicaciones = Publicaciones::where('publicaciones.departamento','=',$id)
			->where(function($query){
				$query->where('fechFin','>=',date('Y-m-d',time()))
				->orWhere('fechFinNormal','>=',date('Y-m-d',time()));
			})		
			->paginate(10,array('id','img_1','titulo','precio'));
		}else
		{
			$lider = Publicaciones::where('tipo','=','Lider')
			->where(function($query){
				$query->where('fechFin','>=',date('Y-m-d',time()))
				->orWhere('fechFinNormal','>=',date('Y-m-d',time()));
			})	
			->get(array('id','img_1','titulo','precio'));

			$publicaciones = Publicaciones::where(function($query){
				$query->where('fechFin','>=',date('Y-m-d',time()))
				->orWhere('fechFinNormal','>=',date('Y-m-d',time()));
			})		
			->paginate(5,array('id','img_1','titulo','precio'));
		}
		

		return View::make('publications.categories')
		->with('title',$title)
		->with('publicaciones',$publicaciones)
		->with('tipoBusq','dep')
		->with('lider',$lider)
		->with('busq','departamentos');
		
	}
	public function postResponse()
	{
		$input = Input::all();
		$rules = array(
			'id' => 'required|numeric',
			'respuesta' => 'required',
			'pub_id' 	=> 'required|numeric'
		);
		$validator = Validator::make($input, $rules);
		if ($validator->fails()) {
			return Response::json(array('type' => 'danger','msg' => 'Debe enviar un mensaje.'));
		}
		$com = Comentarios::find($input['id']);
		if ($com->respondido == 1) {
			return Response::json(array('type' => 'danger','msg' => 'El comantario ya fue respondido.'));	
		}
		if ($com->deleted == 1) {
			$com->deleted = 0;
		}
		$resp = new Respuestas;
		$resp->comentario_id = $input['id'];
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
			
			$com->respondido = 1;
			$com->save();
			$msg = "Han respondido tu comentario: ".$com->comentario;
			$data = array(
				'message' 		=> $msg,
				'title'   		=> $msg,
				'msgcnt'  		=> null,
				'timeToLive' 	=> 3000,
			);
			$gcm = GcmDevices::where('usuario','=',$user->username)->orderBy('id','DESC')->get(array('gcm_regid'));
			$regId = array();
			$i = 0;
			foreach($gcm as $g)
			{
				$regId[$i] = $g['gcm_regid'];
				$i++;
			}
			$doGcm = new Gcm;
			$response = $doGcm->send_notification($regId,$data);
			return Response::json(array('type' => 'success','msg' => 'Respuesta guardada satisfactoriamente'));
		}else
		{
			return Response::json(array('type' => 'danger','msg' => 'Error al guardar la respuesta'));
		}
		return Response::json($input);
	}
	public function postPublicationCasual()
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
	            ->save("images/pubImages/".Auth::user()['username'].'/'.$img1->getClientOriginalName());
			}
		}
		$input = Input::all();
		if (!Input::hasFile('img1')) {
			Session::flash('error', 'La imagen de la portada es obligatoria.');
			return Redirect::to('usuario/publicacion/casual')->withInput();
		}
		$img1 = Input::file('img1');
		$rules = array('img1' => 'image');
		$validator = Validator::make(array('img1' => $img1), $rules);
		if ($validator->fails()) {
			Session::flash('error','Error, el archivo '.$img1->getClientOriginalName().' debe ser una imagen en formato: jpg, png o gif');
			return Redirect::back()->withInput();
		}
		$tam   = getimagesize($img1);
		$width = $tam[0];

		if (Input::hasFile('img2')) {
			$img2 = Input::file('img2');
			$rules = array('img2' => 'image');
			$validator = Validator::make(array('img2' => $img2), $rules);
			if ($validator->fails()) {
				Session::flash('error','Error, el archivo '.$img2->getClientOriginalName().' debe ser una imagen en formato: jpg, png o gif');
				return Redirect::back()->withInput();
			}
			
		}
		if (count(strip_tags($input['input']))>400) {
			Session::flash('inputError', 'La descripción no debe tener mas de 400 caracteres.');
			return Redirect::back()->withInput();
		}
		
		$rules = array(
			'input' => 'required',
			'img1'  => 'required|image',
			'img'   => 'image',
			'precio'=> 'required|numeric',
			'moneda'=> 'required',
			'casCity' => 'required',
			'resultado' => 'required|numeric'
		);
		$messages = array(
			'required' => ':attribute es requerido',
			'image'    => ':attrubute debe ser una imagen',
			'numeric'  => ':attribute debe ser numerico',
			'max'      => ':attribute debe ser no mayor a 400 caracteres'
		);
		$customAttributes = array(
			'input' => 'El campo descripcion',
			'img1' 	=> 'El archivo',
			'img2'  => 'El archivo',
			'resultado'     => 'El captcha',
			'precio'=> 'El precio',
			'moneda'=> 'El campo moneda',
			'casCity' => 'El campo departamento'
		);
		if ($input['x']+$input['y'] != $input['resultado']) {
			Session::flash('error', 'El captcha es incorrecto');
			return Redirect::back()->withInput();
		}
		$validator = Validator::make($input, $rules, $messages, $customAttributes);
		if ($validator->fails()) {
			return Redirect::back()->withErrors($validator)->withInput();
		}else
		{
			$pub = new Publicaciones;
			$pub->img_1 = Auth::user()['username'].'/'.$img1->getClientOriginalName();
			
			chequear($pub,$img1,1);
	        if (Input::hasFile('img2')) {
				$pub->img_2 = Auth::user()['username'].'/'.$img2->getClientOriginalName();
				chequear($pub,$img2,2);
			}
			$pub->user_id = Auth::id();
			$pub->tipo 		  = 'Casual';
			$pub->titulo      = $input['casTit'];
			$pub->departamento= $input['casCity'];
			$pub->categoria   = $input['casCat'];
			$pub->ubicacion   = 'Principal';
			$pub->precio 	  = $input['precio'];
			$pub->moneda 	  = $input['moneda'];
			$pub->descripcion = $input['input'];
			$pub->fechIni 	  = date('Y-m-d',time());
			$pub->fechFin	  = date('Y-m-d',time()+604800);
			$pub->fechRepub	  = date('Y-m-d',time()+2543400);
			$pub->status 	  = 'Procesando';
			$pub->save();
			Session::flash('success','Publicación guardada correctamente');
			return Redirect::to('usuario/publicaciones/mis-publicaciones');
		}
	}
	public function getPublicationNormalType($id)
	{
		$title = "Publicacion habitual | pasillo24.com";
		$subCat = SubCat::where('categoria_id','=',$id)
		->where('deleted','=',0)
		->orderBy('desc')
		->get();
		$otrosub = new StdClass;
		$otrosub->id = null;
		foreach ($subCat as $c) {
			if (strtolower($c->desc) == 'otros') {
				$otrosub->id 		= $c->id;
				$otrosub->desc	= $c->desc;			
			}
		}

		if (is_null($otrosub->id)) {
			Session::flash('error','Lo sentimos, esta categoria esta disponible en estos momentos.');
			return Redirect::back();
		}
		$url = "publicacion/habitual/enviar";
		$departamento = Department::all();
		$marcas = Marcas::all();
		$cat = Categorias::find($id);
		return View::make('publications.habitualForm')
		->with('url',$url)
		->with('title',$title)
		->with('cat_id',$id)
		->with('subCat',$subCat)
		->with('marcas',$marcas)
		->with('departamento',$departamento)
		->with('otrosub',$otrosub)
		->with('cat',$cat);
	}
	public function postPublicationHabitual()
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
	            ->save("images/pubImages/".Auth::user()['username'].'/'.$img1->getClientOriginalName());
	            if($donde == 1)
            	{
	                $publication->img_1 = Auth::user()['username'].'/'.$img1->getClientOriginalName();
            	}elseif($donde == 2)
            	{
            		$publication->img_2 = Auth::user()['username'].'/'.$img1->getClientOriginalName();
            	}
			}
		}
		$input = Input::all();
		$rules = array(

			'departamento'	=> 'required',
			'ciudad'		=> 'required',
			'title' 		=> 'required|min:4',
			'input'			=> 'required|min:4',
			'moneda'		=> 'required',
			'precio'		=> 'required_if:tipoTransac,venta,alquiler,Aticretico,otro',
			'moneda'		=> 'required',
			'img1'			=> 'required|image',
			'tipoTransac'	=> 'required'

		);
		$messages = array(
			'required' 	=> ':attribute es obligatorio',
			'required_if' => ':attribute es obligatorio',
			'min'		=> ':attribute debe ser mas largo',
			'image'		=> ':attribute debe ser una imagen',
			'numeric'	=> ':attribute debe ser numerico'
		);
		$customAttributes = array(
			'precio'	 	=> 'El campo precio',

			'departamento'  => 'El campo departamento',
			'title'		 	=> 'El campo titulo',
			'input' 	 	=> 'El campo descripcion',
			'img1'		 	=> 'El campo imagen de portada',
			'moneda'		=> 'El campo moneda',
			'tipoTransac'	=> 'El campo tipo de operación',

		);
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
			return Redirect::to('publicacion/habitual/crear/'.$input['cat_id'])->withErrors($validator)->withInput();
		}
		$img1 = Input::file('img1');
		$tam   = getimagesize($img1);
		$width = $tam[0];
		$pub 				= new Publicaciones;
		$pub->user_id 		= Auth::id();
		$pub->titulo 		= $input['title'];
		$pub->categoria 	= $input['cat_id'];
		if(!empty($input['subCat']))
		{
					$pub->typeCat 		= $input['subCat'];
		}else
		{
		$pub->typeCat = 0;
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
		$pub->img_1 		= Auth::user()['username'].'/'.$img1->getClientOriginalName();

		if (!empty($input['nomb'])) {
			$pub->name 			= $input['nomb'];
		}
		if (!empty($input['phone'])) {
			$pub->phone 		= $input['phone'];
		}
		if (!empty($input['email'])) {
			$pub->email 		= $input['email'];
		}
		if (!empty($input['pag_web'])) {
			$pub->pag_web_hab = $input['pag_web'];
		}
		chequear($pub,$img1,1);
		
		if ($input['cat_id'] == 34) {
			$pub->marca_id		= $input['marca'];
			$pub->modelo_id  	= $input['modelo'];
			$pub->anio 			= $input['anio'];
			$pub->precio 		= $input['precio'];
			$pub->kilometraje	= $input['kilo'];
			if ($input['cilin'] != "") {
				$pub->cilindraje = $input['cilin'];
			}
			if ($input['trans'] != "") {
				$pub->transmision = $input['trans'];
			}
			if ($input['comb'] != "") {
				$pub->combustible = $input['comb'];
			}
			if ($input['doc'] != "") {
				$pub->documentos = $input['doc'];
			}
			if ($input['trac'] != "") {
				$pub->traccion = $input['trac'];
			}
		}elseif($input['cat_id'] == 20)
		{
			$pub->extension     = $input['ext'];
		}
		if($pub->save())
		{
			return Redirect::to('publicacion/habitual/enviar/imagenes/'.$pub->id);
		}

	}
	public function getPublicationHabitualImages($id)
	{
		$pub = Publicaciones::find($id);
		$title = "Cargar imagenes extras";
		return View::make('publications.imagesHabitual')
		->with('title',$title)
		->with('pub_id',$pub->id);
	}
	public function post_upload(){

		$input = Input::all();
		$rules = array(
		    'file' => 'image|max:8000',
		);
		$messages = array(
			'image' => 'Todos los archivos deben ser imagenes',
			'max'	=> 'Las imagenes deben ser de menos de 3Mb'
		);
		$validation = Validator::make($input, $rules, $messages);

		if ($validation->fails())
		{
			return Response::make($validation)->withErrors($validation);
		}
		$id = Input::get('pub_id');
		$pub = Publicaciones::find($id);
		$file = Input::file('file');
		$campo = "";
		if (empty($pub->img_2)) {
			$campo = 'img_2';
		}elseif(empty($pub->img_3))
		{
			$campo = 'img_3';
		}elseif(empty($pub->img_4))
		{
			$campo = 'img_4';
		}elseif(empty($pub->img_5))
		{
			$campo = 'img_5';
		}elseif(empty($pub->img_6))
		{
			$campo = 'img_6';
		}elseif(empty($pub->img_7))
		{
			$campo = 'img_7';
		}elseif(empty($pub->img_8))
		{
			$campo = 'img_8';
		}

		if (file_exists('images/pubImages/'.Auth::user()['username'].'/'.$file->getClientOriginalName())) {
			//guardamos la imagen en public/imgs con el nombre original
            $i = 0;//indice para el while
            //separamos el nombre de la img y la extensión
            $info = explode(".",$file->getClientOriginalName());
            //asignamos de nuevo el nombre de la imagen completo
            $miImg = $file->getClientOriginalName();
            //mientras el archivo exista iteramos y aumentamos i
            while(file_exists('images/pubImages/'.Auth::user()['username'].'/'. $miImg)){
                $i++;
                $miImg = $info[0]."(".$i.")".".".$info[1];              
            }
            //guardamos la imagen con otro nombre ej foto(1).jpg || foto(2).jpg etc
            $file->move("images/pubImages/".Auth::user()['username'],$miImg);
            $img = Image::make('images/pubImages/'.Auth::user()['username'].'/'.$miImg);
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
	            ->save('images/pubImages/'.Auth::user()['username'].'/'.$miImg);
            if($miImg != $file->getClientOriginalName()){
                if ($campo == 'img_2') {
					$pub->img_2 = Auth::user()['username'].'/'.$miImg;
				}elseif(empty($pub->img_3))
				{
					$pub->img_3 = Auth::user()['username'].'/'.$miImg;
				}elseif($campo == 'img_4')
				{
					$pub->img_4 = Auth::user()['username'].'/'.$miImg;
				}elseif($campo == 'img_5')
				{
					$pub->img_5 = Auth::user()['username'].'/'.$miImg;
				}elseif($campo == 'img_6')
				{
					$pub->img_6 = Auth::user()['username'].'/'.$miImg;
				}elseif($campo == 'img_7')
				{
					$pub->img_7 = Auth::user()['username'].'/'.$miImg;
				}elseif($campo == 'img_8')
				{
					$pub->img_8 = Auth::user()['username'].'/'.$miImg;
				}
            }
		}else
		{
			$file->move("images/pubImages/".Auth::user()['username'],$file->getClientOriginalName());
			$img = Image::make('images/pubImages/'.Auth::user()['username'].'/'.$file->getClientOriginalName());
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
	            ->save("images/pubImages/".Auth::user()['username'].'/'.$file->getClientOriginalName());
	            if ($campo == 'img_2') {
					$pub->img_2 = Auth::user()['username'].'/'.$file->getClientOriginalName();
				}elseif(empty($pub->img_3))
				{
					$pub->img_3 = Auth::user()['username'].'/'.$file->getClientOriginalName();
				}elseif($campo == 'img_4')
				{
					$pub->img_4 = Auth::user()['username'].'/'.$file->getClientOriginalName();
				}elseif($campo == 'img_5')
				{
					$pub->img_5 = Auth::user()['username'].'/'.$file->getClientOriginalName();
				}elseif($campo == 'img_6')
				{
					$pub->img_6 = Auth::user()['username'].'/'.$file->getClientOriginalName();
				}elseif($campo == 'img_7')
				{
					$pub->img_7 = Auth::user()['username'].'/'.$file->getClientOriginalName();
				}elseif($campo == 'img_8')
				{
					$pub->img_8 = Auth::user()['username'].'/'.$file->getClientOriginalName();
				}
		}
		$pub->save();
        return Response::json(array('campo' => $campo));

        if( $upload_success ) {
        	return Response::json('success', 200);
        } else {
        	return Response::json('error', 400);
        }
	}
	public function post_delete()
	{
		$campo 		= Input::get('campo');
		$file 		= Input::get('name');
		$id     	= Input::get('id');
		$pub 		= Publicaciones::find($id);
		if ($campo == 'img_1') {
			$img = $pub->img_1;
			File::delete('images/pubImages/'.Auth::user()['username'].'/'.$img);
			$pub->img_1 = "";
		}elseif ($campo == 'img_2') {
			$img = $pub->img_2;
			File::delete('images/pubImages/'.Auth::user()['username'].'/'.$img);
			$pub->img_2 = "";
		}elseif($campo == 'img_3')
		{
			$img = $pub->img_3;
			File::delete('images/pubImages/'.Auth::user()['username'].'/'.$img);
			$pub->img_3 = "";
		}elseif($campo == 'img_4')
		{
			$img = $pub->img_4;
			File::delete('images/pubImages/'.Auth::user()['username'].'/'.$img);
			$pub->img_4 = "";
		}elseif($campo == 'img_5')
		{
			$img = $pub->img_5;
			File::delete('images/pubImages/'.Auth::user()['username'].'/'.$img);
			$pub->img_5 = "";
		}elseif($campo == 'img_6')
		{
			$img = $pub->img_6;
			File::delete('images/pubImages/'.Auth::user()['username'].'/'.$img);
			$pub->img_6 = "";
		}elseif($campo == 'img_7')
		{
			$img = $pub->img_7;
			File::delete('images/pubImages/'.Auth::user()['username'].'/'.$img);
			$pub->img_7 = "";
		}elseif($campo == 'img_8')
		{
			$img = $pub->img_8;
			File::delete('images/pubImages/'.Auth::user()['username'].'/'.$img);
			$pub->img_8 = "";
		}
		$pub->save();
		return Response::json(array('llego' => 'llego'));
        
	}
	public function getCompra()
	{
		$id = Input::get('id');
		$aux = Compras::where('pub_id','=',$id)
		->where('user_id','=',Auth::id())
		->where(function($query){
			$query->where('valor_vend','=',0)
			->orWhere('fechVal','=',date('Y-m-d',time()+172800));
		})
		->first();

		if (!empty($aux)) {
			Session::flash('error', 'Usted ya ha contactado este usuario y aun no se ha valorado');
			return Redirect::back();
		}
		$comp = new Compras;
		$comp->pub_id  	  = $id;
		$comp->user_id 	  = Auth::id();
		$comp->valor_comp = 0;
		$comp->valor_vend = 0;
		$comp->fechVal 	  = date('Y-m-d',time()+172800);
		if ($comp->save()) {
			$pub = Publicaciones::find($id);
			$user = User::find($pub->user_id);
			$msg = "Han respondido tu comentario: ".$pub->titulo;
			$data = array(
				'message' 		=> $msg,
				'title'   		=> $msg,
				'msgcnt'  		=> null,
				'timeToLive' 	=> 3000,
			);
			$gcm = GcmDevices::where('usuario','=',$user->username)->orderBy('id','DESC')->get(array('gcm_regid'));
			$regId = array();
			$i = 0;
			foreach($gcm as $g)
			{
				$regId[$i] = $g['gcm_regid'];
				$i++;
			}
			$doGcm = new Gcm;
			$response = $doGcm->send_notification($regId,$data);
			return Redirect::to('usuario/mis-compras');
		}
	}
	public function getPreview($id)
	{
		$title = "Previsualización de publicación HABITUAL | pasillo24.com";
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
		return View::make('publications.preview')
		->with('title',$title)
		->with('publication',$publication)
		->with('id',$id);
	}
	public function getModifyPub($id)
	{
		$pub = Publicaciones::find($id);
		$title = "Modificar publicacion | pasillo24.com";
		if ($pub->tipo == 'Lider') {
			$url = "usuario/publicacion/modificar/lider/".$id;
		}elseif ($pub->tipo == 'Habitual') {
			$url = "usuario/publicacion/modificar/habitual/".$id;
		}elseif ($pub->tipo == 'Casual') {
			$url = "usuario/publicacion/modificar/casual/".$id;
		}
		$categorias = Categorias::all();
		$subCat = SubCat::all();
		$departamento = Department::all();
		if ($pub->categoria == 34) {
			$marcas = Marcas::all();
			$modelos = Modelo::where('marca_id','=',$pub->marca_id)->get();
			return View::make('publications.modifyPub')
			->with('title',$title)
			->with('tipo',$pub->tipo)
			->with('publicaciones',$pub)
			->with('url',$url)
			->with('categorias',$categorias)
			->with('subCat',$subCat)
			->with('departamento',$departamento)
			->with('marcas',$marcas)
			->with('modelos',$modelos);
		}else
		{
			return View::make('publications.modifyPub')
			->with('title',$title)
			->with('tipo',$pub->tipo)
			->with('publicaciones',$pub)
			->with('url',$url)
			->with('categorias',$categorias)
			->with('subCat',$subCat)
			->with('departamento',$departamento);
		}
	}
	public function postModifyPub($type, $id)
	{
		$input = Input::all();
		$pub = Publicaciones::find($id);
		if ($pub->tipo == 'Lider') {
			if ($pub->ubicacion != "Principal") {
				$pub->categoria = $input['cat'];
			}
			if (!empty($input['img1']) ) {
				$img1 = Input::file('img1');
				$rules = array('img1' => 'image');
				$validator = Validator::make(array('img1' => $img1), $rules);
				if ($validator->fails()) {
					Session::flash('danger','Error, el archivo '.$img1->getClientOriginalName().' debe ser una imagen en formato: jpg, png o gif');
				}else
				{
					$pub->img_1 = $this->upload_images($img1);
					
				}
			}
			if (!empty($input['img2']) ) {
				$img2 = Input::file('img2');
				$rules = array('img2' => 'image');
				$validator = Validator::make(array('img2' => $img2), $rules);
				if ($validator->fails()) {
					Session::flash('danger','Error, el archivo '.$img2->getClientOriginalName().' debe ser una imagen en formato: jpg, png o gif');
				}else
				{
					$pub->img_2 = $this->upload_images($img2);
					
				}

			}
			if ($input['pagina'] != $pub->pag_web && !empty($input['pagina'])) {
				$pub->pag_web = $input['pagina'];
			}
			if ($input['nomb'] != $pub->name && !empty($input['nomb'])) {
				$pub->name = $input['nomb'];
			}
			if ($input['phone'] != $pub->phone && !empty($input['phone'])) {
				$pub->phone = $input['phone'];
			}
			if ($input['email'] != $pub->email && !empty($input['email'])) {
				$pub->email = $input['email'];
			}
			if ($input['pag_web'] != $pub->pag_web_hab && !empty($input['pag_web'])) {
				$pub->pag_web_hab = $input['pag_web'];
			}
			if (!empty($input['namePub'])) {
				$pub->titulo = $input['namePub'];
			}
			
		}elseif($pub->tipo == 'Habitual')
		{
			if (!empty($input['cat'])) {
				$pub->categoria = $input['cat'];
			}
			if (isset($input['img1']) && !empty($input['img1']) ) {
				$img1 = Input::file('img1');
				$rules = array('img1' => 'image');
				$validator = Validator::make(array('img1' => $img1), $rules);
				if ($validator->fails()) {
					Session::flash('danger','Error, el archivo '.$img1->getClientOriginalName().' debe ser una imagen en formato: jpg, png o gif');
					return Redirect::back();
				}else
				{
					$pub->img_1 = $this->upload_images($img1);
					
				}
				
			}
			if (isset($input['img2']) && !empty($input['img2']) ) {
				$img2 = Input::file('img2');
				$rules = array('img2' => 'image');
				$validator = Validator::make(array('img2' => $img2), $rules);
				if ($validator->fails()) {
					Session::flash('danger','Error, el archivo '.$img2->getClientOriginalName().' debe ser una imagen en formato: jpg, png o gif');
					return Redirect::back();
				}else
				{
					$pub->img_2 = $this->upload_images($img2);
					
				}
			
			}
			if (isset($input['img3']) && !empty($input['img3']) ) {
				$img3 = Input::file('img3');
				$rules = array('img3' => 'image');
				$validator = Validator::make(array('img3' => $img3), $rules);
				if ($validator->fails()) {
					Session::flash('danger','Error, el archivo '.$img3->getClientOriginalName().' debe ser una imagen en formato: jpg, png o gif');
					return Redirect::back();
				}else
				{
					$pub->img_3 = $this->upload_images($img3);
					
				}
			
			}
			if (isset($input['img4']) && !empty($input['img4']) ) {
				$img4 = Input::file('img4');
				$rules = array('img4' => 'image');
				$validator = Validator::make(array('img4' => $img4), $rules);
				if ($validator->fails()) {
					Session::flash('danger','Error, el archivo '.$img4->getClientOriginalName().' debe ser una imagen en formato: jpg, png o gif');
					return Redirect::back();
				}else
				{
					$pub->img_4 = $this->upload_images($img4);
					
				}
			
			}
			if (isset($input['img5']) && !empty($input['img5']) ) {
				$img5 = Input::file('img5');
				$rules = array('img5' => 'image');
				$validator = Validator::make(array('img5' => $img5), $rules);
				if ($validator->fails()) {
					Session::flash('danger','Error, el archivo '.$img5->getClientOriginalName().' debe ser una imagen en formato: jpg, png o gif');
					return Redirect::back();
				}else
				{
					$pub->img_5 = $this->upload_images($img5);
					
				}
			
			}
			if (isset($input['img6']) && !empty($input['img6']) ) {
				$img6 = Input::file('img6');
				$rules = array('img6' => 'image');
				$validator = Validator::make(array('img6' => $img6), $rules);
				if ($validator->fails()) {
					Session::flash('danger','Error, el archivo '.$img6->getClientOriginalName().' debe ser una imagen en formato: jpg, png o gif');
					return Redirect::back();
				}else
				{
					$pub->img_6 = $this->upload_images($img6);
					
				}
			
			}
			if (isset($input['img7']) && !empty($input['img7']) ) {
				$img7 = Input::file('img7');
				$rules = array('img7' => 'image');
				$validator = Validator::make(array('img7' => $img7), $rules);
				if ($validator->fails()) {
					Session::flash('danger','Error, el archivo '.$img7->getClientOriginalName().' debe ser una imagen en formato: jpg, png o gif');
					return Redirect::back();
				}else
				{
					$pub->img_7 = $this->upload_images($img7);
					
				}
			
			}
			if (isset($input['img8']) && !empty($input['img8']) ) {
				$img8 = Input::file('img8');
				$rules = array('img8' => 'image');
				$validator = Validator::make(array('img8' => $img8), $rules);
				if ($validator->fails()) {
					Session::flash('danger','Error, el archivo '.$img8->getClientOriginalName().' debe ser una imagen en formato: jpg, png o gif');
					return Redirect::back();
				}else
				{
					$pub->img_8 = $this->upload_images($img8);
					
				}
			
			}
			if (!empty($input['pagina'])) {
				$pub->pag_web = $input['pagina'];
			}
			if (!empty($input['nomb'])) {
				$pub->name = $input['nomb'];
			}
			if (!empty($input['phone'])) {
				$pub->phone = $input['phone'];
			}
			if (!empty($input['email'])) {
				$pub->email = $input['email'];
			}
			if (!empty($input['pagina'])) {
				$pub->pag_web_hab = $input['pagina'];
			}
			if (!empty($input['subCat'])) {
				$pub->typeCat = $input['subCat'];
			}
			if (!empty($input['title'])) {
				$pub->titulo = $input['title'];
			}
			if (!empty($input['precio'])) {
				$pub->precio = $input['precio'];
			}
			if (!empty($input['moneda'])) {
				$pub->moneda = $input['moneda'];
			}
			if (!empty($input['departamento'])) {
				$pub->departamento = $input['departamento'];
			}
			if (!empty($input['ciudad'])) {
				$pub->ciudad = $input['ciudad'];
			}
			if ($pub->categoria == 34) {
				if (!empty($input['marca'])) {
					$pub->marca_id = $input['marca'];
				}
				if (!empty($input['modelo'])) {
					$pub->modelo_id = $input['modelo'];
				}
				if (!empty($input['anio'])) {
					$pub->anio = $input['anio'];
				}
				if (!empty($input['doc'])) {
					$pub->documentos = $input['doc'];
				}
				if (!empty($input['kilo'])) {
					$pub->kilometraje = $input['kilo'];
				}
				if (!empty($input['cilin'])) {
					$pub->cilindraje = $input['cilin'];
				}
				if (!empty($input['trans'])) {
					$pub->transmision = $input['trans'];
				}
				if (!empty($input['comb'])) {
					$pub->combustible = $input['comb'];
				}
				if (!empty($input['trac'])) {
					$pub->traccion = $input['trac'];
				}
				
			}elseif($pub->categoria == 20)
			{
				if (!empty($input['ext'])) {
					$pub->extension = $input['ext'];
				}
			}
			
			if (!empty($input['tipoTransac'])) {
				$pub->transaccion = $input['tipoTransac'];
			}
			if (!empty($input['input'])) {
				$pub->descripcion = $input['input'];
			}
		}
		
		if($pub->save())
		{
			Session::flash('success', 'Publicación modificada satisfactoriamente.');
			return Redirect::back();
		}else
		{
			Session::flash('danger', 'Error al modificar el usuario.');
			return Redirect::back();
		}
	}
	public function getSubCat()
	{
		if (Request::ajax()) {
			$id = Input::get('id');
			$subCat = SubCat::where('categoria_id','=',$id)->get();
			return $subCat;
		}
	}
public function postElimPub()
	{
		if (Request::ajax()) {
			$id = Input::get('id');
			$pub = Publicaciones::find($id);
			$titulo = $pub->titulo;
			$comment = Comentarios::where('pub_id','=',$id)->get();
			$resp    = Respuestas::where('pub_id','=',$id)->get();
			if (count($comment)>0) {
				foreach ($comment as $c) 
				{
					$c->deleted = 1;
					$c->save();
				}
			}
			
			if (count($resp)>0) {
				foreach ($resp as $r) {
					$r->deleted = 1;
					$r->save();
				}
			}
			$userid = $pub->user_id;
			$user = User::find($userid);
			$subject = "Correo de Aviso";

			$pub->deleted = 1;
			$pub->save();
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


			return Response::json(array('type' => 'success','msg' => 'Publicación eliminada satisfactoriamente. Hemos enviado un email al correo.'));
		}
	}
	public function postChangePost()
	{
		$arr = Input::get('arr');
		$id  = Input::get('id');
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
	public function postElimImage()
	{
		$id = Input::get('id');
		$pub = Publicaciones::find($id);
		$img = Input::get('img');
		switch ($img) {
			case 'img2':
				File::delete('images/pubImages/'.$pub->img_2);
				$pub->img_2 = '';
				break;
			case 'img3':
				File::delete('images/pubImages/'.$pub->img_3);
				$pub->img_3 = '';
				break;
			case 'img4':
				File::delete('images/pubImages/'.$pub->img_4);
				$pub->img_4 = '';
				break;
			case 'img5':
				File::delete('images/pubImages/'.$pub->img_5);
				$pub->img_5 = '';
				break;
			case 'img6':
				File::delete('images/pubImages/'.$pub->img_6);
				$pub->img_6 = '';
				break;
			case 'img7':
				File::delete('images/pubImages/'.$pub->img_7);
				$pub->img_7 = '';
				break;
			case 'img8':
				File::delete('images/pubImages/'.$pub->img_8);
				$pub->img_8 = '';
				break;
			default:
				break;
		}
		if ($pub->save()) {
			return Response::json(array('type' => 'success'));
		}else
		{
			return Response::json(array('type' => 'danger'));
		}
		
	}
}