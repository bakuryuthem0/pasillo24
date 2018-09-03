@extends('main')
@section('content')
<div class="container contenedorUnico">
	<div class="row">
		<div class="col-xs-12 contAnaranjado ">
			@if(Session::has('success'))
				<div class="alert alert-success">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<p class="textoPromedio">{{ Session::get('success') }}</p>
				</div>
			@elseif(Session::has('error'))
				<div class="alert alert-danger">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<p class="textoPromedio">{{ Session::get('error') }}</p>
				</div>
			@endif
			<legend>Administrar pagos</legend>
			@if(isset($type))
			@if(!is_null($publicaciones) && !empty($publicaciones))
				@if($type == "lider")
				<form action="#" method="get">
					<div class="input-group">
						<!-- USE TWITTER TYPEAHEAD JSON WITH API TO SEARCH -->
						<input class="form-control" id="buscar-usuario" name="q" placeholder="Busqueda general" required>
						<span class="input-group-addon">
							<i class="glyphicon glyphicon-search"></i>
						</span>
					</div>
				</form>
				<div class="table-responsive">
					<table class="table table-striped table-hover table-list-search">
						<thead>
							<tr>
								<th class="">Título de la publicación</th>
								<th class="">Ubicación</th>
								<th class="">Fecha de inicio</th>
								<th class="">Fecha de fin</th>
								<th class="">Monto</th>
								<th class="">Ver publicación</th>
								<th class="">Transacción</th>
								<th class="">Datos del usuario</th>
								<th class="">Aprobar Pago</th>
								<th class="">Rechazar Pago</th>
							</tr>
						</thead>
						<tbody>
							@foreach($publicaciones as $publicacion)
							<tr id="pub_{{ $publicacion->id }}">
								<td class="">{{ $publicacion->titulo }}</td>
								<td class="">
									@if($publicacion->ubicacion == "Ambos")
										Categorias/Principal
									@else
										{{ $publicacion->ubicacion }}
									@endif
								</td>
								<td class="">{{ date('d/m/Y',strtotime($publicacion->fechIni)) }}</td>
								<td class="">{{ date('d/m/Y',strtotime($publicacion->fechFin)) }}</td>
								<td class="">{{ $publicacion->monto.' Bs.' }}</td>
								<td class="">
									<a href="{{ URL::to('publicacion/lider/'.base64_encode($publicacion->id)) }}" target="_blank" class="btn btn-primary btn-xs">Ver</a>
								</td>
								<td class="">
									<button class="btn btn-warning btn-xs verTrans" data-toggle="modal" data-target="#showTransData" value="{{ $publicacion->id }}" data-json="">	Ver
									</button>
									<input type="hidden" class="valores" value='{{ json_encode(array('num' => $publicacion->num_trans, 'bank' => $publicacion->banco, 'bankEx' => $publicacion->banco_ext, 'fech' => $publicacion->fech_trans)) }}'>
								</td>
								<td class=" ">
									<button class="btn btn-primary btn-xs ver" data-toggle="modal" data-target="#showUserData" value="{{ $publicacion->id }}">	Ver
									</button>
									<input type="hidden" class="valores" value='{{ json_encode(array('username' => $publicacion->username,'name' => $publicacion->name.' '.$publicacion->lastname,'email' => $publicacion->email,'phone' => $publicacion->phone,'pagWg' => $publicacion->pag_web,'carnet' => $publicacion->id_carnet,'nit' => $publicacion->nit)) }}'>
								</td>
								<td class="">
									@if(Auth::user()->role == 'Administrador')
										<button class="btn btn-success btn-xs btnAprovar" name="id" value="{{ $publicacion->id }}" data-toggle="modal" href="#aprobar-publicacion">Aprobar</button>
									@endif
								</td>
								<td class="">
									<button class="btn btn-danger btn-xs btn-reject" data-url="{{ URL::to('administrador/pagos/cancelar') }}" value="{{ $publicacion->id }}" data-toggle="modal" href="#eliminar-publicacion">Rechazar</button>	
								</td>
							</tr>
							@endforeach
						</tbody>
					</table>
				</div>
				@elseif($type == "habitual")
				<form action="#" method="get">
					<div class="input-group">
						<!-- USE TWITTER TYPEAHEAD JSON WITH API TO SEARCH -->
						<input class="form-control" id="buscar-usuario" name="q" placeholder="Busqueda general" required>
						<span class="input-group-addon">
							<i class="glyphicon glyphicon-search"></i>
						</span>
					</div>
				</form>
				<div class="table-responsive">
					<table class="table table-striped table-hover table-list-search">
						<thead>
							<tr>
								<th class="">Título de la publicación</th>
								<th class="">Categoría</th>
								<th class="">Ubicación</th>
								<th class="">Fecha de inicio</th>
								<th class="">Fecha de fin</th>
								<th class="">Monto</th>
								<th class="">Ver Publicación</th>
								<th class="">Transacción</th>
								<th class="">Datos del usuario</th>
								<th class="">Aprobar Pago</th>
								<th class="">Rechazar Pago</th>
							</tr>
						</thead>
						<tbody>
							@foreach($publicaciones as $publicacion)
							<tr id="pub_{{ $publicacion->id }}">
								<td class="">{{ $publicacion->titulo }}</td>
								<td class="">{{ $publicacion->categoria }}</td>
								<td class="">
									@if($publicacion->ubicacion == "Ambos")
											Categorias/Principal
										@else
											{{ $publicacion->ubicacion }}
										@endif
								</td>
								<td class="">@if($publicacion->fechIni == '0000-00-00') Sin especificar @else {{ date('d/m/Y',strtotime($publicacion->fechIni)) }}@endif</td>
								<td class="">@if($publicacion->fechFin == '0000-00-00') Sin especificar @else{{ date('d/m/Y',strtotime($publicacion->fechFin)) }}@endif</td>
								<td class="">{{ $publicacion->monto.' Bs.' }}</td>
								<td class=""><a href="{{ URL::to('publicacion/habitual/'.base64_encode($publicacion->id)) }}" target="_blank" class="btn btn-primary btn-xs">Ver</a></td>
								<td class="">
									<button class="btn btn-warning btn-xs verTrans" data-toggle="modal" data-target="#showTransData" value="{{ $publicacion->id }}">	Ver
									</button>
									<input type="hidden" class="valores" value='{{ json_encode(array('num' => $publicacion->num_trans, 'bank' => $publicacion->banco, 'bankEx' => $publicacion->banco_ext, 'fech' => $publicacion->fech_trans)) }}'>
								</td>
								<td class=""><button class="btn btn-primary btn-xs ver" data-toggle="modal" data-target="#showUserData" value="{{ $publicacion->id }}">Ver</button>
									<input type="hidden" class="valores" value='{{ json_encode(array('username' => $publicacion->username,'name' => $publicacion->name.' '.$publicacion->lastname,'email' => $publicacion->email,'phone' => $publicacion->phone,'pagWg' => $publicacion->pag_web,'carnet' => $publicacion->id_carnet,'nit' => $publicacion->nit)) }}'>
								</td>
								<td class="">
									@if(Auth::user()['role'] == 'Administrador')
									<button class="btn btn-success btnAprovar btn-xs" name="id" value="{{ $publicacion->id }}" data-toggle="modal" href="#aprobar-publicacion">Aprobar</button>
									@endif
								</td>
								<td class="">
									<button class="btn btn-danger btn-xs btn-reject" data-url="{{ URL::to('administrador/pagos/cancelar') }}" value="{{ $publicacion->id }}" data-toggle="modal" href="#eliminar-publicacion">Rechazar</button>	
								</td>
								
								
							</tr>
							@endforeach
						</tbody>
					</table>
				</div>
				@elseif($type == "casual")
				<form action="#" method="get">
					<div class="input-group">
						<!-- USE TWITTER TYPEAHEAD JSON WITH API TO SEARCH -->
						<input class="form-control" id="buscar-usuario" name="q" placeholder="Busqueda general" required>
						<span class="input-group-addon">
							<i class="glyphicon glyphicon-search"></i>
						</span>
					</div>
				</form>
				<div class="table-responsive">
					<table class="table table-striped table-hover table-list-search">
						<thead>
							<tr>
								<th class="">Título de la publicación</th>
								<th class="">Categoría</th>
								<th class="">Ubicación</th>
								<th class="">Fecha de inicio</th>
								<th class="">Fecha de fin</th>
								<th class="">Ver publicación</th>
								<th class="">Datos del usuario</th>
								<th class="">Aprobar Pago</th>
								<th class="">Rechazar Pago</th>
							</tr>
						</thead>
						<tbody>
							@foreach($publicaciones as $publicacion)
							<tr id="pub_{{ $publicacion->id }}">
								<td class="">{{ $publicacion->titulo }}</td>
								<td class="">{{ $publicacion->categoria_desc}}</td>
								<td class="">
									@if($publicacion->ubicacion == "Ambos")
											Categorias/Principal
										@else
											{{ $publicacion->ubicacion }}
										@endif
								</td>
								<td class="">@if($publicacion->fechIni == '0000-00-00') Sin especificar @else {{ date('d/m/Y',strtotime($publicacion->fechIni)) }}@endif</td>
								<td class="">@if($publicacion->fechFin == '0000-00-00') Sin especificar @else{{ date('d/m/Y',strtotime($publicacion->fechFin)) }}@endif</td>
								<td>
									<a target="_blank" class="btn btn-success btn-xs" href="{{ URL::to('publicacion/casual/'.base64_encode($publicacion->id)) }}">
										Ver
									</a>

								</td>
								<td class="">
									<button class="btn btn-primary btn-xs ver" data-toggle="modal" data-target="#showUserData" value="{{ $publicacion->id }}">Ver</button></td>
									<input type="hidden" class="valores" value='{{ json_encode(array('num' => $publicacion->num_trans, 'bank' => $publicacion->banco, 'bankEx' => $publicacion->banco_ext, 'fech' => $publicacion->fech_trans)) }}'>

									
								<td class="">
									@if(Auth::user()['role'] == 'Administrador')
									<button class="btn btn-success btnAprovar btn-xs btn-do-disable" name="id" value="{{ $publicacion->id }}" data-toggle="modal" href="#aprobar-publicacion">Aprobar</button>
									@endif
								</td>
								<td class="">
									<button class="btn btn-danger btn-xs btn-reject" data-url="{{ URL::to('administrador/pagos/cancelar') }}" value="{{ $publicacion->id }}" data-toggle="modal" href="#eliminar-publicacion">Rechazar</button>	
								</td>
							</tr>
							@endforeach
						</tbody>
					</table>
				</div>
				@endif
			@else
				<div class="alert alert-success">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<p class="textoPromedio">No hay nuevas publicaciones</p>
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
					
						<a href="{{ URL::to('administrador/pagos/lider') }}" class="btn btn-primary pubBtn footerText hidden-md hidden-lg">Ver publicación</a>
					</div>
					<div class="col-sm-12 col-md-4">
						<h3 class="footerText text-center hidden-md hidden-lg">ANUNCIO HABITUAL</h3>
						<img src="{{asset('images/habitual-01.png')}}" class="pubType center-block">
						
						<a href="{{ URL::to('administrador/pagos/habitual') }}" class="btn btn-primary pubBtn footerText hidden-md hidden-lg">Ver publicación</a>
					</div>
					<div class="col-sm-12 col-md-4">
						<h3 class="footerText text-center hidden-md hidden-lg">ANUNCIO CASUAL</h3>
						<img src="{{asset('images/casual-01.png')}}" class="pubType center-block">
				
						<a href="{{ URL::to('administrador/pagos/casual') }}" class="btn btn-primary pubBtn footerText hidden-md hidden-lg">Ver publicación</a>
					</div>
				</div>
				<div class="col-xs-12 pubBottonsCont">
					<div class="col-sm-12 col-md-4 formulario visible-md visible-lg" >
						<a href="{{ URL::to('administrador/pagos/lider') }}" class="btn btn-primary footerText" style="width:100%;">Ver publicación</a>
					</div>
					<div class="col-sm-12 col-md-4 formulario visible-md visible-lg" >
					<a href="{{ URL::to('administrador/pagos/habitual') }}" class="btn btn-primary footerText" style="width:100%;">Ver publicación</a>

					</div>
					<div class="col-sm-12 col-md-4 formulario visible-md visible-lg" >
					<a href="{{ URL::to('administrador/pagos/casual') }}" class="btn btn-primary footerText" style="width:100%;">Ver publicación</a>

					</div>
				</div>
			</div>
		@endif
		</div>
	</div>
</div>
<div class="modal fade" id="showUserData" tabindex="-1" role="dialog" aria-labelledby="modalForggo" aria-hidden="true">
	<div class="forgotPass modal-dialog imgLiderUp">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel">Datos del usuario.</h4>
			</div>
			<div class="modal-body">
				<div class="col-xs-12" style="margin-top:3em;">
					<p class="textoPromedio"><label>Nombre de usuario</label></p>
					<p class="textoPromedio usernameModal">

					</p>
				</div>
				<div class="col-xs-12">
					<p class="textoPromedio"><label>Nombre y apellido</label></p>
					<p class="textoPromedio nameModal">

					</p>
				</div>
				<div class="col-xs-12">
					<p class="textoPromedio"><label>Email</label></p>
					<p class="textoPromedio emailModal">

					</p>
				</div>
				<div class="col-xs-12">
					<p class="textoPromedio"><label>Telefono</label></p>
					<p class="textoPromedio phoneModal">

					</p>
				</div>
				<div class="col-xs-12">
					<p class="textoPromedio"><label>Pagina web</label></p>
					<p class="textoPromedio pagWebModal">

					</p>
				</div>
				<div class="col-xs-12">
					<p class="textoPromedio"><label>Carnet de identidad</label></p>
					<p class="textoPromedio carnetModal">

					</p>
				</div>
				<div class="col-xs-12">
					<p class="textoPromedio"><label>NIT</label></p>
					<p class="textoPromedio nitModal">

					</p>
				</div>
			</div>
			<div class="modal-footer " style="text-align:center;">
				
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="showTransData" tabindex="-1" role="dialog" aria-labelledby="modalForggo" aria-hidden="true">
	<div class="forgotPass modal-dialog imgLiderUp">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel">Datos de la transacción.</h4>
			</div>
			<div class="modal-body">
				<div class="col-xs-12" style="margin-top:3em;">
					<p class="textoPromedio"><label>Numero de transacción</label></p>
					<p class="textoPromedio numModal">

					</p>
				</div>
				<div class="col-xs-12">
					<p class="textoPromedio"><label>Banco remitente</label></p>
					<p class="textoPromedio bankExlModal">

					</p>
				</div>
				<div class="col-xs-12">
					<p class="textoPromedio"><label>Banco de destino</label></p>
					<p class="textoPromedio bankModal">

					</p>
				</div>
				<div class="col-xs-12">
					<p class="textoPromedio"><label>Fecha de transacción</label></p>
					<p class="textoPromedio fechModal">

					</p>
				</div>
				
			</div>
			<div class="modal-footer " style="text-align:center;">
				
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="eliminar-publicacion" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Rechazar Publicación</h4>
			</div>
			<div class="modal-body">
				<div class="alert responseDanger">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<p></p>
				</div>
				<label>Motivo</label>
				<textarea class="form-control motivo" rows="3" placeholder="Introduzca el motivo de rechazo"></textarea>
			</div>
			<div class="modal-footer">
				<img src="{{ asset('images/loading.gif') }}" class="miniLoader hidden">
				<button type="button" class="btn btn-danger send-elim btn-modal-elim" >Enviar</button>
        		<button type="button" class="btn btn-default btn-dimiss btn-close-modal btn-modal-elim" data-dismiss="modal">Cerrar</button>

			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="aprobar-publicacion" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Aprobar Publicación</h4>
			</div>
			<div class="modal-body">
				<div class="alert responseDanger">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<p></p>
				</div>
				<p>¿Segudo resea aprobar esta publicación?</p>
			</div>
			<div class="modal-footer">
				<img src="{{ asset('images/loading.gif') }}" class="miniLoader hidden">
				<button type="button" class="btn btn-success send-aprov btn-modal-elim" >Enviar</button>
        		<button type="button" class="btn btn-default btn-dimiss btn-close-modal btn-modal-elim" data-dismiss="modal">Cerrar</button>

			</div>
		</div>
	</div>
</div>
@stop