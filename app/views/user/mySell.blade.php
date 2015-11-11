@extends('main')

@section('content')

<div class="container contenedorUnico">
	<div class="row">
		<div class="col-xs-12">
			<div class="col-xs-6">
				<table class="table table-striped table-hover textoPromedio">
					<thead>
						<tr>
							<th>Titulo</th>
							<th>Comprador</th>
							<th>Valorar</th>
						</tr>
					</thead>
					<tbody>
					@foreach($compras as $compra)
						<tr>
							<td>{{ $compra->titulo }}</td>
						
							<td>{{ $compra->name.' '.$compra->lastname }}</td>
							
							<td>
                                                        @if($compra->fechVal < $hoy)
									<i class="fa fa-clock-o btn-xs" style="font-size:2em;margin-top:0px;color:orange;"></i>
								@else
									<button class="btn btn-primary sendPubValue" data-toggle="modal" data-target="#modalComprar" value={{ $compra->id }}>Valorar</button>
								@endif
                                                        </td>
						</tr>
					@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="modalComprar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel">Valorar publicacion.</h4>
			</div>
				<div class="modal-body">
					<p class="textoPromedio">Elija su opcion.</p>
					
				</div>
				<div class="modal-footer">
					<div class="alert responseDanger" style="text-align:center;">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					</div>
						<button class="btn btn-success sendValueBuyer" name="pos" value="pos" id="pos">Positivo</button>
						<button class="btn btn-danger sendValueBuyer" name="neg" value="neg" id="neg">Negativo</button>
				</div>
		</div>
	</div>
</div>


@stop