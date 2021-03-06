@extends('main')

@section('content')
{{ HTML::style('js/styles/bottom.css') }}
{{ HTML::style("css/fancybox/jquery.fancybox.css?v=2.1.5",array('media'=>"screen")) }}
{{ HTML::style("js/fancybox/helpers/jquery.fancybox-buttons.css?v=1.0.5") }}

<div class="container contenedorUnico">
	<div class="row">
		
		<div class="col-xs-12 no-padding">
			<div class="col-xs-6 no-padding"><h1 class="pull-left pub-title">{{ $username }}</h1></div>
			<div class="col-xs-6 text-right no-padding">
				@if($publication->tipo == "Lider")
					<img src="{{ asset('images/lider-01.png') }}" class="typePub pull-right">
				@elseif($publication->tipo == "Habitual")
					<img src="{{ asset('images/habitual-01.png') }}" class="typePub pull-right">
				@elseif($publication->tipo == "Casual")
					<img src="{{ asset('images/casual-01.png') }}" class="typePub pull-right">
				@endif
				@if($publication->reputation>=15 && $publication->reputation<60)
					<div id="bronce" class="trofeos">

					</div>
				@elseif($publication->reputation>=60 && $publication->reputation<150)
					<div id="plata" class="trofeos">

					</div>
				@elseif($publication->reputation>=150 && $publication->reputation<300)
					<div id="oro" class="trofeos">

					</div>
				@elseif($publication->reputation>=300 && $publication->reputation<700)
					<div id="c_bronce" class="trofeos">

					</div>
				@elseif($publication->reputation>=700 && $publication->reputation<1500)
					<div id="c_plata" class="trofeos">

					</div>
				@elseif($publication->reputation>=1500)
					<div id="c_oro" class="trofeos">

					</div>
				@endif
				
			</div>
		</div>
		@if($publication->tipo == "Lider")
		<div class="col-xs-12 no-padding">
			<div class="col-md-6 pagMini hidden-xs hidden-sm no-padding-left">
				<div class="list-group">
					<a href="#" class="list-group-item active">
						<h4 class="list-group-item-heading publication-title">{{ $publication->titulo }}</h4>

						<p class="textoPromedio">Creado por: <label>
										@if(!empty($publication->name_pub))
											<td>{{ $publication->name_pub }}</td>
										@else
											<td>{{ $publication->name.' '.$publication->lastname }}</td>
										@endif</label></p>
					</a>
					<div class="col-xs-12 no-padding">
						@if(Auth::check())
						<div class="table-responsive">
							<table class="table table-striped table-hover textoPromedio">
								<tbody>
									<tr>
										<th>Nombre y apellido</th>
										@if(!empty($publication->name_pub))
											<td>{{ $publication->name_pub.' '.$publication->lastname_pub }}</td>
										@else
											<td>{{ $publication->name.' '.$publication->lastname }}</td>
										@endif
									</tr>
									<tr>
										<th>Email</th>
										@if(!empty($publication->email_pub))
											<td>{{ $publication->email_pub }}</td>
										@else
											<td>{{ $publication->email }}</td>
										@endif
									</tr>
									<tr>
										<th>
											Telefono
										</th>
										@if(!empty($publication->phone_pub))
											<td>{{ $publication->phone_pub }}</td>
										@else
											<td>{{ $publication->phone }}</td>
										@endif
									</tr>
									<tr>
										<th>
											Pagina web
										</th>
										@if(!empty($publication->pag_web_hab))
											<td>{{ $publication->pag_web_hab }}</td>
										@else
											<td>{{ $publication->pag_web }}</td>
										@endif
									</tr>
									@if(!is_null($publication->bussiness_type))
									<tr>
										<th>
											Tipo de negocio
										</th>
										<td>
											{{ ucfirst($publication->bussiness_type) }}
										</td>
									</tr>
									@endif
								</tbody>
							</table>
						</div>
						@else

							<p class="bg-info textoPromedio" style="padding:1em;margin-top:2em;"><strong>Para poder ver la información del usuario se debe iniciar sesión en el portal.</strong></p>
						@endif
						<a href="{{ URL::to('inicio') }}" class="btn btn-warning">Volver</a>
						@if(!empty($publication->pag_web))
							<a target="_blank" href="{{ $publication->pag_web }}" class="btn btn-primary" style="margin:2em 0.5em;">Ir a la página</a>
						@else
							@if(!empty($publication->pag_web_hab))
								<a target="_blank" href="{{ $publication->pag_web_web }}" class="btn btn-primary" style="margin:2em 0.5em;">Ir a la página</a>
							@endif
						@endif
						@if(!Auth::check())
							<button class="btn btn-default btn-transparent btn-fav nosepuedeClick mustLog" data-container="body" data-toggle="popover" data-placement="right" data-content="Agregar a favoritos." data-trigger="hover" data-target=".log-to-fav">
								<i class="fa fa-heart-o"></i>
							</button>
							<p class="bg-info textoPromedio noSePuede hidden log-to-fav" style="padding:1em;margin-top:2em;"><strong>Para poder agregar a favoritos se debe iniciar sesion en el portal.</strong></p>
						@else
							@if(Auth::check() && Auth::id() != $publication->user_id && Auth::user()['role'] != 'Administrador' && Auth::user()['role'] != 'Gestor')
								@if(isset($publication->fav_user_id) && $publication->fav_user_id == Auth::id())
									<button class="btn btn-default btn-transparent btn-remove-fav" 
									value="{{ URL::to('usuario/publicaciones/remover-favorito/'.$publication->fav_id) }}" 
									data-container="body" data-toggle="popover" 
									data-placement="top" 
									data-content="Remover de favoritos." 
									data-trigger="hover"><i class="fa fa-heart"></i></button>
								@else
									<button class="btn btn-default btn-transparent btn-fav" 
									value="{{ URL::to('usuario/publicaciones/agregar-favorito/'.$id) }}" 
									data-container="body" data-toggle="popover" 
									data-placement="top" 
									data-content="Agregar a favoritos." 
									data-trigger="hover"><i class="fa fa-heart-o"></i></button>
								@endif
							@endif
							<img src="{{ asset('images/loading.gif') }}" class="miniLoader hidden loader-fav">
							<div class="alert responseDanger responseFav">
								<p class="textoPromedio"></p>
							</div>
						@endif
						<div>
							<a href="https://twitter.com/share" class="twitter-share-button" data-via="pasillo_24" data-hashtags="pasillo_24" data-dnt="true">Tweet</a>
							<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
							<div class="fb-share-button" 
								data-href="{{ Request::url() }}" 
								data-layout="button_count">
							</div>
						</div>
					</div>
				</div>
				
			</div>
			<div class="col-xs-12 col-md-6 pagMini pika_stage_lider_container no-padding" >
				
					<ul class="minis pika pika_stage_lider">
						@if(!empty($publication->img_1))
						<li>
							<img src="{{ asset('images/pubImages/'.$publication->img_1) }}" class="imgMini" data-fancybox-group="gallery" data-value="img_1">
						</li>
						@endif
						@if(!empty($publication->img_2))
						<li>
							<img src="{{ asset('images/pubImages/'.$publication->img_2) }}" class="imgMini" data-fancybox-group="gallery" data-value="img_2">
						</li>
						@endif
					</ul>

			</div>
			<div class="col-xs-12 pagMini visible-xs visible-sm formulario no-padding">
				<div class="list-group">
					<a href="#" class="list-group-item active">
						<h4 class="list-group-item-heading">{{ $publication->titulo }}</h4>

						<p class="textoPromedio">Creado por: <label>
										@if(!empty($publication->name_pub))
											<td>{{ $publication->name_pub }}</td>
										@else
											<td>{{ $publication->name.' '.$publication->lastname }}</td>
										@endif</label></p>
					</a>
					<div class="col-xs-12 no-padding">
						@if(Auth::check())
						<div class="table-responsive">
							<table class="table table-striped table-hover textoPromedio">
								<tbody>
									<tr>
										<th>Nombre y apellido</th>
										@if(!empty($publication->name_pub))
											<td>{{ $publication->name_pub.' '.$publication->lastname_pub }}</td>
										@else
											<td>{{ $publication->name.' '.$publication->lastname }}</td>
										@endif
									</tr>
									<tr>
										<th>Email</th>
										@if(!empty($publication->email_pub))
											<td>{{ $publication->email_pub }}</td>
										@else
											<td>{{ $publication->email }}</td>
										@endif
									</tr>
									<tr>
										<th>
											Telefono
										</th>
										@if(!empty($publication->phone_pub))
											<td>{{ $publication->phone_pub }}</td>
										@else
											<td>{{ $publication->phone }}</td>
										@endif
									</tr>
									<tr>
										<th>
											Pagina web
										</th>
										@if(!empty($publication->pag_web_hab))
											<td>{{ $publication->pag_web_hab }}</td>
										@else
											<td>{{ $publication->pag_web }}</td>
										@endif
									</tr>
									@if(!is_null($publication->bussiness_type))
									<tr>
										<th>
											Tipo de negocio
										</th>
										<td>
											{{ ucfirst($publication->bussiness_type) }}
										</td>
									</tr>
									@endif
								</tbody>
							</table>
						</div>
						@else

						<p class="bg-info textoPromedio" style="padding:1em;margin-top:2em;"><strong>Para poder ver la información del usuario se debe iniciar sesión en el portal.</strong></p>
						@endif
						<a href="{{ URL::to('inicio') }}" class="btn btn-warning">Volver</a>
						@if(!empty($publication->pag_web))
							<a target="_blank" href="{{ $publication->pag_web }}" class="btn btn-primary" style="margin:2em 0.5em;">Ir a la página</a>
						@else
							@if(!empty($publication->pag_web_hab))
								<a target="_blank" href="{{ $publication->pag_web_web }}" class="btn btn-primary" style="margin:2em 0.5em;">Ir a la página</a>
							@endif
						@endif
						@if(!Auth::check())
							<button class="btn btn-default btn-transparent btn-fav nosepuedeClick mustLog" data-container="body" data-toggle="popover" data-placement="top" data-content="Agregar a favoritos." data-trigger="hover" data-target=".log-to-fav"><i class="fa fa-heart-o"></i></button>
							<p class="bg-info textoPromedio noSePuede hidden log-to-fav" style="padding:1em;margin-top:2em;"><strong>Para poder agregar a favoritos se debe iniciar sesion en el portal.</strong></p>
						@else
							@if(Auth::check() && Auth::id() != $publication->user_id && Auth::user()['role'] != 'Administrador' && Auth::user()['role'] != 'Gestor')
								@if(isset($publication->fav_user_id) && $publication->fav_user_id == Auth::id())
									<button class="btn btn-default btn-transparent btn-remove-fav" 
									value="{{ URL::to('usuario/publicaciones/remover-favorito/'.$publication->fav_id) }}" 
									data-container="body" data-toggle="popover" 
									data-placement="top" 
									data-content="Remover de favoritos." 
									data-trigger="hover"><i class="fa fa-heart"></i></button>
								@else
									<button class="btn btn-default btn-transparent btn-fav" 
									value="{{ URL::to('usuario/publicaciones/agregar-favorito/'.$id) }}" 
									data-container="body" data-toggle="popover" 
									data-placement="top" 
									data-content="Agregar a favoritos." 
									data-trigger="hover"><i class="fa fa-heart-o"></i></button>
								@endif
							@endif
							<img src="{{ asset('images/loading.gif') }}" class="miniLoader hidden loader-fav">
							<div class="alert responseDanger responseFav">
								<p class="textoPromedio"></p>
							</div>
						@endif
						<div>
							<a href="https://twitter.com/share" class="twitter-share-button" data-via="pasillo_24" data-hashtags="pasillo_24" data-dnt="true">Tweet</a>
					<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
							<div class="fb-share-button" 
								data-href="{{ Request::url() }}" 
								data-layout="button_count">
							</div>
						</div>
					</div>
					</a>
				</div>
				
			</div>
		</div>
		@elseif($publication->tipo == "Habitual")
			<div class="col-xs-12 no-padding">
				<div class="col-xs-12 col-md-6 pagMini pika_stage_lider_container no-padding-left" >
					<ul class="minis pika pika_stage_lider">
						@if(!empty($publication->img_1))
						<li>
							<img src="{{ asset('images/pubImages/'.$publication->img_1) }}" class="imgMini" data-fancybox-group="gallery" data-value="img_1">
						</li>
						@endif
						@if(!empty($publication->img_2))
						<li>
							<img src="{{ asset('images/pubImages/'.$publication->img_2) }}" class="imgMini" data-fancybox-group="gallery" data-value="img_2">
						</li>
						@endif
						@if(!empty($publication->img_3))
						<li>
							<img src="{{ asset('images/pubImages/'.$publication->img_3) }}" class="imgMini" data-fancybox-group="gallery" data-value="img_3">
						</li>
						@endif
						@if(!empty($publication->img_4))
						<li>
							<img src="{{ asset('images/pubImages/'.$publication->img_4) }}" class="imgMini" data-fancybox-group="gallery" data-value="img_4">
						</li>
						@endif
						@if(!empty($publication->img_5))
						<li>
							<img src="{{ asset('images/pubImages/'.$publication->img_5) }}" class="imgMini" data-fancybox-group="gallery" data-value="img_5">
						</li>
						@endif
						@if(!empty($publication->img_6))
						<li>
							<img src="{{ asset('images/pubImages/'.$publication->img_6) }}" class="imgMini" data-fancybox-group="gallery" data-value="img_6">
						</li>
						@endif
						@if(!empty($publication->img_7))
						<li>
							<img src="{{ asset('images/pubImages/'.$publication->img_7) }}" class="imgMini" data-fancybox-group="gallery" data-value="img_7">
						</li>
						@endif
						@if(!empty($publication->img_8))
						<li>
							<img src="{{ asset('images/pubImages/'.$publication->img_8) }}" class="imgMini" data-fancybox-group="gallery" data-value="img_8">
						</li>
						@endif
					</ul>
					<div class="clearfix"></div>
				</div>
				<div class="col-xs-12 col-md-6 contAnaranjado">
					<div class="col-xs-12">
						<legend class="precioPub title-pub publication-title">{{ ucfirst($publication->titulo) }}</legend>
					</div>
					<div class="col-xs-6 caracteristicasPub">
						<h4>Precio</h4><label class="textoPromedio">@if($publication->precio == "") Sin especificar @else {{ $publication->precio.' '.$publication->moneda }} @endif</label>
					</div>
					@if($publication->categoria == 34)
						
						<div class="col-xs-6 caracteristicasPub">
							<h4>Kilometraje</h4><label class="textoPromedio">@if(strlen($publication->kilometraje) == 0) Sin especificar @else {{ $publication->kilometraje }} @endif</label>
						</div>

						<div class="col-xs-6 caracteristicasPub">
							<h4>Marca</h4><label class="textoPromedio">@if(strlen($publication->marca) == 0) Sin especificar @else {{ $publication->marca }} @endif</label>
						</div>

						<div class="col-xs-6 caracteristicasPub">
							<h4>Modelo</h4><label class="textoPromedio">@if(strlen($publication->modelo) == 0) Sin especificar @else {{ $publication->modelo }} @endif</label>
						</div>

						@if(!empty($publication->combustible))
							<div class="col-xs-6 caracteristicasPub">
								<h4>Combustible</h4><label class="textoPromedio">{{ $publication->combustible }}</label>
							</div>
						@endif

						@if(!empty($publication->transmision))
							<div class="col-xs-6 caracteristicasPub">
								<h4>Transmisión</h4><label class="textoPromedio"> {{ $publication->transmision }}</label>
							</div>
						@endif

						@if(!empty($publication->traccion))
							<div class="col-xs-6 caracteristicasPub">
								<h4>Tracción</h4><label class="textoPromedio"> {{ $publication->traccion }}</label>
							</div>
						@endif

						<div class="col-xs-6 caracteristicasPub">
							<h4>Documentación</h4><label class="textoPromedio">@if(!empty($publication->documentos)) Sin especificar @else {{ $publication->documentos }} @endif</label>
						</div>

						@if(!empty($publication->cilindraje)) 
							<div class="col-xs-6 caracteristicasPub">
								<h4>Cilindraje</h4><label class="textoPromedio">{{ $publication->cilindraje }}</label>
							</div>
						@endif

					@elseif($publication->categoria == 20)
						<div class="col-xs-6 caracteristicasPub">
							<h4>Extensión</h4><label class="textoPromedio">{{ $publication->extension }} mt<sup>2</sup></label>
						</div>
					@endif	
					<div class="col-xs-6 caracteristicasPub">
						<h4>Tipo de Operación</h4><label class="textoPromedio">{{ $publication->transaccion }}</label>

					</div>
					<div class="col-xs-6 caracteristicasPub">
						<h4>Departamento</h4><label class="textoPromedio">{{ $publication->dep
						 }}</label>

					</div>
					<div class="col-xs-6 caracteristicasPub">
						<h4>Ciudad</h4><label class="textoPromedio">{{ $publication->ciudad }}</label>

					</div>	
					@if(!is_null($publication->bussiness_type))

					<div class="col-xs-6 caracteristicasPub">
						<h4>Tipo de negocio</h4><label class="textoPromedio">{{ ucfirst($publication->bussiness_type)
						 }}</label>

					</div>
					@endif
					@if(!is_null($publication->condicion))

					<div class="col-xs-6 caracteristicasPub">
						<h4>Condición</h4><label class="textoPromedio">{{ ucfirst($publication->condicion)
						 }}</label>

					</div>
					@endif
						<div class="col-xs-12">
							@if(Auth::check() && Auth::id() != $publication->user_id && Auth::user()['role'] != 'Administrador' && Auth::user()['role'] != 'Gestor')
								<a href="#" class="btn btn-primary" data-toggle="modal" data-target="#modalComprar">Contactar</a>
								@if(isset($publication->fav_user_id) && $publication->fav_user_id == Auth::id())
									<button class="btn btn-default btn-transparent btn-remove-fav hidden-xs hidden-sm" 
									value="{{ URL::to('usuario/publicaciones/remover-favorito/'.$publication->fav_id) }}" 
									data-container="body" data-toggle="popover" 
									data-placement="top" 
									data-content="Remover de favoritos." 
									data-trigger="hover"><i class="fa fa-heart"></i></button>
									<button class="btn btn-default btn-transparent btn-remove-fav visible-xs visible-sm" 
									value="{{ URL::to('usuario/publicaciones/remover-favorito/'.$publication->fav_id) }}" 
									data-container="body" data-toggle="popover" 
									data-placement="top" 
									data-content="Remover de favoritos." 
									data-trigger="hover"><i class="fa fa-heart"></i></button>
								@else
									<button class="btn btn-default btn-transparent btn-fav hidden-xs hidden-sm" 
									value="{{ URL::to('usuario/publicaciones/agregar-favorito/'.$id) }}" 
									data-container="body" data-toggle="popover" 
									data-placement="top" 
									data-content="Agregar a favoritos." 
									data-trigger="hover"><i class="fa fa-heart-o"></i></button>
									<button class="btn btn-default btn-transparent btn-fav visible-xs visible-sm" 
									value="{{ URL::to('usuario/publicaciones/agregar-favorito/'.$id) }}" 
									data-container="body" data-toggle="popover" 
									data-placement="top" 
									data-content="Agregar a favoritos." 
									data-trigger="hover"><i class="fa fa-heart-o"></i></button>
								@endif
								<img src="{{ asset('images/loading.gif') }}" class="miniLoader hidden loader-fav">
								<div class="alert responseDanger">
									<p class="textoPromedio"></p>
								</div>
								@if(Session::has('error'))
								<div class="alert alert-danger" style="margin-top: 2em;">
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
									<p class="textoPromedio">{{ Session::get('error') }}</p>
								</div>
								@endif
							@endif
							@if(!Auth::check())
							<div class="col-xs-12">
								<button class="btn btn-primary nosepuedeClick">Contactar</button>
								<button class="btn btn-default btn-transparent btn-fav nosepuedeClick" data-container="body" data-toggle="popover" data-placement="right" data-content="Agregar a favoritos." data-trigger="hover"><i class="fa fa-heart-o"></i></button>
								<p class="bg-info textoPromedio noSePuede" style="padding:1em;margin-top:2em;"><strong>Para poder contactar con el usuario o agregar a favoritos se debe iniciar sesion en el portal.</strong></p>
							</div>
							@endif
					
						</div>
						<div class="col-xs-12 ">
							<a href="https://twitter.com/share" class="twitter-share-button" data-via="pasillo_24" data-hashtags="pasillo_24" data-dnt="true">Tweet</a>
					<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
							<div class="fb-share-button" 
								data-href="{{ Request::url() }}" 
								data-layout="button_count">
							</div>
						</div>
				</div>
			</div>
			<div class="col-xs-12 textoPromedio descProd contAnaranjado">
				<div class="col-xs-12 comentarioBox">
					<p>{{ $publication->descripcion }}
					</p>
				</div>
			</div>
			
		@elseif($publication->tipo == "Casual")
			<div class="col-xs-12 no-padding">
				<div class="col-xs-12 col-md-6 pagMini pika_stage_lider_container no-padding-left" >
					<ul class="minis pika pika_stage_lider">
					@if(!empty($publication->img_1))
					<li>
						<img src="{{ asset('images/pubImages/'.$publication->img_1) }}" class="imgMini" data-fancybox-group="gallery" data-value="img_1">
					</li>
					@endif
					@if(!empty($publication->img_2))
					<li>
						<img src="{{ asset('images/pubImages/'.$publication->img_2) }}" class="imgMini" data-fancybox-group="gallery" data-value="img_2">
					</li>
					@endif
					

					</ul>
				
				</div>
				<div class="col-xs-12 col-md-6 contAnaranjado contMovil">
					<div class="col-xs-12">
						<legend class="precioPub"><h2 class="publication-title">{{ ucfirst($publication->titulo) }}</h2></legend>
					</div>
					<div class="col-xs-12 col-sm-6">
						<h4>Categoria </h4><label class="textoPromedio">{{ $publication->cat }}</label>

					</div>
					<div class="col-xs-12 col-sm-6">
						<h4>Precio </h4><label class="textoPromedio">{{ $publication->precio.' '.$publication->moneda }}</label>

					</div>
					<div class="col-xs-12 col-sm-6">
						<h4>Departamento </h4><label class="textoPromedio">{{ $publication->nombre }}</label>

					</div>
					@if(!is_null($publication->bussiness_type))

					<div class="col-xs-12 col-sm-6">
						<h4>Tipo de negocio</h4><label class="textoPromedio">{{ ucfirst($publication->bussiness_type)
						 }}</label>
					</div>
					@endif
					@if(!is_null($publication->condicion))

					<div class="col-xs-12 col-sm-6">
						<h4>Condición</h4><label class="textoPromedio">{{ ucfirst($publication->condicion)
						 }}</label>
					</div>
					@endif
					<div class="col-xs-12">
						@if(Auth::check() && Auth::id() != $publication->user_id && Auth::user()['role'] != 'Administrador' && Auth::user()['role'] != 'Gestor')
						<div class="col-xs-12" style="padding-left: 0px;">
							<button class="btn btn-primary" data-toggle="modal" data-target="#modalComprar">Contactar</button>
							@if(isset($publication->fav_user_id) && $publication->fav_user_id == Auth::id())
								<button class="btn btn-default btn-transparent btn-remove-fav" 
								value="{{ URL::to('usuario/publicaciones/remover-favorito/'.$publication->fav_id) }}" 
								data-container="body" data-toggle="popover" 
								data-placement="top" 
								data-content="Remover de favoritos." 
								data-trigger="hover"><i class="fa fa-heart"></i></button>
							@else
								<button class="btn btn-default btn-transparent btn-fav" 
								value="{{ URL::to('usuario/publicaciones/agregar-favorito/'.$id) }}" 
								data-container="body" data-toggle="popover" 
								data-placement="top" 
								data-content="Agregar a favoritos." 
								data-trigger="hover"><i class="fa fa-heart-o"></i></button>
							@endif
							<img src="{{ asset('images/loading.gif') }}" class="miniLoader hidden loader-fav">
							<div class="alert responseDanger">
								<p class="textoPromedio"></p>
							</div>
						</div>
							
						@endif
						@if(!Auth::check())
						<div class="col-xs-12" style="padding-left: 0px;">
							<button class="btn btn-primary nosepuedeClick">Contactar</button>
							<button class="btn btn-default btn-transparent btn-fav nosepuedeClick" data-container="body" data-toggle="popover" data-placement="right" data-content="Agregar a favoritos." data-trigger="hover"><i class="fa fa-heart-o"></i></button>
							<p class="bg-info textoPromedio noSePuede" style="padding:1em;margin-top:2em;"><strong>Para poder contactar con el usuario o agregar a favoritos se debe iniciar sesion en el portal.</strong></p>
						</div>
						@endif
					</div>
					<div class="col-xs-12">
								<a href="https://twitter.com/share" class="twitter-share-button" data-via="pasillo_24" data-hashtags="pasillo_24" data-dnt="true">Tweet</a>
						<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
								<div class="fb-share-button" 
									data-href="{{ Request::url() }}" 
									data-layout="button_count">
								</div>
					</div>
				</div>

				<div class="col-xs-12 textoPromedio descProd contAnaranjado">
					<div class="col-xs-12 comentarioBox">
						<p>{{ $publication->descripcion }}</p>
					</div>
				</div>
			</div>
		@endif
	</div>
	@if(isset($publication->longitude) && isset($publication->latitude))
	<div class="col-xs-12 no-padding" >
		<h4>Mi ubicación</h4>
		<hr>
		<article class="mapContainer" id="" style="position:relative;">
			<div class="contLoaderBig">
				<img src="{{ asset('images/loading.gif') }}" class="loaderBig">
			</div>
			<div id="mapcontainer" style="width:100%;height:400px;">
			</div>
		</article>
		<input type="hidden" name="latitud" class="latitud" value="{{ $publication->latitude }}">
		<input type="hidden" name="longitud" class="longitud" value="{{ $publication->longitude  }}">
	</div>
	@endif
	<hr>
	@if(count($otrasPub) > 0)
	<div class="col-xs-12">
		<h2>Otras publicaciones de: {{ $username }}</h2>
			<div class="owl-carousel1 owl-carousel-busq owl-carousel-most-user">
			@foreach($otrasPub as $o)
			<div class="item contCatIndex contPubNoLider">
				<a href="{{ URL::to('publicacion/lider/'.base64_encode($o->id)) }}">
					<img src="{{ asset('images/pubImages/'.$o->img_1) }}" class="imgPubCarousel">
				</a>
				<div class="dataIndex textoPromedio dataOtherPubs">
					<div class="col-xs-12 col-md-6">{{ $o->titulo }}</div>
					@if($o->precio)
					<div class="col-xs-12 col-md-6" style="margin-top:0.5em;">
					 <label>Precio: </label>{{ $o->precio.' '.ucfirst(strtolower($o->moneda)).'.' }}
					</div>
					@endif
					<div class="col-xs-12"><a href="{{ URL::to('publicacion/lider/'.base64_encode($o->id)) }}" style="color:white;"><i class="fa fa-hand-o-right"></i> Ver publicación</a>
					</div>
				</div>
			</div>
			@endforeach
				
		</div>
	</div>
	@endif

	<hr>
	<legend>Comentarios</legend>
	<div class="alert responseComment responseDanger">
		<p></p>
	</div>
	<div class="row">
		<div class="comentarioBox">
			
			@if(empty($comentarios) || is_null($comentarios) || count($comentarios)<1)
				@if(Auth::check())
				<div class="contComment">
					<div class="col-xs-12 comentario new-comment">
						<p class="textoPromedio comment-text"></p>
						<p class="textoMedio comment-date" style="float:right;"></p>
					</div>
					<div class="col-xs-12 no-padding">
						<textarea id="inputComentario" class="inputComentario textoPromedio form-control heightTransition" name="inputComentario" placeholder="Escriba su pregunta"></textarea>
						<div class="formulario">
							<button id="enviarComentario" name="enviarComentario" class="btn btn-success hidden" value="{{ $id }}">Enviar</button>
							<img src="{{ asset('images/loading.gif') }}" class="miniLoader hidden loader-comment">
						</div>
					</div>
				</div>
				@else
					<p class="textoPromedio">Inicie sesión para poder agregar un comentario</p>
				@endif
				<div class="col-xs-12 formulario no-padding">
					<p class="textoPromedio">No hay comentarios</p>
				</div>
			@else
				@foreach($comentarios as $comentario)
				<div class="col-xs-12 comentario">
					<p class="textoPromedio"><i class="fa fa-comment"></i> {{ $comentario->comentario }}</p>
					<p class="textoMedio" style="float:right;">{{ date('d-m-Y',strtotime($comentario->created_at)) }}</p>
					@foreach($respuestas as $respuesta)
						@if($respuesta->comentario_id == $comentario->id)
						<div class="col-xs-12 comentario">
							<p class="textoPromedio"><i class="fa fa-comments"></i> {{ $respuesta->respuesta }}</p>
							<p class="textoMedio" style="float:right;">{{ date('d-m-Y',strtotime($respuesta->created_at)) }}</p>
						</div>
						@endif
					@endforeach
				</div>
				@endforeach
				@if(Auth::check())
					<div class="col-xs-12 comentario new-comment">
						<p class="textoPromedio comment-text"></p>
						<p class="textoMedio comment-date pull-right"></p>
					</div>
					<div class="col-xs-12">
						<textarea id="inputComentario" class="inputComentario textoPromedio form-control" name="inputComentario" placeholder="Escriba su pregunta"></textarea>
						<div class="formulario">
							<button id="enviarComentario" name="enviarComentario" class="btn btn-success hidden" value="{{ $id }}">Enviar</button>
							<img src="{{ asset('images/loading.gif') }}" class="miniLoader hidden loader-comment">
						</div>
					</div>
				@endif
			@endif

		</div>
	</div>
</div>

<div class="share">
	
</div>
<div class="modal fade" id="modalComprar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel">Contactar.</h4>
			</div>
				<div class="modal-body">
					<p class="textoPromedio">¿Seguro que desea contactar con el vendedor?</p>
					
				</div>
				<div class="modal-footer">
					<form method="POST" action="{{ URL::to('publicacion/comprar') }}">
						<button class="btn btn-primary" name="id" value="{{ $publication->id }}">Aceptar</button>
					</form>
				</div>
		</div>
	</div>
</div>
<input type="hidden" class="requestUrl" value="{{ Request::url() }}">
@stop


@section('postscript')
{{ HTML::script("js/pikaChoose/jquery.jcarousel.min.js") }}
{{ HTML::script("js/pikaChoose/jquery.pikachoose.min.js") }}
{{ HTML::script("js/pikaChoose/jquery.touchwipe.min.js") }}

{{ HTML::script("js/fancybox/jquery.mousewheel-3.0.6.pack.js") }}

<!-- Add fancyBox main JS and CSS files -->
{{ HTML::script("js/fancybox/jquery.fancybox.js?v=2.1.5") }}

<!-- Add Button helper (this is optional) -->
{{ HTML::script("js/fancybox/helpers/jquery.fancybox-buttons.js?v=1.0.5") }}
<script>
  function initMap() {
  	var $latitud = $('.latitud').val(), $longitud = $('.longitud').val()
    coords = new google.maps.LatLng($latitud,$longitud);

    // Create a map object and specify the DOM element for display.
    var map = new google.maps.Map(document.getElementById('mapcontainer'), {
      center: coords,
      scrollwheel: false,
      zoom: 15
    });

    // Create a marker and set its position.
    var marker = new google.maps.Marker({
      map: map,
      position: coords,
      title: $('.publication-title').html()
    });
  }

</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDsWeMp66ReIVZI4u_J2gteYCyNIKx_MOE&callback=initMap"
        async defer></script>
<script type="text/javascript">
	$(document).ready(function (){
		//loadMap();
		
		$(".pika").PikaChoose(
		{
			autoPlay:false,
			buildFinished:function () {
                $('.pika-textnav').children('.previous').html('Anterior')
                $('.pika-textnav').children('.next').html('Siguiente')
				$('.pika-stage').data('fancybox-group','gallery').fancybox(
				{
					afterClose:function() {
						$('.pika-stage').css({
							'display': 'block'
						});
					}
				});
				
			}
		});
		$('.owl-carousel1').owlCarousel({
		    loop:false,
		    margin:30,
		    nav:false,
		    itemsScaleUp:true,
		    pagination:false,
		    stagePadding: 0,
		    autoplay:false,
			autoplayHoverPause:true,
			items : 4,
		    responsive:{
		        0:{
		            items:1
		        },
		        768:{
		            items:2
		        },
		        1200:{
		            items:4
		        }
		    }
		})
	});
	$('.btn-fav').popover()
</script>
 
@stop