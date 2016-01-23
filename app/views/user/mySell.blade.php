@extends('main')

@section('content')

<div class="container contenedorUnico">
	<div class="row">
		<div class="col-xs-12">
			<div class="col-xs-12 col-md-6">
				<legend>Sistema de reputación de pasillo24.com</legend>
				
				<p class=textoPromedio>Una vez que hayas realizado la venta, podrás valorar al comprador según tus experiencias, pasadas las 48 horas.</p>
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
								@if($compra->fechVal <= date('Y-m-d',time()))
									<button class="btn btn-primary sendPubValue btn-xs" data-toggle="modal" data-target="#modalComprar" value={{ $compra->id }}>Valorar</button>
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
</div>
<div class="modal fade" id="modalComprar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel">Valorar publicacion.</h4>
			</div>
				<div class="modal-body">
					<p class="textoPromedio">Elija su opcion.</p>
					<div class="alert responseDanger" style="text-align:center;">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				</div>
				</div>
				<div class="modal-footer">
					
						<img src="{{ asset('images/loading.gif') }}" class="miniLoader">
						<button class="btn btn-success sendValueType" data-url="{{ URL::to('usuario/valorar-comprador') }}" value="pos" >Positivo</button>
						<button class="btn btn-danger sendValueType" data-url="{{ URL::to('usuario/valorar-comprador') }}" value="neg">Negativo</button>
						<button class="btn btn-success btn-dimiss hidden" data-dismiss="modal">Aceptar</button>
				</div>
		</div>
	</div>
</div>


@stop