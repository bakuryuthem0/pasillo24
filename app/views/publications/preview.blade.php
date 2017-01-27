@extends('main')

@section('content')
<div class="container contenedorUnico">
	<div class="row">
		<div class="col-xs-12 no-padding">
				<div class="col-xs-12 col-md-6 imagesCont no-padding">
					
					<div class="col-xs-12 imgProd ">
						<div class="cien hidden-md">
			              <img src="" class="zoomed">
			            </div>
						<img src="{{ asset('images/pubImages/'.$publication->img_1) }}" class="imgPrinc" style="">
					</div>
					<div class="col-xs-12 minis preview no-padding">
						@if(!empty($publication->img_1))
						<img src="{{ asset('images/pubImages/'.$publication->img_1) }}" class="imgMini" data-value="img_1">
						@endif
						@if(!empty($publication->img_2))
						<img src="{{ asset('images/pubImages/'.$publication->img_2) }}" class="imgMini" data-value="img_2">
						@endif
						@if(!empty($publication->img_3))
						<img src="{{ asset('images/pubImages/'.$publication->img_3) }}" class="imgMini" data-value="img_3">
						@endif
						@if(!empty($publication->img_4))
						<img src="{{ asset('images/pubImages/'.$publication->img_4) }}" class="imgMini" data-value="img_4">
						@endif
						@if(!empty($publication->img_5))
						<img src="{{ asset('images/pubImages/'.$publication->img_5) }}" class="imgMini" data-value="img_5">
						@endif
						@if(!empty($publication->img_6))
						<img src="{{ asset('images/pubImages/'.$publication->img_6) }}" class="imgMini" data-value="img_6">
						@endif
						@if(!empty($publication->img_7))
						<img src="{{ asset('images/pubImages/'.$publication->img_7) }}" class="imgMini" data-value="img_7">
						@endif
						@if(!empty($publication->img_8))
						<img src="{{ asset('images/pubImages/'.$publication->img_8) }}" class="imgMini" data-value="img_8">
						@endif
					</div>
					<div class="col-xs-12 formulario hidden-sm no-padding" style="margin-bottom:1em;">
						<div class="alert responseDanger">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							<p></p>
						</div>
						<img src="{{ asset('images/loading.gif') }}" class="miniLoader hidden">
						<button class="btn btn-primary btnChange" >Cambiar Orden</button>
						<button class="btn btn-success btnChangeEnviar btn-no hidden" value="{{ $publication->id }}">Enviar</button>
						<button class="btn btn-danger btnChangeCancel btn-no hidden">Cancelar</button>

					</div>
				</div>
				<div class="col-xs-12 col-md-6 descriptionPub contAnaranjado">
					<div class="col-xs-12">
						<legend><h2>{{ ucfirst($publication->titulo) }}</h2></legend>
					</div>
					<div class="col-xs-6 caracteristicasPub">
						<h3>Precio</h3>
						<label class="textoPromedio">
							@if($publication->precio == "") 
							Sin especificar 
							@else 
							{{ $publication->precio }} @endif 
								{{ ucwords(strtolower($publication->moneda)) }}
						</label> 
					</div>
					
					<div class="col-xs-12 col-sm-6 caracteristicasPub">
						<h3>Departamento</h3>
						<label class="textoPromedio">
							{{ $publication->dep }}
						</label>

					</div>
					<div class="col-xs-12 col-sm-6 caracteristicasPub">
						<h3>Ciudad</h3>
						<label class="textoPromedio">
							{{ $publication->ciudad }}
						</label>

					</div>
					<div class="col-xs-12 col-sm-6 caracteristicasPub">
						<h3>Categoría</h3>
						<label class="textoPromedio">
							{{ $publication->cat }}
						</label>

					</div>
					<div class="col-xs-12 col-sm-6 caracteristicasPub">
						<h3>Sub-categoría</h3>
						<label class="textoPromedio">
							{{ $publication->subCat }}
						</label>

					</div>
					@if($publication->categoria == 34)
						<div class="col-xs-12 col-sm-6 caracteristicasPub">
							<h3>Año</h3>
							<label class="textoPromedio">
								{{ $publication->anio }}
							</label>

						</div>
						<div class="col-xs-12 col-sm-6 caracteristicasPub">
							<h3>Kilometraje</h3><label class="textoPromedio">@if(strlen($publication->precio) == 0) Sin especificar @else {{ $publication->kilometraje }} @endif</label>
						</div>
						<div class="col-xs-12 col-sm-6 caracteristicasPub">
							<h3>Marca</h3><label class="textoPromedio">@if(strlen($publication->precio) == 0) Sin especificar @else {{ $publication->marca }} @endif</label>
						</div>
						<div class="col-xs-12 col-sm-6 caracteristicasPub">
							<h3>Modelo</h3><label class="textoPromedio">@if(strlen($publication->precio) == 0) Sin especificar @else {{ $publication->modelo }} @endif</label>
						</div>
						
						<div class="col-xs-12 col-sm-6 caracteristicasPub">
							<h3>Documentación</h3><label class="textoPromedio">@if(strlen($publication->precio) == 0) Sin especificar @else {{ $publication->documentos }} @endif</label>
						</div>
						@if(!empty($publication->cilindraje))
						<div class="col-xs-12 col-sm-6 caracteristicasPub">
							<h3>Cilindraje</h3><label class="textoPromedio">{{ $publication->cilindraje }} </label>
						</div>
						@endif

						@if(!empty($publication->transmicion))
						<div class="col-xs-12 col-sm-6 caracteristicasPub">
							<h3>Transmición</h3><label class="textoPromedio"> {{ $publication->transmicion }} </label>
						</div>
						@endif

						@if(empty($publication->traccion))
						<div class="col-xs-12 col-sm-6 caracteristicasPub">
							<h3>Tracción</h3><label class="textoPromedio">{{ $publication->traccion }}</label>
						</div>
						@endif

						@if(!empty($publication->combustible))
						<div class="col-xs-12 col-sm-6 caracteristicasPub">
							<h3>Documentación</h3><label class="textoPromedio">{{ $publication->combustible }}</label>
						</div>
						@endif
					@elseif($publication->categoria == 20)
						<div class="col-xs-12 col-sm-6 caracteristicasPub">
							<h3>Extensión</h3><label class="textoPromedio">{{ $publication->extension }} mt<sup>2</sup></label>
						</div>
					@endif	
					<div class="col-xs-12 col-sm-6 caracteristicasPub">
						<h3>Tipo de operación</h3>
						<label class="textoPromedio">
							{{ $publication->transaccion }}
						</label>

					</div>
					@if(!is_null($publication->bussiness_type))
						<div class="col-xs-12 col-sm-6 caracteristicasPub">
							<h3>Tipo de negocio</h3><label class="textoPromedio">{{ ucfirst($publication->bussiness_type)
							 }}</label>
						</div>
					@endif
					@if(!is_null($publication->condicion))
					<div class="clearfix"></div>
						<div class="col-xs-12 col-sm-6 caracteristicasPub">
							<h3>Condición</h3><label class="textoPromedio">{{ ucfirst($publication->condicion)
							 }}</label>

						</div>
					@endif
				</div>
			</div>
			<div class="col-xs-12 textoPromedio descProd contAnaranjado">
				<div class="col-xs-8 col-sm-offset-2 comentarioBox">
					{{ $publication->descripcion }}
				</div>
				<div class="clearfix"></div>
				<p class="bg-info" style="padding:0.5em;text-align:center;">Si desea modificar su publicación vaya al menú de usuario > mis publicaciones</p>
			</div>
			@if(isset($publication->longitude) && isset($publication->latitude))
			<div class="col-xs-12 no-padding" >
				<h4>Mi ubicación</h4>
				<hr>
				<article class="mapContainer" id="" style="position:relative;">
					<div class="contLoaderBig">
						<img src="{{ asset('images/loading.gif') }}" class="loaderBig">
					</div>
					<div id="mapcontainer" style="width:100%;height:400px;">
					</div>
				</article>
				<input type="hidden" name="latitud" class="latitud" value="{{ $publication->latitude }}">
				<input type="hidden" name="longitud" class="longitud" value="{{ $publication->longitude  }}">
			</div>
			@endif
			<div class="clearfix"></div>
			<a href="{{ URL::to('usuario/publicacion/habitual/pago/'.$id) }}" class="btn btn-success" style="margin:2em auto;width:100px;display:block;">Continuar</a>
		</div>
	</div>
@stop

@section('postscript')
<script type="text/javascript">

	jQuery(document).ready(function($) {
		var imgPrinc = $('.imgPrinc').attr('src');
		$('.imgPrinc').hover(function(event) {
			$('.zoomed').attr('src', $(this).attr('src'));
			$('.zoomed').css({
				'display':'block',
				'height' : $(this).css('height')
			}).stop().animate({'opacity':1}, 250)
			$(this).stop().animate({'opacity':0}, 250)
			$(this).mousemove(function(event) {
				var x = event.pageX - $(this).offset().left;
				var y = event.pageY - $(this).offset().top;

				$('.zoomed').css({
					'transform-origin': x+'px '+y+'px'
				});
				
			});
		}, function(event) {
			$('.zoomed').stop().animate({'opacity':0}, 250,function()
			{
				$(this).css({
					'display':'none'
				})
			})
			$(this).stop().animate({'opacity':1}, 250)
		});
		$('.imgMini').on('click',function() {
			var imgHover = $(this).attr('src');
			$('.imgPrinc').attr('src',imgHover);
		});
	});
</script>
	@if(isset($publication->longitude) && isset($publication->latitude))

		<script type="text/javascript">
			loadMap();
		</script>
	@endif
@stop