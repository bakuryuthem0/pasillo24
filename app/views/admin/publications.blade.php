@extends('main')
@section('content')
<div class="container contenedorUnico">
	<div class="row">
		<div class="col-xs-12 contAnaranjado">
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
			<div class="col-xs-12">
				<legend>Mis Publicaciones</legend>
			</div>
			@if(isset($type))
				@if($type == 'lider')
				<div class="col-xs-12 table-responsive">
					<table class="tablesorter table table-striped table-hover">
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
									Ver Publicación
								</th>
								<th class="textoMedio">
									Ver datos del usuario
								</th>
								<th class="textoMedio">
									Eliminar publicación
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
									@if($publication->ubicacion == "Ambos")
										Categorias/Principal
									@else
										{{ $publication->ubicacion }}
									@endif
								</td>
								<td class="textoMedio">
									{{ $publication->nombre }}
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
									<a target="_blank" href="{{URL::to('publicacion/lider/'.base64_encode($publication->id)) }}" class="btn btn-xs btn-primary" style="width: 100%;">Ver</a>
								</td>
								<td class="textoMedio">
									<button class="btn btn-success btn-xs ver" data-toggle="modal" data-target="#showUserData" value="{{ $publication->id }}" style="width:100%;">Ver</button>
									<input type="hidden" class="valores" value='{{ json_encode(array('username' => $publication->username,'name' => $publication->name.' '.$publication->lastname,'email' => $publication->email,'phone' => $publication->phone,'pagWg' => $publication->pag_web,'carnet' => $publication->id_carnet,'nit' => $publication->nit)) }}'>
								</td>
								<td class="textoMedio">
									<button style="width: 100%;" class="btn btn-danger btn-xs btn-elim-pub" data-url="{{ URL::to('administrador/publicacion/eliminar-publicacion/enviar') }}" value="{{ $publication->id }}" data-toggle="modal" data-target="#modalElimPub"><span class="fa fa-close"></span> Eliminar</button>
								</td>
							</tr>
							@endforeach
						</tbody>
					</table>
				</div>
				@elseif($type == 'habitual')
				<div class="col-xs-12 table-responsive">
					<table class="tablesorter table table-striped table-hover">
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
									Ver
								</th>
								<th class="textoMedio">
									Ver datos del usuario
								</th>
								<th class="textoMedio">
									Eliminar publicación
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
										Categorias/Principal
										@else
											{{ $publication->ubicacion }} 
										@endif
									@endif
								</td>
								<td class="textoMedio">
									{{ $publication->categoria }}
								</td>
								<td class="textoMedio">
									{{ $publication->precio.' '.ucfirst(strtolower($publication->moneda)) }}
								</td>
								<td class="textoMedio">
									@if($publication->fechIni == "0000-00-00" && $publication->fechIniNormal == "0000-00-00")
										Sin aprobar
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
										Sin aprobar
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
									<a target="_blank" href="{{URL::to('publicacion/lider/'.base64_encode($publication->id))}}" class="btn btn-primary btn-xs">Ver</a>
								</td>
								<td class="textoMedio">
									<button class="btn btn-success btn-xs ver" data-toggle="modal" data-target="#showUserData" value="{{ $publication->id }}" style="width:100%;">Ver</button>
									<input type="hidden" class="valores" value='{{ json_encode(array('username' => $publication->username,'name' => $publication->name.' '.$publication->lastname,'email' => $publication->email,'phone' => $publication->phone,'pagWg' => $publication->pag_web,'carnet' => $publication->id_carnet,'nit' => $publication->nit)) }}'>
								</td>
									
								<td class="textoMedio">
									<button style="width: 100%;" class="btn btn-danger btn-xs btn-elim-pub" value="{{ $publication->id }}" data-toggle="modal" data-target="#modalElimPub"><span class="fa fa-close"></span> Eliminar</button>
								</td>
							</tr>
							@endforeach
						</tbody>
					</table>
				</div>
				@elseif($type == 'casual')
				<div class="col-xs-12 table-responsive">
					<table class="tablesorter table table-striped table-hover">
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
									Fecha de Inicio
								</th>
								<th class="textoMedio">
									Fecha de Fin
								</th>
								<th class="textoMedio">
									Ver
								</th>
								<th class="textoMedio">
									Ver datos del usuario
								</th>
								<th class="textoMedio">
									Eliminar publicación
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
									{{ $publication->ubicacion }}
									@endif
								</td>
								<td class="textoMedio">
									{{ $publication->nombre }}
								</td>
								
								<td class="textoMedio">
									@if($publication->fechIni == "0000-00-00")
									Sin aprobar
									@else
									 {{ date('d/m/Y',strtotime($publication->fechIni)) }}
									@endif
								</td>
								<td class="textoMedio">
									@if($publication->fechIni == "0000-00-00")
										Sin aprobar
									@else
										@if($publication->fechFin < date('Y-m-d') ) Publicación expirada @else {{ date('d/m/Y',strtotime($publication->fechFin)) }} @endif
									@endif
								</td>
								<td class="textoMedio">
									<a target="_blank" href="{{URL::to('publicacion/lider/'.base64_encode($publication->id))}}" class="btn btn-primary btn-xs">Ver</a>
								</td>
								<td class="textoMedio">
									<button class="btn btn-success btn-xs ver" data-toggle="modal" data-target="#showUserData" value="{{ $publication->id }}" style="width:100%;">Ver</button>
									<input type="hidden" class="valores" value='{{ json_encode(array('username' => $publication->username,'name' => $publication->name.' '.$publication->lastname,'email' => $publication->email,'phone' => $publication->phone,'pagWg' => $publication->pag_web,'carnet' => $publication->id_carnet,'nit' => $publication->nit)) }}'>
								</td>
									
								<td class="textoMedio">
									<button style="width: 100%;" class="btn btn-danger btn-xs btn-elim-pub" value="{{ $publication->id }}" data-toggle="modal" data-target="#modalElimPub"><span class="fa fa-close"></span> Eliminar</button>
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
						
							<a href="{{ URL::to('administrador/publicacion/lider') }}" class="btn btn-primary pubBtn footerText hidden-md hidden-lg">Ver publicación</a>
						</div>
						<div class="col-sm-12 col-md-4">
							<h3 class="footerText text-center hidden-md hidden-lg">ANUNCIO HABITUAL</h3>
							<img src="{{asset('images/habitual-01.png')}}" class="pubType center-block">
							
							<a href="{{ URL::to('administrador/publicacion/habitual') }}" class="btn btn-primary pubBtn footerText hidden-md hidden-lg">Ver publicación</a>
						</div>
						<div class="col-sm-12 col-md-4">
							<h3 class="footerText text-center hidden-md hidden-lg">ANUNCIO CASUAL</h3>
							<img src="{{asset('images/casual-01.png')}}" class="pubType center-block">
					
							<a href="{{ URL::to('administrador/publicacion/casual') }}" class="btn btn-primary pubBtn footerText hidden-md hidden-lg">Ver publicación</a>
						</div>
					</div>
					<div class="col-xs-12 pubBottonsCont">
						<div class="col-sm-12 col-md-4 formulario visible-md visible-lg" >
							<a href="{{ URL::to('administrador/publicacion/lider') }}" class="btn btn-primary footerText" style="width:100%;">Ver publicación</a>
						</div>
						<div class="col-sm-12 col-md-4 formulario visible-md visible-lg" >
						<a href="{{ URL::to('administrador/publicacion/habitual') }}" class="btn btn-primary footerText" style="width:100%;">Ver publicación</a>

						</div>
						<div class="col-sm-12 col-md-4 formulario visible-md visible-lg" >
						<a href="{{ URL::to('administrador/publicacion/casual') }}" class="btn btn-primary footerText" style="width:100%;">Ver publicación</a>

						</div>
					</div>
				</div>
				@endif

		</div>
	</div>
</div>

<div class="modal fade" id="modalElimPub" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Eliminar publicación</h4>
      </div>
      <div class="modal-body">
      	<div class="alert responseDanger">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			<p class=""></p>
		</div>
      	<div class="alert alert-warning">
			<p class="textoPromedio">Advertencia. ¿Está seguro que desea continuar?, estos cambios son irreversibles</p>
      	</div>
		<textarea class="form-control motivo" placeholder="Motivo"></textarea>      	    
      </div>
      <div class="modal-footer">
      	<img src="{{ asset('images/loading.gif') }}" class="miniLoader hidden">
        <button type="button" class="btn btn-danger send-elim btn-modal-elim" >Eliminar</button>
        <button type="button" class="btn btn-default btn-dimiss btn-close-modal btn-modal-elim" data-dismiss="modal">Cerrar</button>

      </div>
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
					<p class="textoPromedio"><label>Carnet de identificación</label></p>
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
@stop


@section('postscript')
    {{ HTML::style('js/datatables/dataTables.bootstrap.css') }}
    {{ HTML::script('js/datatables/jquery.dataTables.min.js') }}
    {{ HTML::script('js/datatables/dataTables.bootstrap.min.js') }}
    <script>
      $(function () {
        $('.tablesorter').DataTable({
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