@extends('main')
@section('content')
<div class="container contenedorUnico">
	<div class="row">
		<div class="col-xs-12 contAnaranjado formulario">
			@if(Session::has('error'))
			<div class="alert alert-danger">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				<p class="textoPromedio">{{ Session::get('error') }}</p>
			</div>
			@elseif(Session::has('success'))
			<div class="alert alert-success">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				<p class="textoPromedio">{{ Session::get('success') }}</p>
			</div>
			@endif
			@if(isset($type))
			<div class="col-xs-12" style="padding-left:0px;padding-right:0px;margin-bottom:2em;">
				<legend>Mis Publicaciones </legend>
				<div class="col-xs-12" style="border:1px solid black;padding: 2em 1em 2em 4em;border-radius:16px;">
					<legend>Leyenda:</legend>
					<p class="textoPromedio">
						Aprobado: <i class="fa fa-check-circle btn-xs" style="font-size:2em;margin-top:0px;color:green;"></i> 
						- 
						Procesando: <i class="fa fa-clock-o btn-xs" style="font-size:2em;margin-top:0px;color:orange;"></i> 
					</p>
				</div>
			</div>
			<div class="clearfix"></div>
				@if($type == 'lider')
			<div class="table-responsive" style="width:100%;">
				<table id="tablesorter" class="table table-striped table-hover table-list-search">
					<thead>
						<tr>
							<th class="textoMedio">
								Título
							</th>
							<th class="textoMedio">
								Ubicación
							</th>
							<th class="textoMedio">
								Categoría
							</th>
							<th class="textoMedio">
								Página web
							</th>
							<th class="textoMedio">
								Fecha de Inicio
							</th>
							<th class="textoMedio">
								Fecha de Fin
							</th>
							<th class="textoMedio">
								Monto
							</th>

							<th class="textoMedio">
								Observaciones
							</th>
							<th class="textoMedio">Modificar</th>
							<th class="textoMedio">
								Pagar/Pagados
							</th>
							<th class="textoMedio">
								Eliminar
							</th>
						</tr>
					</thead>
					<tbody>
						@foreach($publications as $publication)
						<tr>
							<td class="textoMedio">
								{{ $publication->titulo }}
							</td>
							<td class="textoMedio">
								{{ $publication->ubicacion }}
							</td>
							<td class="textoMedio">
								@if(empty($publication->categoria)) Sin categoria @else {{ $publication->categoria }} @endif
							</td>
							<td class="textoMedio">
								@if($publication->pag_web == "" || is_null($publication->pag_web))
									Sin Página web
								@else
									{{ $publication->pag_web }}
								@endif
							</td>
							<td class="textoMedio">
								 {{ date('d/m/Y',strtotime($publication->fechIni)) }}
							</td>
							<td class="textoMedio">
								@if($publication->fechFin < date('Y-m-d')) Publicación expirada @else {{ date('d/m/Y',strtotime($publication->fechFin)) }} @endif
							</td>
							<td class="textoMedio">
								{{ $publication->monto.' Bs.' }}
							</td>
							
							<td class="textoMedio">
								@if($publication->motivo != "" && $publication->status == 'Rechazado') {{ $publication->motivo }} @else Sin observaciones @endif
							</td>
							<td>
								
									<a target="_blank" href="{{ URL::to('usuario/publicacion/modificar/'.$publication->id) }}" class="btn btn-success btn-xs" name="modificar" >Modificar</a>
								
							</td>
							<td class="textoMedio">
								@if($publication->fechFin < date('Y-m-d') && $publication->status != 'Procesando')
									<a href="{{ URL::to('usuario/publicaciones/reactivar/'.$publication->id) }}" class="btn btn-primary btn-xs">Reactivar</a>	
								@elseif($publication->status == 'Pendiente')
									<a href="{{ URL::to('usuario/publicaciones/pago/'.$publication->id) }}" class="btn btn-primary btn-xs">Pagar</a>
								@elseif($publication->status == "Rechazado")
									<a href="{{ URL::to('usuario/publicaciones/pago/'.$publication->id) }}" class="btn btn-primary btn-xs">Re-enviar</a>
								@elseif($publication->status == 'Procesando')
									    <i class="fa fa-clock-o btn-xs" style="font-size:2em;margin-top:0px;color:orange;"></i>
								@elseif($publication->status == 'Aprobado')
										<i class="fa fa-check-circle btn-xs" style="font-size:2em;margin-top:0px;color:green;"></i>
								@endif
							</td>
							<td>
								<button class="btn btn-danger btn-xs btnEliminarPub" value="{{ $publication->id }}" data-toggle="modal" href="#modalElimUserPub">Eliminar</button>
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
			@elseif($type == 'habitual')
			<div class="table-responsive" style="width:100%;">
				<table id="tablesorter" class="table table-striped table-hover table-list-search">
					<thead>
						<tr>
							<th class="textoMedio">
								Título
							</th>
							<th class="textoMedio">
								Ubicación
							</th>
							<th class="textoMedio">
								Categoría
							</th>
							<th class="textoMedio">
								Precio
							</th>
							<th class="textoMedio">
								Fecha de Inicio
							</th>
							<th class="textoMedio">
								Fecha de Fin
							</th>
							<th class="textoMedio">
								Monto
							</th>
							
							<th class="textoMedio">Modificar</th>
							<th class="textoMedio">
								Pagar/Pagados
							</th>
							<th class="textoMedio">
								Eliminar
							</th>
						</tr>
					</thead>
					<tbody>
						@foreach($publications as $publication)
						<tr>
							<td class="textoMedio">
								{{ $publication->titulo }}
							</td>
							<td class="textoMedio">
								@if($publication->ubicacion == "" || is_null($publication->ubicacion))
									Sin decidir
								@else
									@if($publication->ubicacion == "Ambos")
										Categoria/Principal
									@else
										{{ $publication->ubicacion }}
									@endif
								@endif
							</td>
							<td class="textoMedio">
								{{ $publication->categoria }}
							</td>
							<td class="textoMedio">
								{{ $publication->precio.' '.$publication->moneda }}
							</td>
							<td class="textoMedio">
								@if($publication->fechIni == "0000-00-00" && $publication->fechIniNormal == "0000-00-00")
									@if(!empty($publication->motivo) && $publication->status == 'Rechazado') 
										<strong>Observaciones</strong>
									@else 
										Sin aprobar
									@endif
								@else
									@if($publication->ubicacion == "Ambos")
										Principal: {{ date('d/m/Y',strtotime($publication->fechIni)) }}
								 		<br>
								 		Categoria: {{ date('d/m/Y',strtotime($publication->fechIniNormal)) }}
								 	@elseif($publication->ubicacion == "Principal")
								 		{{ date('d/m/Y',strtotime($publication->fechIni)) }}
								 	@elseif($publication->ubicacion == "Categoria")
								 		{{ date('d/m/Y',strtotime($publication->fechIniNormal)) }}
								 	@endif
								@endif
							</td>
							<td class="textoMedio">
								@if($publication->fechIni == "0000-00-00" && $publication->fechIniNormal == "0000-00-00")
									@if(!empty($publication->motivo) && $publication->status == 'Rechazado') 
										{{ $publication->motivo }} 
									@else 
										Sin aprobar
									@endif
								@else
									@if($publication->ubicacion == "Principal")
										@if($publication->fechFin < date('Y-m-d') ) 
											Publicación expirada 
										@else 
											{{ date('d/m/Y',strtotime($publication->fechFin)) }} 
										@endif
									@elseif($publication->ubicacion == "Categoria")
										@if($publication->fechFinNormal < date('Y-m-d') ) 
											Publicación expirada 
										@else 
											{{ date('d/m/Y',strtotime($publication->fechFinNormal)) }} 
										@endif
									@elseif($publication->ubicacion == "Ambos")
										@if($publication->fechFin < date('Y-m-d')) 
											Principal: Publicación expirada 
											<br>
										@else
											Principal: {{ date('d/m/Y',strtotime($publication->fechFin)) }}
											<br>
										@endif

										@if($publication->fechFinNormal < date('Y-m-d'))
											Categoria: Publicación expirada 
										@else
											Categoria: {{ date('d/m/Y',strtotime($publication->fechFinNormal)) }}
										@endif									 		
									@endif
								@endif
							</td>
							<td class="textoMedio">
								{{ $publication->monto.' Bs.' }}
							</td>
							
							<td>
								<a target="_blank" href="{{ URL::to('usuario/publicacion/modificar/'.$publication->id) }}" class="btn btn-success btn-xs" name="modificar" >Modificar</a>
							</td>
							<td class="textoMedio">
								@if($publication->fechFin < date('Y-m-d') && $publication->fechFin != "0000-00-00" && $publication->status != 'Procesando')
									<a href="{{ URL::to('usuario/publicaciones/reactivar/'.$publication->id) }}" class="btn btn-primary btn-xs">Reactivar</a>	
								@elseif($publication->status == 'Pendiente')
									<a href="{{ URL::to('usuario/publicacion/habitual/pago/'.$publication->id) }}" class="btn btn-primary btn-xs">Pagar</a>
								@elseif($publication->status == "Rechazado")
									<a href="{{ URL::to('usuario/publicaciones/pago/'.$publication->id) }}" class="btn btn-primary btn-xs">Re-enviar</a>
								@elseif($publication->status == 'Procesando')
								    <i class="fa fa-clock-o btn-xs" style="font-size:2em;margin-top:0px;color:orange;"></i>
								@elseif($publication->status == 'Aprobado')
									<i class="fa fa-check-circle btn-xs" style="font-size:2em;margin-top:0px;color:green;"></i>	
								@endif
							</td>
							<td>
								<button class="btn btn-danger btn-xs btnEliminarPub" value="{{ $publication->id }}" data-toggle="modal" href="#modalElimUserPub">Eliminar</button>
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
			@elseif($type == 'casual')
			<div class="clearfix"></div>
			@if(!is_null($rePub))
				<p class="bg-info textoPromedio" style="padding:0.5em;">Fecha para la siguiente publicacion casual:   {{ date('d-m-Y',strtotime($rePub->fechRepub)) }} </p>
				@endif
			<div class="table-responsive" style="width:100%;">
				<table id="tablesorter" class="table table-striped table-hover table-list-search">
					<thead>
						<tr>
							<th class="textoMedio">
								Título
							</th>
							
							<th class="textoMedio">
								Categoría
							</th>
							<th class="textoMedio">
								Precio
							</th>
							<th class="textoMedio">
								Fecha de Inicio
							</th>
							<th class="textoMedio">
								Fecha de Fin
							</th>
							
							<!--<th class="textoMedio">Modificar</th>-->
							<th class="textoMedio">
								Estatus
							</th>
							<th class="textoMedio">
								Eliminar
							</th>
						</tr>
					</thead>
					<tbody>
						@foreach($publications as $publication)
						<tr>
							<td class="textoMedio">
								{{ $publication->titulo }}
							</td>
							
							<td class="textoMedio">
								{{ $publication->categoria }}
							</td>
							<td class="textoMedio">
								{{ $publication->precio.' '.$publication->moneda }}
							</td>
							<td class="textoMedio">
								@if(!empty($publication->motivo) && $publication->status == 'Rechazado') 
										{{ $publication->motivo }} 
								@else 
									@if($publication->fechIni == "0000-00-00")
										Sin aprobar
									@else
									 {{ date('d/m/Y',strtotime($publication->fechIni)) }}
									@endif
								@endif
							</td>
							<td class="textoMedio">
								@if(!empty($publication->motivo) && $publication->status == 'Rechazado') 
									{{ $publication->motivo }} 
								@else 
									@if($publication->fechIni == "0000-00-00")
									Sin aprobar
									@else
										@if($publication->fechFin < date('Y-m-d') ) Publicación expirada @else {{ date('d/m/Y',strtotime($publication->fechFin)) }} @endif
									@endif
								@endif
							</td>
							
							<!--<td>
								<form method="post" action="{{ URL::to('usuario/publicacion/modificar') }}">
									<button class="btn btn-success btn-xs" name="modificar" value="{{ $publication->id }}">Modificar</button>
								</form>
							</td>-->
							<td class="textoMedio">
								@if($publication->status == 'Procesando')
								    <i class="fa fa-clock-o btn-xs" style="font-size:2em;margin-top:0px;color:orange;"></i>
								@elseif($publication->status == 'Aprobado')
									<i class="fa fa-check-circle btn-xs" style="font-size:2em;margin-top:0px;color:green;"></i>	
								@endif
							</td>
							<td>
								<button class="btn btn-danger btn-xs btnEliminarPub" value="{{ $publication->id }}" data-toggle="modal" href="#modalElimUserPub">Eliminar</button>
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
			@endif
			@else
			<div class="col-xs-12">
				<div class="col-xs-12 pubBottonsCont">
					<div class="col-sm-12 col-md-4 visible-md visible-lg" >
						<h3 class="footerText text-center">ANUNCIO LÍDER</h3>
					</div>
					<div class="col-sm-12 col-md-4 visible-md visible-lg" >
						<h3 class="footerText text-center">ANUNCIO HABITUAL</h3>

					</div>
					<div class="col-sm-12 col-md-4 visible-md visible-lg" >
						<h3 class="footerText text-center">ANUNCIO CASUAL</h3>

					</div>
				</div>
				<div class="col-sm-12">
					<div class="col-sm-12 col-md-4">
						<h3 class="footerText text-center hidden-md hidden-lg">ANUNCIO LÍDER</h3>
						<img src="{{asset('images/lider-01.png')}}" class="pubType center-block">
					
						<a href="{{ URL::to('usuario/publicaciones/mis-publicaciones/lider') }}" class="btn btn-primary pubBtn footerText hidden-md hidden-lg">Ver publicación</a>
					</div>
					<div class="col-sm-12 col-md-4">
						<h3 class="footerText text-center hidden-md hidden-lg">ANUNCIO HABITUAL</h3>
						<img src="{{asset('images/habitual-01.png')}}" class="pubType center-block">
						
						<a href="{{ URL::to('usuario/publicaciones/mis-publicaciones/habitual') }}" class="btn btn-primary pubBtn footerText hidden-md hidden-lg">Ver publicación</a>
					</div>
					<div class="col-sm-12 col-md-4">
						<h3 class="footerText text-center hidden-md hidden-lg">ANUNCIO CASUAL</h3>
						<img src="{{asset('images/casual-01.png')}}" class="pubType center-block">
				
						<a href="{{ URL::to('usuario/publicaciones/mis-publicaciones/casual') }}" class="btn btn-primary pubBtn footerText hidden-md hidden-lg">Ver publicación</a>
					</div>
				</div>
				<div class="col-xs-12 pubBottonsCont">
					<div class="col-sm-12 col-md-4 formulario visible-md visible-lg" >
						<a href="{{ URL::to('usuario/publicaciones/mis-publicaciones/lider') }}" class="btn btn-primary footerText" style="width:100%;">Ver publicación</a>
					</div>
					<div class="col-sm-12 col-md-4 formulario visible-md visible-lg" >
					<a href="{{ URL::to('usuario/publicaciones/mis-publicaciones/habitual') }}" class="btn btn-primary footerText" style="width:100%;">Ver publicación</a>

					</div>
					<div class="col-sm-12 col-md-4 formulario visible-md visible-lg" >
					<a href="{{ URL::to('usuario/publicaciones/mis-publicaciones/casual') }}" class="btn btn-primary footerText" style="width:100%;">Ver publicación</a>

					</div>
				</div>
			</div>
			@endif
		</div>
	</div>
</div>
<div class="modal fade" id="modalElimUserPub" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Eliminar publicación</h4>
			</div>
			<div class="modal-body">
				<div class="alert responseDanger">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<p class="textoPromedio responseDanger-text"></p>
				</div>
				<p class="textoPromedio">¿Seguro desea eliminar esta publicación?, esta acción es irreversible</p>
			</div>
			<div class="modal-footer">
				<img src="{{ asset('images/loading.gif') }}" class="miniLoader hidden">
				<button type="button" class="btn btn-danger btnElimPublicacion btn-modal-elim">Eliminar</button>
				<button type="button" class="btn btn-default btn-dimiss btn-close-modal btn-modal-elim" data-dismiss="modal">Cerrar</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
@stop


@section('postscript')
    {{ HTML::style('js/datatables/dataTables.bootstrap.css') }}
    {{ HTML::script('js/datatables/jquery.dataTables.min.js') }}
    {{ HTML::script('js/datatables/dataTables.bootstrap.min.js') }}
    <script>
      $(function () {
        $('#tablesorter').DataTable({
          "paging": true,
          "lengthChange": true,
          "searching": true,
          "ordering": true,
          "info": true,
          "autoWidth": false
        });
      });
    </script>

@stop