@extends('main')

@section('content')
{{ HTML::style('https://rawgit.com/enyo/dropzone/master/dist/dropzone.css') }}
    <div class="container contenedorUnico">
        <div class="row">
            <div class="col-xs-12">
                <div class="col-xs-8 col-sm-offset-2 contAnaranjado">
                    <legend style="text-align:center;">Subir imágenes restantes</legend>
                    <p class="textoPromedio">Arrastre imágenes en el cuadro o presione en él para así cargar las imágenes restantes.</p>
                    <p class="textoPromedio">Recuerde que posee un límite para 7 imágenes adicionales.</p>
                    <input type="hidden" name="pub_id" id="pub_id" value="{{$pub_id}}">
                    <div id="dropzone">
                        <form action="{{ URL::to('publicacion/habitual/enviar/imagenes/procesar') }}" method="POST" class="dropzone textoPromedio" id="my-awesome-dropzone">
                            <div class="dz-message">
                                Arrastre o presione aquí para subir su imagen.
                            </div>
                            <input type="hidden" name="pub_id" value="{{$pub_id}}">
                        </form>
                        <a class="btn btn-primary" href="{{ URL::to('publicacion/habitual/previsualizar/'.$pub_id) }}" id="enviarImagenes" style="margin:2em auto;display:block;">Continuar</a>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
@stop

@section('postscript')
{{ HTML::script('js/dropzone.js') }}
<script type="text/javascript">
    Dropzone.autoDiscover = false;
// or disable for specific dropzone:
// Dropzone.options.myDropzone = false;
    var myDropzone = new Dropzone("#my-awesome-dropzone");
    myDropzone.on("success", function(resp){

        var response = JSON.parse(resp.xhr.response);
        
        $('.dz-preview:last-child').children('.dz-remove').attr({'data-info-value':response.campo,'id':response.campo})
    });
    myDropzone.on("removedfile", function(file) {
        var campo = $(file._removeLink).attr('id');
        if(file.xhr){

            $(function() {
              // Now that the DOM is fully loaded, create the dropzone, and setup the
              // event listeners
                var url = JSON.parse(file.xhr.response);
                var imagepath = url.url;
                $.ajax({
                    url: '../imagenes/eliminar',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        'name' :  file.name,
                        'id'   :  $('#pub_id').val(),
                        'campo':  campo

                    },
                    success:function(response)
                    {
                        console.log(response)
                    }
                })

                
                })
            }
    })
</script>
@stop