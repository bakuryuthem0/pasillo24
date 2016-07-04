@extends('main')
@section('content')
<div class="wrapper">
	<div class="row">
		<aside class="col-sm-3 boder-colored cat-container hidden-xs">
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
		</aside>
		<div class="col-xs-12 col-sm-9 portada">
			<img src="{{ asset('images/portada.png') }}">
			<div class="col-xs-12 banderas hidden-xs">
				<h3>Filtro de búsqueda:</h3>
					<div class="bandera @if(!isset($depFilter)) bandera-bolivia @endif">
						<a href="{{ URL::to('inicio') }}">
							<img src="{{ asset('images/banderas/BOLIVIA.png') }}" style="display:block;margin:0 auto;">
						</a>
					</div>
				@foreach($departamentos as $departamento)
				<div class="bandera @if(isset($depFilter) && $depFilter == $departamento->id) bandera-bolivia @endif ">
					<a href="{{ URL::to('inicio/departamentos/'.$departamento->id) }}">
						<img src="{{ asset('images/banderas/'.strtoupper(str_replace(' ','_',$departamento->nombre)).'.png') }}">
					</a>
				</div>
				@endforeach
			</div>
		</div>
		<div class="col-xs-12 filters visible-xs hidden-md hidden-lg">
			<h3>Filtro de búsqueda:</h3>
			<div class="dropdown">
			  <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-expanded="true">
			    Categorías/Servicios
			    <span class="caret"></span>
			  </button>
			  <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
			  	<li><strong>Categorías</strong></li>
			  	@foreach($categories as $c)
					@if($c->id != $otros->id)
			  			<li role="presentation">
			  				<a role="menuitem" tabindex="-1" href="{{ URL::to('publicaciones/categorias/'.$c->id) }}">
			  					{{$c->nombre }}
			  				</a>
			  			</li>
			  		@endif
				@endforeach
                <li><a href="{{ URL::to('publicaciones/categorias/'.$otros->id) }}">{{ $otros->nombre }}</a></li>
				<li><strong>Servicios</strong></li>
			   	@foreach($servicios as $s)
					@if($s->id != $otros2->id)
						<li><a href="{{ URL::to('publicaciones/categorias/'.$s->id) }}">{{$s->nombre }}</a></li>
					@endif
				@endforeach
                <li><a href="{{ URL::to('publicaciones/categorias/'.$otros2->id) }}">{{ $otros2->nombre }}</a></li>
			  </ul>
			</div>
			<div class="dropdown">
			  <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-expanded="true">
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
		<div class="pub row">
			<div class="col-xs-12 pubs">
				<img src="{{ asset('images/publicidad/cate.gif') }}">
			</div>
			<div>
				<div class="col-xs-12 pubs col-sm-6 pubLeft" >
					<img src="{{ asset('images/publicidad/pub1.gif') }}">
				</div>
				<div class="col-xs-12 pubs col-sm-6 pubRight" >
					<img src="{{ asset('images/publicidad/pub2.gif') }}">
				</div>
			</div>
		</div>
	</div>
	<div class=""> 
			<div class="row">
				<div class="col-xs-12 publication">
					<h3>Anuncios LÍDER de Empresas y Particulares con sitio web.</h3>
						<div class="owl-carousel1">
							@foreach($lider as $pubLider)
							<div class="item contCatIndex">
								<div class="col-xs-12 image">
									<a href="{{ URL::to('publicacion/lider/'.$pubLider->id) }}">
										<img src="{{ asset('images/pubImages/'.$pubLider->img_1) }}" class="imgPubCarousel">
									</a>
								</div>
								<div class="col-xs-12 texto-desc">
									<h3>{{ $pubLider->title }}</h3>
								</div>
								<div class="col-xs-12 btn-group no-padding">
									<a href="{{ URL::to('publicacion/lider/'.$pubLider->id) }}" class="btn btn-warning">Ver publicación</a>
								</div>
							</div>
							@endforeach
							@if(count($lider)<1)
							<div class="item">
									<img src="{{ asset('images/anuncios-01.png') }}" class="disponible">
							</div>
							<div class="item">
									<img src="{{ asset('images/anuncios-02.png') }}" class="disponible">
							</div>
							<div class="item">
									<img src="{{ asset('images/anuncios-03.png') }}" class="disponible">
							</div>
							<div class="item">
									<img src="{{ asset('images/anuncios-04.png') }}" class="disponible">
							</div>
							@elseif(count($lider)>=1 && count($lider)<2)
							<div class="item">
									<img src="{{ asset('images/anuncios-02.png') }}" class="disponible">
							</div>
							<div class="item">
									<img src="{{ asset('images/anuncios-03.png') }}" class="disponible">
							</div>
							<div class="item">
									<img src="{{ asset('images/anuncios-04.png') }}" class="disponible">
							</div>
							@elseif(count($lider)>=2 && count($lider)<3)
							<div class="item">
									<img src="{{ asset('images/anuncios-03.png') }}" class="disponible">
							</div>
							<div class="item">
									<img src="{{ asset('images/anuncios-04.png') }}" class="disponible">
							</div>
							@else
							
							<div class="item">
									<img src="{{ asset('images/anuncios-04.png') }}" class="disponible">
							</div>
							@endif
						</div>
				</div>
				<div class="col-xs-12 publication">
					<h3>Anuncios LÍDER de Empresas y Particulares sin sitio web</h3>
						<div class="owl-carousel2">
							<?php $x = 0;?>
							@foreach($habitual as $pubHabitual)
								@if($x%2 == 0)
								<div class="item">
								@endif
									<div class="col-xs-12 contCatIndex separator no-padding">
								 		<div class="col-xs-12 image">
											<a href="{{ URL::to('publicacion/habitual/'.$pubHabitual->id) }}">
												<img src="{{ asset('images/pubImages/'.$pubHabitual->img_1) }}" class="imgPubCarousel">
											</a>
								 		</div>
								 		<div class="col-xs-12 texto-desc">
											<h3>{{ $pubHabitual->title }}</h3>
								 			{{ ucfirst(strtolower(strip_tags($pubHabitual->descripcion))) }}
								 		</div>
								 		<div class="col-xs-12 btn-group no-padding">
								 			<a href="{{ URL::to('publicacion/habitual/'.$pubHabitual->id) }}" class="btn btn-warning">Ver publicación</a>
								 		</div>
									</div>
								@if(($x+1)%2 == 0)
								</div>
								@endif
								<?php $x++;?>
							
							@endforeach
							@if(count($habitual)<8)
							<div class="item">
									<div class="contCatIndex"><img src="{{ asset('images/anuncios-01.png') }}" class="disponible"></div>
									<div class="contCatIndex"><img src="{{ asset('images/anuncios-02.png') }}" class="disponible"></div>
							</div>
							<div class="item">
									<div class="contCatIndex"><img src="{{ asset('images/anuncios-03.png') }}" class="disponible"></div>

									<div class="contCatIndex"><img src="{{ asset('images/anuncios-04.png') }}" class="disponible"></div>
							</div>
							<div class="item">
									<div class="contCatIndex"><img src="{{ asset('images/anuncios-01.png') }}" class="disponible"></div>
									<div class="contCatIndex"><img src="{{ asset('images/anuncios-02.png') }}" class="disponible"></div>
							</div>
							<div class="item">
									<div class="contCatIndex"><img src="{{ asset('images/anuncios-03.png') }}" class="disponible"></div>

									<div class="contCatIndex"><img src="{{ asset('images/anuncios-04.png') }}" class="disponible"></div>
							</div>
							@elseif(count($habitual)%2 != 0)
								<div class="contCatIndex"><img src="{{ asset('images/anuncios-04.png') }}" class="disponible"></div>
							</div>
							@endif
						</div>
							
				</div>
				<div class="col-xs-12 publication">
					<h3>Últimos anuncios CASUALES</h3>
						<div class="owl-carousel3">
							@foreach($casual as $pubCasual)
							<div class="item">
								<div class="col-xs-12 contCatIndex separator no-padding">
							 		<div class="col-xs-12 image">
										<a href="{{ URL::to('publicacion/habitual/'.$pubCasual->id) }}">
											<img src="{{ asset('images/pubImages/'.$pubCasual->img_1) }}" class="imgPubCarousel">
										</a>
							 		</div>
							 		<div class="col-xs-12 texto-desc">
										<h3>{{ $pubCasual->title }}</h3>
							 			
							 			{{ ucfirst(strtolower(strip_tags($pubCasual->descripcion))) }}
							 		</div>
							 		<div class="col-xs-12 btn-group no-padding">
							 			<a href="{{ URL::to('publicacion/habitual/'.$pubCasual->id) }}" class="btn btn-warning">Ver publicación</a>
							 		</div>
								</div>
							</div>
							@endforeach
							@if(count($casual)<1)
							<div class="item">
									<img src="{{ asset('images/anuncios-01.png') }}" class="disponible">
							</div>
							<div class="item">
									<img src="{{ asset('images/anuncios-02.png') }}" class="disponible">
							</div>
							<div class="item">
									<img src="{{ asset('images/anuncios-03.png') }}" class="disponible">
							</div>
							<div class="item">
									<img src="{{ asset('images/anuncios-04.png') }}" class="disponible">
							</div>
							@elseif(count($casual)>=1 && count($casual)<2)
							<div class="item">
									<img src="{{ asset('images/anuncios-02.png') }}" class="disponible">
							</div>
							<div class="item">
									<img src="{{ asset('images/anuncios-03.png') }}" class="disponible">
							</div>
							<div class="item">
									<img src="{{ asset('images/anuncios-04.png') }}" class="disponible">
							</div>
							@elseif(count($casual)>=2 && count($casual)<3)
							<div class="item">
									<img src="{{ asset('images/anuncios-03.png') }}" class="disponible">
							</div>
							<div class="item">
									<img src="{{ asset('images/anuncios-04.png') }}" class="disponible">
							</div>
							@else
							
							<div class="item">
									<img src="{{ asset('images/anuncios-04.png') }}" class="disponible">
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
		        	items: 2
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
		        	items: 2
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
		        	items: 2
		        },
		        1200:{
		            items:4
		        }
		    }
		})
	});
   </script>
@stop