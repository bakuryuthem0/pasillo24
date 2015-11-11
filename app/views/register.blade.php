@extends('main')
@section('content')
<div class="container contenedorUnico">
	<div class="row">
		<div class="col-xs-12">
			
			<div class="col-xs-8 col-sm-offset-2 contForm contAnaranjado" style="margin-top:2em;">
				@if (Session::has('error'))
				<div class="col-xs-6">
					<div class="alert alert-danger">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						<p class="textoPromedio">{{ Session::get('error') }}</p>
					</div>
				</div>
				<div class="clearfix"></div>
				@endif
				<div class="col-xs-12">
					<div class="col-xs-12">
						<legend>Formulario de registro</legend>
						<p class="textoPromedio">Llene el siguiente formulario para registrarse en pasillo24.com.</p>
						<p class="textoPromedio">(*) Campos obligatorios.</p>
						<hr>
					</div>						
				</div>
				<form action="{{ URL::to('inicio/registro/enviar') }}" method="POST">
					<div class="col-xs-12 formulario">
						<div class="col-xs-6 inputRegister">
							<p class="textoPromedio">(*) Nombre de usuario:</p>
						</div>
						<div class="col-xs-6 inputRegister">
							{{ Form::text('username', Input::old('username'),array('class' => 'form-control','placeholder' => 'Nombre de Usuario','required' => 'required')) }}
							@if ($errors->has('username'))
								 @foreach($errors->get('username') as $err)
								 	<div class="alert alert-danger">
								 		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
								 		<p class="textoPromedio">{{ $err }}</p>
								 	</div>
								 @endforeach
							@endif
						</div>
					</div>
					<div class="col-xs-12 formulario">
						<div class="col-xs-6 inputRegister">
							<p class="textoPromedio">(*) Contraseña:</p>
						</div>
						<div class="col-xs-6 inputRegister">
							{{ Form::password('pass',array('class' => 'form-control','placeholder' => 'Contraseña','required' => 'required')) }}
							@if ($errors->has('pass'))
								 @foreach($errors->get('pass') as $err)
								 	<div class="alert alert-danger">
								 		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
								 		<p class="textoPromedio">{{ $err }}</p>
								 	</div>
								 @endforeach
							@endif
						</div>
					</div>
					<div class="col-xs-12 formulario">
						<div class="col-xs-6 inputRegister">
							<p class="textoPromedio">(*) Repita la contraseña:</p>
						</div>
						<div class="col-xs-6 inputRegister">
							{{ Form::password('pass_confirmation',array('id' => 'pass2','class' => 'form-control','placeholder' => 'Contraseña','required' => 'required')) }}
							@if ($errors->has('pass_confirmation'))
								 @foreach($errors->get('pass_confirmation') as $err)
								 	<div class="alert alert-danger">
								 		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
								 		<p class="textoPromedio">{{ $err }}</p>
								 	</div>
								 @endforeach
							@endif
						</div>
					</div>
					<div class="col-xs-12 formulario">
						<div class="col-xs-6 inputRegister">
							<p class="textoPromedio">(*) Email:</p>
						</div>
						<div class="col-xs-6 inputRegister">
							{{ Form::text('email', Input::old('email'),array('class' => 'form-control','placeholder' => 'Email','required' => 'required')) }}
							@if ($errors->has('email'))
								 @foreach($errors->get('email') as $err)
								 	<div class="alert alert-danger">
								 		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
								 		<p class="textoPromedio">{{ $err }}</p>
								 	</div>
								 @endforeach
							@endif
						</div>
					</div>
					<div class="col-xs-12 formulario">
						<div class="col-xs-6 inputRegister">
							<p class="textoPromedio">(*) Nombre:</p>
						</div>
						<div class="col-xs-6 inputRegister">
							{{ Form::text('name', Input::old('name'),array('class' => 'form-control','placeholder' => 'Nombre','required' => 'required')) }}
							@if ($errors->has('name'))
								 @foreach($errors->get('name') as $err)
								 	<div class="alert alert-danger">
								 		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
								 		<p class="textoPromedio">{{ $err }}</p>
								 	</div>
								 @endforeach
							@endif
						</div>
					</div>
					<div class="col-xs-12 formulario">
						<div class="col-xs-6 inputRegister">
							<p class="textoPromedio">(*) Apellido</p>
						</div>
						<div class="col-xs-6 inputRegister">
							{{ Form::text('lastname', Input::old('lastname'),array('class' => 'form-control','placeholder' => 'Apellido','required' => 'required')) }}
							@if ($errors->has('lastname'))
								 @foreach($errors->get('lastname') as $err)
								 	<div class="alert alert-danger">
								 		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
								 		<p class="textoPromedio">{{ $err }}</p>
								 	</div>
								 @endforeach
							@endif
						</div>
					</div>
					<div class="col-xs-12 formulario">
						<div class="col-xs-6 inputRegister">
							<p class="textoPromedio">(*) Carnet de Identidad</p>
						</div>
						<div class="col-xs-6 inputRegister">
							{{ Form::text('id', Input::old('id'),array('class' => 'form-control','placeholder' => 'Carnet','required' => 'required')) }}
							@if ($errors->has('id'))
								 @foreach($errors->get('id') as $err)
								 	<div class="alert alert-danger">
								 		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
								 		<p class="textoPromedio">{{ $err }}</p>
								 	</div>
								 @endforeach
							@endif
						</div>
					</div>
					<div class="col-xs-12 formulario">
						<div class="col-xs-6 inputRegister">
							<p class="textoPromedio">Nombre de empresa</p>
						</div>
						<div class="col-xs-6 inputRegister">
							{{ Form::text('empresa', Input::old('empresa'),array('class' => 'form-control','placeholder' => 'Nombre de empresa')) }}
							@if ($errors->has('empresa'))
								 @foreach($errors->get('empresa') as $err)
								 	<div class="alert alert-danger">
								 		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
								 		<p class="textoPromedio">{{ $err }}</p>
								 	</div>
								 @endforeach
							@endif
						</div>
					</div>
					<div class="col-xs-12 formulario">
						<div class="col-xs-6 inputRegister">
							<p class="textoPromedio">NIT</p>
						</div>
						<div class="col-xs-6 inputRegister">
							{{ Form::text('nit', Input::old('nit'),array('class' => 'form-control','placeholder' => 'NIT')) }}
							@if ($errors->has('nit'))
								 @foreach($errors->get('nit') as $err)
								 	<div class="alert alert-danger">
								 		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
								 		<p class="textoPromedio">{{ $err }}</p>
								 	</div>
								 @endforeach
							@endif
						</div>
					</div>
					<div class="col-xs-12 formulario">
						<div class="col-xs-6">
							<p class="textoPromedio inputRegister">(*) Sexo:</p>
						</div>
						<div class="col-xs-6 inputRegister">
							{{ Form::select('sexo',array(
								'' => 'Seleccione su sexo',
								'm' => 'Masculino',
								'f' => 'Femenino'),Input::old('sexo'),array('class' => 'form-control','requied' => 'required')
							)}}
							
							@if ($errors->has('sexo'))
								 @foreach($errors->get('sexo') as $err)
								 	<div class="alert alert-danger">
								 		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
								 		<p class="textoPromedio">{{ $err }}</p>
								 	</div>
								 @endforeach
							@endif
						</div>
					</div>
					<div class="col-xs-12 formulario">
						<div class="col-xs-6 inputRegister">
							<p class="textoPromedio">(*) Departamento:</p>
						</div>
						<div class="col-xs-6 inputRegister">
							<?php $arr = array(
									'' => 'Seleccione su departamento');
									 ?>
							@foreach ($departamentos as $departamento)
								<?php $arr = $arr+array($departamento->id => $departamento->nombre);  ?>
							@endforeach
							
							{{ Form::select('department',$arr,Input::old('department'),array('class' => 'form-control','requied' => 'required')
								)}}
							@if ($errors->has('department'))
								
								 @foreach($errors->get('department') as $err)
								 	<div class="alert alert-danger">
								 		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
								 		<p class="textoPromedio">{{ $err }}</p>
								 	</div>
								 @endforeach
							@endif
						</div>
					</div>
					<div class="col-xs-12 formulario">
						<div class="col-xs-6 inputRegister">
							<p class="textoPromedio">(*) Captcha:</p>
						</div>
						<div class="col-xs-6 inputRegister">
							<div class="g-recaptcha" data-sitekey="6Ld4vBATAAAAAATOgZbOLdHfjIwzATHX3hXmXlEQ"></div>
							@if ($errors->has('g-recaptcha-response'))
								
								 @foreach($errors->get('g-recaptcha-response') as $err)
								 	<div class="alert alert-danger">
								 		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
								 		<p class="textoPromedio">{{ $err }}</p>
								 	</div>
								 @endforeach
							@endif
						</div>
					</div>
					<div class="col-xs-12 formulario">
						<div class="col-xs-6 imgLiderUp">
							<input type="submit" id="enviar" name="enviar" value="Enviar" class="btn btn-success btnAlCien">
							<input type="reset" value="Borrar" class="btn btn-warning btnWarningRegister btnAlCien" >
						</div>
					</div>
					
				</form>
			</div>
		</div>
	</div>
</div>
@stop