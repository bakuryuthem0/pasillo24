@extends('main')

@section('content')

<div class="container contenedorUnico">
	<div class="row">
		<div class="col-xs-12 contAnaranjado">
			<legend>Cambiar texto de publicaciones</legend>
			@if(Session::has('success'))
				<div class="col-xs-12">
					<div class="alert alert-success">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						<p class="textoPromedio">{{ Session::get('success') }}</p>
					</div>
				</div>
			@endif
			<form method="POST" action="{{ URL::to('administrador/modificar-textos') }}">
				@foreach($textos as $texto)
				<div class="col-xs-12" style="margin-bottom:2em;">
					<label for="" class="textoPromedio">Texto de publicacion LÍDER</label>
					<textarea id="editor{{ $texto->id }}" name="desc{{ $texto->id }}">{{ $texto->desc }}</textarea>
					@if ($errors->has('desc'.$texto->id))
						 @foreach($errors->get('desc'.$texto->id) as $err)
						 	<div class="alert alert-danger">
						 		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						 		<p class="textoPromedio">{{ $err }}</p>
						 	</div>
						 @endforeach
					@endif
				</div>

				@endforeach
				<div class="col-xs-12">
					<input type="submit" value="enviar" class="btn btn-success">
				</div>
				
			</form>

		</div>
	</div>
</div>

@stop
@section('postscript')

<script>

	CKEDITOR.disableAutoInline = true;
	$( document ).ready( function() {
		$( '#editor1' ).ckeditor(); // Use CKEDITOR.replace() if element is <textarea>.
		$( '#editor2' ).ckeditor(); // Use CKEDITOR.replace() if element is <textarea>.
		$( '#editor3' ).ckeditor(); // Use CKEDITOR.replace() if element is <textarea>.
	} );

</script>
@stop