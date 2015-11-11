@extends('main')

@section('content')

<div class="container contenedorUnico">
	<div class="row">
		<div class="col-xs-12">
				<legend>Sistema de reputación de ffasil.com</legend>
				<p class=textoPromedio>Una vez que hayas realizado tu compra, podrás valorar al vendedor según tus experiencias, pasadas las 48 horas. </p>
				<table class="table table-striped table-hover textoPromedio">
					<thead>
						<tr>
							<th>Título</th>
							<th>Vendedor</th>
							<th>Teléfono</th>
							<th>Correo</th>
							<th class="noMovilMinMin">Página web</th>
							<th>Valorar Vendedor</th>
						</tr>
					</thead>
					<tbody>
					@foreach($compras as $compra)
						<tr>
							<td>{{ $compra->titulo }}</td>
							<td>
								@if(!empty($compra->name))
									{{ $compra->name }}
								@else
									{{ $compra->pName }}
								@endif
								@if(!empty($compra->lastname))
									{{ $compra->lastname }}
								@else
									{{ $compra->pLastname }}
								@endif
							</td>
							<td>
								@if(!empty($compra->phone))
									{{ $compra->phone }}
								@else
									{{ $compra->pPhone }}
								@endif
							</td>
							<td>
								@if(!empty($compra->email))
									{{ $compra->email }}
								@else
									{{ $compra->pEmail }}
								@endif
							</td>
							
							<td class="noMovilMinMin">
								@if(!empty($compra->pag_web_hab))
									{{ $compra->pag_web_hab }}
								@else
									{{ $compra->pPag_web }}
								@endif
							</td>
							<td>
								@if($compra->fechVal <= date('Y-m-d'))
								<button class="btn btn-primary sendPubValue" data-toggle="modal" data-target="#modalComprar" value={{ $compra->id }}>Valorar</button>
								@else
									<i class="fa fa-clock-o btn-xs" style="font-size:2em;margin-top:0px;color:orange;"></i>
								@endif
							</td>
						</tr>
					@endforeach
					</tbody>
				</table>
		</div>
	</div>
</div>
<div class="modal fade" id="modalComprar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel">Valorar publicación.</h4>
			</div>
				<div class="modal-body">
					<p class="textoPromedio">Elija su opción.</p>
					
				</div>
				<div class="modal-footer">
					<div class="alert responseDanger" style="text-align:center;">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					</div>
						<button class="btn btn-success sendValueSeller" value="pos" id="pos">Positivo</button>
						<button class="btn btn-danger sendValueSeller" value="neg" id="neg">Negativo</button>
				</div>
		</div>
	</div>
</div>
@stop