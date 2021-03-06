@extends('main')

@section('content')

<div class="container contenedorUnico">
	<div class="row">
		<div class="col-xs-12">

			<legend style="margin-bottom:1em;margin-top:2em;text-align:center;">Publicaciones LÍDER de esta categoría</legend>
                        
                              <img src="{{ asset('images/publicidad/cate.gif') }}" style="width:100%;margin-bottom:2em;">
			<div class="owl-carousel1 owl-carousel-busq">
				@foreach($lider as $pubLider)
				<div class="item contCatIndex contPubLiderBusq">
					<a href="{{ URL::to('publicacion/lider/'.$pubLider->id) }}">
						<img src="{{ asset('images/pubImages/'.$pubLider->img_1) }}" class="imgPubCarousel">
					</a>
					<div class="dataIndex textoPromedio">
						<div class="col-xs-12" style="padding-top:0px;margin-top:0px;">{{ $pubLider->titulo }}</div>
						<div class="col-xs-12"><a href="{{ URL::to('publicacion/lider/'.$pubLider->id) }}" style="color:white;"><i class="fa fa-hand-o-right"></i> Ver publicación</a>
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
						<img src="{{ asset('images/anuncios-05.png') }}">
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
			<div class="col-xs-12 col-md-2 ">
				<div class="contAnaranjado filter-container">
					<form method="GET" action="{{ URL::to('inicio/buscar') }}" class="form-filter">
					</form>
						<div class="col-xs-12">
							<h3 class="text-center">Filtros</h3>
							<label class="textoPromedio">Departamento</label>
							<select name="filter" class="form-control filterDep depFilterNotWorking" autocomplete="off">
								<option value="-1">Busqueda general</option>
								@foreach($departamento as $dep)
									@if(!empty($filter) && $filter->id == $dep->id)
										<option value="{{ $dep->id }}" selected>{{ ucfirst(strtolower($dep->nombre)) }}</option>
									@else
										<option value="{{ $dep->id }}">{{ ucfirst(strtolower($dep->nombre)) }}</option>
									@endif
								@endforeach
							</select>
							<input type="hidden" name="cat" class="to-filter cat" value="{{ $busq }}">
						</div>
						<div class="col-xs-12">
							<label class="textoPromedio">Relevancia.</label>
							<select name="rel" class="form-control filterRel depFilterNotWorking" autocomplete="off">
								<option value="-1">Busqueda general</option>
								<option value="rep" @if(isset($rel) && $rel == 'rep') selected @endif>Mayor reputación.</option>
								<option value="fin" @if(isset($rel) && $rel == 'fin') selected @endif>Primeros en finalizar</option>
								<option value="ini" @if(isset($rel) && $rel == 'ini') selected @endif>Mas recientes.</option>
							</select>
						</div>
						<div class="col-xs-12">
							<label class="textoPromedio">Condición.</label>
							<select name="cond" class="form-control filterCond depFilterNotWorking" autocomplete="off">
								<option value="-1">Busqueda general</option>
								<option value="nuevo" @if(isset($cond) && $cond == 'nuevo') selected @endif>Nuevo.</option>
								<option value="usado" @if(isset($cond) && $cond == 'usado') selected @endif>Usado</option>
							</select>
						</div>
						<div class="col-xs-12">
							<label class="textoPromedio">Clase de Negocio.</label>
							<select name="buss" class="form-control filterBuss depFilterNotWorking" autocomplete="off">
								<option value="-1">Busqueda general</option>
								<option value="fiscal" @if(isset($buss) && $buss == 'fiscal') selected @endif>Fiscal.</option>
								<option value="virtual" @if(isset($buss) && $buss == 'virtual') selected @endif>Virtual</option>
								<option value="independiente" @if(isset($buss) && $buss == 'independiente') selected @endif>Independiente</option>
								<option value="otro" @if(isset($buss) && $buss == 'otro') selected @endif>Otro</option>

							</select>
						</div>
						<div class="col-xs-12"><label class="textoPromedio">Precio</label></div>
						<div class="col-xs-12 contInputFilter">
							@if(isset($minmax))
								<input type="text" class="form-control min" name="min" placeholder="Min:" value="{{ $minmax[0] }}">
							@else
								<input type="text" class="form-control min" name="min" placeholder="Min:">
							@endif
						</div>
						<div class="col-xs-12 contInputFilter formulario">
							@if(isset($minmax))
								<input type="text" class="form-control max" name="max" placeholder="Max:" value="{{ $minmax[1] }}">
							@else
								<input type="text" class="form-control max" name="max" placeholder="Max:">
							@endif
						</div>
						<div class="col-xs-12"><label class="textoPromedio">Moneda</label></div>
						<div class="col-xs-12">
							<select name="currency" class="form-control currency">
								<option value="Bs" @if(isset($currency) && $currency == 'Bs') selected @endif>Bs.</option>
								<option value="Usd" @if(isset($currency) && $currency == 'Usd') selected @endif>Usd.</option>
							</select>
						</div>
						<div class="col-xs-12 formulario">
							<button class="btn btn-default btn-xs btn-flat btn-filtralo" title="Filtrar">Filtrar <strong><i class="fa fa-angle-right"></i></strong></button>
						</div>
						<div class="clearfix"></div>

				</div>
			</div>
			<div class="col-xs-12 col-md-10 contAnaranjado contAnaranjadoBusq" style="margin-bottom:8em;">
			@if(!empty($publicaciones) && count($publicaciones)>0)
				@foreach($publicaciones as $pub)
					<div class="contCat">
						<div class="col-xs-12 col-md-4">
							<a href="{{ URL::to('publicacion/habitual/'.$pub->id) }}">
								<img src="{{ asset('images/pubImages/'.$pub->img_1) }}" style="width:100%;">
							</a>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-4 text-center-xs">
							<h3>{{ $pub->titulo }}</h3>
								@if(strlen($pub->descripcion) <= 20)
								<p class="textoPromedio">{{ strip_tags($pub->descripcion) }}</p>
								@else
								<p class="textoPromedio">{{ substr(strip_tags($pub->descripcion),0,100) }}...</p>
								@endif
								<label class="textoPromedio">{{ $pub->dep }}</label>
						</div>
						<div class="col-sm-6 visible-sm text-center-xs">
							<label class="textoPromedio" style="display:inline-block;">
								Precio: 
							</label>
							<h3 class="precioPub" style="display:inline-block;">{{ $pub->precio.' '.ucfirst(strtolower($pub->moneda)) }}</h3>
							<br>
							<label class="textoPromedio">
								Finaliza:
								@if($pub->fechFinNormal != "0000-00-00")
									{{ date('d-m-Y',strtotime($pub->fechFinNormal)) }}
								@else
									{{ date('d-m-Y',strtotime($pub->fechFin)) }}
								@endif 
							</label>
						</div>
						<div class="col-xs-12 col-md-4">
							<div class="col-xs-12 text-center-xs hidden-sm no-padding">
                                <label class="textoPromedio" style="display:inline-block;">
									Precio: 
								</label>
								<h3 class="precioPub" style="display:inline-block;">{{ $pub->precio.' '.ucfirst(strtolower($pub->moneda)) }}</h3>
								<br>
								<label class="textoPromedio">
									Finaliza:
									@if($pub->fechFinNormal != "0000-00-00")
										{{ date('d-m-Y',strtotime($pub->fechFinNormal)) }}
									@else
										{{ date('d-m-Y',strtotime($pub->fechFin)) }}
									@endif 
								</label>
							</div>
							<div class="col-xs-12 no-padding">
								<a href="{{ URL::to('publicacion/habitual/'.$pub->id) }}" class="btn btn-primary btn-full">
									<i class="fa fa-hand-o-right">
									</i> Ver publicación
								</a>
							</div>
						</div>
					</div>	
					<div class="clearfix"></div>	
					<hr class="borderBlue">
			
				@endforeach
				<div class="blog-pagination">
					<nav role="navigation">
			          <?php  $presenter = new Illuminate\Pagination\BootstrapPresenter($publicaciones); ?>
			          @if ($publicaciones->getLastPage() > 1)
			          <ul class="pagination cd-pagination no-space">
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
					echo '<li class="disabled"><a href="#!" class="textoMedio">Primera</a></li>';
						//en otro caso obtenemos la url y mostramos en forma de link
					}
					else
					{
					$url = $publicaciones->getUrl(1);
					echo '<li><a class="textoMedio" href="'.$url.'&cat='.$busq.$paginatorFilter.'">&lt;&lt; Primera</a></li>';

					}
					//Para ir a la anterior
	            	if (($currentPage-1) < $start) {
		            	echo '<li class="disable"><a href="#!" >&lt; Atras</a></li>' ;	
		            }else
		            {
	              		echo '<li><a href="'.$publicaciones->getUrl($currentPage-1).'&cat='.$busq.$paginatorFilter.'">&lt; Atras</a></li>';
		            }
		           
					//Rango de enlaces desde el principio al final, 3 delante y 3 detrás
					for($i = $start; $i<=$end;$i++)
					{
						if ($currentPage == $i) {
							echo '<li class="disabled"><a href="#!" >'.$i.'</a></li>';
						}else
						{
							echo '<li><a href="'.$publicaciones->getUrl($i).'&cat='.$busq.$paginatorFilter.'">'.$i.'</a></li>';
						}
					}
			           
					//Para ir a la siguiente
					if (($currentPage+1) > $end) {
						echo '<li class="disable"><a href="#!" >Adelante &gt;</a></li>' ;
					}else
					{
						echo '<li><a href="'.$publicaciones->getUrl($currentPage+1).'&cat='.$busq.$paginatorFilter.'">Adelante &gt;</a></li>';
					}

					////Si es la última página mostramos desactivado
					if ($currentPage >= $lastPage)
					{
					echo '<li class="disabled"><a href="#!" class="textoMedio">Última</a></li>';
						//en otro caso obtenemos la url y mostramos en forma de link
					}
					else
					{
					$url = $publicaciones->getUrl($lastPage);
					echo '<li><a class="textoMedio" href="'.$url.'&cat='.$busq.$paginatorFilter.'">Última &gt;&gt;</a></li>';
					  }
					?>
			            @endif
			          </ul>
			        </nav> <!-- cd-pagination-wrapper -->
				</div>

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