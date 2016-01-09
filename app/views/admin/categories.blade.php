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
			<form method="POST" action="{{ URL::to('administrador/categoria/nueva') }}">
				<div class="col-xs-12" style="padding:0;">
					<label class="textoPromedio">Nombre de la categoria</label>
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
					<label class="textoPromedio">
						Tipo
					</label>
					<select name="type" class="form-control" required>
						<option value="1">Categoria</option>
						<option value="2">Servicios</option>
					</select>
					@if ($errors->has('type'))
						 @foreach($errors->get('type') as $err)
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
			<h3>Editar categorias</h3>
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
							tipo
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
								{{ $c->nombre }}
							</td>	
							<td>
								@if($c->tipo == 1) Categoria @else Servicio @endif
							</td>	
							<td>
								<a href="{{ URL::to('administrador/categoria/modificar/'.$c->id) }}" class="btn btn-xs btn-warning" style="width:100%;">Modificar</a>
							</td>
							<td>
								<button style="width: 100%;" class="btn btn-danger btn-xs btn-elim-cat" value="{{ $c->id }}" data-toggle="modal" data-target="#myModal"><span class="fa fa-close"></span> Eliminar</button>
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
</div>


<!-- Modal -->
<div class="modal modal-elim-cat fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Eliminar categoria</h4>
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
        <button type="button" class="btn btn-danger eliminar-categoria" >Eliminar</button>
        <button type="button" class="btn btn-success btn-dimiss hidden" data-dismiss="modal">Aceptar</button>

      </div>
    </div>
  </div>
</div>
@stop