@extends('main')

@section('content')
<div class="container contenedorUnico">
	<div class="row">
		<div class="col-xs-8 col-sm-offset-2 contAnaranjado imgLiderUp" style="margin-top:2em;">
			<legend style="text-align:center;">Crear nuevo administrador</legend>
			@if(Session::has('error'))
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
			<form method="POST" action="{{ URL::to('administrador/crear-nuevo/enviar') }}">
				<div class="col-xs-12">
					<label for="adminUser" class="textoPromedio">Nombre de usuario</label>
					{{ Form::text('adminUser',Input::old('adminUser'),array('class' => 'form-control adminUser','placeholder' => 'Nombre de usuario')) }}
					@if ($errors->has('adminUser'))
						 @foreach($errors->get('adminUser') as $err)
						 	<div class="alert alert-danger">
						 		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						 		<p class="textoPromedio">{{ $err }}</p>
						 	</div>
						 @endforeach
					@endif
				</div>
				<div class="col-xs-12">
					<label for="pass" class="textoPromedio">Contraseña</label>
					<input type="password" name="pass" class="form-control pass1" placeholder="Contraseña">
					@if ($errors->has('pass'))
						 @foreach($errors->get('pass') as $err)
						 	<div class="alert alert-danger">
						 		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						 		<p class="textoPromedio">{{ $err }}</p>
						 	</div>
						 @endforeach
					@endif
				</div>
				<div class="col-xs-12">
					<label for="" class="textoPromedio">Repita la contraseña</label>
					<input type="password" name="pass2" class="form-control pass2" placeholder="Repita la contraseña">
					@if ($errors->has('pass2'))
						 @foreach($errors->get('pass2') as $err)
						 	<div class="alert alert-danger">
						 		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						 		<p class="textoPromedio">{{ $err }}</p>
						 	</div>
						 @endforeach
					@endif
				</div>
				<div class="col-xs-12">
					<input type="submit" name="enviarNewUser" class="btn btn-success enviarNewAdmin">
				</div>
			</form>
		</div>
	</div>
</div>

@stop