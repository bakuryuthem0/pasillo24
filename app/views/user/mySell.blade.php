@extends('main')

@section('content')

<div class="container contenedorUnico">
	<div class="row">
		<div class="col-xs-12">
			<div class="col-xs-12 col-md-8 center-block contAnaranjado">
				<legend>Sistema de reputación de pasillo24.com</legend>
				
				<p class=textoPromedio>Una vez que hayas realizado la venta, podrás valorar al comprador según tus experiencias, pasadas las 48 horas.</p>
				<div class="table-responsive">
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
										<button class="btn btn-primary valorar btn-xs" data-toggle="modal" data-target="#modalComprar" value="{{ $compra->id }}">Valorar</button>
									@else
										<i class="fa fa-clock-o btn-xs" style="font-size:2em;margin-top:0px;color:orange;"></i>
									@endif
	                            </td>
	                            <td>
	                            	@if(date('Y-m-d',(strtotime($compra->fechVal)+259200)) <= date('Y-m-d',time()))
	                            	<button class="btn btn-danger btn-xs btn-remove" value="{{ $compra->id }}" data-url="{{ URL::to('usuario/mi-reputacion/borrar-venta') }}" data-toggle="modal" href="#removeRep">Quitar</button>
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
					<div class="alert responseDanger">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						<p></p>
					</div>
				</div>
				<div class="modal-footer">
						<img src="{{ asset('images/loading.gif') }}" class="miniLoader hidden">
						<button class="btn btn-success sendValueType btn-modal-elim" data-url="{{ URL::to('usuario/valorar-comprador') }}" data-value="pos">Positivo</button>
						<button class="btn btn-default sendValueType btn-modal-elim" data-url="{{ URL::to('usuario/valorar-comprador') }}" data-value="neutro">Neutro</button>
						<button class="btn btn-danger sendValueType btn-modal-elim" data-url="{{ URL::to('usuario/valorar-comprador') }}" data-value="neg">Negativo</button>
						<button class="btn btn-primary btn-dimiss btn-close-modal btn-modal-elim" data-dismiss="modal">Cerrar</button>
						
				</div>
		</div>
	</div>
</div>

<div class="modal fade" id="removeRep">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Quitar</h4>
			</div>
			<div class="modal-body">
				<div class="alert responseDanger">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<p></p>
				</div>
				¿Seguro desea remover este elemento de su lista?, esta acción es irreversible
			</div>
			<div class="modal-footer">
				<img src="{{ asset('images/loading.gif') }}" class="miniLoader hidden">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
				<button type="button" class="btn btn-danger btn-remove-modal">Remover</button>
			</div>
		</div>
	</div>
</div>
@stop

@section('postscript')
<script type="text/javascript">
	function removeResponse()
	{
		$('.responseDanger').removeClass('alert-success');
		$('.responseDanger').removeClass('alert-danger');
		$('.responseDanger').removeClass('active');
	}
	jQuery(document).ready(function($) {
		$('.modal').on('hide.bs.modal', function(event) {
			$('.to-elim').removeClass('to-elim');
		});
		$('.btn-remove').on('click', function(event) {
			var btn = $(this);
			var url = btn.data('url');
			btn.addClass('to-elim');
			$('.btn-remove-modal').val(btn.val()).attr('data-url',url);
		});
		$('.btn-remove-modal').on('click', function(event) {
			var btn = $(this);
			var url = btn.data('url');
			var dataPost = { id: btn.val() };
			$.ajax({
				url: url,
				type: 'POST',
				dataType: 'json',
				data: dataPost,
				beforeSend:function()
				{
					btn.addClass('disabled').attr('disabled',true);
					btn.prevAll('.miniLoader').removeClass('hidden');
				},
				success:function(response)
				{
					btn.removeClass('disabled').attr('disabled',false);
					btn.prevAll('.miniLoader').addClass('hidden');
					$('.responseDanger').addClass('active').addClass('alert-'+response.type).children('p').html(response.msg);
					if (response.type == 'success') {
						$('.to-elim').parents('tr').remove();
					};
					setTimeout(removeResponse, 4000);
				},
				error:function()
				{
					btn.removeClass('disabled').attr('disabled',false);
					btn.prevAll('.miniLoader').addClass('hidden');
					$('.responseDanger').addClass('active').addClass('alert-danger').children('p').html('Error 500, por favor intente nuevamente.');
					setTimeout(removeResponse, 4000);
				}
			});			
		});
	});
</script>
@stop