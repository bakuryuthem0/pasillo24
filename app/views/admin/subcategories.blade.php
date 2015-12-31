@extends('main')

@section('content')

<div class="container contenedorUnico">
	<div class="row">
		<div class="col-xs-12">
			<h3>Nueva categoria</h3>
			@if(Session::has('danger'))
				<div class="alert alert-danger">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<p class="textoPromedio">{{ Session::get('danger') }}</p>
				</div>
			@elseif(Session::has('success'))
				<div class="alert alert-success">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<p class="textoPromedio">{{ Session::get('success') }}</p>
				</div>
			@endif
			<form method="POST" action="{{ URL::to('administrador/sub-categoria/nueva') }}">
				<div class="col-xs-12" style="padding:0;">
					<label class="textoPromedio">Nombre de la sub-categoria</label>
					<input type="text" name="name" class="form-control" placeholder="Nombre" required>
					@if ($errors->has('name'))
						 @foreach($errors->get('name') as $err)
						 	<div class="alert alert-danger">
						 		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						 		<p class="textoPromedio">{{ $err }}</p>
						 	</div>
						 @endforeach
					@endif
				</div>
				<div class="col-xs-12" style="padding:0;">
					<label class="textoPromedio">Categoria</label>
					<select name="cat" class="form-control">
						<option value="">Seleccione una categoria</option>
						@foreach($cats as $c)
							<option value="{{ $c->id }}">{{ $c->nombre }}</option>
						@endforeach
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
				<div class="col-xs-12" style="padding:0;">
					<button type="submit" class="btn btn-success">Enviar</button>
				</div>
			</form>
		</div>
		<div class="clearfix">
		</div>
		<hr>
		<div class="col-xs-12">
			<h3>Editar sub-categorias</h3>
			@if(Session::has('success'))
			<div class="alert alert-success">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				<p class="textoPromedio">{{ Session::get('success') }}</p>
			</div>
			@endif
			<form action="#" method="get">
				<div class="input-group">
					<!-- USE TWITTER TYPEAHEAD JSON WITH API TO SEARCH -->
					<input class="form-control" id="buscar-usuario" name="q" placeholder="Busqueda general" required>
					<span class="input-group-addon">
						<i class="glyphicon glyphicon-search"></i>
					</span>
				</div>
			</form>
			<table id="tablesorter" class="tablesorter table table-striped table-condensed table-vertical-middle table-super-condensed table-bordered table-list-search textoPromedio">
				<thead>
					<tr class="active">
						<th>
							id
						</th>
						<th>
							Nombre
						</th>
						<th>
							Categoria
						</th>
						<th class="noMovil">
							Modificar
						</th>
						<th class="noMovil">
							Eliminar
						</th>
					</tr>
				</thead>
				<tbody>
					@foreach($cat as $c)
						<tr>
							<td>
								{{ $c->id }}
							</td>
							<td>
								{{ $c->desc }}
							</td>	
							<td>
								@if(empty($c->nombre))
									Sin especificar
								@else
									{{ $c->nombre }}
								@endif
							</td>	
							<td>
								<a href="{{ URL::to('administrador/sub-categoria/modificar/'.$c->id) }}" class="btn btn-xs btn-warning" style="width:100%;">Modificar</a>
							</td>
							<td>
								<button style="width: 100%;" class="btn btn-danger btn-xs btn-elim-subcat" value="{{ $c->id }}" data-toggle="modal" data-target="#myModal"><span class="fa fa-close"></span> Eliminar</button>
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
				<nav role="navigation">
		          <?php  $presenter = new Illuminate\Pagination\BootstrapPresenter($cat); ?>
		          @if ($cat->getLastPage() > 1)
		          <ul class="cd-pagination no-space">
		            <?php
		              $beforeAndAfter = 3;
		           
		              //Página actual
		              $currentPage = $cat->getCurrentPage();
		           	
		              //Última página
		              $lastPage = $cat->getLastPage();
		           
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
		                $url = $cat->getUrl(1);
		           
		                echo '<li><a class="textoMedio" href="'.$url.'">&lt;&lt; Primera</a></li>';
		              }
		           
		              //Para ir a la anterior
		              if(!empty($filter)){
			              if (($currentPage-1) < $start) {
			              	echo '<li class="disable"><span>&lt; Atras</span></li>' ;	
			              }else
			              {
			              	echo '<li><a href="'.$cat->getUrl($currentPage-1).'&filter='.$filter->id.'">&lt; Atras</a></li>' ;
			              }
		              }else
		              {
		              	if (($currentPage-1) < $start) {
			              	echo '<li class="disable"><span>&lt; Atras</span></li>' ;	
			              }else
			              {
			              	echo '<li><a href="'.$cat->getUrl($currentPage-1).'">&lt; Atras</a></li>' ;
			              }
		              }
		           
		              //Rango de enlaces desde el principio al final, 3 delante y 3 detrás
		              for($i = $start; $i<=$end;$i++)
		              {
		              	if ($currentPage == $i) {
		              		echo '<li class="disabled"><span>'.$i.'</span></li>';
		              	}else
		              	{
		              		if(!empty($filter))
		              		{
		              			echo '<li><a href="'.$cat->getUrl($i).'&filter='.$filter->id.'">'.$i.'</a></li>';

		              		}else
		              		{
		              			echo '<li><a href="'.$cat->getUrl($i).'">'.$i.'</a></li>';
		              		}
		              	}
		              }
		           
		              //Para ir a la siguiente
		              if (!empty($filter)) {
			              if (($currentPage+1) > $end) {
			              	echo '<li class="disable"><span>Adelante &gt;</span></li>' ;
			              }else
			              {
			              	echo '<li><a href="'.$cat->getUrl($currentPage+1).'&filter='.$filter->id.'">Adelante &gt;</a></li>' ;
			              }
		              }else
		              {
		              	if (($currentPage+1) > $end) {
			              	echo '<li class="disable"><span>Adelante &gt;</span></li>' ;
			              }else
			              {
			              	echo '<li><a href="'.$cat->getUrl($currentPage+1).'">Adelante &gt;</a></li>' ;
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
		                $url = $cat->getUrl($lastPage);
		                echo '<li><a class="textoMedio" href="'.$url.'">Última &gt;&gt;</a></li>';
		              }
		              ?>
		            @endif
		          </ul>
		        </nav> <!-- cd-pagination-wrapper -->
		</div>
	</div>
</div>


<!-- Modal -->
<div class="modal modal-elim-subcat fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Eliminar sub-categoria</h4>
      </div>
      <div class="modal-body">
      	<div class="alert responseDanger">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<p class="responseDanger-text textoPromedio"></p>
				</div>
      	<div class="alert alert-warning">
			<p class="textoPromedio">Advertencia. ¿Esta seguro que desea continuar?, estos cambios son irreversibles</p>      	    
      	</div>
      </div>
      <div class="modal-footer">
      	<img src="{{ asset('images/loading.gif') }}" class="miniLoader">
        <button type="button" class="btn btn-danger eliminar-subcategoria" >Eliminar</button>
      </div>
    </div>
  </div>
</div>
@stop