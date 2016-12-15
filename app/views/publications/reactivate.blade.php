@extends('main')

@section('content')

<div class="container contenedorUnico">
	<div class="row">
		<div class="col-xs-12 contAnaranjado" style="margin-top:2em;">
			
				<h3>Reactivar publicación</h3>

				@if($pub->tipo == "Lider")
					<p class="bg-info textoPromedio" style="padding:0.5em;">Si desea rectivar su anuncio, especifique la ubicación fecha, duración y periodo.</p>
					@if(Session::has('error'))
					<div class="alert alert-danger">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						<p class="textoPromedio">{{ Session::get('error') }}</p>
					</div>
					@endif
					<legend>Publicación: {{ $pub->titulo }}</legend>
				<form method="POST" action="{{ URL::to('usuario/publicaciones/reactivar/'.$pub->id.'/enviar') }}">
					<div class="col-xs-12 @if($pub->ubicacion == 'Categoria') col-md-6 @else col-md-12 @endif inputLider">
						<label for="department" class="textoPromedio">(*) Ubicación:</label>
						<select name="ubication" class="form-control" id="ubication" required>
							<option value="">Seleccione la ubicación a publicar</option>
							<option value="Principal" @if($pub->ubicacion == 'Principal') selected @endif>Menú principal</option>
							<option value="Categoria" @if($pub->ubicacion == 'Categoria') selected @endif>Menú por categorías</option>
						</select>
						@if ($errors->has('ubication'))
							 @foreach($errors->get('ubication') as $err)
							 	<div class="alert alert-danger">
							 		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							 		<p class="textoPromedio">{{ $err }}</p>
							 	</div>
							 @endforeach
						@endif
					</div>

					<div class="col-xs-12 col-md-6 contCatLider inputLider @if($pub->ubicacion == 'Categoria') showit @endif">
						<label for="cat" class="textoPromedio">(*) Categoría</label>
						<select name="cat" id="category" class="form-control">
							<option value="">Seleccione la categoría</option>
							<optgroup label="Categoría">
							@foreach($cat as $c)
								@if($c->id != $otros->id)
									@if($pub->ubicacion) == 'Categoria')
										<option value="{{ $c->id }}" @if($pub->categoria == $c->id) selected @endif>{{ $c->nombre }}</option>
									@else
										<option value="{{ $c->id }}">{{ $c->nombre }}</option>
									@endif
								@endif
							@endforeach
							<option value="{{ $otros->id }}" @if($pub->categoria == $otros->id) selected @endif>{{ $otros->nombre }}</option>
							</optgroup>
							<optgroup label="Servicios">
							@foreach($serv as $s)
								@if($s->id != $otros2->id)
									@if($errors->has('cat'))
											<option value="{{ $s->id }}" @if($pub->categoria == $s->id) selected @endif>{{ $s->nombre }}</option>
									@else
											<option value="{{ $s->id }}">{{ $s->nombre }}</option>
									@endif
								@endif
							@endforeach
							<option value="{{ $otros2->id }}" @if($pub->categoria == $otros2->id) selected @endif>{{ $otros2->nombre }}</option>
							</optgroup>
						</select>
						@if ($errors->has('cat'))
							 @foreach($errors->get('cat') as $err)
							 	<div class="alert alert-danger">
							 		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							 		<p class="textoPromedio">{{ $err }}</p>
							 	</div>
							 @endforeach
						@endif
					</div>
					<div class="col-xs-12 col-md-3 inputLider formulario">
						<label for="fechIni" class="textoPromedio">(*) Fecha de inicio</label>
						<input type="text" class="form-control" id="fechIni" name="fechIni" placeholder="DD-MM-AAAA" required>
						@if ($errors->has('fechIni'))
							 @foreach($errors->get('fechIni') as $err)
							 	<div class="alert alert-danger">
							 		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							 		<p class="textoPromedio">{{ $err }}</p>
							 	</div>
							 @endforeach
						@endif
					</div>
					<div class="col-xs-12 col-md-3 inputLider formulario sinPadding">
						<label for="duration" class="textoPromedio">(*) Duración:</label>
						{{ Form::text('duration',
						 $pub->duracion,
						 array('class' => 'form-control','id' => 'duration','required' => 'required')) }}
						@if ($errors->has('duration'))
						 @foreach($errors->get('duration') as $err)
						 	<div class="alert alert-danger">
						 		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						 		<p class="textoPromedio">{{ $err }}</p>
						 	</div>
						 @endforeach
					@endif
					</div>
					<div class="col-xs-12 col-md-3 inputLider formulario sinPadding">
						<label for="duration" class="textoPromedio">(*) Período:</label>
						{{ Form::select('time',array(
						'' => 'Seleccione el período',
						'd' => 'Día(s)',
						's' => 'Semana(s)',
						'm' => 'Mes(es)'),Input::old('time'),array('class' => 'form-control fech','id'=>'period','required' => 'required')
					)}}
						@if ($errors->has('time'))
						 @foreach($errors->get('time') as $err)
						 	<div class="alert alert-danger">
						 		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						 		<p class="textoPromedio">{{ $err }}</p>
						 	</div>
						 @endforeach
					@endif
					</div>
					<div class="col-xs-12 col-md-3 contPrecioShow" style="margin-top:2.5em;">
					</div>
					<div class="col-xs-12" id="durError"></div>
					<div class="col-xs-12">
						<label for="fechaFin" class="textoPromedio">Fecha de Cierre</label>
						<div class="fechaFin col-xs-12" class="textoPromedio">
							<img src="{{ asset('images/loading.gif') }}" class="miniLoader hidden">
						</div>
					</div>
					<div class="col-xs-12"><input type="submit" class="btn btn-success" value="Enviar"></div>
				</form>
				@else
					@if(Session::has('error'))
					<div class="alert alert-danger">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						<p class="textoPromedio">{{ Session::get('error') }}</p>
					</div>
					@endif
					<form method="post" action="{{ URL::to('usuario/publicacion/habitual/pago/procesar') }}">
						<div class="col-xs-6  contCien">
							<h3>Incremente su publicidad</h3>
							<p class="bg-info textoPromedio" style="padding:0.5em;">Si desea incrementar la publicidad de su anuncio, especifique ubicación tiempo y periodo.</p>
							<h5>Menú principal</h5>
							<div class="col-xs-12" style="padding:0px;margin-bottom:2em;">
								<div class="col-xs-4 col-md-2" style="margin-top:0px;">
									{{ Form::text('durationPrin',Input::old('durationPrin'), array('class' => 'form-control','id' => 'principalDuration','placeholder' => '#')) }}
								</div>
								<div class="col-xs-8 col-md-10" style="margin-top:0px;">
									<select name="periodoPrin" id="principalPeriodo" class="form-control" >
										<option value="">Seleccione un periodo</option>
										@foreach($precioLider as $lid)
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
								<div class="col-xs-8 col-md-10" style="margin-top:0px;">
									<select name="periodoCat" id="categoriaPeriodo" class="form-control" >
										<option value="">Seleccione un periodo</option>
										@foreach($precioCat as $cat)
										<option value="{{ $cat->precio }}">{{ ucwords($cat->desc) }}</option>
										@endforeach
									</select>
								</div>
							</div>
						</div>
						<div class="col-xs-5 contCien " style="margin-left:1em;margin-top: 1em;">
								<h3 id="totalTexto">El total a pagar será de: {{ $pub->monto }}Bs</h3>
								<button id="enviarPago" class="btn btn-success" style="margin-top:2em;" value="{{ $pub->id }}" name="enviarId">Continuar</button>
						</div>
						</form>
				@endif
			</div>
		</div>
	</div>
</div>
@stop