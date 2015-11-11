@extends('main')
@section('content')
<div class="container contenedorUnico">
	<div class="row">
		
			<div class="col-xs-10 col-sm-offset-1 contPubType contAnaranjado" style="margin-top:2em;">
				<div class="col-xs-12">
					<ol class="breadcrumb textoPromedio">
					 <li><a href="{{ URL::to('usuario/publicar') }}" class="breadcrums"><span class="num numActivo">1</span> Elija el tipo de publicación.</a></li>
					</ol>
				</div>
			@if(Session::has('error'))
			<div class="col-xs-12">
				<div class="alert alert-danger">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<p class="textoPromedio">{{ Session::get('error') }}</p>
				</div>
			</div>
			@endif
			<div class="col-xs-4 publication">
				<h3 class="footerText">ANUNCIO LÍDER</h3>
				<img src="{{asset('images/lider-01.png')}}" class="pubType">
				<p class="textoPromedio footerText textoPublicacion">
					Anuncio LÍDER es un espacio privilegiado de publicidad que se encuentra en la sección de menú principal y categorías, le permite al usuario que el cliente visite su página web en caso de tenerla, además la comunidad FFASIL se encarga de aumentar la publicidad de su anuncio a través de las principales redes sociales.
				</p>
				<a href="{{ URL::to('usuario/publicacion/lider') }}" class="btn btn-primary footerText btn-go-pub">Crear publicación</a>
			</div>
			<div class="col-xs-4 publication">
				<h3 class="footerText">ANUNCIO HABITUAL</h3>
				<img src="{{asset('images/habitual-01.png')}}" class="pubType">
				<p class="textoPromedio footerText textoPublicacion">
					Anuncio HABITUAL es una publicación destinada a productos y servicios, pudiendo determinar la categoría en la cual publicar, cada publicación tiene una duración de 70 días, ideal para el joven emprendedor permitiéndole adquirir reputación dentro de la comunidad FFASIL.
				</p>
				<a href="{{ URL::to('usuario/publicacion/habitual') }}" class="btn btn-primary footerText btn-go-pub">Crear publicación</a>
			</div>
			<div class="col-xs-4 publication">
				<h3 class="footerText">ANUNCIO CASUAL</h3>
				<img src="{{asset('images/casual-01.png')}}" class="pubType">
				<p class="textoPromedio footerText textoPublicacion">
					Al ingresar a nuestra comunidad tienes derecho a un anuncio CASUAL, que es de carácter gratuito.
	El anuncio tiene una duración de 7 días y sólo podrás publicar una vez por mes.
	Ideal para quienes buscan una venta rápida.
				</p>
				<a href="{{ URL::to('usuario/publicacion/casual') }}" class="btn btn-primary footerText btn-go-pub" >Crear publicación</a>
			</div>
			<div class="col-xs-12 pubBottonsCont">
				<div class="col-xs-4" style="padding-left:0px;">
					<a href="{{ URL::to('usuario/publicacion/lider') }}" class="btn btn-primary footerText" style="width:100%;">Crear publicación</a>
				</div>
				<div class="col-xs-4" style="padding: 0px;">
				<a href="{{ URL::to('usuario/publicacion/habitual') }}" class="btn btn-primary footerText" style="width:100%;">Crear publicación</a>

				</div>
				<div class="col-xs-4" style="padding-right:0px;">
				<a href="{{ URL::to('usuario/publicacion/casual') }}" class="btn btn-primary footerText" style="width:100%;">Crear publicación</a>

				</div>
			</div>
		</div>
		<div class="col-xs-10 col-sm-offset-1 pubInfo contPubType contAnaranjado">
			<h5>Recuerde que</h5>
			<ul class="textoPromedio">
				<li>Es un requisito publicar los productos con el precio real de venta.</li>
				<li>Procura colocar fotos reales, eso evitará malos entendidos entre las partes.</li>
				<li>No olvides leer las políticas y condiciones que rigen nuestra comunidad haciendo clic en el ícono superior derecho.</li>
			</ul>
		</div>
	</div>
</div>
@stop