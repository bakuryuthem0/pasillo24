@extends('main')
@section('content')
<div class="container contenedorUnico">
	<div class="row">
		<div class="col-xs-12 contAnaranjado">
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
			<div class="responseDanger">

			</div>
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
					<table class="table table-striped table-hover table-list-search">
						<thead>
							<tr>
								<th class="textoMedio noMovilMin">Título de la publicación</th>
								<th class="textoMedio noMovilMin">Ubicación</th>
								<th class="textoMedio noMovil">Fecha de inicio</th>
								<th class="textoMedio noMovil">Fecha de fin</th>
								<th class="textoMedio">Monto</th>
								<th class="textoMedio">Ver publicación</th>
								<th class="textoMedio">Transacción</th>
								<th class="textoMedio">Datos del usuario</th>
								<th class="textoMedio">Aprobar Pago</th>
								<th class="textoMedio">Rechazar Pago</th>
							</tr>
						</thead>
						<tbody>
							@foreach($publicaciones as $publicacion)
							<tr id="pub_{{ $publicacion->id }}">
								<td class="textoMedio noMovilMin">{{ $publicacion->titulo }}</td>
								<td class="textoMedio noMovilMin">
									@if($publicacion->ubicacion == "Ambos")
										Categorias/Principal
									@else
										{{ $publicacion->ubicacion }}
									@endif
								</td>
								<td class="textoMedio noMovil">{{ date('d/m/Y',strtotime($publicacion->fechIni)) }}</td>
								<td class="textoMedio noMovil">{{ date('d/m/Y',strtotime($publicacion->fechFin)) }}</td>
								<td class="textoMedio">{{ $publicacion->monto.' Bs.' }}</td>
								<td class="textoMedio"><a href="{{ URL::to('publicacion/lider/'.base64_encode($publicacion->id)) }}" target="_blank" class="btn btn-primary btn-xs">Ver</a></td>
								<td class="textoMedio">
									<button class="btn btn-warning btn-xs verTrans" data-toggle="modal" data-target="#showTransData" value="{{ $publicacion->id }}">	Ver
									</button>
									<input type="hidden" class="numtrans-{{ $publicacion->id }}" value="{{ $publicacion->num_trans }}">
									<input type="hidden" class="bank-{{ $publicacion->id }}" value="{{ $publicacion->banco }}">
									<input type="hidden" class="bankEx-{{ $publicacion->id }}" value="{{ $publicacion->banco_ext }}">
									<input type="hidden" class="fech-{{ $publicacion->id }}" value="{{ $publicacion->fech_trans }}">
								</td>
								<td class="textoMedio noMovil">
									<button class="btn btn-primary btn-xs ver" data-toggle="modal" data-target="#showUserData" value="{{ $publicacion->id }}">	Ver
									</button>
									
								</td>
								<input type="hidden" class="username-{{ $publicacion->id }}" value="{{ $publicacion->username }}">
								<input type="hidden" class="name-{{ $publicacion->id }}" value="{{ $publicacion->name.' '.$publicacion->lastname }}">
								<input type="hidden" class="email-{{ $publicacion->id }}" value="{{ $publicacion->email }}">
								<input type="hidden" class="phone-{{ $publicacion->id }}" value="{{ $publicacion->phone }}">
								<input type="hidden" class="pagWeb-{{ $publicacion->id }}" value="{{ $publicacion->pag_web }}">
								<input type="hidden" class="carnet-{{ $publicacion->id }}" value="{{ $publicacion->id_carnet }}">
								<input type="hidden" class="nit-{{ $publicacion->id }}" value="{{ $publicacion->nit }}">
								<form method="post" action="{{ URL::to('administrador/pagos/confirmar') }}">
								<td class="textoMedio">
									@if(Auth::user()['role'] == 'Administrador')
										<button class="btn btn-success btn-xs btnAprovar btn-do-disable" name="id" value="{{ $publicacion->id }}" >Aprobar</button>
									@endif
								</td>
								</form>
								<td class="textoMedio">
									<button class="btn btn-danger btn-xs btnCancelar btn-do-disable" value="{{ $publicacion->id }}" data-toggle="modal" href="#eliminar-publicacion">Rechazar</button>	
								</td>
							</tr>
							@endforeach
						</tbody>
					</table>
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
				<table class="table table-striped table-hover table-list-search">
					<thead>
						<tr>
							<th class="textoMedio noMovilMin">Título de la publicación</th>
							<th class="textoMedio noMovil">Categoría</th>
							<th class="textoMedio noMovilMin">Ubicación</th>
							<th class="textoMedio noMovil">Fecha de inicio</th>
							<th class="textoMedio noMovil">Fecha de fin</th>
							<th class="textoMedio">Monto</th>
							<th class="textoMedio">Ver Publicación</th>
							<th class="textoMedio">Transacción</th>
							<th class="textoMedio">Datos del usuario</th>
							<th class="textoMedio">Aprobar Pago</th>
							<th class="textoMedio">Rechazar Pago</th>
						</tr>
					</thead>
					<tbody>
						@foreach($publicaciones as $publicacion)
						<tr id="pub_{{ $publicacion->id }}">
							<td class="textoMedio noMovilMin">{{ $publicacion->titulo }}</td>
							<td class="textoMedio noMovil">{{ $publicacion->categoria }}</td>
							<td class="textoMedio noMovilMin">
								@if($publicacion->ubicacion == "Ambos")
										Categorias/Principal
									@else
										{{ $publicacion->ubicacion }}
									@endif
							</td>
							<td class="textoMedio noMovil">@if($publicacion->fechIni == '0000-00-00') Sin especificar @else {{ date('d/m/Y',strtotime($publicacion->fechIni)) }}@endif</td>
							<td class="textoMedio noMovil">@if($publicacion->fechFin == '0000-00-00') Sin especificar @else{{ date('d/m/Y',strtotime($publicacion->fechFin)) }}@endif</td>
							<td class="textoMedio">{{ $publicacion->monto.' Bs.' }}</td>
							<td class="textoMedio"><a href="{{ URL::to('publicacion/habitual/'.base64_encode($publicacion->id)) }}" target="_blank" class="btn btn-primary btn-xs">Ver</a></td>
							<td class="textoMedio">
								<button class="btn btn-warning btn-xs verTrans" data-toggle="modal" data-target="#showTransData" value="{{ $publicacion->id }}">	Ver
								</button>
								<input type="hidden" class="numtrans-{{ $publicacion->id }}" value="{{ $publicacion->num_trans }}">
								<input type="hidden" class="bank-{{ $publicacion->id }}" value="{{ $publicacion->banco }}">
								<input type="hidden" class="bankEx-{{ $publicacion->id }}" value="{{ $publicacion->banco_ext }}">
								<input type="hidden" class="fech-{{ $publicacion->id }}" value="{{ $publicacion->fech_trans }}">
							</td>
							<td class="textoMedio noMovil"><button class="btn btn-primary btn-xs ver" data-toggle="modal" data-target="#showUserData" value="{{ $publicacion->id }}">Ver</button></td>
								<input type="hidden" class="username-{{ $publicacion->id }}" value="{{ $publicacion->username }}">
								<input type="hidden" class="name-{{ $publicacion->id }}" value="{{ $publicacion->name.' '.$publicacion->lastname }}">
								<input type="hidden" class="email-{{ $publicacion->id }}" value="{{ $publicacion->email }}">
								<input type="hidden" class="phone-{{ $publicacion->id }}" value="{{ $publicacion->phone }}">
								<input type="hidden" class="pagWeb-{{ $publicacion->id }}" value="@if(!empty($publicacion->pag_web_hab)){{ $publicacion->pag_web_hab }} @else {{ $publicacion->pag_web }} @endif">
								<input type="hidden" class="carnet-{{ $publicacion->id }}" value="{{ $publicacion->id_carnet }}">
								<input type="hidden" class="nit-{{ $publicacion->id }}" value="{{ $publicacion->nit }}">
							<form method="post" action="{{ URL::to('administrador/pagos/confirmar') }}">
							<td class="textoMedio">
								@if(Auth::user()['role'] == 'Administrador')
								<button class="btn btn-success btnAprovar btn-xs btn-do-disable" name="id" value="{{ $publicacion->id }}" >Aprobar</button>
								@endif
							</td>
							</form>
							
							<td class="textoMedio">
								<button class="btn btn-danger btnCancelar btn-xs btn-do-disable" value="{{ $publicacion->id }}" data-toggle="modal" href="#eliminar-publicacion">Rechazar</button>	
							</td>
							
							
						</tr>
						@endforeach
					</tbody>
				</table>
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
				<table class="table table-striped table-hover table-list-search">
					<thead>
						<tr>
							<th class="textoMedio noMovilMin">Título de la publicación</th>
							<th class="textoMedio noMovil">Categoría</th>
							<th class="textoMedio noMovilMin">Ubicación</th>
							<th class="textoMedio noMovil">Fecha de inicio</th>
							<th class="textoMedio noMovil">Fecha de fin</th>
							<th class="textoMedio noMovil">Ver publicación</th>
							<th class="textoMedio">Datos del usuario</th>
							<th class="textoMedio">Aprobar Pago</th>
							<th class="textoMedio">Rechazar Pago</th>
						</tr>
					</thead>
					<tbody>
						@foreach($publicaciones as $publicacion)
						<tr id="pub_{{ $publicacion->id }}">
							<td class="textoMedio noMovilMin">{{ $publicacion->titulo }}</td>
							<td class="textoMedio noMovil">{{ $publicacion->categoria_desc}}</td>
							<td class="textoMedio noMovilMin">
								@if($publicacion->ubicacion == "Ambos")
										Categorias/Principal
									@else
										{{ $publicacion->ubicacion }}
									@endif
							</td>
							<td class="textoMedio noMovil">@if($publicacion->fechIni == '0000-00-00') Sin especificar @else {{ date('d/m/Y',strtotime($publicacion->fechIni)) }}@endif</td>
							<td class="textoMedio noMovil">@if($publicacion->fechFin == '0000-00-00') Sin especificar @else{{ date('d/m/Y',strtotime($publicacion->fechFin)) }}@endif</td>
							<td>
								<a target="_blank" class="btn btn-success btn-xs" href="{{ URL::to('publicacion/casual/'.base64_encode($publicacion->id)) }}">
									Ver
								</a>

							</td>
							<td class="textoMedio noMovil"><button class="btn btn-primary btn-xs ver" data-toggle="modal" data-target="#showUserData" value="{{ $publicacion->id }}">Ver</button></td>
								<input type="hidden" class="username-{{ $publicacion->id }}" value="{{ $publicacion->username }}">
								<input type="hidden" class="name-{{ $publicacion->id }}" value="{{ $publicacion->name.' '.$publicacion->lastname }}">
								<input type="hidden" class="email-{{ $publicacion->id }}" value="{{ $publicacion->email }}">
								<input type="hidden" class="phone-{{ $publicacion->id }}" value="{{ $publicacion->phone }}">
								<input type="hidden" class="pagWeb-{{ $publicacion->id }}" value="{{ $publicacion->pag_web }}">
								<input type="hidden" class="carnet-{{ $publicacion->id }}" value="{{ $publicacion->id_carnet }}">
								<input type="hidden" class="nit-{{ $publicacion->id }}" value="{{ $publicacion->nit }}">
							<form method="post" action="{{ URL::to('administrador/pagos/confirmar') }}">
							<td class="textoMedio">
								@if(Auth::user()['role'] == 'Administrador')
								<button class="btn btn-success btnAprovar btn-xs btn-do-disable" name="id" value="{{ $publicacion->id }}" >Aprobar</button>
								@endif
							</td>
							</form>
							
							<td class="textoMedio">
								<button class="btn btn-danger btnCancelar btn-xs btn-do-disable" value="{{ $publicacion->id }}" data-toggle="modal" href="#eliminar-publicacion">Rechazar</button>	
							</td>
							
							
						</tr>
						@endforeach
					</tbody>
				</table>
				@endif
			@else
				<div class="alert alert-success">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<p class="textoPromedio">No hay nuevas publicaciones</p>
				</div>
			@endif
		@else
		<div class="col-xs-12" style="display: block;
margin: 0 auto;
float: none;">
			<div class="col-xs-4 typePub imgLiderUp">
				<h3 class="footerText" style="margin-bottom:1em;">ANUNCIO LÍDER</h3>
				<img src="{{asset('images/lider-01.png')}}" class="pubType">
			
				<a href="{{ URL::to('administrador/pagos/lider') }}" class="btn btn-primary footerText " style="margin-top:2em;width:100%;">Ver publicaciones</a>
			</div>
			<div class="col-xs-4 typePub imgLiderUp">
				<h3 class="footerText" style="margin-bottom:1em;">ANUNCIO HABITUAL</h3>
				<img src="{{asset('images/habitual-01.png')}}" class="pubType">
				
				<a href="{{ URL::to('administrador/pagos/habitual') }}" class="btn btn-primary footerText " style="margin-top:2em;width:100%;">Ver publicaciones</a>
			</div>
			<div class="col-xs-4 typePub imgLiderUp">
				<h3 class="footerText" style="margin-bottom:1em;">ANUNCIO CASUAL</h3>
				<img src="{{asset('images/habitual-01.png')}}" class="pubType">
				
				<a href="{{ URL::to('administrador/pagos/casual') }}" class="btn btn-primary footerText " style="margin-top:2em;width:100%;">Ver publicaciones</a>
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
				<p class="textoPromedio">Motivo</p>
				<div class="alert responseDanger">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<p class="textoPromedio text-centered responseDanger-text"></p>
				</div>
				<textarea class="form-control motivo"></textarea>
			</div>
			<div class="modal-footer">
				<img src="{{ asset('images/loading.gif') }}" class="miniLoader">
				<button type="button" class="btn btn-danger send-elim" >Enviar</button>
        		<button type="button" class="btn btn-success btn-dimiss hidden" data-dismiss="modal">Aceptar</button>

			</div>
		</div>
	</div>
</div>
@stop