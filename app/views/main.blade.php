<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<html lang="en">
	<head>
		<meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>{{ $title }}</title>
		<link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}">
		<meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta property="og:url"                content="{{ Request::url() }}" />
		<meta property="og:type"               content="article" />
		<meta property="og:title"              content="{{ $title }}" />
		<meta property="og:description"        content="Compra, Vende, Publica " />
		@if(isset($publication->img_1))
			<meta property="og:image"          content="{{ asset('images/pubImages/'.$publication->img_1) }}" />
		@else
			<meta property="og:image"          content="http://preview.pasillo24.com/images/portal.png" />
		@endif
		{{ HTML::style('css/bootstrap.min.css') }}
		{{ HTML::style('//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css') }}
		{{ HTML::style('css/bootstrap-theme.min.css')}}
		{{ HTML::style('css/custom.css?v=1.4') }}
		{{ HTML::style('css/jquery.cleditor.css') }}
		{{ HTML::style('css/owl.carousel.css') }}
		{{ HTML::style('css/owl.theme.default.css') }}
		{{ HTML::style('//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css') }}
		{{ HTML::script('js/jquery.min.js') }}
		{{ HTML::script('js/owl.carousel.min.js') }}
		<script src='https://www.google.com/recaptcha/api.js'></script>
		<script type="text/javascript" src="http://maps.google.com/maps/api/js"></script>
	</head>
	<body>
		<script>
		  window.fbAsyncInit = function() {
		    FB.init({
		      appId      : '1665202273729031',
		      xfbml      : true,
		      version    : 'v2.5'
		    });
		  };

		  (function(d, s, id){
		     var js, fjs = d.getElementsByTagName(s)[0];
		     if (d.getElementById(id)) {return;}
		     js = d.createElement(s); js.id = id;
		     js.src = "//connect.facebook.net/en_US/sdk.js";
		     fjs.parentNode.insertBefore(js, fjs);
		   }(document, 'script', 'facebook-jssdk'));
		</script>
		<!--[if lt IE 7]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
         <div class="contLoading">
	       	<i class="fa fa-spinner fa-pulse fa-5x"></i>
	       </div>
		<div clas="row">
			<header class="header">
				<div class="container">
					<a href="{{ URL::to('inicio') }}">
						@if($title != "pasillo24.com el portal de comercio  creado por bolivianos para bolivianos")
							<img src="{{ asset('images/logo.png') }}" class="logo ">
						@else
							<img src="{{ asset('images/logo.png') }}" class="logo logoFront">
						@endif
					</a>
					@if($title != "pasillo24.com el portal de comercio  creado por bolivianos para bolivianos")
					<nav class="navbar navbar-default">
						<div class="container-fluid">
							<!-- Brand and toggle get grouped for better mobile display -->
							<div class="navbar-header">

								<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">

								<span class="sr-only">Toggle navigation</span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
								</button>
								<ul class="navbar-nav redes2 hidden-xs hidden-md" style="float:right;">

									<li>
										<a target="_blank" href="https://twitter.com/pasillo_24">
											<i class="fa fa-twitter menu-icon" role="button" data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="Twitter"></i>
										</a>
									</li>
									<li>
										<a target="_blank" href="http://www.facebook.com/Pasillo24-906179522768848/?ref=ts&fref=ts">
											<i class="fa fa-facebook menu-icon" role="button" data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="Facebook"></i>
										</a>
									</li>
									<li>
										<a target="_blank" href="https://www.youtube.com/channel/UCIMKRE2kcjEQ4KgbiuX-AwQ">
											<i class="fa fa-youtube menu-icon" role="button" data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="Youtube"></i>

										</a>
									</li>
									<li>
										<a target="_blank" href="http://blog.pasillo24.com">
											<i class="fa fa-wordpress menu-icon" role="button" data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="Blog"></i>

										</a>
									</li>
									<li>
										<a target="_blank" href="https://www.instagram.com/pasillo24/">
											<i class="fa fa-instagram menu-icon" role="button" data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="Instagram"></i>

										</a>
									</li>
								</ul>
							</div>
							<!-- Collect the nav links, forms, and other content for toggling -->
							<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
								<ul class="navbar-nav redes" style="float:right;">

									<li>
										<a target="_blank" href="https://twitter.com/pasillo_24">
											<i class="fa fa-twitter menu-icon" role="button" data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="Twitter"></i>
										</a>
									</li>
									<li>
										<a target="_blank" href="http://www.facebook.com/Pasillo24-906179522768848/?ref=ts&fref=ts">
											<i class="fa fa-facebook menu-icon" role="button" data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="Facebook"></i>
										</a>
									</li>
									<li>
										<a target="_blank" href="https://www.youtube.com/channel/UCIMKRE2kcjEQ4KgbiuX-AwQ">
											<i class="fa fa-youtube menu-icon" role="button" data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="Youtube"></i>

										</a>
									</li>
									<li>
										<a target="_blank" href="http://blog.pasillo24.com">
											<i class="fa fa-wordpress menu-icon" role="button" data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="Blog"></i>

										</a>
									</li>
									<li>
										<a target="_blank" href="https://www.instagram.com/pasillo24/">
											<i class="fa fa-instagram menu-icon" role="button" data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="Instagram"></i>

										</a>
									</li>
								</ul>
								<ul class="nav navbar-nav form-movil" style="float:right;">
									<li style="text-align:center;">
										<form mothod="POST" action="{{ URL::to('inicio/buscar') }}" class="form-search">
											<input type="text" class="form-control input-search" name="busq" style="width:70%;vertical-align: middle;
margin-top: 0.5em;display:inline-block;">
											<button class="btn btn-success btn-buscar" style="display:inline-block;margin-top: 0.5em;">Buscar</button>
										</form>

									</li>
									<li>
										<a href="{{ URL::to('inicio') }}" class="btn log">
											<span class="fa fa-home menu-icon" role="button" data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="Inicio">
											</span>
											<p class="menuOpt">Inicio</p>

										</a>
									</li>
									<li>
										<a href="{{ URL::to('inicio/contactenos') }}" class="btn log" type="submit">
											<span class="fa fa-envelope menu-icon" role="button" data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="Contáctenos">
											</span>
											<p class="menuOpt">Contáctenos</p>
										</a>
									</li>
									<li>
										<a href="{{ URL::to('inicio/terminos-y-condiciones') }}" class="btn log">
											<span class="fa fa-exclamation-triangle menu-icon termAndCond" role="button" data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="Términos y condiciones"></span>
											<p class="menuOpt">Términos y condiciones</p>
										</a>
									</li>
									@if(!Auth::check())
										<li><a href="{{ URL::to('inicio/login') }}" class="btn log" type="submit">
											<span class="fa fa-check menu-icon"  role="button" data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="Iniciar sesión">
											</span>
											<p class="menuOpt">Iniciar sesión</p>
											</a>
										</li>
										<li><a href="{{ URL::to('inicio/registro') }}" class="btn sign" type="submit">
											<span class="fa fa-sign-in menu-icon" role="button" data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="Registrarse"></span>
											<p class="menuOpt">Registrarse</p>
											</a>
										</li>
									@else
										<li class="dropdown myMenu">
											<a href="#" class="dropdown-toggle log textoPromedio" data-toggle="dropdown" role="button" aria-expanded="false">
												<i class="fa fa-user menu-icon" role="button" data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="{{ Auth::user()['username'] }}"></i>
												<span class="caret"></span></a>
											<ul class="dropdown-menu multi-level" role="menu">
												@if(Auth::user()->role != 'Administrador' && Auth::user()->role != 'Gestor' && Auth::user()->role != 'Moderador')
												<li>
													<a href="{{ URL::to('usuario/perfil') }}">
														<span class="fa fa-cog"></span> Perfil
													</a>
												</li>
												<li class="divider"></li>
												<li>
													<a href="{{ URL::to('usuario/publicar') }}">
														<span class="fa fa-pencil-square-o"></span> Publicar
													</a>
												</li>
												<li>
													<a href="{{ URL::to('usuario/publicaciones/mis-publicaciones') }}">
														<i class="fa fa-book"></i> Mis publicaciones
													</a>
												</li>
												<li class="divider"></li>
												<li>
													<a href="{{ URL::to('usuario/mis-compras') }}">
														<i class="fa fa-shopping-cart"></i> Mis compras
													</a>
												</li>
												<li>
													<a href="{{ URL::to('usuario/mis-ventas') }}">
														<i class="fa fa-cart-arrow-down"></i>
 Mis ventas
													</a>
												</li>
												
												<li class="divider"></li>
												<li>
													<a href="{{ URL::to('usuario/mi-reputacion') }}">
														<span class="fa fa-star"></span> Reputación
													</a>
												</li>
												<li><a href="{{ URL::to('usuario/publicaciones/comentarios') }}">
													<span class="fa fa-comments-o"></span><sup class="subComentario"></sup> Comentarios</a>
												</li>
												@else

												<li>
													<a href="{{ URL::to('administrador/pagos') }}">
														<i class="fa fa-credit-card"></i> Ver Pagos
													</a>
												</li>
												<li>
													<a href="{{ URL::to('administrador/publicaciones') }}">
														<i class="fa fa-book"></i> Ver Publicaciones
													</a>
												</li>
												<li class="showMovil">
													<a href="{{ URL::to('administrador/modificar-publicaciones') }}">
														<i class="fa fa-list-alt"></i>Modifica textos de publicaciones
													</a>
												</li>
												<li class="showMovil">
													<a href="{{ URL::to('administrador/modificar-precios') }}">
														<i class="fa fa-money"></i>
														Modifica Precios
													</a>
												</li>
												<li class="showMovil">
													<a href="{{ URL::to('administrador/categorias') }}">
														<i class="fa fa-list"></i> Ver Categorias / Servicios
													</a>
												</li>
												<li class="showMovil">
													<a href="{{ URL::to('administrador/sub-categorias') }}">
														<i class="fa fa-list-alt"></i> Ver Sub-categorias
													</a>
												</li>
												@if(Auth::user()->role != 'Moderador')
												<li class="dropdown-submenu noDisplay">
													<a href="#" >
														<i class="fa fa-cogs"></i> Modificaciones
													</a>
													<ul class="dropdown-menu" role="menu">
														<li>
															<a href="{{ URL::to('administrador/modificar-publicaciones') }}">
																<i class="fa fa-list-alt"></i> Textos de publicaciones
															</a>
														</li>
														<li>
															<a href="{{ URL::to('administrador/modificar-precios') }}">
																<i class="fa fa-money"></i>
																Precios
															</a>
														</li>
														<li>
															<a href="{{ URL::to('administrador/editar-publicidad') }}">
																<i class="fa fa-tags"></i>
																Cambiar publicidad
															</a>
														</li>
														<li>
															<a href="{{ URL::to('administrador/categorias') }}">
																<i class="fa fa-list"></i>
																Ver Categorias / Servicios
															</a>
														</li>
														<li>
															<a href="{{ URL::to('administrador/sub-categorias') }}">
																<i class="fa fa-list-alt"></i>
																Ver sub-ategorias
															</a>
														</li>
													</ul>
												</li>
												<li class="showMovil">
													<a href="{{ URL::to('administrador/crear-nuevo') }}">
														<i class="fa fa-user-plus"></i> Crear usuario
													</a>
												</li>
												<li class="showMovil">
													<a href="{{ URL::to('administrador/eliminar-usuario') }}">
														<i class="fa fa-user-times"></i> Eliminar usuario
													</a>
												</li>
												<li class="dropdown-submenu noDisplay">
													<a href="#" >
														<i class="fa fa-users"></i> Usuarios
													</a>
													<ul class="dropdown-menu" role="menu">
														<li>
															<a href="{{ URL::to('administrador/crear-nuevo') }}">
																<i class="fa fa-user-plus"></i> Crear usuario
															</a>
														</li>
														<li>
															<a href="{{ URL::to('administrador/eliminar-usuario') }}">
																<i class="fa fa-user-times"></i> Eliminar usuario
															</a>
														</li>
													</ul>
												</li>
												<li class="dropdown-submenu noDisplay">
													<a href="#" >
														<i class="fa fa-users"></i> Bancos
													</a>
													<ul class="dropdown-menu" role="menu">
														<li>
															<a href="{{ URL::to('administrador/agregar-cuenta') }}">
																<i class="fa fa-university"></i> Agregar cuenta
															</a>
														</li>
														<li>
															<a href="{{ URL::to('administrador/editar-cuenta') }}">
																<i class="fa fa-cogs"></i> Editar cuentas
															</a>
														</li>
													</ul>
												</li>
												@endif
												
												@endif
												<li>
													<a href="{{ URL::to('usuario/cambiar-clave') }}"><i class="fa fa-exchange"></i>
 Cambiar Contraseña</a>
												</li>
											</ul>
										</li>
										<li>
											<a href="{{ URL::to('inicio/logout') }}" class="btn log" type="submit">
												<span class="fa fa-sign-out menu-icon btn-cerrarSession"  role="button" data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="Cerrar sesión"></span>
												<p class="menuOpt">Cerrar sesión</p>
											</a>
										</li>
									@endif
									
								</ul>
								
								</div><!-- /.navbar-collapse -->
								
							</div><!-- /.container-fluid -->
					</nav>
					@else
					<ul class="mainMenu nav navbar-nav menuFront">
						<li><a href="{{ URL::to('mision-y-vision') }}" class="btn log hidden-xs"><strong>¿Qué es pasillo24.com?</strong></a></li>
						<li><a href="{{ URL::to('inicio') }}" class="btn log"><strong>Ingresar</strong></a></li>
					</ul>
					@endif
				</div>
			</header>
		</div>
		@yield('content')
		<div class="row @if(isset($portada)) portadaFooter @else @endif" style="margin-right:0px;">
			<footer>
				<div class="panel-footer col-xs-12">
					<p class="footerText textoPromedio"><strong>pasillo24.com</strong>. Todos los derechos reservados &copy 2016| <a href="{{ URL::to('inicio/terminos-y-condiciones') }}">Términos y condiciones de uso</a> | <a href="{{ URL::to('inicio/politica-de-privacidad') }}">Política de privacidad</a></p>
				</div>
			</footer>
		</div>
	<input type="hidden" value="{{  URL::full() }}" id="urlBase">
	
	{{ HTML::script('js/jquery.min.js') }}
	{{ HTML::script('js/bootstrap.min.js') }}
	{{ HTML::script('js/custom-preview.js?v=1.5')}}
	
	{{ HTML::script('js/ckeditor.js') }}
	{{ HTML::script('js/jquery.ckeditor.js') }}
	{{ HTML::script('js/owl.carousel.min.js') }}
	{{ HTML::script('//code.jquery.com/ui/1.11.2/jquery-ui.js') }}

       <script type="text/javascript">
       $(function () {
		  $('[data-toggle="popover"]').popover()
		})
       </script>
       @yield('postscript')
       <script type="text/javascript">
       	jQuery(document).ready(function($) {
				$('.contLoading').fadeOut('fast',function(){
					$(this).hide('fast')
				})
       	});
       </script>
       <script>
	  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

	  ga('create', 'UA-65010779-1', 'auto');
	  ga('send', 'pageview');

	</script>
     <!--Start of Zopim Live Chat Script-->
<script type="text/javascript">
window.$zopim||(function(d,s){var z=$zopim=function(c){z._.push(c)},$=z.s=
d.createElement(s),e=d.getElementsByTagName(s)[0];z.set=function(o){z.set.
_.push(o)};z._=[];z.set._=[];$.async=!0;$.setAttribute("charset","utf-8");
$.src="//v2.zopim.com/?3VU6EaVziALL1oDsFWNHJ2aTsaLDknMU";z.t=+new Date;$.
type="text/javascript";e.parentNode.insertBefore($,e)})(document,"script");
</script>
<!--End of Zopim Live Chat Script-->
       <script type="text/javascript">

       $( "#fechIni" ).datepicker({

			inline: true,
			showAnim: 'fadeIn',
			dateFormat: 'd-m-yy',
			minDate: 0
		});
       </script>
	</body>
</html>