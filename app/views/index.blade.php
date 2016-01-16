@extends('main')
@section('content')
<div class="container contenedorUnico">
	<div class="row">
		<div class="col-xs-12 no-padding-movil" style="margin-top:0;">
			<div class="col-xs-2 contCategorias contAnaranjado">
				
				<h3 style="text-align:left;">Categorías</h3>
				<ul class="categorias">
				@foreach($categories as $category)
					@if($category->id != $otros->id)
					<li><a href="{{ URL::to('publicaciones/categorias/'.$category->id) }}">{{$category->nombre }}</a></li>
					@endif
				@endforeach
                    <li><a href="{{ URL::to('publicaciones/categorias/'.$otros->id) }}">{{ $otros->nombre }}</a></li>
				</ul>
				<h3 style="text-align:left;">Servicios</h3>
				<ul class="categorias">
				@foreach($servicios as $servicio)
					@if($servicio->id != $otros2->id)
					<li><a href="{{ URL::to('publicaciones/categorias/'.$servicio->id) }}">{{$servicio->nombre }}</a></li>
					@endif
				@endforeach
                    <li><a href="{{ URL::to('publicaciones/categorias/'.$otros2->id) }}">{{ $otros2->nombre }}</a></li>
				</ul>
			</div>
			<div class="col-xs-10 contBanner no-padding-movil" style="margin-top:0;">
				@if(Session::has('error'))
				<div class="col-xs-12" >
					<div class="alert alert-danger">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						<p class="textoPromedio">{{ Session::get('error') }}.</p>
					</div>
					
				</div>
				@endif
				<div class="col-xs-12 " style="margin-top:0;padding:0;">
					<img src="{{ asset('images/portada.png') }}">
				</div>
				<div class="col-xs-12 banderas hidden-xs hidden-md">
					<h3>Filtro de búsqueda:</h3>
					<a href="{{ URL::to('inicio') }}">
						<div class="bandera @if(!isset($depFilter)) bandera-bolivia @endif">
								<img src="{{ asset('images/banderas/BOLIVIA.png') }}" style="display:block;margin:0 auto;">
						</div>
					</a>
					@foreach($departamentos as $departamento)
					<a href="{{ URL::to('inicio/departamentos/'.$departamento->id) }}">
					<div class="bandera @if(isset($depFilter) && $depFilter == $departamento->id) bandera-bolivia @endif ">
						<img src="{{ asset('images/banderas/'.strtoupper(str_replace(' ','_',$departamento->nombre)).'.png') }}">
					</div>
					</a>
					@endforeach
				</div>
				<div class="col-xs-12 banderas visible-xs visible-md hidden-lg">
					<h3>Filtro de búsqueda:</h3>
					<div class="dropdown">
					  <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-expanded="true" style="color:black;margin: 0 auto;display: block;">
					    Departamentos
					    <span class="caret"></span>
					  </button>
					  <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu2">
					  	<li>
					  		<a href="{{ URL::to('inicio') }}" class="@if(!isset($depFilter)) link-activo @endif">
					  			Bolivia
					  		</a>
					  	</li>
					  	@foreach($departamentos as $departamento)
					  		<li role="presentation">
					  			<a  role="menuitem" tabindex="-1" href="{{ URL::to('inicio/departamentos/'.$departamento->id) }}" class="@if(isset($depFilter) && $depFilter == $departamento->id) link-activo @endif">
					  				{{ ucfirst(strtolower(str_replace('_',' ',$departamento->nombre))) }}
					  			</a>
					  		</li>
						@endforeach
					  </ul>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">

		<div class="col-xs-12 catInv" style="margin-bottom: 2em;">
			<div class="dropdown">
			  <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-expanded="true" style="color:black;margin: 0 auto;
display: block;">
			    Categorías/Servicios
			    <span class="caret"></span>
			  </button>
			  <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
			  	<li><strong>Categorías</strong></li>
			  	@foreach($categories as $category)
					@if($category->id != $otros->id)
			  			<li role="presentation">
			  				<a role="menuitem" tabindex="-1" href="{{ URL::to('publicaciones/categorias/'.$category->id) }}">
			  					{{$category->nombre }}
			  				</a>
			  			</li>
			  		@endif
				@endforeach
                <li><a href="{{ URL::to('publicaciones/categorias/'.$otros->id) }}">{{ $otros->nombre }}</a></li>
				<li><strong>Servicios</strong></li>
			   	@foreach($servicios as $servicio)
					@if($servicio->id != $otros2->id)
						<li><a href="{{ URL::to('publicaciones/categorias/'.$servicio->id) }}">{{$servicio->nombre }}</a></li>
					@endif
				@endforeach
                <li><a href="{{ URL::to('publicaciones/categorias/'.$otros2->id) }}">{{ $otros2->nombre }}</a></li>
			  </ul>
			</div>
		</div>
		<div class="container">
			<div class="row">
				<div clas="col-xs-12">
					<div class="container">
						<div clas="col-xs-12">
							@if($publi[0]->activo == 0)

								<img src="{{ asset('images/pubgrande.png') }}" style="width:100%;">
							@else
								<a href="@if($publi[0]->pag_web == "http://") # @else {{ URL::to($publi[0]->pag_web) }} @endif">
								<img src="{{ asset('images/publicidad/'.$publi[0]->image) }}" style="width:100%;">
							</a>
							@endif
						</div>
						<div class="col-xs-12" style="padding-left:0px;padding-right:0px;">
							<div class="col-xs-12 col-md-6 pubLeft" >
								@if($publi[1]->activo == 0)
									<img src="{{ asset('images/pubpeq.png') }}" style="width:100%;">
								@else
									<a href="@if($publi[1]->pag_web == "http://") # @else {{ URL::to($publi[1]->pag_web) }} @endif">
									<img src="{{ asset('images/publicidad/'.$publi[1]->image) }}" style="width:100%;">
								</a>
								@endif
							</div>
							<div class="col-xs-12 col-md-6 pubRight" >
								@if($publi[2]->activo == 0)
									<img src="{{ asset('images/pubpeq.png') }}" style="width:100%;">
								@else
									<a href="@if($publi[2]->pag_web == "http://") # @else {{ URL::to($publi[2]->pag_web) }} @endif">
									<img src="{{ asset('images/publicidad/'.$publi[2]->image) }}" style="width:100%;">
								</a>
								@endif
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="container"> 
			<div class="row">
				<div class="col-xs-12 publication">
					<h3>Anuncios LÍDER de Empresas y Particulares con sitio web.
</h3>
						<div class="owl-carousel1">
							@foreach($lider as $pubLider)
							<div class="item contCatIndex">
								<a href="{{ URL::to('publicacion/lider/'.base64_encode($pubLider->id)) }}">
									<img src="{{ asset('images/pubImages/'.$pubLider->img_1) }}" class="imgPubCarousel">
								</a>
								<div class="dataIndex textoPromedio">
									<div class="col-xs-6" style="padding-top:0px;margin-top:0px;">{{ $pubLider->titulo }}</div>
									<div class="col-xs-12"><a href="{{ URL::to('publicacion/lider/'.base64_encode($pubLider->id)) }}" style="color:white;"><i class="fa fa-hand-o-right"></i> Ver publicación</a>
									</div>
								</div>
							</div>
							@endforeach
							@if(count($lider)<1)
							<div class="item">
									<img src="{{ asset('images/anuncios-01.png') }}">
							</div>
							<div class="item">
									<img src="{{ asset('images/anuncios-02.png') }}">
							</div>
							<div class="item">
									<img src="{{ asset('images/anuncios-03.png') }}">
							</div>
							<div class="item">
									<img src="{{ asset('images/anuncios-04.png') }}">
							</div>
							@elseif(count($lider)>=1 && count($lider)<2)
							<div class="item">
									<img src="{{ asset('images/anuncios-02.png') }}">
							</div>
							<div class="item">
									<img src="{{ asset('images/anuncios-03.png') }}">
							</div>
							<div class="item">
									<img src="{{ asset('images/anuncios-04.png') }}">
							</div>
							@elseif(count($lider)>=2 && count($lider)<3)
							<div class="item">
									<img src="{{ asset('images/anuncios-03.png') }}">
							</div>
							<div class="item">
									<img src="{{ asset('images/anuncios-04.png') }}">
							</div>
							@else
							
							<div class="item">
									<img src="{{ asset('images/anuncios-04.png') }}">
							</div>
							@endif
						</div>
				</div>
				<div class="col-xs-12 publication">
					<h3>Anuncios LÍDER de Empresas y Particulares sin sitio web</h3>
						<div class="owl-carousel2">
							@foreach($habitual as $pubHabitual)
							 <div class="item contCatIndex">
								<a href="{{ URL::to('publicacion/habitual/'.base64_encode($pubHabitual->id)) }}">
									<img src="{{ asset('images/pubImages/'.$pubHabitual->img_1) }}" class="imgPubCarousel">
								</a>
								<div class="dataIndex textoPromedio">
									<div class="col-xs-6" style="padding-top:0px;margin-top:0px;">{{ $pubHabitual->titulo }}</div>
									<div class="col-xs-6" style="padding-top:0px;margin-top:0px;">
									@if($pubHabitual->precio)
									 <label>Precio: </label>{{ $pubHabitual->precio.' '.ucfirst(strtolower($pubHabitual->moneda)).'.' }}
									@endif
									</div>
									<div class="col-xs-12"><a href="{{ URL::to('publicacion/lider/'.base64_encode($pubHabitual->id)) }}" style="color:white;"><i class="fa fa-hand-o-right"></i> Ver publicación</a>
									</div>
								</div>
							</div>
							
							@endforeach
							@if(count($habitual)<1)
							<div class="item">
									<img src="{{ asset('images/anuncios-01.png') }}">
							</div>
							<div class="item">
									<img src="{{ asset('images/anuncios-02.png') }}">
							</div>
							<div class="item">
									<img src="{{ asset('images/anuncios-03.png') }}">
							</div>
							<div class="item">
									<img src="{{ asset('images/anuncios-04.png') }}">
							</div>
							@elseif(count($habitual)>=1 && count($habitual)<2)
							<div class="item">
									<img src="{{ asset('images/anuncios-02.png') }}">
							</div>
							<div class="item">
									<img src="{{ asset('images/anuncios-03.png') }}">
							</div>
							<div class="item">
									<img src="{{ asset('images/anuncios-04.png') }}">
							</div>
							@elseif(count($habitual)>=2 && count($habitual)<3)
							<div class="item">
									<img src="{{ asset('images/anuncios-03.png') }}">
							</div>
							<div class="item">
									<img src="{{ asset('images/anuncios-04.png') }}">
							</div>
							@else
							
							<div class="item">
									<img src="{{ asset('images/anuncios-04.png') }}">
							</div>
							@endif
						</div>
							
				</div>
				<div class="col-xs-12 publication">
					<h3>Últimos anuncios CASUALES</h3>
						<div class="owl-carousel3">
							@foreach($casual as $pubCasual)
							<div class="item contCatIndex">
								<a href="{{ URL::to('publicacion/casual/'.base64_encode($pubCasual->id)) }}">
									<img src="{{ asset('images/pubImages/'.$pubCasual->img_1) }}" class="imgPubCarousel">
								</a>
								<div class="dataIndex textoPromedio">
									<div class="col-xs-6" style="padding-top:0px;margin-top:0px;">{{ $pubCasual->titulo }}</div>
									<div class="col-xs-6" style="padding-top:0px;margin-top:0px;"><label>Precio: </label>{{ $pubCasual->precio.' '.ucfirst(strtolower($pubCasual->moneda)).'.' }}</div>
									<div class="col-xs-12"><a href="{{ URL::to('publicacion/lider/'.base64_encode($pubCasual->id)) }}" style="color:white;"><i class="fa fa-hand-o-right"></i> Ver publicación</a>
									</div>
								</div>
							</div>
							@endforeach
							@if(count($casual)<1)
							<div class="item">
									<img src="{{ asset('images/anuncios-01.png') }}">
							</div>
							<div class="item">
									<img src="{{ asset('images/anuncios-02.png') }}">
							</div>
							<div class="item">
									<img src="{{ asset('images/anuncios-03.png') }}">
							</div>
							<div class="item">
									<img src="{{ asset('images/anuncios-04.png') }}">
							</div>
							@elseif(count($casual)>=1 && count($casual)<2)
							<div class="item">
									<img src="{{ asset('images/anuncios-02.png') }}">
							</div>
							<div class="item">
									<img src="{{ asset('images/anuncios-03.png') }}">
							</div>
							<div class="item">
									<img src="{{ asset('images/anuncios-04.png') }}">
							</div>
							@elseif(count($casual)>=2 && count($casual)<3)
							<div class="item">
									<img src="{{ asset('images/anuncios-03.png') }}">
							</div>
							<div class="item">
									<img src="{{ asset('images/anuncios-04.png') }}">
							</div>
							@else
							
							<div class="item">
									<img src="{{ asset('images/anuncios-04.png') }}">
							</div>
							@endif
						</div>
				</div>

			</div>
		</div>
	</div>
</div>
@stop

@section('postscript')
<script type="text/javascript">
      $(document).ready(function(){
      	$('.owl-carousel1').owlCarousel({
		    loop:true,
		    margin:30,
		    nav:true,
		    navText: [ 'Anterior', 'Siguiente' ],
		    itemsScaleUp:true,
		    stagePadding: 0,
		    autoplay:true,
			autoplayTimeout:5000,
			autoplayHoverPause:true,
			items : 3,
      		itemsDesktop : [1200,3],
      		itemsDesktopSmall : [979,2],
		    responsive:{
		        0:{
		            items:1
		        },
	        	650:{
		            items:2
		        },
		        850:
		        {
		        	items: 3
		        },
		        1200:{
		            items:4
		        }
		    }
		})
		  $('.owl-carousel2').owlCarousel({
		    loop:true,
		    margin:30,
		    nav:true,
		    navText: [ 'Anterior', 'Siguiente' ],
		    itemsScaleUp:true,
		    stagePadding: 0,
		    autoplay:true,
			autoplayTimeout:5000,
			autoplayHoverPause:true,
			items : 3,
      		itemsDesktop : [1200,3],
      		itemsDesktopSmall : [979,2],
		    responsive:{
		        0:{
		            items:1
		        },
		        650:{
		            items:2
		        },
		        850:
		        {
		        	items: 3
		        },
		        1200:{
		            items:4
		        }
		    }
		})
		  $('.owl-carousel3').owlCarousel({
		    loop:true,
		    margin:30,
		    nav:true,
		    navText: [ 'Anterior', 'Siguiente' ],
		    itemsScaleUp:true,
		    stagePadding: 0,
		    autoplay:true,
			autoplayTimeout:5000,
			autoplayHoverPause:true,
			items : 3,
      		itemsDesktop : [1200,3],
      		itemsDesktopSmall : [979,2],
		    responsive:{
		        0:{
		            items:1
		        },
		        650:{
		            items:2
		        },
		        850:
		        {
		        	items: 3
		        },
		        1200:{
		            items:4
		        }
		    }
		})
	});
   </script>
@stop