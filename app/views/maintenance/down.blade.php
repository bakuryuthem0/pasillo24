<?php 
	date_default_timezone_set('America/La_Paz');
	$time = strtotime('30 January 2016 1:00:00');
	$now  = time();
	$dif  = $time-$now;
	$date = date('d-m-Y G:i:s',$dif);

	$date = explode(' ', $date);
	$newdate  = explode('-', $date[0]);
	$datetime = explode(':', $date[1]);
	$sec  = $datetime[2];
	
	$min  = $datetime[1];
	$hour = $datetime[0];
	
	$days = $newdate[0];
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Pasillo24.com</title>
	<meta name="description" content="Proximamente el E-commerce mas accesible de Bolivia" >
	<meta name="author" content="tecnographic">

	<!-- Mobile Specific Meta -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!--[if IE]><meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1'><![endif]-->

	<!-- Bootstrap  -->
	<link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">

	<!-- icon fonts font Awesome -->
	<link href="{{ asset('assets/css/font-awesome.min.css') }}" rel="stylesheet">

	<!-- Custom Styles -->
	<link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">

	<!--[if lt IE 9]>
	<script src="assets/js/html5shiv.js"></script>
	<![endif]-->
	<style type="text/css">
		body
		{
			background-color: #333;
		}
		.section-style
		{
			height: 100vh;
			background-size: auto 100%;
		}
		@media (max-width : 767px){
			.section-style
			{
				background-image: none !important;

				background-color: #12401B;
				height: auto !important;
			}
			.text-conteiner
			{
				text-align: center;
			}
			.greenband
			{
				background-color: #16623B;
			}
			.blackband
			{
				background-color: #000;
			}
			.redband{
				background-color: #E84B14;
			}
			.time-box,.time-box-inner
			{
				width: 100px;
				height: 100px;
			}
			.time-box-inner
			{
				padding-top: 17%;
			}
			.time-number
			{
				font-size: 20px;
				padding: 0;
			}
			.time-number .digit
			{
				line-height: 20px;
			}
			.time-name
			{
				font-size: 20px;
				display: block !important;
				visibility: visible !important;
			}
		}
	</style>
</head>
<body>
	<input type="hidden" class="days_data" value="<?php echo $days; ?>">
	<input type="hidden" class="hours_data" value="<?php echo $hour; ?>">
	<input type="hidden" class="minutes_data" value="<?php echo $min; ?>">
	<input type="hidden" class="seconds_data" value="<?php echo $sec; ?>">

	<!-- Preloader -->
	<div id="preloader">
		<div id="loader">
			<div class="dot"></div>
			<div class="dot"></div>
			<div class="dot"></div>
			<div class="dot"></div>
			<div class="dot"></div>
			<div class="dot"></div>
			<div class="dot"></div>
			<div class="dot"></div>
			<div class="lading"></div>
		</div>
	</div><!-- /#preloader -->
	<!-- Preloader End-->

<div class="container">
				<div id="time_countdown" class="time-count-container">

					<div class="col-xs-6 col-sm-3">
						<div class="time-box">
							<div class="time-box-inner dash days_dash animated" data-animation="rollIn" data-animation-delay="300">
								<span class="time-number">
									<span class="digit">0</span>
									<span class="digit">0</span>
									<span class="digit">0</span>
								</span>
								<span class="time-name">Dias</span>
							</div>
						</div>
					</div>

					<div class="col-xs-6 col-sm-3">
						<div class="time-box">
							<div class="time-box-inner dash hours_dash animated" data-animation="rollIn" data-animation-delay="600">
								<span class="time-number">
									<span class="digit">0</span>
									<span class="digit">0</span>	
								</span>
								<span class="time-name">Horas</span>
							</div>
						</div>
					</div>

					<div class="col-xs-6 col-sm-3">
						<div class="time-box">
							<div class="time-box-inner dash minutes_dash animated" data-animation="rollIn" data-animation-delay="900">
								<span class="time-number">
									<span class="digit">0</span>
									<span class="digit">0</span>
								</span>
								<span class="time-name">Minutos</span>
							</div>
						</div>
					</div>

					<div class="col-xs-6 col-sm-3">
						<div class="time-box">
							<div class="time-box-inner dash seconds_dash animated" data-animation="rollIn" data-animation-delay="1200">
								<span class="time-number">
									<span class="digit">0</span>
									<span class="digit">0</span>
									<span class="digit">0</span>
								</span>
								<span class="time-name">Segundos</span>
							</div>
						</div>
					</div>
					
				</div><!-- /.time-count-container -->
	<!-- Page Top Section -->
	<section id="page-top" class="section-style" data-background-image="{{ asset('images/background/portada-01.jpg') }}">
		<div class="height-resize">
			
			<div class="container">
				<div class="text-conteiner row visible-xs">
					<div class="col-xs-12 greenband">
						<h1>TAN PODEROSO COMO SE VE</h1>
						<p>Gran lanzamiento del E-commerce más avanzado de Bolivia, cuenta con la tecnología Seo, mayor rapidez de búsqueda donde compras y vendes artículos Vía Online.</p>
						<h3>WWW.PASILLO24.COM</h3>
					</div>
					<div class="col-xs-12 blackband">
						<div class="col-xs-12"><i class="fa fa-facebook"></i> pasillo24</div>
						<div class="col-xs-12"><i class="fa fa-instagram"></i> @pasillo24</div>
						<div class="col-xs-12"><i class="fa fa-twitter"></i> @pasillo_24</div>
					</div>
					<div class="col-xs-12 redband">
						<h3>¡PRÓXIMAMENTE!</h3>
					</div>
				</div>
			</div><!-- /.container -->
		</div><!-- /.pattern -->		
	</section><!-- /#page-top -->
	<!-- Page Top Section  End -->



		<!-- jQuery Library -->
		<script type="text/javascript" src="{{ asset('assets/js/jquery-2.1.0.min.js') }}"></script>
		<!-- Modernizr js -->
		<script type="text/javascript" src="{{ asset('assets/js/modernizr-2.8.0.min.js') }}"></script>
		<!-- Plugins -->
		<script type="text/javascript" src="{{ asset('assets/js/plugins.js') }}"></script>
		<!-- Custom JavaScript Functions -->
		<script type="text/javascript" src="{{ asset('assets/js/functions.js') }}"></script>
		<!-- Custom JavaScript Functions -->
		<script type="text/javascript" src="{{ asset('assets/js/jquery.ajaxchimp.min.js') }}"></script>


	</body>
</html>
