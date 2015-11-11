@extends('main')

@section('content')
<div class="container contenedorUnico">
	<div class="row">
		@if(Session::has('success'))
		<div class="col-xs-12">
			<div class="alert alert-success">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				<p class="textoPromedio">{{ Session::get('success') }}</p>
			</div>
		</div>
		@endif
		<div class="col-xs-12 contAnaranjado" style="margin-top:2em;padding:1em 0px 3em 3em;">
			<form method="POST" action="{{ URL::to('administrador/modificar-precios/enviar') }}">
				<legend>Precios página principal</legend>
				@foreach($princ as $p)
				<div class="col-xs-12">
					<label class="textoPromedio">{{ $p->desc }}</label>
					<input type="text" name="princ{{ $p->id }}" value="{{ $p->precio }}" class="form-control">
				</div>
				@endforeach
				<div class="col-xs-12">
					<button class="btn btn-success">Enviar</button>
				</div>
			</form>
		</div>
		<div class="col-xs-12 contAnaranjado" style="margin-top:2em;padding:1em 0px 3em 3em;">
		<form method="POST" action="{{ URL::to('administrador/modificar-precios/enviar') }}">
			<legend>Precios del menú por categoría</legend>
			@foreach($cat as $c)
			<div class="col-xs-12">
				<label class="textoPromedio">{{ $c->desc }}</label>
				<input type="text" name="cat{{ $c->id }}" value="{{ $c->precio }}" class="form-control">
			</div>
			@endforeach
			<div class="col-xs-12">
				<button class="btn btn-success">Enviar</button>
			</div>
		</form>
	</div>
	</div>
	
</div>
@stop