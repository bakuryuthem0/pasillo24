@extends('main')
@section('content')
<div class="container contenedorUnico">
	<div class="row">
		
		<div class="col-xs-12 info contAnaranjado @if(Session::has('error')) noMostrar @endif" style="margin-top:2em;">

		@if ($tipo == 'lider')
			<div class="col-xs-12">
				<ol class="breadcrumb textoPromedio">
				  <li><a href="{{ URL::to('usuario/publicar') }}" class="breadcrums"><span class="num">1</span> Elija el tipo de publicación.</a></li>
				  <li class="active"><a href="{{ URL::to('usuario/publicacion/lider') }}" class="breadcrums"><span class="num numActivo">2</span> Complete el formulario LÍDER</a></li>
				</ol>
			</div>
			<div class="col-xs-12">
				<h3>ANUNCIO LÍDER</h3>
				
				<p class="textoPromedio" style="text-align:justify;padding-right: 2em;">{{ $texto->desc }}</p>
				<ul class="textoPromedio">					
					@foreach($precio as $p)
						<li>{{ucwords($p->desc)}}: {{ $p->precio }}</li>
					@endforeach
				</ul>
			</div>
			<div class="col-xs-12">
				<button class="btn btn-warning continue">Continuar</button>
			</div>
			<div class="col-xs-12"></div>
		@elseif($tipo == 'normal')
			<div class="col-xs-12">
				<ol class="breadcrumb textoPromedio">
				  <li><a href="{{ URL::to('usuario/publicar') }}" class="breadcrums"><span class="num">1</span> Elije el tipo de publicación.</a></li>
				  <li class="active"><a href="{{ URL::to('usuario/publicacion/habitual') }}" class="breadcrums"><span class="num numActivo">2</span> Elija la categoría de su publicación</a></li>
				</ol>
			</div>
			<div class="col-xs-12">
				<h3>ANUNCIO HABITUAL</h3>

				<p class="textoPromedio" style="text-align:justify;padding-right: 2em;">{{ $texto->desc}}</p>
			</div>
			
			<div class="col-xs-12"><button class="btn btn-warning continueNormal">Continuar</button></div>
		@elseif($tipo == 'casual')	
			<div class="col-xs-12">
				<ol class="breadcrumb textoPromedio">
				  <li><a href="{{ URL::to('usuario/publicar') }}" class="breadcrums"><span class="num">1</span> Elije el tipo de publicación.</a></li>
				  <li class="active"><a href="{{ URL::to('usuario/publicacion/casual') }}" class="breadcrums"><span class="num numActivo">2</span> Complete el formulario CASUAL</a></li>
				</ol>
			</div>
			<div class="col-xs-12">
				<h3>ANUNCIO CASUAL.</h3>
				<p class="textoPromedio" style="text-align:justify;padding-right: 2em;">{{ $texto->desc }}</p>
			</div>
			
			<div class="col-xs-12"><button class="btn btn-warning continueCasual">Continuar</button></div>
		@endif
		</div>
		<div class="col-xs-12 formPub contAnaranjado @if(Session::has('error')) mostrar @endif" style="margin-top:2em;">
			
			<form method="post" action="{{ URL::to($url) }}" enctype="multipart/form-data">
			@if ($tipo == 'lider')
				<div class="col-xs-12">
					<ol class="breadcrumb textoPromedio">
					  <li><a href="{{ URL::to('usuario/publicar') }}" class="breadcrums"><span class="num">1</span> Elije el tipo de publicación.</a></li>
					  <li class="active"><a href="{{ URL::to('usuario/publicacion/lider') }}" class="breadcrums"><span class="num numActivo">2</span> Complete el formulario LÍDER</a></li>
					</ol>
				</div>
				<div class="col-xs-12">
					<h4>Publicación LÍDER</h4>
					<h5><strong>(*)Campo obligatorio</strong></h5>
				<hr>

				</div>
				@if(Session::has('error'))
				<div class="col-xs-12">
					<div class="alert alert-danger">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						<p class="textoPromedio">{{ Session::get('error') }}</p>
					</div>
				</div>
				@endif
				<div class="@if (Input::old('ubication') && Input::old('ubication') == 'Categoria') col-xs-6 @else col-xs-12 @endif inputLider">
					<label for="department" class="textoPromedio">(*) Ubicación:</label>
					<select name="ubication" class="form-control" id="ubication" required>
						<option value="">Seleccione la ubicación a publicar</option>
						<option value="Principal">Menú principal</option>
						@if(Input::old('ubication') && Input::old('ubication') == 'Categoria')<option value="Categoria" selected>Menú por categorías</option>
						@else <option value="Categoria">Menú por categorías</option>
						@endif
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
				<div class="col-xs-6 contCatLider inputLider @if(Input::old('ubication') && Input::old('ubication') == 'Categoria') showit @endif">
					<label for="cat" class="textoPromedio">(*) Categoría</label>
					<select name="cat" id="category" class="form-control">
						<option value="">Seleccione la categoría</option>
						<optgroup label="Categoría">
						@foreach($categorias as $categoria)
						@if($categoria->id != $otros->id)
							@if(Input::old('ubication') && Input::old('ubication') == 'Categoria')
								@if(Input::old('cat') == $categoria->id)
									<option value="{{ $categoria->id }}" selected>{{ $categoria->nombre }}</option>
								@else
									<option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
								@endif
							@else
								<option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
							@endif
						@endif
						@endforeach
						<option value="{{ $otros->id }}">{{ $otros->nombre }}</option>
						</optgroup>
						<optgroup label="Servicios">
						@foreach($servicios as $servicio)
						@if($servicio->id != $otros2->id)
							@if($errors->has('cat'))
								@if(Input::old('cat') == $servicio->id)
									<option value="{{ $servicio->id }}" selected>{{ $servicio->nombre }}</option>
								@else
									<option value="{{ $servicio->id }}">{{ $servicio->nombre }}</option>
								@endif
							@else
									<option value="{{ $servicio->id }}">{{ $servicio->nombre }}</option>
							@endif
						@endif
						@endforeach
						<option value="{{ $otros2->id }}">{{ $otros2->nombre }}</option>
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
				<div class="col-xs-12 textoPromedio inputLider">
					<label for="namePub">(*) Nombre / Título de la publicación:</label>
					{{ Form::text('namePub',
					 Input::old('namePub'),
					 array('class' => 'form-control','id' => 'name','placeholder' => 'Titulo','required' => 'required')) }}
					@if ($errors->has('namePub'))
						 @foreach($errors->get('namePub') as $err)
						 	<div class="alert alert-danger">
						 		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						 		<p class="textoPromedio">{{ $err }}</p>
						 	</div>
						 @endforeach
					@endif
				</div>
				
				<div class="col-xs-6 inputLider">

					<label for="fechIni" class="textoPromedio">(*) Fecha de inicio</label>
					<input type="text" class="form-control" id="fechIni" name="fechIni" placeholder="DD-MM-AAAA" style="margin-top:1em;" required>
					
					@if ($errors->has('fechIni'))
						 @foreach($errors->get('fechIni') as $err)
						 	<div class="alert alert-danger">
						 		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						 		<p class="textoPromedio">{{ $err }}</p>
						 	</div>
						 @endforeach
					@endif
					<label for="fechaFin" class="textoPromedio">Fecha de Cierre</label>
					<div class="fechaFin col-xs-12" class="textoPromedio">
						<img src="{{ asset('images/loading.gif') }}" class="loading">
					</div>
				</div>
				<div class="col-xs-6 inputLider">
					<div class="col-xs-12 sinPadding">
						<label for="duration" class="textoPromedio">(*) Duración:</label>
					</div>
					<div class="col-xs-2 inputLider sinPadding">
						{{ Form::text('duration',
						 Input::old('duration'),
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
					<div class="col-xs-5 inputLider sinPadding" style="margin-top:1em;">
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
					<div class="col-xs-5 contPrecioShow">
					</div>
					<div class="col-xs-12" id="durError"></div>
				</div>
				<div class="col-xs-12" style="margin: 2em 0px 2em 0px;">
					<hr>

					<p class="bg-info textoPromedio" style="padding:1em;text-align:center;">Se recomienda que la imagen tenga un mínimo de 400px</p>
					<div class="col-xs-6 textoPromedio imgLiderUp">
						<label>(*) Imagen de la Publicación</label>
						<input type="file" name="portada" required>
						@if ($errors->has('portada'))
						 @foreach($errors->get('portada') as $err)
						 	<div class="alert alert-danger">
						 		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						 		<p class="textoPromedio">{{ $err }}</p>
						 	</div>
						 @endforeach
					@endif
					</div>
					<div class="col-xs-6 textoPromedio imgLiderUp">
						<label>Imagen secundaria</label>
						<input type="file" name="portada2">
						@if ($errors->has('portada2'))
						 @foreach($errors->get('portada2') as $err)
						 	<div class="alert alert-danger">
						 		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						 		<p class="textoPromedio">{{ $err }}</p>
						 	</div>
						 @endforeach
					@endif
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

					<p class="bg-info textoPromedio" style="padding:1em;text-align:center;">El url de la página web debe comenzar con http:// o https://. Recuerde que la dirección que coloque será el enlace que tendrá el usuario hacia su página web.</p>
					<label for="pagina" class="textoPromedio">Url de su página web</label>
					{{ Form::text('pagina','http://',array('class' => 'form-control','placeholder' => 'http://example.com')) }}

					@if ($errors->has('pagina'))
						 @foreach($errors->get('pagina') as $err)
						 	<div class="alert alert-danger">
						 		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						 		<p class="textoPromedio">{{ $err }}</p>
						 	</div>
						 @endforeach
					@endif
				</div>
				<div class="col-xs-12">
					<h4>¿Deseas mostrar la ubicación de tu publicación?</h4>
					<input type="checkbox" class="doMap">
				</div>
				<div class="col-xs-12">
					<article class="mapContainer" id="" style="position:relative;">
						<div class="contLoaderBig">
							<img src="{{ asset('images/loading.gif') }}" class="loaderBig">
						</div>
					</article>
					<input type="hidden" name="latitud" class="latitud">
					<input type="hidden" name="longitud" class="longitud">
				</div>
				<div class="col-xs-12 imgLiderUp" style="margin-top:5em;">
					<legend>Información de contacto</legend>
				</div>
				<div class="col-xs-6 imgLiderUp">
					<label for="" class="textoPromedio">Nombre de contacto</label>
					{{ Form::text('nomb',Input::old('nomb'),array('class' => 'form-control','placeholder' => 'Nombre de contacto')) }}
				</div>
				<div class="col-xs-6 imgLiderUp">
					<label for="" class="textoPromedio">Teléfono de contacto</label>
					{{ Form::text('phone',Input::old('phone'),array('class' => 'form-control','placeholder' => 'Telefono de contacto')) }}
				</div>
				<div class="col-xs-6 imgLiderUp">
					<label for="" class="textoPromedio">Correo electrónico</label>
					{{ Form::text('email',Input::old('email'),array('class' => 'form-control', 'placeholder' => 'Correo electronico')) }}
				</div>
				<div class="col-xs-6 imgLiderUp">
					<label for="" class="textoPromedio">Sitio web</label>
					{{ Form::text('pag_web',Input::old('pag_web'),array('class' => 'form-control', 'placeholder' => 'Sitio web')) }}
				</div>
				<div class="col-xs-12">
					<input type="submit" value="Enviar" name="enviarPub" class="btn btn-success enviarPub">
					<input type="reset" value="Borrar" name="borrar" class="btn btn-warning">
					<a href="{{ URL::to('usuario/publicar') }}" class="btn btn-danger cancel">Cancelar</a>
				</div>
				
			@elseif($tipo == 'normal')
			<div class="col-xs-12">
				<ol class="breadcrumb textoPromedio">
				  <li><a href="{{ URL::to('usuario/publicar') }}" class="breadcrums"><span class="num">1</span> Elije el tipo de publicación.</a></li>
				  <li class="active"><a href="{{ URL::to('usuario/publicacion/habitual') }}" class="breadcrums"><span class="num numActivo">2</span> Elija la categoría y llene el formulario</a></li>
				</ol>
			</div>
			<div class="col-xs-12">
				<legend style="text-aling:left;">Categorias</legend>
			</div>
			@if(Session::has('error'))
			<div class="col-xs-12">
				<div class="alert alert-danger">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<p class="textoPromedio">{{ Session::get('error') }}</p>
				</div>
			</div>
			@endif
			<div class="col-xs-12 normalType contCategoriasPub">
				<ul class="textoPromedio" style="text-align:left;">
				@foreach($categorias as $categoria)
					@if($categoria->id != $otros->id)
					<li><a href="{{ URL::to('publicacion/habitual/crear/'.$categoria->id) }}">{{ $categoria->nombre }}</a></li>
					@endif
				@endforeach
					<li><a href="{{ URL::to('publicacion/habitual/crear/'.$otros->id) }}">{{ $otros->nombre }}</a></li>

				</ul>
				
			</div>
			<div class="clearfix"></div>
			<div class="col-xs-12">
				<legend style="text-aling:left;">Servicios</legend>
			</div>
			<div class="col-xs-12 normalType contCategoriasPub">
				<ul class="textoPromedio" style="text-align:left;">
				@foreach($servicios as $servicio)
					@if($servicio->id != $otros2->id)
					<li><a href="{{ URL::to('publicacion/habitual/crear/'.$servicio->id) }}">{{ $servicio->nombre }}</a></li>
					@endif
				@endforeach
					<li><a href="{{ URL::to('publicacion/habitual/crear/'.$otros2->id) }}">{{ $otros2->nombre }}</a></li>
				</ul>
				
			</div>
			

			@elseif($tipo == 'casual')	
			<input type="hidden" name="casual" class="casual-form">
				<div class="col-xs-12">
					<ol class="breadcrumb textoPromedio">
					  <li><a href="{{ URL::to('usuario/publicar') }}" class="breadcrums"><span class="num">1</span> Elije el tipo de publicación.</a></li>
					  <li class="active"><a href="{{ URL::to('usuario/publicacion/casual') }}" class="breadcrums"><span class="num numActivo">2</span> Complete el formulario CASUAL</a></li>
					</ol>
				</div>
				<h4>Publicación CASUAL</h4>
				<p class="textoPromedio">(*) Campos obligatorios</p>
				<hr>
				@if(Session::has('error'))
				<div class="col-xs-12">
					<div class="alert alert-danger">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						<p class="textoPromedio">{{ Session::get('error') }}</p>
					</div>
				</div>
				@endif
				<div class="col-xs-12 col-md-6">
					<label for="casCat" class="textoPromedio">(*) Categoría</label>
					<select name="casCat" id="category" class="form-control" required>
						<option value="">Seleccione la categoría</option>
						<optgroup label="Categoría">
						@foreach($categorias as $categoria)
						@if($categoria->id != $otros->id)
							<option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
						@endif
						@endforeach
							<option value="{{ $otros->id }}">{{ $otros->nombre }}</option>
						</optgroup>
						<optgroup label="Servicios">
						@foreach($servicios as $servicio)
						@if($servicio->id != $otros2->id)
							<option value="{{ $servicio->id }}">{{ $servicio->nombre }}</option>
						@endif
						@endforeach
							<option value="{{ $otros2->id }}">{{ $otros2->nombre }}</option>
						</optgroup>
					</select>
				</div>
				<div class="col-xs-12 col-md-6">
					<label for="casCity" class="textoPromedio">(*) Departamento</label>
					<?php $arr = array(
							'' => 'Seleccione su departamento');
							 ?>
					@foreach ($departamento as $department)
						<?php $arr = $arr+array($department->id => $department->nombre);  ?>
					@endforeach
					
					{{ Form::select('casCity',$arr,Input::old('casCity'),array('class' => 'form-control','required' => 'required')
						)}}
					@if ($errors->has('casCity'))
						 @foreach($errors->get('casCity') as $err)
						 	<div class="alert alert-danger">
						 		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						 		<p class="textoPromedio">{{ $err }}</p>
						 	</div>
						 @endforeach
					@endif
				</div>
				<div class="col-xs-12">
					<label for="casTit" class="textoPromedio">(*) Titulo</label>
					{{ Form::text('casTit', Input::old('casTit'), array('class' => 'form-control','placeholder' => 'Titulo','required' => 'required')) }}
				</div>
				<div class="col-xs-12 col-md-6">
					<label for="precio" class="textoPromedio">(*) Precio</label>
					{{ Form::text('precio',Input::old('precio'),array('placeholder' => 'Precio','class' => 'form-control','required' => 'required')) }}
					@if ($errors->has('precio'))
						 @foreach($errors->get('precio') as $err)
						 	<div class="alert alert-danger">
						 		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						 		<p class="textoPromedio">{{ $err }}</p>
						 	</div>
						 @endforeach
					@endif
				</div>
				<div class="col-xs-12 col-md-6">
					<label class="textoPromedio">(*) Moneda</label>
					<div class="col-xs-12" class="textoPromedio">
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
				<div class="col-xs-12">
					<p class="bg-info textoPromedio" style="padding:1em;text-align:center;">Se recomienda que la imagen tenga un mínimo de 400px</p>
					<hr>
				</div>
				<div class="col-xs-12 col-md-6">
					<label for="casTit" class="textoPromedio">(*) Imagen principal</label>
					<input type="file" name="img1" class="textoPromedio" required>
				</div>
				<div class="col-xs-12 col-md-6">
					<label for="casTit" class="textoPromedio">Segunda imagen</label>
					<input type="file" name="img2" class="textoPromedio">
				</div>
				<div class="col-xs-12">
					<h4>¿Deseas mostrar la ubicación de tu publicación?</h4>
					<input type="checkbox" class="doMap">
				</div>
				<div class="col-xs-12">
					<article class="mapContainer" id="" style="position:relative;">
						<div class="contLoaderBig">
							<img src="{{ asset('images/loading.gif') }}" class="loaderBig">
						</div>
						
					</article>
					<input type="hidden" name="latitud" class="latitud">
					<input type="hidden" name="longitud" class="longitud">
				</div>
				<div class="col-xs-12">
					<p class="bg-info textoPromedio" style="text-align:center;padding:1em;">La descripción deberá tener máximo 400 caracteres.</p>
					<label for="input" class="textoPromedio">Descripción</label>
					<!--<textarea id="input" name="input" class="form-control descripcionCasual"></textarea>-->
					<textarea  id="editor1" name="input" class="form-control descripcionCasual" required></textarea>
					@if ($errors->has('input'))
						 @foreach($errors->get('input') as $err)
						 	<div class="alert alert-danger">
						 		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						 		<p class="textoPromedio">{{ $err }}</p>
						 	</div>
						 @endforeach
					@endif
					<div class="cantCaracteres textoPromedio bg-info" style="padding:1em;margin-top:1em;margin-bottom:1em;"></div>
					<div class="captcha col-xs-qw">
							<p class="bg-info textoPromedio" style="padding:0.5em;">Ayúdenos a verificar que la publicación no fue creada por un robot, resolviendo la siguiente suma:</p>
							<p class="textoPromedio formula">
							</p>
							<div class="col-xs-6" style="padding-left:0px;">
								<input type="text" name="resultado" class="form-control" required>
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
@section('postscript')

<script>
	CKEDITOR.disableAutoInline = true;
	$( document ).ready( function() {
		$( '#editor1' ).ckeditor(); // Use CKEDITOR.replace() if element is <textarea>.
		CKEDITOR.on("instanceReady", function(event)
		{
			if ($('.casual-form').length > 0) {
				var total = 400;
				var texto = $('.cke_wysiwyg_frame').contents().find('body').html();
				texto2 = $(texto).text();
				
				
				var actual = total - parseInt(texto2.length);
				$('.cantCaracteres').html('Caracteres restantes: '+actual);
				$('.cke_wysiwyg_frame').contents().on('keyup',function(event){
					var texto = $('.cke_wysiwyg_frame').contents().find('body').html();
					texto2 = $(texto).text();
					actual = total - parseInt(texto2.length);
					$('.cantCaracteres').html('Caracteres restantes: '+actual);
					if (texto2.length>400) {
						if (event.which != 8) {
							event.preventDefault();
							var newText = texto.substr(0,400);
							$('.cke_wysiwyg_frame').contents().find('body').html(newText);
							$('.cantCaracteres').html('Caracteres restantes: '+0);
							alert('Ha alcanzado el limite de caracteres.')
						}
						

					};
				})

			};
	 		//put your code here
		});
	} );

</script>
@stop