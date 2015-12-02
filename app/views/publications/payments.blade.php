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
				                  <select name="banco" class="form-control" required>
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
				                <label>Numero de transacción / Codigo de transacción de PayPal</label>
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
						<div class="col-xs-6">
							<h2 class="payment-text">Total a pagar:</h2>
							<h3 class="payment-amount">{{ $pub->monto }} Bs.</h3>
						</div>
					</div>
				</form>
				
				<div class="clearfix"></div>
			</div>
			<div class="contAnaranjado">
				<div class="col-xs-12">
					<legend>Pago con <strong>PayPal</strong></legend>
					<div class="col-xs-12"><p class="textoPromedio">pasillo24.com le ofrece la facilidad de pagar mediante nuestra cuenta paypal.</p></div>
					<div class="col-xs-12">
						<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
							<input type="hidden" name="cmd" value="_xclick">
							<input type="hidden" name="business" value="32XX7VACEPPQ4">
							<input type="hidden" name="lc" value="AL">
							<input type="hidden" name="item_name" value="Pago de publicacion | pasillo24.com">
							<input type="hidden" name="button_subtype" value="services">
							<input type="hidden" name="no_note" value="0">
							<input type="hidden" name="cn" value="Dar instrucciones especiales al vendedor:">
							<input type="hidden" name="no_shipping" value="2">
							<input type="hidden" name="amount" value="{{ number_format(($pub->monto/6.94),2,'.','') }}">
							<input type="hidden" name="currency_code" value="USD">
							<input type="hidden" name="bn" value="PP-BuyNowBF:btn_paynowCC_LG.gif:NonHosted">
							<input type="image" src="https://www.paypalobjects.com/es_XC/i/btn/btn_paynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
							<img alt="" border="0" src="https://www.paypalobjects.com/es_XC/i/scr/pixel.gif" width="1" height="1">
						</form>

					</div>
					<div class="col-xs-12" style="margin-top:1em;">
						<p class="textoPromedio">Si usa el método de pago por PayPal, una vez realizado el pago debe seleccionar la opción <strong>Cuenta PayPal pasillo24</strong> que se encuentra en la lista desplegable <strong>Banco de destino</strong> e ingresar el número de transacción mediante nuestro formulario de pago.Recuerde que el correo para el pago por PayPal es <a href="mailto:aquipasillo24@gmail.com">aquipasillo24@gmail.com</a></p>
						<p class="textoPromedio">La tasa de Cambio es 6.94 Bs. El monto a pagar en dolares es: ${{ number_format(($pub->monto/6.94),2,',','') }} </p>
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
						<div class="col-xs-12">
							<p class="textoPromedio">Número(s) de cuenta(s)</p>
							<ul class="textoPromedio">
							@foreach($numCuentas as $num)
								@if($num->banco_id == 2)
									<li><label>Número de cuenta:</label>{{$num->num_cuenta}} - <label>Tipo de cuenta:</label> {{ $num->tipoCuenta }}</li>
								@endif
							@endforeach
							</ul>
							<a target="_blank" href="http://www.bnb.com.bo/portal/frmpaginaprincipal.html">
								<img src="{{ asset('images/bancos/bnb.jpg') }}" style="width:100%;margin-bottom:2em;">
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
							<a target="_blank" href="http://www.bancounion.com.bo/pub/pub/AgenciasCajeros.aspx?rbtAgencia=true&rbtCajero=false&rbtRecinto=false&Buscar=no">
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
							<a target="_blank" href="http://www3.bcp.com.bo/nuestrobanco/oficinas.asp">
								<img src="{{ asset('images/bancos/3.png') }}" style="width:100%;margin-bottom:2em;">
							</a>
						</div>
						<div class="col-xs-12" style="margin-top:2em;">
							<p class="textoPromedio">Numero(s) de cuenta(s)</p>
							<ul class="textoPromedio">
							@foreach($numCuentas as $num)
								@if($num->banco_id == 1)
									<li><label>Número de cuenta:</label>{{$num->num_cuenta}} - <label>Tipo de cuenta:</label> {{ $num->tipoCuenta }}</li>
								@endif
							@endforeach
							</ul>
							<a target="_blank" href="https://www.bmsc.com.bo/Paginas/reddeoficinas.aspx">
								<img src="{{ asset('images/bancos/1.png') }}" style="width:100%;margin-bottom:2em;"/>
							</a>
						</div>
						<div class="col-xs-12">
							<p class="textoPromedio">Número(s) de cuenta(s)</p>
							<ul class="textoPromedio">
							@foreach($numCuentas as $num)
								@if($num->banco_id == 6)
									<li><label>Número de cuenta:</label>{{$num->num_cuenta}} - <label>Tipo de cuenta:</label> {{ $num->tipoCuenta }}</li>
								@endif
							@endforeach
							</ul>
							<a target="_blank" href="http://www.bisa.com/mapav4.php?lat=-17.772131&lon=-63.182598&dep=10">
								<img src="{{ asset('images/bancos/6.png') }}" style="width:100%;margin-bottom:2em;"></a>
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