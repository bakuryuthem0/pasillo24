@extends('main')

@section('content')

<div class="container contenedorUnico">
	<div class="row">
		<div class="col-xs-8 contAnaranjado textoPromedio" style="padding:2em;float:none;margin:0 auto;margin-top:2em;">
			@if(Session::has('danger'))
				<div class="alert alert-danger">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<p class="textoPromedio">{{ Session::get('danger') }}</p>
				</div>
			@endif
			<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
			  <div class="panel panel-default">
			    <div class="panel-heading" role="tab" id="headingOne">
			      <h4 class="panel-title">
			        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
			          Publicidad principal
			        </a>
			      </h4>
			    </div>
			    <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
			      <div class="panel-body">
			        <form method="POST" action="{{ URL::to('administrador/modificar-publicidad/top') }}" enctype="multipart/form-data">
			        	<p class="bg-info" style="padding:0.5em;">Se recomienda que la imagen tenga 2075x250 pixels</p>
			        	<br>
			        	<label for="pag_web">Pagina web</label>
			        	<input type="text" name="pag_web" class="form-control" value="http://">
			        	<br>
			        	<label for="imagen">Imagen</label>
			        	<input type="file" name="imagen">
			        	<br>
			        	<input type="submit" class="btn btn-success" value="Enviar">
			        </form>
			      </div>
			    </div>
			  </div>
			  <div class="panel panel-default">
			    <div class="panel-heading" role="tab" id="headingTwo">
			      <h4 class="panel-title">
			        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
			          Publicidad izquierda
			        </a>
			      </h4>
			    </div>
			    <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
			      <div class="panel-body">
			        <form method="POST" action="{{ URL::to('administrador/modificar-publicidad/izq') }}" enctype="multipart/form-data">
			        	<p class="bg-info" style="padding:0.5em;">Se recomienda que la imagen tenga 1010x250 pixels</p>
			        	<br>
			        	<label for="pag_web">Pagina web</label>
			        	<input type="text" name="pag_web" class="form-control" value="http://">
			        	<br>
			        	<label for="imagen">Imagen</label>
			        	<input type="file" name="imagen">
			        	<br>
			        	<input type="submit" class="btn btn-success" value="Enviar">
			        </form>
			      </div>
			    </div>
			  </div>
			  <div class="panel panel-default">
			    <div class="panel-heading" role="tab" id="headingThree">
			      <h4 class="panel-title">
			        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
			          Publicidad derecha
			        </a>
			      </h4>
			    </div>
			    <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
			      <div class="panel-body">
			        <form method="POST" action="{{ URL::to('administrador/modificar-publicidad/der') }}" enctype="multipart/form-data">
			        	<p class="bg-info" style="padding:0.5em;">Se recomienda que la imagen tenga 1010x250 pixels</p>
			        	<br>
			        	<label for="pag_web">Pagina web</label>
			        	<input type="text" name="pag_web" class="form-control" value="http://">
			        	<br>
			        	<label for="imagen">Imagen</label>
			        	<input type="file" name="imagen">
			        	<br>
			        	<input type="submit" class="btn btn-success" value="Enviar">
			        </form>
			      </div>
			    </div>
			  </div>
			</div>
		</div>

		<div  class="col-xs-8 contAnaranjado textoPromedio" style="padding:2em;float:none;margin:0 auto;margin-top:2em;">
			@if(Session::has('success'))
			<div class="alert alert-success">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				<p class="">{{ Session::get('success') }}</p>
			</div>
			@endif
			<table class="table table-hover">
					<tr>
						<th>Imagen</th>
						<th>Pagina Web</th>
						<th>Posición</th>
					</tr>
				<tbody>
			@foreach($publi as $p)
					<tr>
						<td>{{ $p->pag_web }}</td>
						<td>
							@if($p->pos == 'top')
								Superior
							@elseif($p->pos == 'izq')
								Izquierda
							@elseif($p->pos == 'der')
								Derecha
							@endif
						</td>
						<td>
							@if($p->activo == 1)
								<button class="btn btn-warning btn-xs refresh active" data-status="{{ $p->activo }}" value="{{ $p->id }}">
									Desactivar
								</button>
							@else
								<button class="btn btn-warning btn-xs refresh" data-status="{{ $p->activo }}" value="{{ $p->id }}">
									Activar
								</button>
							@endif
						</td>
					</tr>
			@endforeach
				</tbody>
			</table>
		</div>
	</div>
</div>

@stop