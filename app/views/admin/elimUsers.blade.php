@extends('main')

@section('content')

<div class="container contenedorUnico">
	<div class="row">
		<div class="col-xs-12">
			<h3>Editar usuarios</h3>
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
							Carnet
						</th>
						<th>
							Nit
						</th>
						<th>
							<strong>Usuario</strong>
						</th>
						<th>
							<strong>Nombre</strong>
						</th>
						<th class="noMovil">
							<strong>Email</strong>
						</th>
						<th class="noMovil">
							Departamento
						</th>
						<th>
							Rol
						</th>
						<td style="width:42px;">
							<strong>Acciones</strong>
						</td>
					</tr>
				</thead>
				<tbody>
					@foreach($users as $user)
						<tr>
							@if($user->id != Auth::id())
								<td>
									{{ $user->id_carnet }}
								</td>
								<td>
									{{ $user->nit }}
								</td>
								<td>
									{{ $user->username }}
								</td>
								<td>
									{{ $user->name.' '.$user->lastname }}
								</td>
								
								<td class="noMovil">
									{{ $user->email }}
								</td>
								<td class="noMovil">
									{{ $user->nombre }}
								</td>
								<td>
									{{ $user->role }}
								</td>
								<td style="width:42px;">
									<button style="width: 100%;" class="btn btn-danger btn-xs btn-elim" value="{{ $user->id }}" data-toggle="modal" data-target="#myModal"><span class="fa fa-close"></span> Eliminar</button>
								</td>
							@endif
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
</div>


<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Eliminar usuario</h4>
      </div>
      <div class="modal-body">
      	<div class="alert responseDanger">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				</div>
      	<div class="alert alert-warning">
			<p class="textoPromedio">Advertencia. ¿Esta seguro que desea continuar?, estos cambios son irreversibles</p>      	    
      	</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" id="eliminarUsuarioModal">Eliminar</button>
      </div>
    </div>
  </div>
</div>
@stop