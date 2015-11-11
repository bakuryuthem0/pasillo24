@extends('main')

@section('content')

<div class="container contenedorUnico">
	<div class="row">
		<div class="col-xs-6 col-sm-offset-3 contAnaranjado textoPromedio imgLiderUp" style="padding:2em;">
			
			<legend style="text-align:center;">Agregar nueva cuenta</legend>
			<form method="POST" action="{{ URL::to('administrador/agregar-cuenta/procesar') }}">
				@if(Session::has('error'))
					<div class="alert alert-danger">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						<p>{{ Session::get('error') }}</p>
					</div>
				@elseif(Session::has('success'))
					<div class="alert alert-success">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						<p>{{ Session::get('success') }}</p>
					</div>
				@endif
				<div class="col-xs-12">
					<label>Seleccione el banco</label>
					<select name="banco" class="form-control">
						<option value="">Seleccione el banco</option>
						@foreach($bancos as $banco)
						<option value="{{ $banco->id }}">{{ $banco->nombre }}</option>
						@endforeach
					</select>
					@if ($errors->has('banco'))
						 @foreach($errors->get('banco') as $err)
						 	<div class="alert alert-danger">
						 		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						 		<p class="">{{ $err }}</p>
						 	</div>
						 @endforeach
					@endif
				</div>
				<div class="col-xs-12">
					<label>Ingrese su numero de cuenta</label>
					{{ Form::text('numCuenta',Input::old('numCuenta'),array('class' => 'form-control','placeholder' => 'Numero de cuenta')) }}
					@if ($errors->has('numCuenta'))
						 @foreach($errors->get('numCuenta') as $err)
						 	<div class="alert alert-danger">
						 		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						 		<p class="">{{ $err }}</p>
						 	</div>
						 @endforeach
					@endif
				</div>
				<div class="col-xs-12">
					<label>Ingrese el tipo de cuenta</label>
					{{ Form::text('tipoCuenta',Input::old('tipoCuenta'),array('class' => 'form-control','placeholder' => 'Tipo de cuenta')) }}
					@if ($errors->has('tipoCuenta'))
						 @foreach($errors->get('tipoCuenta') as $err)
						 	<div class="alert alert-danger">
						 		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						 		<p class="">{{ $err }}</p>
						 	</div>
						 @endforeach
					@endif
				</div>
				<div class="col-xs-12">
					<button class="btn btn-success">Enviar</button>
					<input type="reset" class="btn btn-warning" value="Borrar">
				</div>
			</form>	
		</div>
	</div>
</div>

@stop