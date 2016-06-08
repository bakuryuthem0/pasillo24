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
        <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet">

		{{ HTML::style('css/bootstrap-theme.min.css')}}
		{{ HTML::style('css/custom.css?v=1.9') }}
		{{ HTML::style('css/jquery.cleditor.css') }}
		{{ HTML::style('css/owl.carousel.css') }}
		{{ HTML::style('css/owl.theme.default.css') }}
        {{ HTML::style('css/ui-lightness/jquery-ui-1.9.2.custom.min.css') }}
		{{ HTML::script('js/jquery.min.js') }}
		{{ HTML::script('js/owl.carousel.min.js') }}
		<script src='https://www.google.com/recaptcha/api.js'></script>
		<script type="text/javascript" src="http://maps.google.com/maps/api/js?v=3"></script>
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
		     js.src = "//connect.facebook.net/es_LA/sdk.js";
		     fjs.parentNode.insertBefore(js, fjs);
		   }(document, 'script', 'facebook-jssdk'));
		</script>
		<!--[if lt IE 7]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
        <div class="contLoading">
	       	<i class="fa fa-spinner fa-pulse fa-5x"></i>
        </div>
        <input type="hidden" value="{{ URL::to('/') }}" class="baseUrl">
		 <header>
                @if($title != "pasillo24.com el portal de comercio  creado por bolivianos para bolivianos")
                <nav class="navbar">
                    <div class="wrapper">
                        <div class="nav-container">
                            <div class="logo">
                                <img src="{{ asset('images/logo.png') }}">
                            </div>
                            <div class="menu visible-md-block visible-lg-block">
                                <form class="navbar-form navbar-left" role="search">
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="Search">
                                    </div>
                                    <button type="submit" class="btn btn-flat btn-search">Buscar</button>
                                </form>
                                <ul class="nav navbar-nav">
                                    <li class="active">
                                        <a href="{{ URL::to('inicio') }}">
                                            <i class="fa fa-home menu-icon" role="button" data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="Inicio"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ URL::to('inicio/contactenos') }}">
                                            <i class="fa fa-envelope menu-icon" role="button" data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="Contáctenos"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ URL::to('inicio/terminos-y-condiciones') }}">
                                            <i class="fa fa-exclamation-triangle menu-icon termAndCond" role="button" data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="Términos y condiciones"></i>
                                        </a>
                                    </li>
                                    @if(!Auth::check())
                                    <li>
                                        <a href="{{ URL::to('inicio/login') }}">
                                            <i class="fa fa-check menu-icon"  role="button" data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="Iniciar sesión"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ URL::to('inicio/registro') }}">
                                            <i class="fa fa-sign-in menu-icon" role="button" data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="Registrarse"></i>
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
                                                <li>
                                                    <a href="{{ URL::to('usuario/mis-favoritos') }}">
                                                        <i class="fa fa-heart"></i>
                                                        Mis favoritos
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
                                                <li class="divider"></li>
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
                                                    <li class="divider"></li>
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
                                                <a href="{{ URL::to('usuario/cambiar-clave') }}">
                                                    <i class="fa fa-exchange"></i> Cambiar Contraseña
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li>
                                        <a href="{{ URL::to('inicio/logout') }}" class="btn log" type="submit">
                                            <span class="fa fa-sign-out menu-icon btn-cerrarSession"  role="button" data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="Cerrar sesión"></span>
                                        </a>
                                    </li>
                                    @endif
                                </ul>
                            </div>
                            <div class="redes visible-md-block visible-lg-block">
                                <ul class="nav navbar-nav navbar-right">
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
                            <button class="btn btn-flat btn-menu pull-right hidden-md hidden-lg " data-toggle="collapse" href="#menu-movile" >
                                <i class="fa fa-bars"></i>
                            </button>
                            <div  class="col-xs-12 col-sm-8 hidden-md hidden-lg menu-movil collapse " id="menu-movile">
                                <form class="navbar-form form-movil" role="search">
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="Search">
                                    </div>
                                    <button type="submit" class="btn btn-flat btn-search">Buscar</button>
                                </form>
                                <ul class="">
                                    <li class="active">
                                        <a href="{{ URL::to('inicio') }}">
                                            <i class="fa fa-home menu-icon" role="button" ></i>
                                            <p>Inicio</p>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ URL::to('inicio/contactenos') }}">
                                            <i class="fa fa-envelope menu-icon" role="button"></i>
                                            <p>Contáctenos</p>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ URL::to('inicio/terminos-y-condiciones') }}">
                                            <i class="fa fa-exclamation-triangle menu-icon termAndCond" role="button" ></i>
                                            <p>Términos y condiciones</p>
                                        </a>
                                    </li>
                                    @if(!Auth::check())
                                    <li>
                                        <a href="{{ URL::to('inicio/login') }}">
                                            <i class="fa fa-check menu-icon"  role="button" ></i>
                                            <p>Iniciar sesión</p>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ URL::to('inicio/registro') }}">
                                            <i class="fa fa-sign-in menu-icon" role="button" ></i>
                                            <p>Registrarse</p>
                                        </a>
                                    </li>
                                    @else
                                    <li class="">
                                        <a data-toggle="collapse" href="#submenu" aria-expanded="false" aria-controls="submenu">
                                            <i class="fa fa-user menu-icon" role="button"></i> 
                                            <p>{{ Auth::user()->username }}</p>
                                            <span class="caret"></span>
                                        </a>
                                        <ul class="collapse" id="submenu">
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
                                                    <li>
                                                        <a href="{{ URL::to('usuario/mis-favoritos') }}">
                                                            <i class="fa fa-heart"></i>
                                                            Mis favoritos
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
                                                    @if(Auth::user()->role != 'Moderador')
                                                        <li>
                                                            <a data-toggle="collapse" href="#subsubmenu" aria-expanded="false" aria-controls="subsubmenu">
                                                                <i class="fa fa-cogs"></i> Modificaciones 
                                                                <span class="caret"></span>
                                                            </a>
                                                            <ul class="collapse" id="subsubmenu" role="menu">
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
                                                        <li >
                                                            <a data-toggle="collapse" href="#submenu-usuario" aria-expanded="false" aria-controls="submenu-usuario">
                                                                <i class="fa fa-users"></i> Usuarios
                                                                <span class="caret"></span>
                                                            </a>
                                                            <ul class="collapse" id="submenu-usuario" role="menu">
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
                                                        <li>
                                                            <a data-toggle="collapse" href="#submenu-banco" aria-expanded="false" aria-controls="submenu-banco">
                                                                <i class="fa fa-users"></i> Bancos
                                                                <span class="caret"></span>
                                                            </a>
                                                            <ul class="collapse" id="submenu-banco" role="menu">
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
                                                    <a href="{{ URL::to('usuario/cambiar-clave') }}">
                                                        <i class="fa fa-exchange"></i> Cambiar Contraseña</a>
                                                </li>
                                            </ul>
                                        </li>
                                        <li>
                                            <a href="{{ URL::to('inicio/logout') }}" class="btn log" type="submit">
                                                <span class="fa fa-sign-out menu-icon btn-cerrarSession"  role="button" data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="Cerrar sesión"></span>
                                                <p class="menuOpt">Cerrar sesión</p>
                                            </a>
                                        </li>
                                    </ul>
                                    </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                </nav>
                @else
                    <ul class="mainMenu nav navbar-nav menuFront">
                        <li><a href="{{ URL::to('mision-y-vision') }}" class="btn log hidden-xs"><strong>¿Qué es pasillo24.com?</strong></a></li>
                        <li><a href="{{ URL::to('inicio') }}" class="btn log"><strong>Ingresar</strong></a></li>
                    </ul>
                @endif
            </header>
            @yield('content')
            <footer>
                <div class="wrapper">
                    <div class="col-xs-12 text-center">
                        <p class="footerText textoPromedio"><strong>pasillo24.com</strong>. Todos los derechos reservados &copy 2016| <a href="{{ URL::to('inicio/terminos-y-condiciones') }}">Términos y condiciones de uso</a> | <a href="{{ URL::to('inicio/politica-de-privacidad') }}">Política de privacidad</a></p>
                    </div>
                </div>
            </footer>
	<input type="hidden" value="{{  URL::full() }}" id="urlBase">
	
	{{ HTML::script('js/jquery.min.js') }}
	{{ HTML::script('js/bootstrap.min.js') }}
	{{ HTML::script('js/custom-preview.js?v=1.8')}}
	
	{{ HTML::script('js/ckeditor.js') }}
	{{ HTML::script('js/jquery.ckeditor.js') }}
	{{ HTML::script('js/owl.carousel.min.js') }}
	{{ HTML::script('js/jquery-ui-1.9.2.custom.min.js') }}

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