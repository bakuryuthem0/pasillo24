@extends('main')
@section('content')
<div class="container contenedorUnico">
	<div class="row">
		<div class="container">
		@if (Session::has('success'))
		<div class="alert alert-success contAnaranjado" style="margin-top:2em;">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			<p class="textoPromedio">{{ Session::get('success') }}</p>
		</div>

		@endif
		</div>
	</div>
</div>

@stop()