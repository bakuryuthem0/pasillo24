@extends('main')

@section('content')
<div class="container contenedorUnico">
	<div class="row">
		<div class="col-xs-12">
				<div class="col-xs-6 imagesCont">
					
					<div class="col-xs-12 imgProd">
						<div class="cien">
			              <img src="" class="zoomed">
			            </div>
						<img src="{{ asset('images/pubImages/'.$publication->img_1) }}" class="imgPrinc" style="">
					</div>
					<div class="col-xs-12 minis">
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
					<div class="col-xs-12">
						<div class="alert responseDanger">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						</div>
						<button class="btn btn-primary btnChange" >Cambiar Orden</button>
						<button class="btn btn-success btnChangeEnviar btn-no" value="{{ $publication->id }}">Enviar</button>
						<button class="btn btn-danger btnChangeCancel btn-no">Cancelar</button>
					</div>
				</div>
				<div class="col-xs-6 descriptionPub contAnaranjado">
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
					
					<div class="col-xs-6 caracteristicasPub">
						<h3>Departamento</h3>
						<label class="textoPromedio">
							{{ $publication->dep }}
						</label>

					</div>
					<div class="col-xs-6 caracteristicasPub">
						<h3>Ciudad</h3>
						<label class="textoPromedio">
							{{ $publication->ciudad }}
						</label>

					</div>
					<div class="col-xs-6 caracteristicasPub">
						<h3>Categoría</h3>
						<label class="textoPromedio">
							{{ $publication->cat }}
						</label>

					</div>
					<div class="col-xs-6 caracteristicasPub">
						<h3>Sub-categoría</h3>
						<label class="textoPromedio">
							{{ $publication->subCat }}
						</label>

					</div>
					@if($publication->categoria == 34)
						<div class="col-xs-6 caracteristicasPub">
							<h3>Año</h3>
							<label class="textoPromedio">
								{{ $publication->anio }}
							</label>

						</div>
						<div class="col-xs-6 caracteristicasPub">
							<h3>Kilometraje</h3><label class="textoPromedio">@if(strlen($publication->precio) == 0) Sin especificar @else {{ $publication->kilometraje }} @endif</label>
						</div>
						<div class="col-xs-6 caracteristicasPub">
							<h3>Marca</h3><label class="textoPromedio">@if(strlen($publication->precio) == 0) Sin especificar @else {{ $publication->marca }} @endif</label>
						</div>
						<div class="col-xs-6 caracteristicasPub">
							<h3>Modelo</h3><label class="textoPromedio">@if(strlen($publication->precio) == 0) Sin especificar @else {{ $publication->modelo }} @endif</label>
						</div>
						
						<div class="col-xs-6 caracteristicasPub">
							<h3>Documentación</h3><label class="textoPromedio">@if(strlen($publication->precio) == 0) Sin especificar @else {{ $publication->documentos }} @endif</label>
						</div>
						@if(!empty($publication->cilindraje))
						<div class="col-xs-6 caracteristicasPub">
							<h3>Cilindraje</h3><label class="textoPromedio">{{ $publication->cilindraje }} </label>
						</div>
						@endif

						@if(!empty($publication->transmicion))
						<div class="col-xs-6 caracteristicasPub">
							<h3>Transmición</h3><label class="textoPromedio"> {{ $publication->transmicion }} </label>
						</div>
						@endif

						@if(empty($publication->traccion))
						<div class="col-xs-6 caracteristicasPub">
							<h3>Tracción</h3><label class="textoPromedio">{{ $publication->traccion }}</label>
						</div>
						@endif

						@if(!empty($publication->combustible))
						<div class="col-xs-6 caracteristicasPub">
							<h3>Documentación</h3><label class="textoPromedio">{{ $publication->combustible }}</label>
						</div>
						@endif
					@elseif($publication->categoria == 20)
						<div class="col-xs-6 caracteristicasPub">
							<h3>Extensión</h3><label class="textoPromedio">{{ $publication->extension }} mt<sup>2</sup></label>
						</div>
					@endif	
					<div class="col-xs-6 caracteristicasPub">
						<h3>Tipo de operación</h3>
						<label class="textoPromedio">
							{{ $publication->transaccion }}
						</label>

					</div>
				</div>
			</div>
			<div class="col-xs-12 textoPromedio descProd contAnaranjado">
				<div class="col-xs-8 col-sm-offset-2 comentarioBox">
					{{ $publication->descripcion }}
				</div>
				<div class="clearfix"></div>
				<p class="bg-info" style="padding:0.5em;text-align:center;">Si desea modificar su publicación vaya al menú de usuario > mis publicaciones</p>
			</div>
			
			<div class="clearfix"></div>
			<a href="{{ URL::to('usuario/publicacion/habitual/pago/'.$id) }}" class="btn btn-success" style="margin:2em auto;width:100px;display:block;">Continuar</a>
		</div>
	</div>
@stop