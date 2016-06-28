@extends('main')

@section('content')
<div class="container contenedorUnico">
	<div class="row">
		<div class="col-xs-8 col-sm-offset-2 contAnaranjado imgLiderUp" style="margin-top:2em;">
			<legend style="text-align:center;">Modificar Categoria</legend>
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
			<form method="POST" action="{{ URL::to('administrador/categoria/modificar') }}">
				<div class="col-xs-12 formulario">
					<label for="adminUser" class="textoPromedio">Nombre de la categoria</label>
					{{ Form::text('name',$cat->nombre,array('class' => 'form-control','placeholder' => 'Nombre de la categoria')) }}
					@if ($errors->has('name'))
						 @foreach($errors->get('name') as $err)
						 	<div class="alert alert-danger">
						 		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						 		<p class="textoPromedio">{{ $err }}</p>
						 	</div>
						 @endforeach
					@endif
				</div>
				<div class="col-xs-12">
					<label class="textoPromedio">
						Tipo
					</label>
					<select name="type" class="form-control" required>
						<option value="1" @if($cat->tipo == 1) selected @endif>Categoria</option>
						<option value="2" @if($cat->tipo == 2) selected @endif>Servicios</option>
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
				<div class="col-xs-12 formulario">
					<input type="hidden" name="id" value="{{ $id }}">
					<input type="submit" name="enviarNewUser" class="btn btn-success enviarNewAdmin">
				</div>
			</form>
		</div>
	</div>
</div>

@stop