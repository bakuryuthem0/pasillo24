@extends('main')

@section('content')

<div class="container contenedorUnico">
	<div class="row">
		<div class="col-xs-12">
			<legend style="margin-bottom:2em;margin-top:2em;text-align:center;">Publicaciones LÍDER encontradas para: "{{ $busq }}"</legend>
			<div class="owl-carousel1">
				@foreach($lider as $pubLider)
				<div class="item contCatCat">
					<a href="{{ URL::to('publicacion/lider/'.base64_encode($pubLider->id)) }}">
						<img src="{{ asset('images/pubImages/'.$pubLider->img_1) }}" class="imgPubCarousel">
						<div class="dataIndex textoPromedio">
							<div class="col-xs-6">{{ $pubLider->titulo }}</div>
							@if($pubLider->precio)
							<div class="col-xs-6" >
							 <label>Precio: </label>{{ $pubLider->precio.' '.ucfirst(strtolower($pubLider->moneda)).'.' }}
							</div>
							@endif
							<div class="col-xs-12">
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
		</div>
		<div class="col-xs-12">
			<legend style="text-align:center;margin:2em 0px;">Listado de publicaciones encontradas para: "{{ $busq }}"</legend>
		</div>
		<div class="depFilterCont">
			<label class="textoPromedio">Filtro por departamento</label>
			<form class="formDepFilter" method="GET" action="{{ URL::to('inicio/buscar') }}">
				<select name="filter" class="form-control depFilter">
					<option value="">Busqueda general</option>
					@foreach($departamento as $dep)
						@if(!empty($filter) && $filter->id == $dep->id)
							<option value="{{ $dep->id }}" selected>{{ ucfirst(strtolower($dep->nombre)) }}</option>
						@else
							<option value="{{ $dep->id }}">{{ ucfirst(strtolower($dep->nombre)) }}</option>
						@endif
					@endforeach
				</select>
				@if(isset($minmax))
					<input type="hidden" name="min" value="{{ $minmax[0] }}">
					<input type="hidden" name="max" value="{{ $minmax[1] }}">
				@else
					<input type="hidden" name="min">
					<input type="hidden" name="max">
				@endif
				<input type="hidden" name="currency" value="{{ $currency }}">
				<input type="hidden" name="busq" value="{{ $busq }}">
			</form>
		</div>
		<div class="col-xs-12 col-md-2 ">
			<div class="contAnaranjado filter-container">
				<form method="GET" action="{{ URL::to('inicio/buscar') }}">
					<div class="col-xs-12"><label class="textoPromedio">Precio</label></div>
					<div class="col-xs-5 contInputFilter" style="padding-right: 0;">
						@if(isset($minmax))
							<input type="text" class="form-control filter-price" name="min" placeholder="Min:" value="{{ $minmax[0] }}">
						@else
							<input type="text" class="form-control filter-price" name="min" placeholder="Min:">
						@endif
					</div>
					<div class="col-xs-1" style="padding-left:0;padding-right:0;text-align:center;"><p class="textoPromedio">-</p></div>
					<div class="col-xs-4 contInputFilter" style="padding-left:0;padding-right:0;margin-top:0;">
						@if(isset($minmax))
							<input type="text" class="form-control filter-price" name="max" placeholder="Max:" value="{{ $minmax[1] }}">
						@else
							<input type="text" class="form-control filter-price" name="max" placeholder="Max:">
						@endif
					</div>
					<div class="col-xs-1" style="padding-left:0;padding-right:0;text-align:center;">
						<button class="btn btn-default btn-xs btn-flat" title="Filtrar"><i class="fa fa-angle-right"></i></button>
					</div>
					<div class="clearfix"></div>
					<input type="hidden" name="busq" value="{{ $busq }}">
					@if(isset($filter->id))
						<input type="hidden" name="filter" value="{{ $filter->id }}">
					@endif
					<div class="col-xs-12"><label class="textoPromedio">Moneda</label></div>
					<div class="col-xs-12">
						<select name="currency" class="form-control">
							<option value="Bs">Bs.</option>
							<option value="Usd">USD.</option>
						</select>
					</div>
				</form>
			</div>
		</div>
		<div class="col-xs-12 col-md-10 contAnaranjado contAnaranjadoBusq">
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
								@if(strlen($pub->descripcion) <= 20)
								<p class="textoPromedio">{{ strip_tags($pub->descripcion) }}</p>
								@else
								<p class="textoPromedio">{{ substr(strip_tags($pub->descripcion),0,100) }}...</p>
								@endif
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
		              $beforeAndAfter = 3;
		           
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
		           		if(isset($filterPrice)){
		                	echo '<li><a class="textoMedio" href="'.$url.'&busq='.$busq.$filterPrice.'">&lt;&lt; Primera</a></li>';
		           		}
		           		else{
		                	echo '<li><a class="textoMedio" href="'.$url.'&busq='.$busq.'">&lt;&lt; Primera</a></li>';
		           			
		           		}

		              }
		           
		              //Para ir a la anterior
		              if(!empty($filter)){
			            if (($currentPage-1) < $start) {
			            	echo '<li class="disable"><span>&lt; Atras</span></li>' ;	
			            }else
			            {
		           			if(isset($filterPrice)){
			              		echo '<li><a href="'.$publicaciones->getUrl($currentPage-1).'&busq='.$busq.'&filter='.$filter->id.$filterPrice.'">&lt; Atras</a></li>';

		           			}else
		           			{
			              		echo '<li><a href="'.$publicaciones->getUrl($currentPage-1).'&busq='.$busq.'&filter='.$filter->id.'">&lt; Atras</a></li>';
		           			}
			            }
		              }else
		              {
		              	if (($currentPage-1) < $start) {
			              	echo '<li class="disable"><span>&lt; Atras</span></li>' ;	
			              }else
			              {
			              	if(isset($filterPrice))
			              	{
			              		echo '<li><a href="'.$publicaciones->getUrl($currentPage-1).'&busq='.$busq.$filterPrice.'">&lt; Atras</a></li>' ;
			              	}else
			              	{
			              		echo '<li><a href="'.$publicaciones->getUrl($currentPage-1).'&busq='.$busq.'">&lt; Atras</a></li>' ;
			              	}
			              }
		              }
		           
		              //Rango de enlaces desde el principio al final, 3 delante y 3 detrás
		              for($i = $start; $i<=$end;$i++)
		              {
		              	if ($currentPage == $i) {
		              		echo '<li class="disabled"><span>'.$i.'</span></li>';
		              	}else
		              	{
		              		if(!empty($filter)){
			              		if(isset($filterPrice))
			              		{
		              				echo '<li><a href="'.$publicaciones->getUrl($i).'&busq='.$busq.'&filter='.$filter->id.$filterPrice.'">'.$i.'</a></li>';
			              		}else
			              		{
		              				echo '<li><a href="'.$publicaciones->getUrl($i).'&busq='.$busq.'&filter='.$filter->id.'">'.$i.'</a></li>';
			              		}


		              		}else
		              		{
			              		if(isset($filterPrice))
			              		{
		              				echo '<li><a href="'.$publicaciones->getUrl($i).'&busq='.$busq.$filterPrice.'">'.$i.'</a></li>';
			              		}else
			              		{
		              				echo '<li><a href="'.$publicaciones->getUrl($i).'&busq='.$busq.'">'.$i.'</a></li>';
			              		}
		              		}
		              	}
		              }
		           
		              //Para ir a la siguiente
		              if (!empty($filter)) {
			              if (($currentPage+1) > $end) {
			              	echo '<li class="disable"><span>Adelante &gt;</span></li>' ;
			              }else
			              {
			              		if(isset($filterPrice)){
			              			echo '<li><a href="'.$publicaciones->getUrl($currentPage+1).'&busq='.$busq.'&filter='.$filter->id.$filterPrice.'">Adelante &gt;</a></li>';

			              		}else
			              		{
			              			echo '<li><a href="'.$publicaciones->getUrl($currentPage+1).'&busq='.$busq.'&filter='.$filter->id.'">Adelante &gt;</a></li>';
			              		}

			              }
		              }else
		              {
		              	if (($currentPage+1) > $end) {
			              	echo '<li class="disable"><span>Adelante &gt;</span></li>' ;
			              }else
			              {
			              		if(isset($filterPrice))
			              		{
			              			echo '<li><a href="'.$publicaciones->getUrl($currentPage+1).'&busq='.$busq.$filterPrice.'">Adelante &gt;</a></li>' ;
			              		}else
			              		{
			              			echo '<li><a href="'.$publicaciones->getUrl($currentPage+1).'&busq='.$busq.'">Adelante &gt;</a></li>' ;
			              		}

			              }
		              }
		           
		              ////Si es la última página mostramos desactivado
		              if ($currentPage >= $lastPage)
		              {
		                echo '<li class="disabled"><span class="textoMedio">Última</span></li>';
		              }
		              //en otro caso obtenemos la url y mostramos en forma de link
		              else
		              {
		                $url = $publicaciones->getUrl($lastPage);
	              		if(isset($filterPrice))
	              		{
		                	echo '<li><a class="textoMedio" href="'.$url.'&busq='.$busq.$filterPrice.'">Última &gt;&gt;</a></li>';
	              		}else
	              		{
		                	echo '<li><a class="textoMedio" href="'.$url.'&busq='.$busq.'">Última &gt;&gt;</a></li>';
	              		}
	              	}
		              ?>
		            @endif
		          </ul>
		        </nav> <!-- cd-pagination-wrapper -->
			@else
				<p class="textoPromedio bg-primary" style="padding:1em;border-radius:4px;text-align:center;margin-top:1em;.">No se encontraron resultados para: "{{ $busq }}".</p>
			@endif
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
		$('.depFilter').on('change', function(event) {
			event.preventDefault();
			$('.formDepFilter').submit();
		});
	});
</script>
@stop