<?php

class AdministratorController extends BaseController {

    /*
    |--------------------------------------------------------------------------
    | Default Home Controller
    |--------------------------------------------------------------------------
    |
    | You may wish to use controllers instead of, or in addition to, Closure
    | based routes. That's great! Here is an example controller method to
    | get you started. To route to this controller, just add the route:
    |
    |   Route::get('/', 'HomeController@showWelcome');
    |
    */
    public function getPagos()
    {
        $title = "Pagos | Administrador";
        $publicaciones =Publicaciones::leftJoin('usuario','usuario.id','=','publicaciones.user_id')
        ->leftJoin('categoria','categoria.id','=','publicaciones.categoria')
        ->leftJoin('pagos','publicaciones.id','=','pagos.pub_id')
        ->where('status','=','Procesando')
        ->groupBy('publicaciones.id')
        ->get(
            array(
                'usuario.username',
                'pagos.num_trans',
                'publicaciones.titulo',
                'categoria.desc as categoria',
                'publicaciones.ubicacion',
                'publicaciones.id',
                'publicaciones.fechIni',
                'publicaciones.fechFin'
        ));
        return View::make('admin.pagos')
        ->with('title',$title)
        ->with('publicaciones',$publicaciones);
    }
    public function postPagos()
    {
        $id = Input::get('id');
        $publicacion = Publicaciones::find($id);
        $titulo = $publicacion->titulo;
        if($publicacion->tipo == 'Lider')
        {
            $url = "lider";
        }elseif ($publicacion->tipo == 'Habitual') {
            $url = "habitual";
        }elseif ($publicacion->tipo == 'Casual') {
            $url = "casual";
        }
        $publicacion->status = 'Aprobado';
        if ($publicacion->tipo == 'Habitual') {
            if ($publicacion->ubicacion == "Principal") {
                $publicacion->fechIni = date('Y-m-d');
                $publicacion->fechFin = date('Y-m-d',time()+$publicacion->duracion);
            }elseif($publicacion->ubicacion == "Categoria")
            {
                $publicacion->fechIniNormal = date('Y-m-d');
                $publicacion->fechFinNormal = date('Y-m-d',time()+$publicacion->duracionNormal);
            }elseif($publicacion->ubicacion == "Ambos")
            {
                $publicacion->fechIni = date('Y-m-d');
                $publicacion->fechFin = date('Y-m-d',time()+$publicacion->duracion);
                $publicacion->fechIniNormal = date('Y-m-d');
                $publicacion->fechFinNormal = date('Y-m-d',time()+$publicacion->duracionNormal);
            }
        }
        $subject = "Correo de administrador";
        $admin = Auth::user()['username'];
        $data = array(
            'subject' => $subject,
            'publicacion' => $titulo,
            'creadoPor'=> $admin
        );
        $to_Email = 'gestor@pasillo24.com';
        Mail::send('emails.aprvPub', $data, function($message) use ($titulo,$admin,$to_Email,$subject)
        {
            $message->to($to_Email)->from('pasillo24.com@pasillo24.com')->subject($subject);
        });

        $user = User::find($publicacion->user_id);
        $subject = 'Publicación aprobada | pasillo24.com';
        $data = array(
            'subject' => $subject,
            'publicacion' => $titulo,
        );
        $to_Email = $user->email;
        Mail::send('emails.aprvPubUser', $data, function($message) use ($titulo,$to_Email,$subject)
        {
            $message->to($to_Email)->from('pasillo24.com@pasillo24.com')->subject($subject);
        });
        $publicacion->motivo = "";
        if ($publicacion->save()) {
            Session::flash('success', 'Publicación aprobada satisfactoriamente');
            return Redirect::to('administrador/pagos/'.$url);
        }else
        {
            Session::flash('error', 'No se pudo aprobar la publicación');
            return Redirect::to('administrador/pagos');
        }
    }
    public function postPagosCancel()
    {
        if (Request::ajax()) {
            $id = Input::get('id');
            $input = Input::all();
            $publicacion = Publicaciones::find($id);
            if ($publicacion->tipo == 'Casual') {
                $publicacion->fechRepub = date('Y-m-d',strtotime("-1 days"));
            }
            $publicacion->status    = 'Rechazado';
            $publicacion->motivo    = Input::get('motivo');
            $data = array(
                'publicacion' => $publicacion->titulo,
                'motivo'   => $publicacion->motivo,
                'createBy' => Auth::user()['username']
            );
            Mail::send('emails.rejectPub', $data, function ($message) use ($input){
                $message->subject('Correo de rechazo de publicación | pasillo24.com');
                $message->from('pasillo24.com@pasillo24.com');
                $message->to('gestor@pasillo24.com');
            });
            $user = User::find($publicacion->user_id);
            $data = array(
                'publicacion' => $publicacion->titulo,
                'motivo'   => $publicacion->motivo,
            );
            Mail::send('emails.rejectPubUser', $data, function ($message) use ($input,$user){
                $message->subject('Correo de rechazo de publicación | pasillo24.com');
                $message->from('pasillo24.com@pasillo24.com');
                $message->to($user->email);
            });
            if ($publicacion->save()) {
                return Response::json(array('type' => 'success','msg' => 'Publicación rechazada satisfactoriamente.'));
            }else
            {
                return Response::json(array('type' => 'danger','msg' => 'Error al rechazar la publicación.'));
            }
        }
    }

    public function getPagosType($type)
    {
        $title ="Verificar pagos";
        if ($type == "lider") {
            $publicaciones = Publicaciones::where('publicaciones.tipo','=','Lider')
            ->join('usuario','usuario.id','=','publicaciones.user_id')
            ->leftJoin('categoria','categoria.id','=','publicaciones.categoria')
            ->join('pagos','pagos.pub_id','=','publicaciones.id')
            ->leftJoin('bancos','bancos.id','=','pagos.banco_id')
            ->where('publicaciones.deleted','=',0)
            ->where('publicaciones.status','=','Procesando')
            ->groupBy('publicaciones.id')
            ->orderBy('publicaciones.created_at','desc')
            ->get(array(
                'usuario.username',
                'usuario.name',
                'usuario.lastname',
                'usuario.email',
                'usuario.phone',
                'usuario.pag_web',
                'usuario.id_carnet',
                'usuario.nit',
                'publicaciones.titulo',
                'publicaciones.ubicacion',
                'categoria.desc',
                'publicaciones.fechIni',
                'publicaciones.fechFin',
                'pagos.num_trans',
                'pagos.fech_trans',
                'pagos.banco_ext',
                'bancos.nombre as banco',
                'publicaciones.id',
                'publicaciones.monto',
                'publicaciones.name as name_pub',
                'publicaciones.phone as phone_pub',
                'publicaciones.email as email_pub',
                'publicaciones.pag_web_hab as pag_pub',


            ));
        }elseif($type == "habitual")
        {
            $publicaciones = Publicaciones::join('usuario','usuario.id','=','publicaciones.user_id')
            ->join('pagos','publicaciones.id','=','pagos.pub_id')
            ->leftJoin('bancos','bancos.id','=','pagos.banco_id')
            ->leftJoin('categoria','categoria.id','=','publicaciones.categoria')
            ->where('publicaciones.status','=','Procesando')
            ->where('publicaciones.tipo','=','Habitual')
            ->where('publicaciones.deleted','=',0)
            ->groupBy('publicaciones.id')
            ->orderBy('publicaciones.created_at','desc')
            ->get(array(
                'usuario.username',
                'usuario.name',
                'usuario.lastname',
                'usuario.email',
                'usuario.phone',
                'usuario.pag_web',
                'usuario.id_carnet',
                'usuario.nit',
                'pagos.num_trans',
                'pagos.fech_trans',
                'pagos.banco_ext',
                'bancos.nombre as banco',
                'publicaciones.titulo',
                'categoria.nombre as categoria',
                'publicaciones.ubicacion',
                'publicaciones.id',
                'publicaciones.fechIni',
                'publicaciones.fechFin',
                'publicaciones.monto',
                'publicaciones.name as name_pub',
                'publicaciones.phone as phone_pub',
                'publicaciones.email as email_pub',
                'publicaciones.pag_web_hab as pag_pub',
                ));
        }elseif($type == "casual")
        {
            $publicaciones = Publicaciones::join('usuario','usuario.id','=','publicaciones.user_id')
            ->leftJoin('categoria','categoria.id','=','publicaciones.categoria')
            ->where('publicaciones.status','=','Procesando')
            ->where('publicaciones.tipo','=','Casual')
            ->where('publicaciones.deleted','=',0)
            ->groupBy('publicaciones.id')
            ->orderBy('publicaciones.created_at','desc')
            ->get(array(
                'usuario.username',
                'usuario.name',
                'usuario.lastname',
                'usuario.email',
                'usuario.phone',
                'usuario.pag_web',
                'usuario.id_carnet',
                'usuario.nit',
                'categoria.nombre as categoria_desc',
                'publicaciones.titulo',
                'publicaciones.categoria',
                'publicaciones.ubicacion',
                'publicaciones.id',
                'publicaciones.fechIni',
                'publicaciones.fechFin'));
        }
        return View::make('admin.pagos')->with('title',$title)->with('publicaciones',$publicaciones)->with('type',$type);
    }
    public function getPublication()
    {
        $title = "Administración de publicacione | pasillo24.com";
        return View::make('admin.publications')->with('title',$title);  
    }
    public function getPublicationType($type)
    {
        $title = "Administración de publicaciones LÍDER | pasillo24.com";
        $publicaciones = "";
        if ($type == 'lider') {
            $pub = Publicaciones::leftJoin('categoria','categoria.id','=','publicaciones.categoria')
            ->join('usuario','usuario.id','=','publicaciones.user_id')
            ->where('publicaciones.tipo','=','Lider')
            ->where(function($query){
                $query->where('publicaciones.status','=','Procesando')
                ->orWhere('publicaciones.status','=','Aprobado');
            })
            ->where('publicaciones.deleted','=',0)
            ->orderBy('fechIni','desc')
            ->get(array(
                'usuario.username',
                'usuario.name',
                'usuario.lastname',
                'usuario.email',
                'usuario.phone',
                'usuario.pag_web',
                'usuario.id_carnet',
                'usuario.nit',
                'publicaciones.id',
                'publicaciones.titulo',
                'publicaciones.ubicacion',
                'publicaciones.pag_web',
                'categoria.nombre',
                'publicaciones.fechIni',
                'publicaciones.fechFin',
                'publicaciones.name as name_pub',
                'publicaciones.phone as phone_pub',
                'publicaciones.email as email_pub',
                'publicaciones.pag_web_hab as pag_pub',
            ));
            $publicaciones = $pub;
        }elseif($type == 'habitual')
        {
            $pub = Publicaciones::join('categoria','categoria.id','=','publicaciones.categoria')
            ->join('usuario','usuario.id','=','publicaciones.user_id')
            ->where('publicaciones.tipo','=','Habitual')
            ->where('publicaciones.deleted','=',0)
            ->where(function($query){
                $query->where('publicaciones.status','=','Procesando')
                ->orWhere('publicaciones.status','=','Aprobado');
            })
            ->orderBy('fechIniNormal','desc')
            ->get(array(
                'usuario.username',
                'usuario.name',
                'usuario.lastname',
                'usuario.email',
                'usuario.phone',
                'usuario.pag_web',
                'usuario.id_carnet',
                'usuario.nit',
                'publicaciones.id',
                'publicaciones.titulo',
                'publicaciones.ubicacion',
                'categoria.desc as categoria',
                'publicaciones.precio',
                'publicaciones.moneda',
                'publicaciones.fechIni',
                'publicaciones.fechFin',
                'publicaciones.fechIniNormal',
                'publicaciones.fechFinNormal',
                'publicaciones.name as name_pub',
                'publicaciones.phone as phone_pub',
                'publicaciones.email as email_pub',
                'publicaciones.pag_web_hab as pag_pub',
            ));
            $publicaciones = $pub;
        }elseif($type == 'casual')
        {
            $pub = Publicaciones::join('categoria','categoria.id','=','publicaciones.categoria')
            ->join('usuario','usuario.id','=','publicaciones.user_id')
            ->where('publicaciones.tipo','=','Casual')
            ->where('publicaciones.deleted','=',0)
            ->get(array(
                'usuario.username',
                'usuario.name',
                'usuario.lastname',
                'usuario.email',
                'usuario.phone',
                'usuario.pag_web',
                'usuario.id_carnet',
                'usuario.nit',
                'publicaciones.id',
                'publicaciones.titulo',
                'publicaciones.ubicacion',
                'categoria.nombre',
                'publicaciones.fechIni',
                'publicaciones.fechFin'
            ));
            $publicaciones = $pub;
        }
        return View::make('admin.publications')
        ->with('title',$title)
        ->with('type',$type)
        ->with('publications',$publicaciones);
    }
    public function getNewAdmin()
    {
        $title = "Crear nuevo administrador | pasillo24.com";
        return View::make('admin.createUser')->with('title',$title);
    }
    public function postNewAdmin()
    {
        $input = Input::all();
        $user = User::where('username','=',$input['adminUser'])->get();
        if (count($user)>0) {
            Session::flash('error', 'El nombre de usuario ya existe');
            return Redirect::to('administrador/crear-nuevo');
        }else
        {
            $rules = array(
                'adminUser' => 'required',
                'pass' => 'required|min:8',
                'pass2' => 'required|same:pass'
            );      
            $messages = array(
                'required' => ':attribute es obligatorio',
                'min'      => ':attribute debe tener al menos 8 caracteres',
                'same'     => ':attribute no coincide'
            );
            $attributes = array(
                'adminUser'  => 'El campo nombre de administrador',
                'pass'  => 'El campo contraseña nueva',
                'pass2'  => 'El campo repetir contraseña'
            );
            $validator = Validator::make($input, $rules, $messages, $attributes);
            if ($validator->fails()) {
                return Redirect::to('administrador/crear-nuevo')->withErrors($validator)->withInput();
            }
            $user = new User;
            $user->username = $input['adminUser'];
            $user->password = Hash::make($input['pass']);
            $user->email    = $input['adminUser'].'@pasillo24.com';
            $user->role     = $input['role'];

            if ($user->save()) {
                $data = array(
                    'username' => $input['adminUser'],
                    'createBy' => Auth::user()['username']
                );
                Mail::send('emails.newAdmin', $data, function ($message) use ($input){
                        $message->subject('Correo creacion de usuario pasillo24.com');
                        $message->to('gestor@pasillo24.com');
                    });
                Session::flash('success', 'El usuario fue creado satisfactoriamente');
                return Redirect::to('administrador/crear-nuevo');
            }else
            {
                Session::flash('error', 'la contraseña no se pudo cambiar.');
                return Redirect::to('administrador/crear-nuevo');
            }
        }
    }
    public function getUserElim()
    {
        $title ="Eliminar usuarios | pasillo24.com";
        if (Auth::user()->role == 'Administrador') {
            $sql = '(`usuario`.`role` = "Usuario" or `usuario`.`role` = "Gestor")';
        }else
        {
            $sql = '`usuario`.`role` = "Usuario"';
        }
        $user = User::leftJoin('departamento','usuario.state','=','departamento.id')
        ->where('usuario.user_deleted','=',0)
        ->whereRaw(DB::raw($sql))
        ->orderBy('usuario.role')
        ->orderBy('usuario.username')
        ->get(array(
            'usuario.username',
            'usuario.name',
            'usuario.lastname',
            'usuario.email',
            'usuario.id_carnet',
            'usuario.id',
            'usuario.nit',
            'usuario.role',
            'departamento.nombre'
        ));
        return View::make('admin.elimUsers')->with('title',$title)->with('users',$user);
    }
    public function  postUserElim()
    {
        if (Request::ajax()) {
            $id = Input::get('id');
            $pub = Publicaciones::where('user_id','=',$id)->get();
            $comment = Comentarios::where('user_id','=',$id)->get();
            $resp    = Respuestas::where('user_id','=',$id)->get();
            $user    = User::find($id);
            if (count($pub)>0) {
                foreach($pub as $p)
                {
                    $p->deleted = 1;
                    $p->save();
                }
            }
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
            $username = $user->username;
            $email    = $user->email;
            $user->user_deleted = 1;

            $subject = "Correo de administrador";
            $admin = Auth::user()['username'];

            $data = array(
                'subject' => $subject,
                'usuario' => $username,
                'creadoPor'=> $admin
            );

            $to_Email = 'gestor@pasillo24.com';

            Mail::send('emails.elimUser', $data, function($message) use ($admin,$to_Email,$subject,$username)
            {
                $message->to($to_Email)->from('pasillo24.com@pasillo24.com')->subject($subject);
            });
            $to_Email = $email;

            $data = array(
                'subject' => 'Usuario eliminado en pasillo24.com',
            );
            Mail::send('emails.elimUserForUser', $data, function($message) use ($to_Email,$subject)
            {
                $message->to($to_Email)->from('pasillo24.com@pasillo24.com')->subject($subject);
            });
            $user->save();

            return Response::json(array('type' => 'success','msg' => 'Usuario eliminado satisfactoriamente.'));
        }
    }
    public function postElimPub()
    {
        $id = Input::get('id');
        $motivo = Input::get('motivo');
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
        $user = User::find($pub->user_id);
        $subject = "Correo de Aviso";

        $data = array(
            'subject' => $subject,
            'publicacion' => $titulo,
            'motivo'      => $motivo,
        );
        $to_Email = $user->email;
        Mail::send('emails.elimPubUser', $data, function($message) use ($titulo,$to_Email,$subject)
        {
            $message->to($to_Email)->from('pasillo24.com@pasillo24.com')->subject($subject);
        });
        $pub->deleted = 1;
        $pub->save();

        $subject = "Correo de administrador";
        $admin = Auth::user()['username'];

        $data = array(
            'subject' => $subject,
            'publicacion' => $titulo,
            'creadoPor'=> $admin
        );
        $to_Email = 'gestor@pasillo24.com';
        Mail::send('emails.elmPub', $data, function($message) use ($titulo,$admin,$to_Email,$subject)
        {
            $message->to($to_Email)->from('pasillo24.com@pasillo24.com')->subject($subject);
        });
        return Response::json(array('type' => 'success','msg' => 'Publicación eliminada satisfactoriamente. Hemos enviado un email al correo.'));
    }
    public function getModifyPub()
    {
        $textos = Textos::all();
        $title = "Cambiar texto | pasillo24.com";
        return View::make('admin.modifyText')
        ->with('title',$title)
        ->with('textos',$textos);
    }
    public function postModifyPub()
    {
        $input = Input::all();
        $rules = array(
            'desc1' => 'required',
            'desc2' => 'required',
            'desc3' => 'required',
        );  

        $messages = array(
            'required' => ':attribute es obligatorio'
        );
        $attributes = array(
            'desc1' => 'El texto Lider',
            'desc2' => 'El texto Habitual',
            'desc3' => 'El texto Casual'
        );
        $validator = Validator::make($input, $rules, $messages, $attributes);
        if ($validator->fails()) {
            return Redirect::to('administrador/modificar-publicaciones')->withErrors($validator);
        }
        $text1 = Textos::find(1);
        $text1->desc = $input['desc1'];
        $text1->save();
        $text2 = Textos::find(2);
        $text2->desc = $input['desc2'];
        $text2->save();
        $text3 = Textos::find(3);
        $text3->desc = $input['desc3'];
        $text3->save();

        $subject = "Correo de administrador";
        $admin = Auth::user()['username'];

        $data = array(
            'subject' => $subject,
            'creadoPor'=> $admin
        );
        $to_Email = 'gestor@pasillo24.com';
        Mail::send('emails.mdfText', $data, function($message) use ($admin,$to_Email,$subject)
        {
            $message->to($to_Email)->from('pasillo24.com@pasillo24.com')->subject($subject);
        });
        Session::flash('success', 'Textos guardados satisfactoriamente. Se ha enviado un correo al administrador.');
        return Redirect::to('administrador/modificar-publicaciones');
    }
    public function getModifyPrice()
    {
        $title = 'Cambiar precios | pasillo24.com';
        $princ = Precios::where('pub_type_id','=',1)->get();
        $cat   = Precios::where('pub_type_id','=',2)->get();
        return View::make('admin.modifyPrice')
        ->with('title',$title)
        ->with('princ',$princ)
        ->with('cat',$cat);
    }
    public function postModifyPrice()
    {
        $input = Input::all();
        if (isset($input['princ1'])) {
            $texto = Precios::find(1);
            $texto->precio = $input['princ1'];
            $texto->save();
        }
        if (isset($input['princ2'])) {
            $texto = Precios::find(2);
            $texto->precio = $input['princ2'];
            $texto->save();
        }
        if (isset($input['princ3'])) {
            $texto = Precios::find(3);
            $texto->precio = $input['princ3'];
            $texto->save();
        }
        if (isset($input['cat4'])) {
            $texto = Precios::find(4);
            $texto->precio = $input['cat4'];
            $texto->save();
        }
        if (isset($input['cat5'])) {
            $texto = Precios::find(5);
            $texto->precio = $input['cat5'];    
            $texto->save();
        }
        if (isset($input['cat6'])) {
            $texto = Precios::find(6);
            $texto->precio = $input['cat6'];
            $texto->save();
        }
        $subject = "Correo de administrador";
        $admin = Auth::user()['username'];
        $data = array(
            'subject' => $subject,
            'creadoPor'=> $admin
        );
        $to_Email = 'gestor@pasillo24.com';
        Mail::send('emails.mdfPrice', $data, function($message) use ($admin,$to_Email,$subject)
        {
            $message->to($to_Email)->from('pasillo24.com@pasillo24.com')->subject($subject);
        });
        Session::flash('success', 'Precios cambiados correctamentes');
        return Redirect::to('administrador/modificar-precios');
    }

    public function getAddAccount()
    {
        $title = "Nueva cuenta | pasillo24.com";
        $bancos = Bancos::all();
        return View::make('admin.addBank')
        ->with('title',$title)
        ->with('bancos',$bancos);
    }
    public function postAddAccount()
    {
        $input = Input::all();
        $rules = array(
            'banco'      => 'required',
            'numCuenta'  => 'required',
            'tipoCuenta' => 'required'
        );
        $messages = array(
            'required' => ':attribute es obligatorio'
        );
        $custom = array(
            'banco' => 'El campo banco',
            'numCuenta' => 'El campo numero de cuenta',
            'tipoCuenta'=> 'El campo tipo de cuenta'
        );
        $validator = Validator::make($input, $rules, $messages, $custom);
        if ($validator->fails()) {
            Session::flash('error', 'Error al validar algunos campos.');
            return Redirect::to('administrador/agregar-cuenta')->withErrors($validator)->withInput();
        }
        $numCuenta = new NumCuentas;
        $numCuenta->banco_id   = $input['banco'];
        $numCuenta->num_cuenta = $input['numCuenta'];
        $numCuenta->tipoCuenta = $input['tipoCuenta'];
        $subject = "Correo de administrador";
        $admin = Auth::user()['username'];
        $num = $input['numCuenta'];
        if ($numCuenta->save()) {
            $data = array(
                'subject' => $subject,
                'num'     => $num,
                'creadoPor'=> $admin
            );
            $to_Email = 'gestor@pasillo24.com';
            Mail::send('emails.newAcc', $data, function($message) use ($admin,$to_Email,$subject,$num)
            {
                $message->to($to_Email)->from('pasillo24.com@pasillo24.com')->subject($subject);
            });
            Session::flash('success', 'Número de cuenta creado satisfactoriamente.');
            return Redirect::to('administrador/agregar-cuenta');
        }else
        {
            Session::flash('error', 'Error al validar algunos campos.');
            return Redirect::to('administrador/agregar-cuenta')->withInput();
        }
    }
public function getEditPub()
    {
        $title = "Editar publicidad | pasillo24.com";

        $publi = Publicidad::get();

        return View::make('admin.editPublicidad')
        ->with('title',$title)
        ->with('publi',$publi);
    }
    public function postEditPublicidad($pos)
    {

        $input = Input::all();
        $rules = array(
            'imagen'    => 'required|image'
        );
        $validator = Validator::make($input,$rules);
        if ($validator->fails()) {
            Session::flash('danger', 'Error, compruebe que el archivo sea una imagen.');
            return Redirect::back();
        }
        $publi = Publicidad::where('pos','=',$pos)->first();
        if (file_exists('images/publicidad/'.$publi->image)) {
            File::delete('images/publicidad/'.$publi->image);
        }
        $publi->pag_web = Input::get('pag_web');
        $file = Input::file('imagen');
        if (file_exists('images/publicidad/'.$file->getClientOriginalName())) {
            //guardamos la imagen en public/imgs con el nombre original
            $i = 0;//indice para el while
            //separamos el nombre de la img y la extensión
            $info = explode(".",$file->getClientOriginalName());
            //asignamos de nuevo el nombre de la imagen completo
            $miImg = $file->getClientOriginalName();
            //mientras el archivo exista iteramos y aumentamos i
            while(file_exists('images/publicidad/'. $miImg)){
                $i++;
                $miImg = $info[0]."(".$i.")".".".$info[1];              
            }
            //guardamos la imagen con otro nombre ej foto(1).jpg || foto(2).jpg etc
            $file->move("images/publicidad/",$miImg);
            if($miImg != $file->getClientOriginalName()){
                $publi->image = $miImg;
            }
        }else
        {
            $file->move("images/publicidad/",$file->getClientOriginalName());
            $publi->image = $file->getClientOriginalName();
        }
        if ($publi->save()) {
            Session::flash('success', 'Se ha actualizado la publicidad');
            return Redirect::back();
        }else
        {
            Session::flash('danger', 'Error al actualizar la publicidad');
            return Redirect::back();
        }
    }
    public function postElimSlides()
    {
        if (Request::ajax()) {
            $id = Input::get('id');
            $st = Input::get('status');

            $publi = Publicidad::find($id);

            if ($st == 1) {
                $publi->activo = 0; 
            }else
            {
                $publi->activo = 1;
            }
            if($publi->save())
            {
                return Response::json(array('type' => 'success','msg' => 'Slide eliminado satisfactoriamente'));
            }else
            {
                return Response::json(array('type' =>'danger','msg' =>'Error al eliminar el slide'));
            }

        }
    }

    public function getCategories()
    {
        $title = "Categorias | pasillo24";
        $cat = Categorias::where('deleted','=',0)
        ->orderBy('tipo')
        ->orderBy('nombre')
        ->get();
        return View::make('admin.categories')
        ->with('title',$title)
        ->with('cat',$cat);
    }
    public function getModifyCategories($id)
    {
        $cat = Categorias::find($id);
        $title = "Modificar Categorias | pasillo24";
        return View::make('admin.modifyCat')
        ->with('title',$title)
        ->with("cat",$cat)
        ->with('id',$id);
    }
    public function postModifyCategories()
    {
        $dat = Input::all();
        $rules = array(
            'name' => 'required|min:4|max:64',
            'type' => 'required',
        );
        $msg = array(
            'name.required' => 'El nombre de la categoria es obligatorio',
            'type.required' => 'El tipo de la categoria es obligatorio',

            'min'      => 'El nombre de la categoria debe tener un minimo de 4 caracteres',
            'max'      => 'El nombre de la categoria debe tener un maximo de 64 caracteres'
        );
        $validator = Validator::make($dat, $rules, $msg);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        }
        $id = Input::get('id');
        $cat = Categorias::find($id);
        $cat->nombre = $dat['name'];
        $cat->tipo   = $dat['type'];
        if ($cat->save()) {
            Session::flash('success','Se ha modificado satisfactoriamente la categoria.');
            return Redirect::to('administrador/categorias');
        }else
        {
            Session::flash('error','Error al modificar la categoria.');
            return Redirect::back();
        }
    }
    public function postElimCat()
    {
        if (Request::ajax()) {
            $id = Input::get('id');
            $cat = Categorias::find($id);
            $cat->deleted = 1;
            if ($cat->save()) {
                return Response::json(array(
                    'type' => 'success',
                    'msg'  => 'Se ha eliminado satisfactoriamente la categoria.'
                ));
            }else
            {
                 return Response::json(array(
                    'type' => 'danger',
                    'msg'  => 'Error al eliminar la categoria.'
                ));
            }
        }
    }
    public function getSubCat()
    {
        $title = "Categorias | pasillo24";
        $cat = Categorias::where('deleted','=',0)
        ->where('tipo','=',1)
        ->orderBy('nombre')
        ->get();
        $otros = new StdClass;
        $otros->id = null;
        foreach ($cat as $c) {
            if (strtolower($c->nombre) == 'otros') {
                $otros->id      = $c->id;
                $otros->nombre  = $c->nombre;           
            }
        }
        $ser = Categorias::where('deleted','=',0)
        ->where('tipo','=',2)
        ->orderBy('nombre')
        ->get();
        $otros2 = new StdClass;
        $otros2->id = null;
        foreach ($ser as $c) {
            if (strtolower($c->nombre) == 'otros') {
                $otros2->id      = $c->id;
                $otros2->nombre  = $c->nombre;           
            }
        }
        $cats = SubCat::leftJoin('categoria','categoria.id','=','subcategoria.categoria_id')
        ->where('subcategoria.deleted','=',0)
        ->orderBy('categoria.nombre')
        ->orderBy('subcategoria.desc')
        ->paginate(50,array(
            'subcategoria.id',
            'subcategoria.desc',
            'categoria.nombre',
        ));
        return View::make('admin.subcategories')
        ->with('title',$title)
        ->with('cat',$cat)
        ->with('ser',$ser)
        ->with('otros',$otros)
        ->with('otros2',$otros2)
        ->with('cats',$cats);
    }
    public function getModifySubCategories($id)
    {
        $sc = SubCat::find($id);
        $cat = Categorias::where('deleted','=',0)->get();
        $title = "Modificar Sub-categorias | pasillo24";
        return View::make('admin.modifySubCat')
        ->with('title',$title)
        ->with("sc",$sc)
        ->with('cat',$cat)
        ->with('id',$id);
    }
    public function postModifySubCategories()
    {
        $dat = Input::all();
        $rules = array(
            'name'      => 'required|min:4|max:64',
            'categoria' => 'required',
        );
        $msg = array(
            'name.required'      => 'El campo nombre de la categoria es obligatorio',
            'categoria.required' => 'El campo categoria es obligatorio',
            'min'                => 'El campo nombre de la categoria debe tener un minimo de 4 caracteres',
            'max'                => 'El campo nombre de la categoria debe tener un maximo de 64 caracteres'
        );
        $validator = Validator::make($dat, $rules, $msg);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        }
        $id                 = Input::get('id');
        $cat                = SubCat::find($id);
        $cat->desc          = $dat['name'];
        $cat->categoria_id  = $dat['categoria'];
        if ($cat->save()) {
            Session::flash('success','Se ha modificado satisfactoriamente la sub-categoria.');
            return Redirect::to('administrador/sub-categorias');
        }else
        {
            Session::flash('error','Error al modificar la sub-categoria.');
            return Redirect::back();
        }
    }
    public function postElimSubCat()
    {
        if (Request::ajax()) {
            $id = Input::get('id');
            $cat = SubCat::find($id);
            $cat->deleted = 1;
            if ($cat->save()) {
                return Response::json(array(
                    'type' => 'success',
                    'msg'  => 'Se ha eliminado satisfactoriamente la sub-categoria.'
                ));
            }else
            {
                 return Response::json(array(
                    'type' => 'danger',
                    'msg'  => 'Error al eliminar la sub-categoria.'
                ));
            }
        }
    }
    public function postNewCat()
    {
        $dat = Input::all();
        $rules = array(
            'name' => 'required|min:4|max:64',
            'type' => 'required',
        );
        $msg = array(
            'name.required' => 'El nombre de la categoria es obligatorio',
            'type.required' => 'El tipo es obligatorio',
            'min'      => 'El nombre de la categoria debe tener un minimo de 4 caracteres',
            'max'      => 'El nombre de la categoria debe tener un maximo de 64 caracteres'
        );
        $validator = Validator::make($dat, $rules, $msg);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        }

        $nomb = Input::get('name');
        $type = Input::get('type');
        $cat = new Categorias;
        $cat->nombre = $nomb;
        $cat->tipo   = $type;
        if ($cat->save()) {
            Session::flash('success','Se ha creado la categoria satisfactoriamente.');
            return Redirect::back();
        }else
        {
            Session::flash('danger','Error al guardar la nueva categoria.');
            return Redirect::back();
        }
    }
    public function postNewSubCat()
    {
        $dat = Input::all();
        $rules = array(
            'name' => 'required|min:4|max:64',
            'cat'  => 'required'
        );
        $msg = array(
            'name.required'      => 'El campo nombre de la categoria es obligatorio',
            'categoria.required' => 'El campo categoria es obligatorio',
            'min'                => 'El campo nombre de la categoria debe tener un minimo de 4 caracteres',
            'max'                => 'El campo nombre de la categoria debe tener un maximo de 64 caracteres'
        );
        $validator = Validator::make($dat, $rules, $msg);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        }

        $nomb               = Input::get('name');
        $cat_id             = Input::get('cat');
        $cat                = new SubCat;
        $cat->desc          = $nomb;
        $cat->categoria_id  = $cat_id;
        if ($cat->save()) {
            Session::flash('success','Se ha creado la sub-categoria satisfactoriamente.');
            return Redirect::back();
        }else
        {
            Session::flash('danger','Error al guardar la nueva sub-categoria.');
            return Redirect::back();
        }
    }
    public function getCuentas()
    {
        $title = 'Cuentas bancarias | pasillo24';

        $cuentas = NumCuentas::leftJoin('bancos','bancos.id','=','numcuentas.banco_id')
        ->orderBy('bancos.id')
        ->get(
            array(
                'numcuentas.id',
                'numcuentas.tipoCuenta',
                'numcuentas.num_cuenta',
                'bancos.nombre'
            )
        );

        return View::make('admin.accounts')
        ->with('title',$title)
        ->with('cuentas',$cuentas);
    }
    public function modifyAccount($id)
    {
        $title = "Modificar cuenta | pasillo24";

        $bancos = Bancos::get();

        $acc    = NumCuentas::find($id);

        return View::make('admin.modifyAccount')
        ->with('title',$title)
        ->with('acc',$acc)
        ->with('bancos',$bancos)
        ->with('id',$id);
    }
    public function postModifyAccount($id)
    {
        $d = Input::all();
        $rules = array(
            'num'        => 'required',
            'tipo'       => 'required',
            'banco'      => 'required',
        );
        $msg = array(
            'num.required'  => 'El numero de cuenta es obligatorio',
            'tipo.required' => 'El tipo de cuenta es obligatorio',
            'banco.required' => 'El banco es obligatorio',

        );
        $validator = Validator::make($d, $rules, $msg);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        }

        $acc = NumCuentas::find($id);
        $acc->num_cuenta = $d['num'];
        $acc->tipoCuenta = $d['tipo'];
        $acc->banco_id   = $d['banco'];
        if ($acc->save()) {
            $data = array(
                'subject' => 'Cuenta bancaria modificada en pasillo24.com',
                'num'     => $d['num'],
                'creadoPor'=> Auth::user()->username
            );
            $to_Email = 'gestor@pasillo24.com';
            Mail::send('emails.modifyAcc', $data, function($message)
            {
                $message->to('gestor@pasillo24.com')->from('pasillo24.com@pasillo24.com')->subject('Cuenta bancaria modificada en pasillo24.com');
            });
            Session::flash('success','Se modificado la cuenta satisfactoriamente.');
            return Redirect::to('administrador/editar-cuenta');
        }else
        {
            Session::flash('danger','Error al modificar la cuenta.');
            return Redirect::back();
        }
    }
    public function postElimAccount()
    {
        $id = Input::get('id');
        if (is_null($id)) {
            return Response::json(array('type' => 'danger','msg' =>'Error al eliminar la cuenta'));
        }

        $acc = NumCuentas::find($id);
        if($acc->delete()){
            return Response::json(array('type' => 'success','msg' => 'Se ha eliminado la cuenta satisfactoriamente.'));
        }
    }
    
}