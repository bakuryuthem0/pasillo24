@extends('main')

@section('content')

<div class="container contenedorUnico">
	<div class="row">
		<div class="col-xs-12 contAnaranjado" style="margin-top:5em;">
			<legend style="text-align:center;">Sistema de reputación</legend>
			<div class="col-xs-6 cotMyRep table-responsive">
				<legend><h3>Reputación como comprador</h3></legend>
				<table class="table table-striped table-hover textoPromedio ">
					<thead>
						<tr>
							<th>Título</th>
							<th>Vendedor</th>
							<th>Valoraciones dadas</th>
							<th>Valoraciones recibidas</th>
						</tr>
					</thead>
					<tbody>
					@foreach($compras as $compra)
						<tr>
							<td>{{ $compra->titulo }}</td>
							@if(!empty($compra->name_pub))
								<td>{{ $compra->name_pub }}</td>
							@else
								<td>{{ $compra->name.' '.$compra->lastname }}</td>
							@endif
							<td class="" style="text-align:center;">
								@if($compra->valor_vend == 0)
									Sin valorar
								@elseif($compra->valor_vend == 1)
									<i class="fa fa-plus-circle textoPromedio" style="color:green;"></i>
								@elseif($compra->valor_vend == -1)
									<i class="fa fa-minus-circle textoPromedio" style="color:red;"></i>
								@endif
							</td>
							<td style="text-align:center;">
								@if($compra->valor_comp == 0)
									Sin valorar
								@elseif($compra->valor_comp == 1)
									<i class="fa fa-plus-circle textoPromedio" style="color:green;"></i>
								@elseif($compra->valor_comp == -1)
									<i class="fa fa-minus-circle textoPromedio" style="color:red;"></i>
								@endif
							</td>
						</tr>
					@endforeach
					</tbody>
				</table>
				<nav role="navigation">
		          <?php  $presenter = new Illuminate\Pagination\BootstrapPresenter($compras); ?>
		          @if ($compras->getLastPage() > 1)
		          <ul class="cd-pagination no-space">
		            <?php
		              $beforeAndAfter = 2;
		           
		              //Página actual
		              $currentPage = $compras->getCurrentPage();
		           
		              //Última página
		              $lastPage = $compras->getLastPage();
		           
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
		                $url = $compras->getUrl(1);
		           
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
		                $url = $compras->getUrl($lastPage);
		           
		                echo '<li><a class="textoMedio" href="'.$url.'">Última &gt;&gt;</a></li>';
		              }
		              ?>
		            @endif
		          </ul>
		        </nav> <!-- cd-pagination-wrapper -->
		      </div>
			<div class="col-xs-6 cotMyRep table-responsive">
				<legend><h3>Reputación como Vendedor</h3></legend>
				<table class="table table-striped table-hover textoPromedio">
					<thead>
						<tr>
							<th>Título</th>
							<th>Comprador</th>
							<th>Valoraciones recibidas</th>
							<th>Valoraciones dadas</th>
						</tr>
					</thead>
					<tbody>
					@foreach($ventas as $venta)
						<tr>
							<td>{{ $venta->titulo }}</td>
						
							<td>{{ $venta->name.' '.$venta->lastname }}</td>
							<td class="" style="text-align:center;">
								@if($venta->valor_vend == 0)
									Sin valorar
								@elseif($venta->valor_vend == 1)
									<i class="fa fa-plus-circle textoPromedio" style="color:green;"></i>
								@elseif($venta->valor_vend == -1)
									<i class="fa fa-minus-circle textoPromedio" style="color:red;"></i>
								@endif
							</td>
							<td style="text-align:center;">
								@if($venta->valor_comp == 0)
									Sin valorar
								@elseif($venta->valor_comp == 1)
									<i class="fa fa-plus-circle textoPromedio" style="color:green;"></i>
								@elseif($venta->valor_comp == -1)
									<i class="fa fa-minus-circle textoPromedio" style="color:red;"></i>
								@endif
							</td>
						</tr>
					@endforeach
					</tbody>
				</table>
				<nav role="navigation">
		          <?php  $presenter = new Illuminate\Pagination\BootstrapPresenter($ventas); ?>
		          @if ($ventas->getLastPage() > 1)
		          <ul class="cd-pagination no-space">
		            <?php
		              $beforeAndAfter = 2;
		           
		              //Página actual
		              $currentPage2 = $ventas->getCurrentPage();
		           
		              //Última página
		              $lastPage = $ventas->getLastPage();
		           
		              //Comprobamos si las páginas anteriores y siguientes de la actual existen
		              $start = $currentPage2 - $beforeAndAfter;
		           
		                  //Comprueba si la primera página en la paginación está por debajo de 1
		                  //para saber como colocar los enlaces
		              if($start < 1)
		              {
		                $pos = $start - 1;
		                $start = $currentPage2 - ($beforeAndAfter + $pos);
		              }
		           
		              //Último enlace a mostrar
		              $end = $currentPage2 + $beforeAndAfter;
		           
		              if($end > $lastPage)
		              {
		                $pos = $end - $lastPage;
		                $end = $end - $pos;
		              }
		           
		              //Si es la primera página mostramos el enlace desactivado
		              if ($currentPage2 <= 1)
		              {
		                echo '<li class="disabled"><span class="textoMedio">Primera</span></li>';
		              }
		              //en otro caso obtenemos la url y mostramos en forma de link
		              else
		              {
		                $url = $ventas->getUrl(1);
		           
		                echo '<li><a class="textoMedio" href="'.$url.'">&lt;&lt; Primera</a></li>';
		              }
		           
		              //Para ir a la anterior
		              echo $presenter->getPrevious('&lt; Atras');
		           
		              //Rango de enlaces desde el principio al final, 3 delante y 3 detrás
		              echo $presenter->getPageRange($start, $end);
		           
		              //Para ir a la siguiente
		              echo $presenter->getNext('Adelante &gt;');
		           
		              ////Si es la última página mostramos desactivado
		              if ($currentPage2 >= $lastPage)
		              {
		                echo '<li class="disabled"><span class="textoMedio">Última</span></li>';
		              }
		              //en otro caso obtenemos la url y mostramos en forma de link
		              else
		              {
		                $url = $ventas->getUrl($lastPage);
		           
		                echo '<li><a class="textoMedio" href="'.$url.'">Última &gt;&gt;</a></li>';
		              }
		              ?>
		            @endif
		          </ul>
		        </nav> <!-- cd-pagination-wrapper -->
			</div>
			<div class="clearfix"></div>
			<hr>
			<div class="col-xs-12" style="margin-top:2em;">
				<button class="btn btn-primary" data-toggle="modal" data-target="#repModal" style="margin:0 auto;display:block;">¿Qué es el sistema de reputación?</button>
				
				<div class="col-xs-12 col-md-6">
					<h4>Total de compras con valoración positiva: {{ $comp_pos }}</h4>
					<h4>Total de compras con valoración negativa: {{ $comp_neg }}</h4>
				</div>
				<div class="col-xs-12 col-md-6">
					<h4>Total de ventas con valoración positiva: {{ $vend_pos }}</h4>
					<h4>Total de ventas con valoración negativa: {{ $vend_neg }}</h4>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="repModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialogo">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel">Valorar publicación.</h4>
			</div>
				<div class="modal-body">
					<ul class="textoPromedio">
						<li>La comunidad pasillo24.com ha creado un sistema de valoración para determinar la calidad de los productos así como también la calidad de atención al público.</li>
						<li>No trate de generar reputación utilizando programas o medios ilícitos, pasillo24.com cuenta con un sistema de gestión de usuarios y nos reservamos el derecho de eliminar al usuario que este ejerciendo este tipo de actividad.</li>
						<li>
							Como verá en el área de "mis compras" o "mis ventas" encontrará un botón con el cual podrá calificar al  usuario.
						</li>
						<li>En caso de existir denuncias múltiples por parte de la comunidad hacia un usuario determinado se bloqueara la  cuenta hasta aclarar los hechos.
						</li>
						<li>Los usuarios podrán calificar usando <button class="btn btn-success">Positivo</button>  para dar un valoración positiva y una  <button class="btn btn-danger">Negativo</button> para valoración negativa.</li>

						<li>Una valoración negativa anula a una positiva.</li>
						<li>Evite utilizar vocabulario inadecuado, recuerde que los Bolivianos nos caracterizamos por la amabilidad ante todo.</li>
						<li>La comunidad pasillo24.com le desea éxitos, no olvide comunicarse al: <strong>contacto@pasillo24.com</strong> estamos  para servirle.</li>
					</ul>
				</div>
				<div class="modal-footer " style="text-align:center;">
					<div class="col-xs-12">
						<p class="bg-primary textoPromedio" style="float:left;padding:1em;">Niveles de reputación</p>
					<p class="bg-primary textoPromedio" style="float:right;padding:1em;"><i class="fa fa-plus-circle" style="color:white;"></i> Reputación positiva</p>
					</div>
					
					<div clas="clearfix"></div>
					<div class="col-xs-2">
						<div id="bronce" class="trofeos" style="float:none;display:inline-block;">

						</div>
						<label class="textoPromedio"><i class="fa fa-plus-circle" style="color:green;"></i> 15</label>
					</div>
					<div class="col-xs-2">
						<div id="plata" class="trofeos" style="float:none;display:inline-block;">

						</div>
						<label class="textoPromedio"><i class="fa fa-plus-circle" style="color:green;"></i> 60</label>

					</div>
					<div class="col-xs-2">
						<div id="oro" class="trofeos" style="float:none;display:inline-block;">

						</div>
						<label class="textoPromedio"><i class="fa fa-plus-circle" style="color:green;"></i> 150</label>

					</div>
					<div class="col-xs-2">
						<div id="c_bronce" class="trofeos" style="float:none;display:inline-block;">

						</div>
						<label class="textoPromedio"><i class="fa fa-plus-circle" style="color:green;"></i> 300</label>

					</div>
					<div class="col-xs-2">
						<div id="c_plata" class="trofeos" style="float:none;display:inline-block;">

						</div>
						<label class="textoPromedio"><i class="fa fa-plus-circle" style="color:green;"></i> 700</label>

					</div>
					<div class="col-xs-2">
						<div id="c_oro" class="trofeos" style="float:none;display:inline-block;">

						</div>
						<label class="textoPromedio"><i class="fa fa-plus-circle" style="color:green;"></i> 1500</label>

					</div>
					
				</div>
		</div>
	</div>
</div>
@stop