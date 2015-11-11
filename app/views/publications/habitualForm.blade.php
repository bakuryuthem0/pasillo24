@extends('main')

@section('content')
<div class="container contenedorUnico">
	<div class="row">
		<div class="col-xs-12 contAnaranjado">
			<div class="col-xs-12">
				<ol class="breadcrumb textoPromedio">
				  <li><a href="{{ URL::to('usuario/publicar') }}" class="breadcrums"><span class="num">1</span> Elije el tipo de publicación.</a></li>
				  <li class="active"><a href="{{ URL::to('usuario/publicacion/habitual') }}" class="breadcrums"><span class="num numActivo">2</span> Elija la categoría y llene el formulario</a></li>
				</ol>
			</div>
			<div class="col-xs-12">
				<h3>Publicación HABITUAL</h3>
				<h5>(*) Campo obligatorio</h5>
				<hr>
			</div>
			<form method="post" action="{{ URL::to($url) }}" enctype="multipart/form-data">
				<div class="col-xs-12">
					<legend>Información de la publicación</legend>
				</div>
				@if(Session::has('error'))
				<div class="col-xs-12">
					<div class="alert alert-danger">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						<p class="textoPromedio">{{ Session::get('error') }}</p>
					</div>
				</div>
				@endif
				<div class="col-xs-12">
					<label for="subCat" class="textoPromedio">(*) Sub-categoría</label>
					<?php $arr = array(
							'' => 'Seleccione la sub-categoría');
							 ?>
					@if(!empty($subCat) && !is_null($subCat) && count($subCat)>0)
						@foreach ($subCat as $sub)
							<?php $arr = $arr+array($sub->id => $sub->desc);  ?>
						@endforeach
					@endif
					{{ Form::select('subCat',$arr,Input::old('subCat'),array('class' => 'form-control','requied' => 'required')
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
				
				<div class="col-xs-12">
					<label for="title" class="textoPromedio">(*) Título</label>
					{{ Form::text('title',Input::old('title'),array('placeholder' => 'Titulo','class' => 'form-control')) }}
					@if ($errors->has('title'))
						 @foreach($errors->get('title') as $err)
						 	<div class="alert alert-danger">
						 		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						 		<p class="textoPromedio">{{ $err }}</p>
						 	</div>
						 @endforeach
					@endif
				</div>
				<div class="col-xs-6">
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
				<div class="col-xs-6">
					<label class="textoPromedio">(*) Moneda</label>
					<div class="col-xs-12" class="textoPromedio">
						<span for="moneda" class="textoPromedio">USD</span>
						{{ Form::radio('moneda','Usd',Input::old('moneda')) }}
						<span for="moneda" class="textoPromedio">BS</span>
						{{ Form::radio('moneda','Bs',Input::old('moneda')) }}
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
				<div class="col-xs-6">
					<label for="" class="textoPromedio">(*) Departamento</label>
					<?php $arr = array(
							'' => 'Seleccione su departamento');
							 ?>
					@foreach ($departamento as $department)
						<?php $arr = $arr+array($department->id => $department->nombre);  ?>
					@endforeach
					
					{{ Form::select('departamento',$arr,Input::old('departamento'),array('class' => 'form-control','requied' => 'required')
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
				<div class="col-xs-6">
					<label for="" class="textoPromedio">(*) Ciudad</label>
					{{ Form::text('ciudad',Input::old('ciudad'),array('class' => 'form-control','placeholder' => 'Ciudad')) }}
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
					<div class="col-xs-6">
						<label class="textoPromedio">(*) Marca</label>
						<select name="marca" class="form-control" id="veiMarca">
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
					<div class="col-xs-6">
						<label class="textoPromedio">(*) Modelo</label>
						<select name="modelo" class="form-control" id="veiModel">
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
					<div class="col-xs-6">
						<label class="textoPromedio">(*) Año</label>
						{{ Form::text('anio',Input::old('anio'),array('class' => 'form-control','placeholder' => 'Año')) }}
						@if ($errors->has('anio'))
						 @foreach($errors->get('anio') as $err)
						 	<div class="alert alert-danger">
						 		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						 		<p class="textoPromedio">{{ $err }}</p>
						 	</div>
						 @endforeach
						@endif
					</div>
					<div class="col-xs-6">
						<label class="textoPromedio">(*) Documentación</label>
						{{ Form::text('doc',Input::old('doc'),array('class' => 'form-control','placeholder' => 'Documentacion')) }}
						@if ($errors->has('doc'))
						 @foreach($errors->get('doc') as $err)
						 	<div class="alert alert-danger">
						 		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						 		<p class="textoPromedio">{{ $err }}</p>
						 	</div>
						 @endforeach
						@endif
					</div>
					<div class="col-xs-12">
						<label class="textoPromedio">(*) Kilometraje</label>
						{{ Form::text('kilo',Input::old('kilo'),array('class' => 'form-control','placeholder' => 'Kilometraje')) }}
						@if ($errors->has('kilo'))
						 @foreach($errors->get('kilo') as $err)
						 	<div class="alert alert-danger">
						 		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						 		<p class="textoPromedio">{{ $err }}</p>
						 	</div>
						 @endforeach
						@endif
					</div>
					
					<div class="col-xs-6">
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
					<div class="col-xs-6">
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
					<div class="col-xs-6">
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
					<div class="col-xs-6">
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
					<div class="col-xs-12">
						<label class="textoPromedio">(*) Extensión (mt<sup>2</sup>)</label>
						{{ Form::text('ext',Input::old('ext'),array('class' => 'form-control','placeholder' => 'metros cuadrados')) }}
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
				<div class="col-xs-12">
					<label class="textoPromedio">(*) Operación</label>
					<div class="col-xs-12" class="textoPromedio">
						<span for="tipoTransac" class="textoPromedio">Venta</span>
						{{ Form::radio('tipoTransac','venta',Input::old('tipoTransac')) }}
						<span for="tipoTransac" class="textoPromedio">Alquiler</span>
						{{ Form::radio('tipoTransac','alquiler',Input::old('tipoTransac')) }}
						@if($cat_id == 20)
							<span for="tipoTransac" class="textoPromedio">Anticrético </span>
							{{ Form::radio('tipoTransac','Aticretico',Input::old('tipoTransac')) }}
							<span for="tipoTransac" class="textoPromedio">otro</span>
							{{ Form::radio('tipoTransac','otro',Input::old('tipoTransac')) }}
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
				<div class="col-xs-12">
					<label for="input" class="textoPromedio">(*) Descripción</label>
					<!--<textarea id="input" name="input" class="form-control descHabitual"></textarea>-->
					<textarea  id="editor1" name="input" >{{ Input::old('input') }}</textarea>
					@if ($errors->has('input'))
						 @foreach($errors->get('input') as $err)
						 	<div class="alert alert-danger">
						 		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						 		<p class="textoPromedio">{{ $err }}</p>
						 	</div>
						 @endforeach
					@endif
				</div>

				<div class="col-xs-12 textoPromedio">
					
					<label>(*) Imagen principal</label>
					<input type="file" name="img1">
					@if ($errors->has('img1'))
						 @foreach($errors->get('img1') as $err)
						 	<div class="alert alert-danger">
						 		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						 		<p class="">{{ $err }}</p>
						 	</div>
						 @endforeach
					@endif
				</div>
				<div class="col-xs-12" style="margin-top:5em;">
					<legend>Información de contacto</legend>
				</div>
				<div class="col-xs-6">
					<label for="" class="textoPromedio">Nombre de contacto</label>
					{{ Form::text('nomb',Input::old('nomb'),array('class' => 'form-control','placeholder' => 'Nombre de contacto')) }}
				</div>
				<div class="col-xs-6">
					<label for="" class="textoPromedio">Teléfono de contacto</label>
					{{ Form::text('phone',Input::old('phone'),array('class' => 'form-control','placeholder' => 'Telefono de contacto')) }}
				</div>
				<div class="col-xs-6">
					<label for="" class="textoPromedio">Correo electrónico</label>
					{{ Form::text('email',Input::old('email'),array('class' => 'form-control', 'placeholder' => 'Correo electronico')) }}
				</div>
				<div class="col-xs-6">
					<label for="" class="textoPromedio">Sitio web</label>
					{{ Form::text('pag_web',Input::old('pag_web'),array('class' => 'form-control', 'placeholder' => 'Sitio web')) }}
				</div>
				<div class="col-xs-12" style="margin-bottom:2em;">
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