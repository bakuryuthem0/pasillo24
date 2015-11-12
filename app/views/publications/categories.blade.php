@extends('main')

@section('content')

<div class="container contenedorUnico">
	<div class="row">
		<div class="col-xs-12">

			<legend style="margin-bottom:1em;margin-top:2em;text-align:center;">Publicaciones LÍDER de esta categoría</legend>
                                                @if(isset($publi))
                                                        @if($publi->activo == 0)
								<img src="{{ asset('images/pubgrande.png') }}" style="width:100%;margin-bottom:2em;">
							@else
								<img src="{{ asset('images/publicidad/'.$publi->image) }}" style="width:100%;margin-bottom:2em;">
							@endif
                                                @else
                                                      <img src="{{ asset('images/pubgrande.png') }}" style="width:100%;margin-bottom:2em;">
                                                @endif
			<div class="owl-carousel1">
				@foreach($lider as $pubLider)
				<div class="item contCatCat">
					<a href="{{ URL::to('publicacion/lider/'.base64_encode($pubLider->id)) }}">
						<img src="{{ asset('images/pubImages/'.$pubLider->img_1) }}" class="imgPubCarousel">
<div class="dataIndex textoPromedio">
							<div class="col-xs-6">{{ $pubLider->titulo }}</div>
							<div class="col-xs-6">
								<a href="{{ URL::to('publicacion/lider/'.base64_encode($pubLider->id)) }}" style="color:white;">
									<i class="fa fa-hand-o-right"></i> Ver publicación
								</a>
							</div>
						</div>
					</a>
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

			<div class="clearfix"></div>
			<legend style="text-align:center;margin:2em 0px;">Listado de publicaciones para @if(isset($tipoBusq))
				este departamento 
				@else 
				esta categoría
				@endif
			</legend>
			<div class="depFilterCont">
				<label class="textoPromedio">Filtro por departamento</label>
				<form class="formDepFilter" method="GET" action="{{ URL::to('inicio/buscar') }}">
					<select name="filter" class="form-control depFilter" autocomplete="off">
						<option value="">Busqueda general</option>
						@foreach($departamento as $dep)
							@if(!empty($filter) && $filter->id == $dep->id)
								<option value="{{ $dep->id }}" selected>{{ ucfirst(strtolower($dep->nombre)) }}</option>
							@else
								<option value="{{ $dep->id }}">{{ ucfirst(strtolower($dep->nombre)) }}</option>
							@endif
						@endforeach
					</select>
					<input type="hidden" name="busq" value="{{ $busq }}">
				</form>
			</div>
			<div class="contAnaranjado contAnaranjadoBusq" style="margin-bottom:8em;">
			@if(!empty($publicaciones) && count($publicaciones)>0)
			@foreach($publicaciones as $pub)
			<div class="contCat">
					<a href="{{ URL::to('publicacion/habitual/'.base64_encode($pub->id)) }}">
						<div class="col-xs-12 col-md-4 contCatPub">
								<img src="{{ asset('images/pubImages/'.$pub->img_1) }}" style="width:100%;">
						</div>
					</a>
						<div class="col-xs-12 col-md-4 contCatPub">
							<h3>{{ $pub->titulo }}</h3>
								<label class="textoPromedio">{{ $pub->dep }}</label>
						</div>
						<div class="col-xs-12 col-md-4 contCatPub">
                                                                <label class="textoPromedio" style="display:inline-block;">
									Precio: 
								</label>
								<h3 class="precioPub" style="display:inline-block;">{{ $pub->precio.' '.ucfirst(strtolower($pub->moneda)) }}</h3>
						
							<br>
							<a href="{{ URL::to('publicacion/habitual/'.base64_encode($pub->id)) }}" class="btn btn-primary btnBusq">
								<i class="fa fa-hand-o-right">
								</i> Ver publicación
							</a>
						</div>
				</div>		
				<hr class="borderBlue">
		
			@endforeach
			
<nav role="navigation">
		          <?php  $presenter = new Illuminate\Pagination\BootstrapPresenter($publicaciones); ?>
		          @if ($publicaciones->getLastPage() > 1)
		          <ul class="cd-pagination no-space">
		            <?php
		              $beforeAndAfter = 2;
		           
		              //Página actual
		              $currentPage = $publicaciones->getCurrentPage();
		           
		              //Última página
		              $lastPage = $publicaciones->getLastPage();
		           
		              //Comprobamos si las páginas anteriores y siguientes de la actual existen
		              $start = $currentPage - $beforeAndAfter;
		           
		                  //Comprueba si la primera página en la paginación está por debajo de 1
		                  //para saber como colocar los enlaces
		              if($start < 1)
		              {
		                $pos = $start - 1;
		                $start = $currentPage - ($beforeAndAfter + $pos);
		              }
		           
		              //Último enlace a mostrar
		              $end = $currentPage + $beforeAndAfter;
		           
		              if($end > $lastPage)
		              {
		                $pos = $end - $lastPage;
		                $end = $end - $pos;
		              }
		           
		              //Si es la primera página mostramos el enlace desactivado
		              if ($currentPage <= 1)
		              {
		                echo '<li class="disabled"><span class="textoMedio">Primera</span></li>';
		              }
		              //en otro caso obtenemos la url y mostramos en forma de link
		              else
		              {
		                $url = $publicaciones->getUrl(1);
		           
		                echo '<li><a class="textoMedio" href="'.$url.'">&lt;&lt; Primera</a></li>';
		              }
		           
		              //Para ir a la anterior
		              echo $presenter->getPrevious('&lt; Atras');
		           
		              //Rango de enlaces desde el principio al final, 3 delante y 3 detrás
		              echo $presenter->getPageRange($start, $end);
		           
		              //Para ir a la siguiente
		              echo $presenter->getNext('Adelante &gt;');
		           
		              ////Si es la última página mostramos desactivado
		              if ($currentPage >= $lastPage)
		              {
		                echo '<li class="disabled"><span class="textoMedio">Última</span></li>';
		              }
		              //en otro caso obtenemos la url y mostramos en forma de link
		              else
		              {
		                $url = $publicaciones->getUrl($lastPage);
		           
		                echo '<li><a class="textoMedio" href="'.$url.'">Última &gt;&gt;</a></li>';
		              }
		              ?>
		            @endif
		          </ul>
		        </nav> <!-- cd-pagination-wrapper -->
			@else
				<p class="textoPromedio bg-primary" style="padding:1em;border-radius:4px;text-align:center;margin-top:1em;.">No existen publicaciones para 
					@if(isset($tipoBusq))
					este departamento 
					@else 
					esta categoría.
					@endif</p>
			@endif
			
			</div>
		</div>
	</div>
</div>
@stop

@section('postscript')

<script type="text/javascript">
	jQuery(document).ready(function($) {
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
		        800:{
		            items:2
		        },
		        1200:{
		            items:3
		        }
		    }
		})
	});
</script>
@stop