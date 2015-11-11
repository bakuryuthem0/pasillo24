@extends('main')
@section('content')
<div class="container contenedorUnico">
	<div class="row">
		<div class="col-xs-12">
			
			<div class="col-xs-8 col-sm-offset-2 contForm contAnaranjado">
				@if (Session::has('error'))
				<div class="col-xs-6">
					<div class="alert alert-danger">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						<p class="textoPromedio">{{ Session::get('error') }}</p>
					</div>
				</div>
				@elseif (Session::has('success'))
					<div class="alert alert-success">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						<p class="textoPromedio">{{ Session::get('success') }}</p>
					</div>
				<div class="clearfix"></div>
				@endif
				<form action="{{ URL::to('usuario/perfil/enviar') }}" method="POST">
					<div class="col-xs-12 formulario">
						<div class="col-xs-6 inputRegister">
							<p class="textoPromedio"><strong>Nombre:</strong></p>
						</div>
						<div class="col-xs-6 inputRegister">
							<p class="mdfInfo textoPromedio">{{ $user->name }}</p>
							<input type="text" name="name" class="form-control mdfForm" placeholder="Nombre" id="name" value="{{ $user->name }}">
							@if ($errors->has('name'))
								 @foreach($errors->get('name') as $err)
								 	<div class="alert alert-danger">
								 		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
								 		<p class="textoPromedio">{{ $err }}</p>
								 	</div>
								 @endforeach
							@endif
						</div>
					</div>
					<div class="col-xs-12 formulario">
						<div class="col-xs-6 inputRegister">
							<p class="textoPromedio"><strong>Apellido</strong></p>
						</div>
						<div class="col-xs-6 inputRegister">
							<p class="mdfInfo textoPromedio">{{ $user->lastname }}</p>
							<input type="text" name="lastname" class="form-control mdfForm" placeholder="Apellido" id="lastname" value="{{ $user->lastname }}">
							@if ($errors->has('lastname'))
								 @foreach($errors->get('lastname') as $err)
								 	<div class="alert alert-danger">
								 		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
								 		<p class="textoPromedio">{{ $err }}</p>
								 	</div>
								 @endforeach
							@endif
						</div>
					</div>
					<div class="col-xs-12 formulario">
						<div class="col-xs-6 inputRegister">
							<p class="textoPromedio"><strong>Carnet de Identidad</strong></p>
						</div>
						<div class="col-xs-6 inputRegister">
							<p class="mdfInfo textoPromedio">{{ $user->id_carnet }}</p>
							<input type="text" name="id" class="form-control mdfForm" placeholder="Carnet de identidad" id="id" value="{{ $user->id_carnet }}">
							@if ($errors->has('id'))
								 @foreach($errors->get('id') as $err)
								 	<div class="alert alert-danger">
								 		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
								 		<p class="textoPromedio">{{ $err }}</p>
								 	</div>
								 @endforeach
							@endif
						</div>
					</div>
					<div class="col-xs-12 formulario">
						<div class="col-xs-6 inputRegister">
							<p class="textoPromedio"><strong>NIT</strong></p>
						</div>
						<div class="col-xs-6 inputRegister">
							<p class="mdfInfo textoPromedio">{{ $user->nit }}</p>
							<input type="text" name="nit" class="form-control mdfForm" placeholder="NIT" id="nit" value="{{ $user->nit }}">
							@if ($errors->has('nit'))
								 @foreach($errors->get('nit') as $err)
								 	<div class="alert alert-danger">
								 		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
								 		<p class="textoPromedio">{{ $err }}</p>
								 	</div>
								 @endforeach
							@endif
						</div>
					</div>	
					<div class="col-xs-12 formulario">
						<div class="col-xs-6 inputRegister">
							<p class="textoPromedio"><strong>Teléfono</strong></p>
						</div>
						<div class="col-xs-6 inputRegister">
							<p class="mdfInfo textoPromedio">{{ $user->phone }}</p>
							<input type="text" name="phone" class="form-control mdfForm" placeholder="Telefono" id="phone" value="{{ $user->phone }}">
							@if ($errors->has('phone'))
								 @foreach($errors->get('phone') as $err)
								 	<div class="alert alert-danger">
								 		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
								 		<p class="textoPromedio">{{ $err }}</p>
								 	</div>
								 @endforeach
							@endif
						</div>
					</div>		
					<div class="col-xs-12 formulario">
						<div class="col-xs-6 inputRegister">
							<p class="textoPromedio"><strong>Página web</strong></p>
						</div>
						<div class="col-xs-6 inputRegister">
							<p class="mdfInfo textoPromedio">{{ $user->pag_web }}</p>
							<input type="text" name="pag_web" class="form-control mdfForm" placeholder="Pagina web" id="pag_web" value="@if($user->pag_web == '')http://@else{{ $user->pag_web }}@endif">
							@if ($errors->has('pag_web'))
								 @foreach($errors->get('pag_web') as $err)
								 	<div class="alert alert-danger">
								 		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
								 		<p class="textoPromedio">{{ $err }}</p>
								 	</div>
								 @endforeach
							@endif
						</div>
					</div>		
					<div class="col-xs-12 formulario">
						<div class="col-xs-6 inputRegister">
							<p class="textoPromedio"><strong>Código Postal</strong></p>
						</div>
						<div class="col-xs-6 inputRegister">
							<p class="mdfInfo textoPromedio">{{ $user->postal_cod }}</p>
							<input type="text" name="postal_cod" class="form-control mdfForm" placeholder="Codigo postal" id="zip_cod" value="{{ $user->postal_cod }}">
							@if ($errors->has('zip_code'))
								 @foreach($errors->get('zip_code') as $err)
								 	<div class="alert alert-danger">
								 		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
								 		<p class="textoPromedio">{{ $err }}</p>
								 	</div>
								 @endforeach
							@endif
						</div>
					</div>	
					<div class="col-xs-12 formulario">
						<div class="col-xs-6 inputRegister">
							<p class="textoPromedio"><strong>País:</strong></p>
						</div>
						<div class="col-xs-6 inputRegister">
							<p class="mdfInfo textoPromedio">{{ $user->pais }}</p>
							<input type="text" name="pais" class="form-control mdfForm" placeholder="Pais" id="pais" value="{{ $user->pais }}">
							@if ($errors->has('department'))
								 @foreach($errors->get('department') as $err)
								 	<div class="alert alert-danger">
								 		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
								 		<p class="textoPromedio">{{ $err }}</p>
								 	</div>
								 @endforeach
							@endif
						</div>
					</div>	
					<div class="col-xs-12 formulario">
						<div class="col-xs-6 inputRegister">
							<p class="textoPromedio"><strong>Departamento:</strong></p>
						</div>
						<div class="col-xs-6 inputRegister">
							<p class="mdfInfo textoPromedio">{{ $userDep }}</p>
							<select name="department" class="form-control mdfForm" id="department">
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
					</div>	
					<div class="col-xs-12 formulario">
						<div class="col-xs-6 inputRegister">
							<p class="textoPromedio"><strong>Dirección:</strong></p>
						</div>
						<div class="col-xs-6 inputRegister">
							<p class="mdfInfo textoPromedio">{{ $user->dir }}</p>
							<input type="text" name="direccion" class="form-control mdfForm" placeholder="Dirección" id="zip_cod" value="{{ $user->dir }}">
							@if ($errors->has('department'))
								 @foreach($errors->get('department') as $err)
								 	<div class="alert alert-danger">
								 		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
								 		<p class="textoPromedio">{{ $err }}</p>
								 	</div>
								 @endforeach
							@endif
						</div>
					</div>				
					<div class="col-xs-12 formulario">
						<div class="col-xs-12">
							<input type="reset" name="reset" value="Resetear" class="btn btn-warning mdfForm mdfSub botones inputRegister" style="margin-left:1em;margin-right:1em;">
							<input type="submit" name="enviar" value="Enviar" class="btn btn-success mdfForm  botones inputRegister">
						</div>
					</div>
					
				</form>
				<div class="col-xs-12 btnCont">
					<div class="col-xs-12">
						<button class="btn btn-success mdfButton">Modificar</button>
					</div>
				</div>
				
			</div>
		</div>
	</div>
</div>
@stop