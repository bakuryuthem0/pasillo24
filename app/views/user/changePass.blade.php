@extends('main')

@section('content')
<div class="container contenedorUnico">
	<div class="row">
		<div class="col-xs-6 contAnaranjado changePassCont" style="float:none;display:block;margin:2em auto;">
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
			<form method="POST" action="{{ URL::to('usuario/cambiar-clave/enviar') }}">
				<div class="col-xs-12">
					<label for="" class="textoPromedio">Clave anterior</label>
					<input type="password" class="form-control claveChange" name="old" id="old" placeholder="Clave anterior">
					@if ($errors->has('old'))
						 @foreach($errors->get('old') as $err)
						 	<div class="alert alert-danger">
						 		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						 		<p class="textoPromedio">{{ $err }}</p>
						 	</div>
						 @endforeach
					@endif
				</div>
				<div class="col-xs-12">
					<label for="" class="textoPromedio">Clave nueva</label>
					<input type="password" class="form-control claveChange" name="new" id="new" placeholder="Clave nueva">
					@if ($errors->has('new'))
						 @foreach($errors->get('new') as $err)
						 	<div class="alert alert-danger">
						 		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						 		<p class="textoPromedio">{{ $err }}</p>
						 	</div>
						 @endforeach
					@endif	
				</div>
				<div class="col-xs-12">
					<label for="" class="textoPromedio">Repita la clave</label>
					<input type="password" class="form-control claveChange" name="rep" id="rep" placeholder="Repita la clave">
					@if ($errors->has('rep'))
						 @foreach($errors->get('rep') as $err)
						 	<div class="alert alert-danger">
						 		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						 		<p class="textoPromedio">{{ $err }}</p>
						 	</div>
						 @endforeach
					@endif
				</div>
				<div class="col-xs-12">
					<button class="btn btn-success enviarModPass">Cambiar</button>
					<input type="reset" class="btn btn-warning" value="Restablecer">
				</div>
			</form>
			<div class="clearfix"></div>
		</div>
	</div>
</div>
@stop