@extends('main')

@section('content')

<div class="container contenedorUnico">
	<div class="row">
		<div class="col-xs-12">
				<div class="formulario"><legend>Mis publicaciones favoritas.</legend></div>

				<div class="table-responsive">
					<table class="table table-striped table-hover vertical-middle textoPromedio">
						<thead>
							<tr>
								<th class="text-center">Imagen</th>
								<th class="text-center">Título</th>
								<th class="text-center">Precio</th>
								<th class="text-center">Departamento</th>
								<th class="text-center">Descripción</th>
								<th class="text-center">Ir a la publicación</th>
								<th class="text-center">Remover de favoritos</th>
							</tr>
						</thead>
						<tbody>
						@foreach($fav as $f)
							<tr>
								<td class="text-center">
									<img src="{{ asset('images/pubImages/'.$f->img_1) }}" class="imgFav">
								</td>
								<td class="text-center">{{ $f->titulo }}</td>
								<td class="text-center">
									@if($f->precio == 0)
										Sin especificar
									@else
										{{ $f->precio.' '.$f->moneda }}
									@endif
								</td>
								<td>
									@if(!isset($f->dep_desc))
										Sin especificar
									@else
										{{ $f->dep_desc }}
									@endif
								</td>
								<td>
									@if(empty($f->descripcion))
										Sin descripción
									@else
										@if(strlen($f->descripcion) <= 20)
										<p class="">{{ strip_tags($f->descripcion) }}</p>
										@else
										<p class="">{{ substr(strip_tags($f->descripcion),0,100) }}...</p>
										@endif
									@endif
								</td>
								
								<td class="text-center">
									<a class="btn btn-primary btn-xs " href="{{ URL::to('publicacion/'.strtolower($f->tipo).'/'.$f->id) }}">ir</a>
								</td>
								<td class="text-center">
									<button class="btn btn-danger  btn-xs btn-fav-remove" value="{{ URL::to('usuario/publicaciones/remover-favorito/'.$f->fav_id) }}" data-toggle="modal" data-target="#removeFav">Remover</button>
								</td>
							</tr>
						@endforeach
						</tbody>
					</table>
				</div>

				<div class="blog-pagination">

					<nav role="navigation">
		          <?php  $presenter = new Illuminate\Pagination\BootstrapPresenter($fav); ?>
		          @if ($fav->getLastPage() > 1)
		         <ul class="pagination cd-pagination no-space">
		            <?php
		              $beforeAndAfter = 3;
		           
		              //Página actual
		              $currentPage = $fav->getCurrentPage();
		           	
		              //Última página
		              $lastPage = $fav->getLastPage();
		           
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
		              }
		              //en otro caso obtenemos la url y mostramos en forma de link
		              else
		              {
		                $url = $fav->getUrl(1);
		           		if(isset($filterPrice)){
		                	echo '<li><a class="textoMedio" href="'.$url.'">&lt;&lt; Primera</a></li>';
		           		}
		           		else{
		                	echo '<li><a class="textoMedio" href="'.$url.'">&lt;&lt; Primera</a></li>';
		           			
		           		}

		              }
		           
		              //Para ir a la anterior
		              if(!empty($filter)){
			            if (($currentPage-1) < $start) {
			            	echo '<li class="disable"><a href="#!">&lt; Atras</a></li>' ;	
			            }else
			            {
		           			if(isset($filterPrice)){
			              		echo '<li><a href="'.$fav->getUrl($currentPage-1).'&filter='.$filter->id.$filterPrice.'">&lt; Atras</a></li>';

		           			}else
		           			{
			              		echo '<li><a href="'.$fav->getUrl($currentPage-1).'&filter='.$filter->id.'">&lt; Atras</a></li>';
		           			}
			            }
		              }else
		              {
		              	if (($currentPage-1) < $start) {
			              	echo '<li class="disable"><a href="#!">&lt; Atras</a></li>' ;	
			              }else
			              {
			              	if(isset($filterPrice))
			              	{
			              		echo '<li><a href="'.$fav->getUrl($currentPage-1).'">&lt; Atras</a></li>' ;
			              	}else
			              	{
			              		echo '<li><a href="'.$fav->getUrl($currentPage-1).'">&lt; Atras</a></li>' ;
			              	}
			              }
		              }
		           
		              //Rango de enlaces desde el principio al final, 3 delante y 3 detrás
		              for($i = $start; $i<=$end;$i++)
		              {
		              	if ($currentPage == $i) {
		              		echo '<li class="disabled"><a href="#!">'.$i.'</a></li>';
		              	}else
		              	{
		              		if(!empty($filter)){
			              		if(isset($filterPrice))
			              		{
		              				echo '<li><a href="'.$fav->getUrl($i).'&filter='.$filter->id.$filterPrice.'">'.$i.'</a></li>';
			              		}else
			              		{
		              				echo '<li><a href="'.$fav->getUrl($i).'&filter='.$filter->id.'">'.$i.'</a></li>';
			              		}


		              		}else
		              		{
			              		if(isset($filterPrice))
			              		{
		              				echo '<li><a href="'.$fav->getUrl($i).'">'.$i.'</a></li>';
			              		}else
			              		{
		              				echo '<li><a href="'.$fav->getUrl($i).'">'.$i.'</a></li>';
			              		}
		              		}
		              	}
		              }
		           
		              //Para ir a la siguiente
		              if (!empty($filter)) {
			              if (($currentPage+1) > $end) {
			              	echo '<li class="disable"><a href="#!">Adelante &gt;</a></li>' ;
			              }else
			              {
			              		if(isset($filterPrice)){
			              			echo '<li><a href="'.$fav->getUrl($currentPage+1).'&filter='.$filter->id.$filterPrice.'">Adelante &gt;</a></li>';

			              		}else
			              		{
			              			echo '<li><a href="'.$fav->getUrl($currentPage+1).'&filter='.$filter->id.'">Adelante &gt;</a></li>';
			              		}

			              }
		              }else
		              {
		              	if (($currentPage+1) > $end) {
			              	echo '<li class="disable"><a href="#!">Adelante &gt;</a></li>' ;
			              }else
			              {
			              		if(isset($filterPrice))
			              		{
			              			echo '<li><a href="'.$fav->getUrl($currentPage+1).'">Adelante &gt;</a></li>' ;
			              		}else
			              		{
			              			echo '<li><a href="'.$fav->getUrl($currentPage+1).'">Adelante &gt;</a></li>' ;
			              		}

			              }
		              }
		           
		              ////Si es la última página mostramos desactivado
		              if ($currentPage >= $lastPage)
		              {
		                echo '<li class="disabled"><a href="#!" class="textoMedio">Última</a></li>';
		              }
		              //en otro caso obtenemos la url y mostramos en forma de link
		              else
		              {
		                $url = $fav->getUrl($lastPage);
	              		if(isset($filterPrice))
	              		{
		                	echo '<li><a class="textoMedio" href="'.$url.'">Última &gt;&gt;</a></li>';
	              		}else
	              		{
		                	echo '<li><a class="textoMedio" href="'.$url.'">Última &gt;&gt;</a></li>';
	              		}
	              	}
		              ?>
		            @endif
		          </ul>
		        </nav> <!-- cd-pagination-wrapper -->				
		    </div>
		</div>
	</div>
</div>
<div class="modal fade" id="removeFav" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel">Remover publicación de favoritos.</h4>
			</div>
				<div class="modal-body">
					<p class="textoPromedio">¿Seguro que desea realizar esta acción?.</p>
					<div class="alert responseDanger">
						<p class="textoPromedio"></p>
					</div>
				</div>
				<div class="modal-footer">
						<img src="{{ asset('images/loading.gif') }}" class="miniLoader hidden">
						<button class="btn btn-danger btn-fav-remove-modal btn-modal-elim">Remover</button>
						<button type="button" class="btn btn-default btn-close-modal btn-modal-elim" data-dismiss="modal">Cerrar</button>
				</div>
		</div>
	</div>
</div>
@stop