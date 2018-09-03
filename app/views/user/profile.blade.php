@extends('main')
@section('content')
<div class="container contenedorUnico">
	<div class="row">
		<div class="col-sm-12 col-md-8 center-block contAnaranjado">
			@if (Session::has('error'))
			<div class="alert alert-danger">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				<p class="textoPromedio">{{ Session::get('error') }}</p>
			</div>
			@elseif (Session::has('success'))
				<div class="alert alert-success">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<p class="textoPromedio">{{ Session::get('success') }}</p>
				</div>
			@endif
			<form class="form" action="{{ URL::to('usuario/perfil/enviar') }}" method="POST">
					<div class="col-sm-12 col-md-6 inputRegister formulario no-padding">
						<p class="textoPromedio"><strong>Nombre:</strong></p>
					</div>
					<div class="col-sm-12 col-md-6 inputRegister formulario no-padding">
						@if(!is_null($user->name) && !empty($user->name))
						<p class="mdfInfo textoPromedio">{{ $user->name }}</p>
						@else
						<p class="mdfInfo textoPromedio">Sin especificar</p>
						@endif
						<input type="text" name="name" class="form-control mdfForm hidden" placeholder="Nombre" id="name" value="{{ $user->name }}">
						@if ($errors->has('name'))
							 @foreach($errors->get('name') as $err)
							 	<div class="alert alert-danger">
							 		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							 		<p class="textoPromedio">{{ $err }}</p>
							 	</div>
							 @endforeach
						@endif
					</div>
					<div class="col-sm-12 col-md-6 inputRegister formulario no-padding">
						<p class="textoPromedio"><strong>Apellido</strong></p>
					</div>
					<div class="col-sm-12 col-md-6 inputRegister formulario no-padding">
						@if(!is_null($user->lastname) && !empty($user->lastname))
						<p class="mdfInfo textoPromedio">{{ $user->lastname }}</p>
						@else
						<p class="mdfInfo textoPromedio">Sin especificar</p>
						@endif
						<input type="text" name="lastname" class="form-control mdfForm hidden" placeholder="Apellido" id="lastname" value="{{ $user->lastname }}">
						@if ($errors->has('lastname'))
							 @foreach($errors->get('lastname') as $err)
							 	<div class="alert alert-danger">
							 		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							 		<p class="textoPromedio">{{ $err }}</p>
							 	</div>
							 @endforeach
						@endif
					</div>
					<div class="col-sm-12 col-md-6 inputRegister formulario no-padding">
						<p class="textoPromedio"><strong>Carnet de Identidad</strong></p>
					</div>
					<div class="col-sm-12 col-md-6 inputRegister formulario no-padding">
						@if(!is_null($user->id_carnet) && !empty($user->id_carnet))
						<p class="mdfInfo textoPromedio">{{ $user->id_carnet }}</p>
						@else
						<p class="mdfInfo textoPromedio">Sin especificar</p>
						@endif
						<input type="text" name="id" class="form-control mdfForm hidden" placeholder="Carnet de identidad" id="id" value="{{ $user->id_carnet }}">
						@if ($errors->has('id'))
							 @foreach($errors->get('id') as $err)
							 	<div class="alert alert-danger">
							 		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							 		<p class="textoPromedio">{{ $err }}</p>
							 	</div>
							 @endforeach
						@endif
					</div>
					<div class="col-sm-12 col-md-6 inputRegister formulario no-padding">
						<p class="textoPromedio"><strong>NIT</strong></p>
					</div>
					<div class="col-sm-12 col-md-6 inputRegister formulario no-padding">
						@if(!is_null($user->nit) && !empty($user->nit))
						<p class="mdfInfo textoPromedio">{{ $user->nit }}</p>
						@else
						<p class="mdfInfo textoPromedio">Sin especificar</p>
						@endif
						<input type="text" name="nit" class="form-control mdfForm hidden" placeholder="NIT" id="nit" value="{{ $user->nit }}">
						@if ($errors->has('nit'))
							 @foreach($errors->get('nit') as $err)
							 	<div class="alert alert-danger">
							 		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							 		<p class="textoPromedio">{{ $err }}</p>
							 	</div>
							 @endforeach
						@endif
					</div>
					<div class="col-sm-12 col-md-6 inputRegister formulario no-padding">
						<p class="textoPromedio"><strong>Teléfono</strong></p>
					</div>
					<div class="col-sm-12 col-md-6 inputRegister formulario no-padding">
						@if(!is_null($user->phone) && !empty($user->phone))
						<p class="mdfInfo textoPromedio">{{ $user->phone }}</p>
						@else
						<p class="mdfInfo textoPromedio">Sin especificar</p>
						@endif
						<input type="text" name="phone" class="form-control mdfForm hidden" placeholder="Telefono" id="phone" value="{{ $user->phone }}">
						@if ($errors->has('phone'))
							 @foreach($errors->get('phone') as $err)
							 	<div class="alert alert-danger">
							 		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							 		<p class="textoPromedio">{{ $err }}</p>
							 	</div>
							 @endforeach
						@endif
					</div>
					<div class="col-sm-12 col-md-6 inputRegister formulario no-padding">
						<p class="textoPromedio"><strong>Página web</strong></p>
					</div>
					<div class="col-sm-12 col-md-6 inputRegister formulario no-padding">
						@if(!is_null($user->pag_web) && !empty($user->pag_web))
						<p class="mdfInfo textoPromedio">{{ $user->pag_web }}</p>
						@else
						<p class="mdfInfo textoPromedio">Sin especificar</p>
						@endif
						<input type="text" name="pag_web" class="form-control mdfForm hidden" placeholder="Pagina web" id="pag_web" value="@if($user->pag_web == '')http://@else{{ $user->pag_web }}@endif">
						@if ($errors->has('pag_web'))
							 @foreach($errors->get('pag_web') as $err)
							 	<div class="alert alert-danger">
							 		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							 		<p class="textoPromedio">{{ $err }}</p>
							 	</div>
							 @endforeach
						@endif
					</div>
					<div class="col-sm-12 col-md-6 inputRegister formulario no-padding">
						<p class="textoPromedio"><strong>Código Postal</strong></p>
					</div>
					<div class="col-sm-12 col-md-6 inputRegister formulario no-padding">
						@if(!is_null($user->postal_cod) && !empty($user->postal_cod))
						<p class="mdfInfo textoPromedio">{{ $user->postal_cod }}</p>
						@else
						<p class="mdfInfo textoPromedio">Sin especificar</p>
						@endif
						<input type="text" name="postal_cod" class="form-control mdfForm hidden" placeholder="Codigo postal" id="zip_cod" value="{{ $user->postal_cod }}">
						@if ($errors->has('zip_code'))
							 @foreach($errors->get('zip_code') as $err)
							 	<div class="alert alert-danger">
							 		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							 		<p class="textoPromedio">{{ $err }}</p>
							 	</div>
							 @endforeach
						@endif
					</div>
					<div class="col-sm-12 col-md-6 inputRegister formulario no-padding">
						<p class="textoPromedio"><strong>País:</strong></p>
					</div>
					<div class="col-sm-12 col-md-6 inputRegister formulario no-padding">
						@if(!is_null($user->pais) && !empty($user->pais))
						<p class="mdfInfo textoPromedio">{{ $user->pais }}</p>
						@else
						<p class="mdfInfo textoPromedio">Sin especificar</p>
						@endif
						<input type="text" name="pais" class="form-control mdfForm hidden" placeholder="Pais" id="pais" value="{{ $user->pais }}">
						@if ($errors->has('department'))
							 @foreach($errors->get('department') as $err)
							 	<div class="alert alert-danger">
							 		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							 		<p class="textoPromedio">{{ $err }}</p>
							 	</div>
							 @endforeach
						@endif
					</div>
					<div class="col-sm-12 col-md-6 inputRegister formulario no-padding">
						<p class="textoPromedio"><strong>Departamento:</strong></p>
					</div>
					<div class="col-sm-12 col-md-6 inputRegister formulario no-padding">
						@if(!is_null($userDep) && !empty($userDep))
						<p class="mdfInfo textoPromedio">{{ $userDep }}</p>
						@else
						<p class="mdfInfo textoPromedio">Sin especificar</p>
						@endif
						<select name="department" class="form-control mdfForm hidden" id="department">
							<option value="">Seleccione un departamento</option>
							@foreach ($department as $departamento)
								@if($user->state == $departamento->id)
									<option value="{{ $departamento->id }}" selected>{{ $departamento->nombre }}</option>
								@else
									<option value="{{ $departamento->id }}">{{ $departamento->nombre }}</option>
								@endif
							@endforeach
						</select>
						@if ($errors->has('department'))
							 @foreach($errors->get('department') as $err)
							 	<div class="alert alert-danger">
							 		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							 		<p class="textoPromedio">{{ $err }}</p>
							 	</div>
							 @endforeach
						@endif
					</div>
					<div class="col-sm-12 col-md-6 inputRegister formulario no-padding">
						<p class="textoPromedio"><strong>Dirección:</strong></p>
					</div>
					<div class="col-sm-12 col-md-6 inputRegister formulario no-padding">
						@if(!is_null($user->dir) && !empty($user->dir))
						<p class="mdfInfo textoPromedio">{{ $user->dir }}</p>
						@else
						<p class="mdfInfo textoPromedio">Sin especificar</p>
						@endif
						<input type="text" name="direccion" class="form-control mdfForm hidden" placeholder="Dirección" id="zip_cod" value="{{ $user->dir }}">
						@if ($errors->has('department'))
							 @foreach($errors->get('department') as $err)
							 	<div class="alert alert-danger">
							 		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							 		<p class="textoPromedio">{{ $err }}</p>
							 	</div>
							 @endforeach
						@endif
					</div>
			<div class="clearfix"></div>
			</form>
			<div class="btnCont">
				<button class="btn btn-success btn-mdf">Modificar</button>
				<button class="btn btn-warning btn-send btn-to-mdf hidden">Enviar</button>
				<button class="btn btn-danger  btn-cancel btn-to-mdf hidden">Cancelar</button>
			</div>
			
		</div>
	</div>
</div>
@stop