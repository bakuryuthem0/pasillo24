<?php

class AuthController extends BaseController {

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

	public function getLogin()
	{
		
		$title = "Inicio de Sesión";
		if (Auth::check())
        {
            return Redirect::to('inicio');
        }

		return View::make('login')->with('title',$title);

	}
	public function postLogin()
	{

		$input = Input::all();
		if (isset($input['remember'])) {
			$valor = true;
		}else
		{
			$valor = false;
		}
		$find = User::where('username','=',$input['username'])->pluck('user_deleted');
		if ($find == 1) {
			Session::flash('error', 'Su usuario ha sido eliminado, para más información contáctenos desde nuestro módulo de contacto.');
			return Redirect::to('inicio/login');
		}
		$userdata = array(
			'username' 	=> $input['username'],
			'password' 	=> $input['pass']

		);
		if (Auth::attempt($userdata,$valor)) {
			return Redirect::to('inicio');
		}else
		{
			Session::flash('error', 'Usuario o contraseña incorrectos');
			return Redirect::to('inicio/login');
		}
		
	}
	public function logOut()
	{
		Auth::logout();
		return Redirect::to('inicio');
	}
	public function getRegister()
	{
		$title = 'Registro';
		$departamentos = Department::all();
		return View::make('register')->with('title',$title)->with('departamentos',$departamentos);
	}
	public function postRegister()
	{
		$input = Input::all();
		$rules = array(
			'username'   			 => 'required|min:4|unique:usuario',
			'pass'      		 	 => 'required|min:6|confirmed',
			'pass_confirmation'      => 'required',
			'name'       			 => 'required|min:4',
			'lastname'   			 => 'required|min:4',
			'email'      			 => 'required|email|unique:usuario',
			'id'         			 => 'required|min:5|unique:usuario',
			'sexo'       			 => 'required|in:f,m',
			'department' 			 => 'required',
			'g-recaptcha-response'   	 => 'required'

		);
		$messages = array(
			'required' => ':attribute es obligatoria',
			'min'      => ':attribute debe ser mas largo',
			'email'    => 'Debe introducir un email válido',
			'unique'   => ':attribute ya existe',
			'confirmed'=> 'La contraseña no concuerdan'
		);
		$custom = array(
			'username' 			=> 'EL nombre de usuario',
			'pass'    	 		=> 'La contraseña',
			'pass_confirmation' => 'la confirmacion de la contraseña',
			'name'              => 'El nombre',
			'lastname'          => 'El apellido',
			'email' 			=> 'El email',
			'id'				=> 'El carnet de identificacion',
			'sexo'				=> 'El sexo',
			'departament'  		=> 'El departamento',
			'g-recaptcha-response'  => 'El captcha es obligatorio'
		);
		$validator = Validator::make($input, $rules, $messages,$custom);
		if ($validator->fails()) {
			return Redirect::to('inicio/registro')->withErrors($validator)->withInput();
		}
		
		$user = new User;
		$user->username 	 = $input['username'];
		$user->password    	 = Hash::make($input['pass']);
		$user->email    	 = $input['email'];
		$user->name     	 = $input['name'];
		$user->lastname 	 = $input['lastname'];
		$user->id_carnet	 = $input['id'];
		$user->nombEmp	     = $input['empresa'];
		$user->nit  		 = $input['nit'];
		$user->state  		 = $input['department'];
		if(!empty($input['phone']))
		{
			$user->phone =  $input['phone'];
		}
		$user->role          = 'Usuario';
		
		if ($user->save()) {
			Session::flash('success', 'Su cuenta se ha registrado satisfactoriamente, Inicie sesión para disfrutar de los beneficios de pasillo24.com');
return Redirect::to('inicio/login');
			
		}else
		{
			Session::flash('error','Error al crear el usuario, por favor contacte con el administrador del sitio');
			return Redirect::back()->withErrors($validator)->withInput();
		}
		
	}
	public function postEmailCheck()
	{
		if (Request::ajax()) {
			$email = Input::get('email');
			$user = User::where('email','=',$email)->first();
			if (count($user)<1) {
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
	}
	

}
?>