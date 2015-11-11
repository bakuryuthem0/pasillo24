@extends('main')
@section('content')
<div class="container contenedorUnico">
	<div class="row">
		<div class="col-xs-12">
			@if(Session::has('success'))
			<div class="alert alert-success">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				<p class="textoPromedio">{{ Session::get('success') }}</p>
			</div>
			@endif
			<div class="contAnaranjado">
				<div class="col-xs-12">
					<ol class="breadcrumb textoPromedio">
						<li><a href="{{ URL::to('usuario/publicar') }}" class="breadcrums"><span class="num">1</span> Elije el tipo de publicación.</a></li>
						<li class="active"><a href="{{ URL::to('usuario/publicacion/lider') }}" class="breadcrums"><span class="num">2</span> Complete el formulario LÍDER</a></li>
						<li class="active"><a href="#" class="breadcrums"><span class="num numActivo">3</span> Envíe el número de transferencia.</a></li>
					</ol>
				</div>
				<div class="col-xs-12">
					<div class="col-xs-12" style="padding-left:0px;">
					<legend>DEPÓSITO O TRANSFERENCIA BANCARIA:</legend>
						<p class="textoPromedio" style="text-align:justify;">
						Usted ha elegido la opción de pagar a través de TRANSFERENCIA O DEPÓSITO 
							BANCARIO, recuerde que contamos con más de 5 entidades bancarias a las cuales usted 
							puede apersonarse a fin de realizar la cancelación del servicio mediante un depósito o 
							realizar una transferencia desde la comodidad de su hogar. Para conocer nuestros 
							números de cuentas, entidades bancarias y sucursales  en todo el territorio nacional le 
							invitamos a pulsar sobre el ícono “CUENTAS Y ENTIDADES BANCARIAS”. Una vez 
							hecho el depósito o transferencia bancaria debe enviarnos el número de transacción.
							Recuerde que los datos del depositante y del titular de la cuenta en pasillo24.com. deben ser 
							los mismos.
							Si dentro de las 48 horas próximas no se ha confirmado la publicación del anuncio 
							mediante el pago respectivo, este será eliminado de nuestra base de datos 
							automáticamente
							</p>
					</div>
				</div>
				<div class="col-xs-12">
					<div class="col-xs-12" style="padding-left:0px;">
						<p class="textoPromedio">Una vez haya realizado su pago, introduzca el número de transacción en la casilla</p>
					</div>
				</div>
				<form method="post" action="{{ URL::to('usuario/publicaciones/pago/enviar') }}">
					<div class="col-xs-12">
						<div class="col-xs-6" style="padding-left:0px;">
							<div class="col-xs-12 formulario textoPromedio" required>
				                <label>Banco de destino</label>
				                  <select name="banco" class="form-control">
				                    <option value="">Seleccione el banco</option>
				                    @foreach($bancos as $b)
				                      <option value="{{ $b->id }}">{{ $b->nombre }}</option>
				                    @endforeach
				                  </select>
				                  @if ($errors->has('banco'))
									 @foreach($errors->get('banco') as $err)
									 	<div class="alert alert-danger">
									 		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
									 		<p class="textoPromedio">{{ $err }}</p>
									 	</div>
									 @endforeach
								@endif
				                <p class="bg-info" style="padding:0.5em;margin-top:2em;">Para ver la informacion de cada banco, precione el botón "CUENTAS Y ENTIDADES BANCARIAS"</p>
			              	</div>
							<div class="col-xs-12 formulario textoPromedio">
				                <label>Banco Emisor (si el pago del servicio fue mediante depósito bancario, omita esta casilla)</label>
				                <input type="text" class="form-control" name="emisor" >
				                @if ($errors->has('emisor'))
									 @foreach($errors->get('emisor') as $err)
									 	<div class="alert alert-danger">
									 		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
									 		<p class="textoPromedio">{{ $err }}</p>
									 	</div>
									 @endforeach
								@endif
			                </div>
			              	<div class="col-xs-12 formulario textoPromedio">
				                <label>Fecha de transacción</label>
				                <input type="text" id="fecha" name="fecha" placehlder="DD-MM-YYYY" class="form-control" required>
				                @if ($errors->has('fecha'))
									 @foreach($errors->get('fecha') as $err)
									 	<div class="alert alert-danger">
									 		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
									 		<p class="textoPromedio">{{ $err }}</p>
									 	</div>
									 @endforeach
								@endif
			              	</div>
			              	<div class="col-xs-12 formulario textoPromedio">
				                <label>Numero de transacción</label>
								<input type="text" id="numTransVal" name="transNumber" placehlder="Numero de transaccion" class="form-control" required>
								@if ($errors->has('transNumber'))
									 @foreach($errors->get('transNumber') as $err)
									 	<div class="alert alert-danger">
									 		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
									 		<p class="textoPromedio">{{ $err }}</p>
									 	</div>
									 @endforeach
								@endif
			              	</div>
						<button class="btn btn-success" id="enviarNumTrans" value="{{ $pub_id }}" name="enviarPago" style="margin-top:2em;">Enviar</button>
						<a class="btn btn-primary"  data-toggle="modal" data-target="#myModalBancos" style="margin-top:2em;">CUENTAS Y ENTIDADES BANCARIAS.</a>
							
						</div>
					</div>
				</form>
				
				<div class="clearfix"></div>
			</div>
			<div class="contAnaranjado">
				<div class="col-xs-12">
					<legend>Pago con <strong>PayPal</strong></legend>
					<p class="textoPromedio">pasillo24.com le ofrece la facilidad de pagar mediante nuestra cuenta paypal.</p>
					<div class="col-xs-12">
						<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
							<input type="hidden" name="cmd" value="_s-xclick">
							<input type="hidden" name="hosted_button_id" value="GPH56GS2CYEEY">
							<input type="image" src="https://www.paypalobjects.com/es_XC/i/btn/btn_paynowCC_LG.gif" border="0" name="submit" alt="PayPal, la forma más segura y rápida de pagar en línea.">
							<img alt="" border="0" src="https://www.paypalobjects.com/es_XC/i/scr/pixel.gif" width="1" height="1">
						</form>
					</div>
				</div>
				<div class="clearfix"></div>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="myModalBancos" tabindex="-1" role="dialog" aria-labelledby="myModalBancos" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title" id="myModalLabel">Lista de bancos.</h4>
				</div>
				<form method="POST" action="">
					<div class="modal-body">
						<p class="textoPromedio">Haga click en el banco de su preferencia para ver la sucursal mas cercana a su domicilio.</p>
						<div class="col-xs-12" style="margin-top:2em;">
							<p class="textoPromedio">Numero(s) de cuenta(s)</p>
							<ul class="textoPromedio">
							@foreach($numCuentas as $num)
								@if($num->banco_id == 1)
									<li><label>Número de cuenta:</label>{{$num->num_cuenta}} - <label>Tipo de cuenta:</label> {{ $num->tipoCuenta }}</li>
								@endif
							@endforeach
							</ul>
							<a target="_blank" href="https://www.bmsc.com.bo/Puntos%20de%20Atencion/redDeOficinas.aspx
">
								<img src="{{ asset('images/bancos/1.png') }}" style="width:100%;margin-bottom:2em;"/>
							</a>
						</div>
						<div class="col-xs-12">
							<p class="textoPromedio">Número(s) de cuenta(s)</p>
							<ul class="textoPromedio">
							@foreach($numCuentas as $num)
								@if($num->banco_id == 2)
									<li><label>Número de cuenta:</label>{{$num->num_cuenta}} - <label>Tipo de cuenta:</label> {{ $num->tipoCuenta }}</li>
								@endif
							@endforeach
							</ul>
							<a target="_blank" href="http://www.bancounion.com.bo/index.php?option=com_content&task=view&id=177&Itemid=234">
								<img src="{{ asset('images/bancos/2.png') }}" style="width:100%;margin-bottom:2em;">
							</a>
						</div>
						<div class="col-xs-12">
							<p class="textoPromedio">Número(s) de cuenta(s)</p>
							<ul class="textoPromedio">
							@foreach($numCuentas as $num)
								@if($num->banco_id == 3)
									<li><label>Número de cuenta:</label>{{$num->num_cuenta}} - <label>Tipo de cuenta:</label> {{ $num->tipoCuenta }}</li>
								@endif
							@endforeach
							</ul>
							<a target="_blank" href="http://www.bcp.com.bo/nuestrobanco/oficinas.asp">
								<img src="{{ asset('images/bancos/3.png') }}" style="width:100%;margin-bottom:2em;">
							</a>
						</div>
						<div class="col-xs-12">
							<p class="textoPromedio">Número(s) de cuenta(s)</p>
							<ul class="textoPromedio">
							@foreach($numCuentas as $num)
								@if($num->banco_id == 4)
									<li><label>Número de cuenta:</label>{{$num->num_cuenta}} - <label>Tipo de cuenta:</label> {{ $num->tipoCuenta }}</li>
								@endif
							@endforeach
							</ul>
							<a target="_blank" href="http://www.losandesprocredit.com.bo/bolivia.aspx">
								<img src="{{ asset('images/bancos/4.png') }}" style="width:100%;margin-bottom:2em;"></a></div>
						<div class="col-xs-12">
							<p class="textoPromedio">Número(s) de cuenta(s)</p>
							<ul class="textoPromedio">
							@foreach($numCuentas as $num)
								@if($num->banco_id == 5)
									<li><label>Número de cuenta:</label>{{$num->num_cuenta}} - <label>Tipo de cuenta:</label> {{ $num->tipoCuenta }}</li>
								@endif
							@endforeach
							</ul>
							<a target="_blank" href="https://www.bg.com.bo/atms.aspx">
								<img src="{{ asset('images/bancos/5.png') }}" style="width:100%;margin-bottom:2em;"></a></div>
						<div class="col-xs-12">
							<p class="textoPromedio">Número(s) de cuenta(s)</p>
							<ul class="textoPromedio">
							@foreach($numCuentas as $num)
								@if($num->banco_id == 6)
									<li><label>Número de cuenta:</label>{{$num->num_cuenta}} - <label>Tipo de cuenta:</label> {{ $num->tipoCuenta }}</li>
								@endif
							@endforeach
							</ul>
							<a target="_blank" href="http://bisa.com/buscarPuntoAtencion.php
"><img src="{{ asset('images/bancos/6.png') }}" style="width:100%;margin-bottom:2em;"></a></div>
						<div class="col-xs-12">
							<p class="textoPromedio">Número(s) de cuenta(s)</p>
							<ul class="textoPromedio">
							@foreach($numCuentas as $num)
								@if($num->banco_id == 7)
									<li><label>Número de cuenta:</label>{{$num->num_cuenta}} - <label>Tipo de cuenta:</label> {{ $num->tipoCuenta }}</li>
								@endif
							@endforeach
							</ul>
							<a target="_blank" href="https://www.baneco.com.bo/oficinas/oficinas-santacruz
"><img src="{{ asset('images/bancos/7.png') }}" style="width:100%;margin-bottom:2em;"></a></div>
						<div class="col-xs-12">
							<p class="textoPromedio">Número(s) de cuenta(s)</p>
							<ul class="textoPromedio">
							@foreach($numCuentas as $num)
								@if($num->banco_id == 8)
									<li><label>Número de cuenta:</label>{{$num->num_cuenta}} - <label>Tipo de cuenta:</label> {{ $num->tipoCuenta }}</li>
								@endif
							@endforeach
							</ul>
							<a target="_blank" href="http://www.bancosol.com.bo/contactenos/direcciones-de-agencias
"><img src="{{ asset('images/bancos/8.png') }}" style="width:100%;margin-bottom:2em;"></a></div>
					</div>
					<div class="modal-footer">
					</div>
			</div>
		</div>
	</div>
@stop


@section('postscript')
<script type="text/javascript">
	 $( "#fecha" ).datepicker({

			inline: true,
			showAnim: 'fadeIn',
			dateFormat: 'd-m-yy',
		});
</script>
@stop