@extends('main')

@section('content')
<div class="container contenedorUnico">
	<div class="row">
		<div class="col-sm-12 contAnaranjado">
			<div class="col-sm-12">
				<ol class="breadcrumb textoPromedio">
				  <li><a href="{{ URL::to('usuario/publicar') }}" class="breadcrums"><span class="num">1</span> Elije el tipo de publicación.</a></li>
				  <li class="active"><a href="{{ URL::to('usuario/publicacion/habitual') }}" class="breadcrums"><span class="num numActivo">2</span> Elija la categoría y llene el formulario</a></li>
				</ol>
			</div>
			<div class="col-sm-12">
				<h3>Publicación HABITUAL</h3>
				<h5>(*) Campo obligatorio</h5>
				<hr>
			</div>
			<form method="post" action="{{ URL::to($url) }}" enctype="multipart/form-data">
				<div class="col-sm-12">
					<legend>Información de la publicación</legend>
				</div>
				@if(Session::has('error'))
				<div class="col-sm-12">
					<div class="alert alert-danger">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						<p class="textoPromedio">{{ Session::get('error') }}</p>
					</div>
				</div>
				@endif
				<div class="col-sm-12 col-md-6 formulario">
					<label for="subCat" class="textoPromedio">(*) Sub-categoría</label>
					<?php $arr = array(
							'' => 'Seleccione la sub-categoría');
							 ?>
					@if(!empty($subCat) && !is_null($subCat) && count($subCat)>0)
						@foreach ($subCat as $sub)
							@if($sub->id != $otrosub->id)
							<?php $arr = $arr+array($sub->id => $sub->desc);  ?>
							@endif
						@endforeach
					@endif
					<?php $arr = $arr+array($otrosub->id => $otrosub->desc);  ?>
					{{ Form::select('subCat',$arr,Input::old('subCat'),array('class' => 'form-control','required' => 'required')
						)}}
					
					@if ($errors->has('subCat'))
						 @foreach($errors->get('subCat') as $err)
						 	<div class="alert alert-danger">
						 		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						 		<p class="textoPromedio">{{ $err }}</p>
						 	</div>
						 @endforeach
					@endif
				</div>
				<div class="col-sm-12 col-md-6 formulario">
					<label class="textoPromedio">(*) Clase de negocio.</label>
					<div class="col-sm-12" class="textoPromedio">
						<span for="negocioType" class="textoPromedio">Negocio con domicilio fiscal</span>
						{{ Form::radio('negocioType','fiscal',Input::old('negocioType'),array('required' => 'required')) }}
						<span for="negocioType" class="textoPromedio">Tienda-negocio virtual</span>
						{{ Form::radio('negocioType','virtual',Input::old('negocioType'),array('required' => 'required')) }}
						<span for="negocioType" class="textoPromedio">Independiente </span>
						{{ Form::radio('negocioType','independiente',Input::old('negocioType'),array('required' => 'required')) }}
						<span for="negocioType" class="textoPromedio">Otro</span>
						{{ Form::radio('negocioType','otro',Input::old('negocioType'),array('required' => 'required')) }}
					</div>
					@if ($errors->has('negocioType'))
						 @foreach($errors->get('negocioType') as $err)
						 	<div class="alert alert-danger">
						 		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						 		<p class="textoPromedio">{{ $err }}</p>
						 	</div>
						 @endforeach
					@endif
				</div>
				<div class="col-sm-12">
					<label for="title" class="textoPromedio">(*) Título</label>
					{{ Form::text('title',Input::old('title'),array('placeholder' => 'Titulo','class' => 'form-control','required' => 'required')) }}
					@if ($errors->has('title'))
						 @foreach($errors->get('title') as $err)
						 	<div class="alert alert-danger">
						 		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						 		<p class="textoPromedio">{{ $err }}</p>
						 	</div>
						 @endforeach
					@endif
				</div>
				<div class="col-sm-12 col-md-6 formulario">
					<label for="precio" class="textoPromedio">(*) Precio</label>
					{{ Form::text('precio',Input::old('precio'),array('placeholder' => 'Precio','class' => 'form-control')) }}
					@if ($errors->has('precio'))
						 @foreach($errors->get('precio') as $err)
						 	<div class="alert alert-danger">
						 		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						 		<p class="textoPromedio">{{ $err }}</p>
						 	</div>
						 @endforeach
					@endif
				</div>
				<div class="col-sm-12 col-md-6 formulario">
					<label class="textoPromedio">(*) Moneda</label>
					<div class="col-sm-12" class="textoPromedio">
						<span for="moneda" class="textoPromedio">USD</span>
						{{ Form::radio('moneda','Usd',Input::old('moneda'),array('required' => 'required')) }}
						<span for="moneda" class="textoPromedio">BS</span>
						{{ Form::radio('moneda','Bs',Input::old('moneda'),array('required' => 'required')) }}
					</div>
					@if ($errors->has('moneda'))
						 @foreach($errors->get('moneda') as $err)
						 	<div class="alert alert-danger">
						 		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						 		<p class="textoPromedio">{{ $err }}</p>
						 	</div>
						 @endforeach
					@endif
				</div>
				<div class="clearfix"></div>
				<div class="col-sm-12 col-md-6 formulario">
					<label for="" class="textoPromedio">(*) Departamento</label>
					<?php $arr = array(
							'' => 'Seleccione su departamento');
							 ?>
					@foreach ($departamento as $department)
						<?php $arr = $arr+array($department->id => $department->nombre);  ?>
					@endforeach
					
					{{ Form::select('departamento',$arr,Input::old('departamento'),array('class' => 'form-control','required' => 'required')
						)}}
					
					@if ($errors->has('departamento'))
						 @foreach($errors->get('departamento') as $err)
						 	<div class="alert alert-danger">
						 		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						 		<p class="textoPromedio">{{ $err }}</p>
						 	</div>
						 @endforeach
					@endif
				</div>
				<div class="col-sm-12 col-md-6 formulario">
					<label for="" class="textoPromedio">(*) Ciudad</label>
					{{ Form::text('ciudad',Input::old('ciudad'),array('class' => 'form-control','placeholder' => 'Ciudad','required' => 'required')) }}
					@if ($errors->has('ciudad'))
						 @foreach($errors->get('ciudad') as $err)
						 	<div class="alert alert-danger">
						 		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						 		<p class="textoPromedio">{{ $err }}</p>
						 	</div>
						 @endforeach
					@endif
				</div>
				
				@if($cat_id == 34)
					<div class="col-sm-12 col-md-6 formulario">
						<label class="textoPromedio">(*) Marca</label>
						<select name="marca" class="form-control" id="veiMarca" required>
							<option value="">Seleccione una marca</option>
							@foreach($marcas as $marca)
							<option value="{{ $marca->id }}">{{ $marca->nombre }}</option>
							@endforeach
						</select>
						@if ($errors->has('marca'))
						 @foreach($errors->get('marca') as $err)
						 	<div class="alert alert-danger">
						 		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						 		<p class="textoPromedio">{{ $err }}</p>
						 	</div>
						 @endforeach
						@endif
					</div>
					<div class="col-sm-12 col-md-6 formulario">
						<label class="textoPromedio">(*) Modelo</label>
						<select name="modelo" class="form-control" id="veiModel" required>
							<option value="">Seleccione un modelo</option>
						</select>
						@if ($errors->has('modelo'))
						 @foreach($errors->get('modelo') as $err)
						 	<div class="alert alert-danger">
						 		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						 		<p class="textoPromedio">{{ $err }}</p>
						 	</div>
						 @endforeach
						@endif
					</div>
					<div class="col-sm-12 col-md-6 formulario">
						<label class="textoPromedio">(*) Año</label>
						{{ Form::text('anio',Input::old('anio'),array('class' => 'form-control','placeholder' => 'Año','required' => 'required')) }}
						@if ($errors->has('anio'))
						 @foreach($errors->get('anio') as $err)
						 	<div class="alert alert-danger">
						 		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						 		<p class="textoPromedio">{{ $err }}</p>
						 	</div>
						 @endforeach
						@endif
					</div>
					<div class="col-sm-12 col-md-6 formulario">
						<label class="textoPromedio">(*) Documentación</label>
						{{ Form::text('doc',Input::old('doc'),array('class' => 'form-control','placeholder' => 'Documentacion','required' => 'required')) }}
						@if ($errors->has('doc'))
						 @foreach($errors->get('doc') as $err)
						 	<div class="alert alert-danger">
						 		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						 		<p class="textoPromedio">{{ $err }}</p>
						 	</div>
						 @endforeach
						@endif
					</div>
					<div class="col-sm-12 formulario">
						<label class="textoPromedio">(*) Kilometraje</label>
						{{ Form::text('kilo',Input::old('kilo'),array('class' => 'form-control','placeholder' => 'Kilometraje','required' => 'required')) }}
						@if ($errors->has('kilo'))
						 @foreach($errors->get('kilo') as $err)
						 	<div class="alert alert-danger">
						 		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						 		<p class="textoPromedio">{{ $err }}</p>
						 	</div>
						 @endforeach
						@endif
					</div>
					
					<div class="col-sm-12 col-md-6 formulario">
						<label class="textoPromedio">Cilindrada</label>
						{{ Form::text('cilin',Input::old('cilin'),array('class' => 'form-control','placeholder' => 'Cilindrada')) }}
						@if ($errors->has('cilin'))
						 @foreach($errors->get('cilin') as $err)
						 	<div class="alert alert-danger">
						 		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						 		<p class="textoPromedio">{{ $err }}</p>
						 	</div>
						 @endforeach
						@endif
					</div>
					<div class="col-sm-12 col-md-6 formulario">
						<label class="textoPromedio">Transmisión</label>
						{{ Form::text('trans',Input::old('trans'),array('class' => 'form-control','placeholder' => 'Transmisión')) }}
						@if ($errors->has('trans'))
						 @foreach($errors->get('trans') as $err)
						 	<div class="alert alert-danger">
						 		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						 		<p class="textoPromedio">{{ $err }}</p>
						 	</div>
						 @endforeach
						@endif
					</div>
					<div class="col-sm-12 col-md-6 formulario">
						<label class="textoPromedio">Combustible</label>
						{{ Form::text('comb',Input::old('comb'),array('class' => 'form-control','placeholder' => 'Combustible')) }}
						@if ($errors->has('comb'))
						 @foreach($errors->get('comb') as $err)
						 	<div class="alert alert-danger">
						 		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						 		<p class="textoPromedio">{{ $err }}</p>
						 	</div>
						 @endforeach
						@endif
					</div>
					<div class="col-sm-12 col-md-6 formulario">
						<label class="textoPromedio">Tracción</label>
						{{ Form::text('trac',Input::old('trac'),array('class' => 'form-control','placeholder' => 'Tracción')) }}
						@if ($errors->has('trac'))
						 @foreach($errors->get('trac') as $err)
						 	<div class="alert alert-danger">
						 		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						 		<p class="textoPromedio">{{ $err }}</p>
						 	</div>
						 @endforeach
						@endif
					</div>
				@elseif($cat_id == 20)
					<div class="col-sm-12 formulario">
						<label class="textoPromedio">(*) Extensión (mt<sup>2</sup>)</label>
						{{ Form::text('ext',Input::old('ext'),array('class' => 'form-control','placeholder' => 'metros cuadrados','required' => 'required')) }}
						@if ($errors->has('ext'))
						 @foreach($errors->get('ext') as $err)
						 	<div class="alert alert-danger">
						 		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						 		<p class="textoPromedio">{{ $err }}</p>
						 	</div>
						 @endforeach
						@endif
					</div>
				@endif
				<div class="col-sm-12 col-md-6 formulario">
					<label class="textoPromedio">(*) Operación</label>
					<div class="col-sm-12" class="textoPromedio">
						<span for="tipoTransac" class="textoPromedio">Venta</span>
						{{ Form::radio('tipoTransac','venta',Input::old('tipoTransac'),array('required' => 'required')) }}
						<span for="tipoTransac" class="textoPromedio">Alquiler</span>
						{{ Form::radio('tipoTransac','alquiler',Input::old('tipoTransac'),array('required' => 'required')) }}
						@if($cat->tipo == 2)
						<span for="tipoTransac" class="textoPromedio">A convenir </span>
						{{ Form::radio('tipoTransac','A convenir',Input::old('tipoTransac'),array('required' => 'required')) }}
						@endif
						@if($cat_id == 20)
							<span for="tipoTransac" class="textoPromedio">Anticrético </span>
							{{ Form::radio('tipoTransac','Aticretico',Input::old('tipoTransac'),array('required' => 'required')) }}
							<span for="tipoTransac" class="textoPromedio">otro</span>
							{{ Form::radio('tipoTransac','otro',Input::old('tipoTransac'),array('required' => 'required')) }}
						@endif
					</div>
					@if ($errors->has('tipoTransac'))
						 @foreach($errors->get('tipoTransac') as $err)
						 	<div class="alert alert-danger">
						 		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						 		<p class="textoPromedio">{{ $err }}</p>
						 	</div>
						 @endforeach
					@endif
				</div>
				@if($cat->tipo == 1)
				<div class="col-sm-12 col-md-6 formulario">
					<label class="textoPromedio"><span class="required-on-cat">(*)</span> Condición</label>
					<div class="col-sm-12" class="textoPromedio">
						<span for="condition" class="textoPromedio">Nuevo</span>
						{{ Form::radio('condition','nuevo',Input::old('condition')) }}
						<span for="condition" class="textoPromedio">Usado</span>
						{{ Form::radio('condition','usado',Input::old('condition')) }}
					</div>
					@if ($errors->has('condition'))
						 @foreach($errors->get('condition') as $err)
						 	<div class="alert alert-danger">
						 		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						 		<p class="textoPromedio">{{ $err }}</p>
						 	</div>
						 @endforeach
					@endif
				</div>
				@endif
				<div class="col-sm-12 formulario">
					<label for="input" class="textoPromedio">(*) Descripción</label>
					<!--<textarea id="input" name="input" class="form-control descHabitual"></textarea>-->
					<textarea  id="editor1" name="input" required>{{ Input::old('input') }}</textarea>
					@if ($errors->has('input'))
						 @foreach($errors->get('input') as $err)
						 	<div class="alert alert-danger">
						 		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						 		<p class="textoPromedio">{{ $err }}</p>
						 	</div>
						 @endforeach
					@endif
				</div>
				<div class="col-sm-12 formulario">
					<h4>¿Desea mostrar la ubicación de su publicación?</h4>
					<input type="checkbox" class="doMap">
					<label class="textoPromedio">Si desea publicar su ubicación, permita en el popup para poder acceder a ella.</label>
					
				</div>
				<div class="col-sm-12">
					<article class="mapContainer hidden" id="" style="position:relative;">
						<div class="contLoaderBig">
							<img src="{{ asset('assets/images/loading.gif') }}" class="loaderBig">
						</div>
					</article>
					<input type="hidden" name="latitud" class="latitud">
					<input type="hidden" name="longitud" class="longitud">
				</div>
				<div class="col-sm-12 formulario textoPromedio">
					
					<label>(*) Imagen principal</label>
					<input type="file" name="img1" required>
					@if ($errors->has('img1'))
						 @foreach($errors->get('img1') as $err)
						 	<div class="alert alert-danger">
						 		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						 		<p class="">{{ $err }}</p>
						 	</div>
						 @endforeach
					@endif
				</div>
				<div class="col-sm-12 formulario" style="margin-top:5em;">
					<legend>Información de contacto</legend>
				</div>
				<div class="col-sm-12 col-md-6 formulario">
					<label for="" class="textoPromedio">Nombre de contacto</label>
					{{ Form::text('nomb',Input::old('nomb'),array('class' => 'form-control','placeholder' => 'Nombre de contacto')) }}
				</div>
				<div class="col-sm-12 col-md-6 formulario">
					<label for="" class="textoPromedio">Teléfono de contacto</label>
					{{ Form::text('phone',Input::old('phone'),array('class' => 'form-control','placeholder' => 'Telefono de contacto')) }}
				</div>
				<div class="col-sm-12 col-md-6 formulario">
					<label for="" class="textoPromedio">Correo electrónico</label>
					{{ Form::text('email',Input::old('email'),array('class' => 'form-control', 'placeholder' => 'Correo electronico')) }}
				</div>
				<div class="col-sm-12 col-md-6 formulario">
					<label for="" class="textoPromedio">Sitio web</label>
					{{ Form::text('pag_web',Input::old('pag_web'),array('class' => 'form-control', 'placeholder' => 'Sitio web')) }}
				</div>
				<div class="col-sm-12 formulario" style="margin-bottom:2em;">
					<button value="{{ $cat_id }}" name="cat_id" class="btn btn-success">Enviar</button>
					<input type="reset" value="Borrar" class="btn btn-warning">
				</div>
			</form>
		</div>
	</div>
</div>
@stop

@section('postscript')

<script>

	CKEDITOR.disableAutoInline = true;
	$( document ).ready( function() {
		$( '#editor1' ).ckeditor(); // Use CKEDITOR.replace() if element is <textarea>.
	} );

</script>
@stop