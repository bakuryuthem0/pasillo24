<?php

class ContactoController extends BaseController {
	public function postContacto()
	{
		$input = Input::all();
		$rules = array(
		'nombre'	 => 'required|min:4',
		'asunto'	 => 'required|min:4',
		'email'  	 => 'required|email',
		'mensaje'    => 'required|min:4'
		);
		$messages = array(
		'required' => 'El :attribute es obligatorio',
		'min'      => 'El :attribute es muy corto',
		'email'    => 'Debe ingresar un :attribute valido'
		);
		$validator = Validator::make($input, $rules, $messages);
		if ($validator->fails()) {

			return Redirect::to('inicio/contactenos')->withErrors($validator)->withInput();
		}
		$to_Email     = 'atencionalclienteffasil@gmail.com';
		$user_Name    = $input['nombre'];
		$subject      = $input['asunto'];
		$user_Email   = $input['email'];
		$user_Message = $input['mensaje'];
		$data = array('subject' => $subject,
							'from' => $user_Email,
							'fecha' => date('Y/m/d H:i:s'),
							'mensaje' => $user_Message,
							'nombre'  => $input['nombre']
							);

			Mail::send('emails.envia', $data, function($message) use ($to_Email,$user_Name,$subject,$user_Email)
			{
				$message->to($to_Email)->from($user_Email)->subject($subject);
			});
			Session::flash('success', 'Mensaje enviado correctamente. pronto nuestros agentes se pondrán en contacto con usted.');
			return Redirect::to('inicio/contactenos');
	}
}