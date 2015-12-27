@extends('main')

@section('content')
<div class="container contenedorUnico">
	<div class="row">
		<div class="col-xs-8 col-sm-offset-2 contAnaranjado imgLiderUp" style="margin-top:2em;">
			<legend style="text-align:center;">Modificar Sub-categoria</legend>
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
			<form method="POST" action="{{ URL::to('administrador/sub-categoria/modificar') }}">
				<div class="col-xs-12 formulario">
					<label for="adminUser" class="textoPromedio">Nombre de la sub-categoria</label>
					{{ Form::text('name',$sc->desc,array('class' => 'form-control','placeholder' => 'Nombre de la categoria')) }}
					@if ($errors->has('name'))
						 @foreach($errors->get('name') as $err)
						 	<div class="alert alert-danger">
						 		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						 		<p class="textoPromedio">{{ $err }}</p>
						 	</div>
						 @endforeach
					@endif
				</div>
				<div class="col-xs-12 formulario">
					<label for="adminUser" class="textoPromedio">Categoria</label>
					<select class="form-control" name="categoria">
						@foreach($cat as $c)
							@if($c->id == $sc->categoria_id)	
								<option value="{{ $c->id }}" selected>{{ $c->nombre }}</option>
							@else
								<option value="{{ $c->id }}">{{ $c->nombre }}</option>
							@endif
						@endforeach
					</select>
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
					<input type="hidden" name="id" value="{{ $id }}">
					<input type="submit" name="enviarNewUser" class="btn btn-success enviarNewAdmin">
				</div>
			</form>
		</div>
	</div>
</div>

@stop