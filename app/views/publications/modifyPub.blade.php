@extends('main')
@section('content')
<div class="container contenedorUnico">
	<div class="row">

		<div class="col-xs-12 contAnaranjado" style="margin-top:2em;">
			
			<form method="post" action="{{ URL::to($url) }}" enctype="multipart/form-data">
			@if ($tipo == 'Lider')

				<h4>Modificar publicación LÍDER</h4>
				<hr>
				@if(Session::has('error'))
				<div class="col-xs-12">
					<div class="alert alert-danger">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						<p class="textoPromedio">{{ Session::get('error') }}</p>
					</div>
				</div>
				@endif

				<div class="col-xs-6 inputLider">
					<label for="cat" class="textoPromedio">Categoría</label>
					<select name="cat" id="category" class="form-control">
						<option value="1">Seleccione la categoría</option>
						@foreach($categorias as $categoria)
							@if($publicaciones->categoria == $categoria->id)
								<option value="{{ $categoria->id }}" selected>{{ $categoria->nombre }}</option>
							@else
								<option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
							@endif
						@endforeach
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
				<div class="col-xs-12 textoPromedio inputLider">
					<label for="namePub">Nombre / Título de la publicación:</label>
					{{ Form::text('namePub',
					$publicaciones->titulo,
					 array('class' => 'form-control','id' => 'name','placeholder' => 'Titulo')) }}
					@if ($errors->has('namePub'))
						 @foreach($errors->get('namePub') as $err)
						 	<div class="alert alert-danger">
						 		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						 		<p class="textoPromedio">{{ $err }}</p>
						 	</div>
						 @endforeach
					@endif
				</div>
				
				
				<div class="col-xs-12" style="margin: 2em 0px 2em 0px;">
					<hr>

					<p class="bg-info textoPromedio" style="padding:1em;text-align:center;">La imagen debe tener un ancho mínimo de 400px</p>
					<div class="col-xs-6 textoPromedio">
						<label>Imagen de la Publicación</label>
						<input type="file" name="img1">
					</div>
					<div class="col-xs-6 textoPromedio">
						<label>Imagen secundaria</label>
						<input type="file" name="img2">
					</div>
					@if ($errors->has('img1'))
					<div class="col-xs-6">
						 @foreach($errors->get('img1') as $err)
						 	<div class="alert alert-danger">
						 		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						 		<p class="textoPromedio">{{ $err }}</p>
						 	</div>
						 @endforeach
					</div>
					@endif
				</div>
				<div class="col-xs-12">
					<hr>

					<p class="bg-info textoPromedio" style="padding:1em;text-align:center;">El url de la página web debe comenzar con http:// o https://</p>
					<label for="pagina" class="textoPromedio">Url de su página web</label>
					{{ Form::text('pagina',$publicaciones->pag_web,array('class' => 'form-control','placeholder' => 'http://example.com')) }}

					@if ($errors->has('pagina'))
						 @foreach($errors->get('pagina') as $err)
						 	<div class="alert alert-danger">
						 		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						 		<p class="textoPromedio">{{ $err }}</p>
						 	</div>
						 @endforeach
					@endif
				</div>
				<div class="col-xs-12" style="margin-top:5em;">
					<legend>Información de contacto</legend>
				</div>
				<div class="col-xs-6">
					<label for="" class="textoPromedio">(*) Nombre de contacto</label>
					{{ Form::text('nomb',$publicaciones->name,array('class' => 'form-control','placeholder' => 'Nombre de contacto')) }}
				</div>
				<div class="col-xs-6">
					<label for="" class="textoPromedio">(*) Teléfono de contacto</label>
					{{ Form::text('phone',$publicaciones->phone,array('class' => 'form-control','placeholder' => 'Telefono de contacto')) }}
				</div>
				<div class="col-xs-6">
					<label for="" class="textoPromedio">(*) Correo electrónico</label>
					{{ Form::text('email',$publicaciones->email,array('class' => 'form-control', 'placeholder' => 'Correo electronico')) }}
				</div>
				<div class="col-xs-6">
					<label for="" class="textoPromedio">(*) Sitio web</label>
					{{ Form::text('pag_web',$publicaciones->pag_web_hab,array('class' => 'form-control', 'placeholder' => 'Sitio web')) }}
				</div>
				<div class="col-xs-12">
					<button  value="{{ $publicaciones->id }}" name="enviarPub" class="btn btn-success">Enviar</button>
					<input type="reset" value="Borrar" name="borrar" class="btn btn-warning">
					<a href="{{ URL::to('usuario/publicaciones/mis-publicaciones/lider') }}" class="btn btn-danger cancel">Cancelar</a>
				</div>
				
			@elseif($tipo == 'Habitual')
			



			<div class="col-xs-12">
					<legend>Modificación de publicación HABITUAL</legend>
				</div>
				@if(Session::has('error'))
				<div class="col-xs-12">
					<div class="alert alert-danger">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						<p class="textoPromedio">{{ Session::get('error') }}</p>
					</div>
				</div>
				@endif
				<div class="col-xs-6 inputLider">
					<label for="cat" class="textoPromedio">Categoría</label>
					<select name="cat" id="category" class="form-control">
						<option value="1">Seleccione la categoría</option>
						@foreach($categorias as $categoria)
							@if($publicaciones->categoria == $categoria->id)
								<option value="{{ $categoria->id }}" selected>{{ $categoria->nombre }}</option>
							@else
								<option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
							@endif
						@endforeach
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
				<div class="col-xs-6">
					<label for="subCat" class="textoPromedio">Sub-categoría</label>
					<select class="form-control" name="subCat" id="subCat">
						<option value="">Seleccione la sub-categoría</option>
						@foreach($subCat as $sub)
							@if($sub->categoria_id == $publicaciones->categoria)
								@if($sub->id == $publicaciones->typeCat)
									<option class="optiongroup" value="{{ $sub->id }}" selected>{{ $sub->desc }}</option>
								@else
									<option class="optiongroup" value="{{ $sub->id }}">{{ $sub->desc }}</option>
								@endif
							@endif
						@endforeach
					</select>
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
					<label for="title" class="textoPromedio">Título</label>
					{{ Form::text('title',$publicaciones->titulo,array('placeholder' => 'Titulo','class' => 'form-control')) }}
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
					<label for="precio" class="textoPromedio">Precio</label>
					{{ Form::text('precio',$publicaciones->precio,array('placeholder' => 'Precio','class' => 'form-control')) }}
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
					<label class="textoPromedio">Moneda</label>
					<div class="col-xs-12" class="textoPromedio">
						<span for="moneda" class="textoPromedio">USD</span>
						@if($publicaciones->moneda == "usd")
							<input type="radio" name="moneda" value="usd" checked>
						@else
							<input type="radio" name="moneda" value="usd">
						@endif
						<span for="moneda" class="textoPromedio">BS</span>
						@if($publicaciones->moneda == "Bs")
							<input type="radio" name="moneda" value="Bs" checked>
						@else
							<input type="radio" name="moneda" value="Bs">
						@endif
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
					<label for="" class="textoPromedio">Departamento</label>
					<select name="departamento" class="form-control">
						<option value="">Seleccione el departamento</option>
						@foreach($departamento as $department)
							@if($publicaciones->departamento == $department->id)
								<option value="{{ $department->id }}" selected>{{ $department->nombre }}</option>
							@else
								<option value="{{ $department->id }}">{{ $department->nombre }}</option>
							@endif
						@endforeach
					</select>
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
					<label for="" class="textoPromedio">Ciudad</label>
					{{ Form::text('ciudad',$publicaciones->ciudad,array('class' => 'form-control','placeholder' => 'Ciudad')) }}
					@if ($errors->has('ciudad'))
						 @foreach($errors->get('ciudad') as $err)
						 	<div class="alert alert-danger">
						 		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						 		<p class="textoPromedio">{{ $err }}</p>
						 	</div>
						 @endforeach
					@endif
				</div>
				
				@if($publicaciones->categoria == 34)
					<div class="col-xs-6">
						<label class="textoPromedio">Marca</label>
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
						<label class="textoPromedio">Modelo</label>
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
						<label class="textoPromedio">Año</label>
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
						<label class="textoPromedio">Documentación</label>
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
						<label class="textoPromedio">Kilometraje</label>
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
						<label class="textoPromedio">(*) Cilindrada</label>
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
						<label class="textoPromedio">(*) Transmisión</label>
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
						<label class="textoPromedio">(*) Combustible</label>
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
						<label class="textoPromedio">(*) Tracción</label>
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
				@elseif($publicaciones->categoria == 20)
					<div class="col-xs-12">
						<label class="textoPromedio">Extension (mt2)</label>
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
					<label class="textoPromedio">Operación</label>
					<div class="col-xs-12" class="textoPromedio">
						
						<span for="tipoTransac" class="textoPromedio">Venta</span>
						<input type="radio" name="tipoTransac" value="venta" > 
						<span for="tipoTransac" class="textoPromedio">Alquiler</span>
						<input type="radio" name="tipoTransac" value="alquiler">
						@if($publicaciones->categoria == 20)
							<span for="tipoTransac" class="textoPromedio">Anticrético </span>
							<input type="radio" name="tipoTransac" value="Aticretico">
							<span for="tipoTransac" class="textoPromedio">otro</span>
							<input type="radio" name="tipoTransac" value="otro">
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
					<label for="input" class="textoPromedio">Descripción</label>
					<textarea id="input" name="input" class="form-control">{{ $publicaciones->descripcion }}</textarea>
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
					
					<label>Imagen principal</label>
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
					<label for="" class="textoPromedio">(*) Nombre de contacto</label>
					{{ Form::text('nomb',$publicaciones->name,array('class' => 'form-control','placeholder' => 'Nombre de contacto')) }}
				</div>
				<div class="col-xs-6">
					<label for="" class="textoPromedio">(*) Teléfono de contacto</label>
					{{ Form::text('phone',$publicaciones->lastname,array('class' => 'form-control','placeholder' => 'Telefono de contacto')) }}
				</div>
				<div class="col-xs-6">
					<label for="" class="textoPromedio">(*) Correo electrónico</label>
					{{ Form::text('email',$publicaciones->email,array('class' => 'form-control', 'placeholder' => 'Correo electronico')) }}
				</div>
				<div class="col-xs-6">
					<label for="" class="textoPromedio">(*) Sitio web</label>
					{{ Form::text('pagina',$publicaciones->pag_web_hab,array('class' => 'form-control', 'placeholder' => 'Sitio web')) }}
				</div>

				<div class="col-xs-12">
					<button  value="{{ $publicaciones->id }}" name="enviarPub" class="btn btn-success">Enviar</button>
					<input type="reset" value="Borrar" name="borrar" class="btn btn-warning">
					<a href="{{ URL::to('usuario/publicaciones/mis-publicaciones/lider') }}" class="btn btn-danger cancel">Cancelar</a>
				</div>



			

			@elseif($tipo == 'casual')	
				<div class="col-xs-12">
					<ol class="breadcrumb textoPromedio">
					  <li><a href="{{ URL::to('usuario/publicar') }}" class="breadcrums"><span class="num">1</span> Elije el tipo de publicación.</a></li>
					  <li class="active"><a href="{{ URL::to('usuario/publicacion/casual') }}" class="breadcrums"><span class="num numActivo">2</span> Complete el formulario CASUAL</a></li>
					</ol>
				</div>
				<h4>Publicación CASUAL</h4>
				<hr>
				@if(Session::has('error'))
				<div class="col-xs-12">
					<div class="alert alert-danger">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						<p class="textoPromedio">{{ Session::get('error') }}</p>
					</div>
				</div>
				@endif
				<div class="col-xs-6">
					<label for="casCat" class="textoPromedio">Categoría</label>
					<select name="casCat" id="category" class="form-control">
						<option value="1">Seleccione la categoría</option>
						@foreach($categorias as $categoria)
						<option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
						@endforeach
					</select>
				</div>
				<div class="col-xs-6">
					<label for="casCity" class="textoPromedio">Ciudad</label>
					<select name="casCity" class="form-control">
						<option value="">Seleccione la ciudad</option>
						<option value="la_paz">La Paz</option>
						<option value="santa_cruz">Santa Cruz</option>
						<option value="cochabamba">Cochabamba</option>
						<option value="beni">Beni</option>
						<option value="potosi">Potosi</option>
						<option value="tarija">Tarija</option>
						<option value="chuquisaca">Chuquisaca</option>
						<option value="oruro">Oruro</option>
						<option value="pando">Pando</option>
					</select>
				</div>
				<div class="col-xs-12">
					<label for="casTit" class="textoPromedio">Título</label>
					{{ Form::text('casTit', Input::old('casTit'), array('class' => 'form-control','placeholder' => 'Titulo')) }}
				</div>
				<div class="col-xs-6">
					<p class="bg-info textoPromedio" style="padding:1em;text-align:center;">La imagen debe tener un ancho mínimo de 400px</p>
					<hr>
					<label for="casTit" class="textoPromedio">Imagen principal</label>
					<input type="file" name="img1" class="textoPromedio">
				</div>
				<div class="col-xs-6">
					<label for="casTit" class="textoPromedio">Segunda imagen</label>
					<input type="file" name="img2" class="textoPromedio">
				</div>
				<div class="col-xs-12">
					<label for="input" class="textoPromedio">Descripción</label>
					<textarea id="input" name="input" class="form-control descripcionCasual"></textarea>
					@if ($errors->has('input'))
						 @foreach($errors->get('input') as $err)
						 	<div class="alert alert-danger">
						 		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						 		<p class="textoPromedio">{{ $err }}</p>
						 	</div>
						 @endforeach
					@endif
					<div class="cantCaracteres textoPromedio"></div>
					<div class="captcha col-xs-qw">
							<p class="textoPromedio formula">
							</p>
							<div class="col-xs-6">
								<input type="text" name="resultado" class="form-control">
							</div>
							
					</div>
				</div>
				<div class="col-xs-12">
					<button class="btn btn-success">Enviar</button>
					<a href="#" class="btn btn-danger">Cancelar</a>
				</div>
			@endif
				
			</form>
		</div>
	</div>
</div>
@stop