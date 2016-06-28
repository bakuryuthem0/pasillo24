@extends('main')

@section('content')
<div class="container contenedorUnico">
	<div class="row">
		<div class="col-xs-12">
			<h3>Editar cuentas</h3>
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
							Numero
						</th>
						<th>
							Tipo
						</th>
						<th>
							Banco
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
					@foreach($cuentas as $c)
						<tr>
							<td>
								{{ $c->num_cuenta }}
							</td>
							<td>
								{{ $c->tipoCuenta }}
							</td>	
							<td>
								{{ $c->nombre }}
							</td>	
							<td>
								<a href="{{ URL::to('administrador/editar-cuenta/'.$c->id) }}" class="btn btn-xs btn-warning" style="width:100%;">Modificar</a>
							</td>
							<td>
								<button style="width: 100%;" class="btn btn-danger btn-xs btn-elim-account" value="{{ $c->id }}" data-toggle="modal" data-target="#elimAccountModal"><span class="fa fa-close"></span> Eliminar</button>
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
</div>


<!-- Modal -->
<div class="modal modal-elim-cat fade" id="elimAccountModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Eliminar cuenta</h4>
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
      	<img src="{{ asset('images/loading.gif') }}" class="miniLoader hidden">
        <button type="button" class="btn btn-danger eliminar-cuenta btn-modal-elim" >Eliminar</button>
        <button type="button" class="btn btn-default btn-dimiss btn-close-modal btn-modal-elim" data-dismiss="modal">Cerrar</button>

      </div>
    </div>
  </div>
</div>
@stop