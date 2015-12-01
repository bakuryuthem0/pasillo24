@extends('main')

@section('content')

<div class="container contenedorUnico">
	<div class="col-xs-12 ">

		<div class="col-xs-12 contAnaranjado">
			<legend>Comentarios recibidos</legend>
			<div class="table-responsive">
				<table class="table table-hover">
					<thead>
						<tr class="textoPromedio">
							<th>Publicaci&oacuten</th>
							<th>Comentario</th>
							<th>Creado el</th>
							<th>Respuesta(s)</th>
						</tr>
					</thead>
					<tbody>
						@foreach($recividos as $comentario)
						<tr class="textoPromedio">
							<td>{{ $comentario->titulo }}</td>
							<td>{{ $comentario->comentario }}</td>
							<td>{{ date('d-m-Y H:i:s',strtotime($comentario->created_at)) }}</td>
							@if($comentario->deleted == 1)
							<td><div class="alert alert-danger">
								<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
								<p class="textoPromedio">El comentario fue borrado</p>
							</div></td>
							@else
							<td>
								<button class="btn btn-primary btn-responder" data-toggle="modal" data-target="#myComment" data-pub-id="{{ $comentario->pub_id }}" value="{{ $comentario->id }}">Responder</button>
							</td>
							@endif
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>

		<div class="col-xs-12 contAnaranjado">
			<legend>Comentarios realizados</legend>
			<div class="table-responsive">
				<table class="table table-hover">
					<thead>
						<tr class="textoPromedio">
							<th>Publicaci&oacuten</th>
							<th>Comentario</th>
							<th>Creado el</th>
							<th>Respuesta(s)</th>
						</tr>
					</thead>
					<tbody>
						@foreach($hechos as $respuesta)
						<tr class="textoPromedio">
							<td>{{ $respuesta->titulo }}</td>
							<td>{{ $respuesta->comentario }}</td>
							<td>{{ date('d-m-Y H:i:s',strtotime($respuesta->created_at)) }}</td>
							@if($respuesta->deleted == 1)
							<td><div class="alert alert-danger">
								<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
								<p class="textoPromedio">El comentario fue borrado</p>
							</div></td>
							@else
							<td>
								@if($respuesta->respuesta)
									@if(strlen($respuesta->respuesta) > 10)
										{{ substr($respuesta->respuesta,0,10) }}... <a class="change-response-text" data-toggle="modal" data-txt="{{ $respuesta->respuesta }}" href='#modal-id'>Leer Mas</a>
									@else
										{{$respuesta->respuesta}}
									@endif
								@else
									Sin Respuesta
								@endif
							</td>
							@endif
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="myComment" tabindex="-1" role="dialog" aria-labelledby="modalForggo" aria-hidden="true">
	<div class="forgotPass modal-dialog imgLiderUp">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<label for="respuesta" class="textoPromedio">Respuesta</label>

			</div>
				<div class="modal-body">
					<p class="textoPromedio">Introduzca su respuesta</p>

				</div>
				<div class="modal-footer " style="text-align:right;">
					<div class="alert responseDanger">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					</div>
					<textarea name="respuesta" class="form-control textoRespuesta" placeholder="Enviar respuesta"></textarea>
					<img src="{{ asset('images/loading.gif') }}" class="miniLoader">
					<button class="btn btn-success enviarRespuesta" style="margin-top:1em;margin-left:1em;" data-pub-id="" value="">Enviar</button>
				</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modal-id">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Respuesta</h4>
			</div>
			<div class="modal-body">
				<p class="response-text textoPromedio"></p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
			</div>
		</div>
	</div>
</div>

@stop