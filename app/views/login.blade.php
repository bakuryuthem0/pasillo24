@extends('main')
@section('content')
<div class="container contenedorUnico">
	<div class="row">
		<div class="col-sm-12 col-md-8 center-block vcenter contAnaranjado">
			<form action="{{ URL::to('inicio/login/auth') }}" method="POST">
				@if (Session::has('error'))
				<div class="alert alert-danger">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<p class="textoPromedio">{{ Session::get('error') }}</p>
				</div>
				@elseif(Session::has('success'))
				<div class="alert alert-success">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<p class="textoPromedio">{{ Session::get('success') }}</p>
				</div>
				@endif
				<div class="input-group formulario">
					<label for="username" class="textoPromedio">Nombre de usuario:</label>
					{{ Form::text('username','', array('class'=>'form-control','required' => 'required')) }}
				</div>
				<div class="input-group formulario">
					<label for="pass" class="textoPromedio">Contraseña</label>
					<input type="password" name="pass" class="form-control" required>
				</div>
				<div class="input-group formulario">
					<label for="pass" class="textoPromedio"><a href="#" class="forgot" data-toggle="modal" data-target="#changePass">¿Olvidó su contraseña?</a></label>
				</div>
				<div class="input-group formulario">
					<label for="remember" class="textoPromedio">¿Recordar?</label>
					<input type="checkbox" name="remember">
				</div>
				<div class="input-group formulario">
					<input type="submit" name="enviar" value="Enviar" class="btn btn-primary">
				</div>
			</form>
		</div>
	</div>
	
</div>
<div class="modal fade" id="changePass" tabindex="-1" role="dialog" aria-labelledby="modalForggo" aria-hidden="true">
	<div class="forgotPass modal-dialog imgLiderUp">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h3>Recuperar Contraseña</h3>
			</div>
				<div class="modal-body">
					<div class="alert responseDanger">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						<p></p>
					</div>
					<p class="textoPromedio">Introduzca el email con el cual creó su cuenta</p>
					<input class="form-control emailForgot" name="email" placeholder="Email">
				</div>
				<div class="modal-footer">
						<img src="{{ URL::to('images/loading.gif') }}" class="miniLoader hidden">
						<button class="btn btn-success envForgot" >Enviar</button>	
				</div>
		</div>
	</div>
</div>
@stop