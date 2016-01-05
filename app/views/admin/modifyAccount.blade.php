@extends('main')

@section('content')
<div class="container contenedorUnico">
	<div class="row">
		<div class="col-xs-8 col-sm-offset-2 contAnaranjado imgLiderUp" style="margin-top:2em;">
			<legend style="text-align:center;">Modificar cuenta</legend>
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
			<form method="POST" action="{{ URL::to('administrador/editar-cuenta/'.$id.'/enviar') }}">
				<div class="col-xs-12 formulario">
					<label for="adminUser" class="textoPromedio">Numero de la cuenta</label>
					{{ Form::text('num',$acc->num_cuenta,array('class' => 'form-control','placeholder' => 'Nombre de la categoria')) }}
					@if ($errors->has('num'))
						 @foreach($errors->get('num') as $err)
						 	<div class="alert alert-danger">
						 		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						 		<p class="textoPromedio">{{ $err }}</p>
						 	</div>
						 @endforeach
					@endif
				</div>
				<div class="col-xs-12 formulario">
					<label for="adminUser" class="textoPromedio">Tipo de cuenta</label>
					{{ Form::text('tipo',$acc->tipoCuenta,array('class' => 'form-control','placeholder' => 'Tipo de cuenta')) }}
					@if ($errors->has('tipo'))
						 @foreach($errors->get('tipo') as $err)
						 	<div class="alert alert-danger">
						 		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						 		<p class="textoPromedio">{{ $err }}</p>
						 	</div>
						 @endforeach
					@endif
				</div>
				<div class="col-xs-12">
					<label class="textoPromedio">
						Banco
					</label>
					<select name="banco" class="form-control" required>
						@foreach($bancos as $b)
						<option value="{{ $b->id }}" @if($acc->banco_id == $b->id) selected @endif>{{ $b->nombre }}</option>
						@endforeach
					</select>
					@if ($errors->has('type'))
						 @foreach($errors->get('type') as $err)
						 	<div class="alert alert-danger">
						 		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						 		<p class="textoPromedio">{{ $err }}</p>
						 	</div>
						 @endforeach
					@endif
				</div>
				<div class="col-xs-12">
					<input type="hidden" name="id" value="{{ $id }}">
					<input type="submit" name="enviarNewUser" class="btn btn-success enviarNewAdmin">
				</div>
			</form>
		</div>
	</div>
</div>

@stop