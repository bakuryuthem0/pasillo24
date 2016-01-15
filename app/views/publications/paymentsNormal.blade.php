@extends('main')

@section('content')

<div class="container contenedorUnico">
	<div class="row">
		<div class="col-xs-12 ">
			@if(Session::has('error'))
			<div class="alert alert-danger">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				<p class="textoPromedio">{{ Session::get('error') }}</p>
			</div>
			@endif
			<form method="post" action="{{ URL::to('usuario/publicacion/habitual/pago/procesar') }}">
			<div class="col-xs-6 contAnaranjado contCien">
				<h3>Incremente su publicidad</h3>
				<p class="bg-info textoPromedio" style="padding:0.5em;">Si desea incrementar la publicidad de su anuncio, especifique ubicación tiempo y periodo.</p>
				<h5>Menú principal</h5>
				<div class="col-xs-12" style="padding:0px;margin-bottom:2em;">
					<div class="col-xs-4 col-md-2" style="margin-top:0px;">
						{{ Form::text('durationPrin',Input::old('durationPrin'), array('class' => 'form-control','id' => 'principalDuration','placeholder' => '#')) }}
					</div>
					<div class="col-xs-8 col-md-10">
						<select name="periodoPrin" id="principalPeriodo" class="form-control">
							<option value="">Seleccione un periodo</option>
							@foreach($precLid as $lid)
							<option value="{{ $lid->precio }}">{{ ucwords($lid->desc) }}</option>
							@endforeach
						</select>
					</div>
					
				</div>
				
				<h5>Menú por categorías</h5>
				<div class="col-xs-12" style="padding:0px;">
					<div class="col-xs-4 col-md-2" style="margin-top:0px;">
						{{ Form::text('durationCat',Input::old('durationPrin'), array('class' => 'form-control','id' => 'categoriaDuration','placeholder' => '#','style' => 'padding-top:0px;')) }}
					</div>
					<div class="col-xs-8 col-md-10">
						<select name="periodoCat" id="categoriaPeriodo" class="form-control">
							<option value="">Seleccione un periodo</option>
							@foreach($precCat as $cat)
							<option value="{{ $cat->precio }}">{{ ucwords($cat->desc) }}</option>
							@endforeach
						</select>
					</div>
				</div>
			</div>
			<div class="col-xs-5 contCien contAnaranjado" style="margin-left:1em;margin-top: 1em;">
					<h3 id="totalTexto">El total a pagar será de: {{ $pub->monto }}Bs</h3>
					<button id="enviarPago" class="btn btn-success" style="margin-top:2em;" value="{{ $id }}" name="enviarId">Continuar</button>
			</div>
			</form>
		</div>
	</div>
</div>
@stop