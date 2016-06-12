function resizeCategoryContainer() {
	$('.cat-container').css('max-height',$('.portada').height());
}
function alerta(esto,msg)
{
	$(esto).addClass('inputError');
	$(esto).after('<p class="erroneo textoPromedio">'+msg+'</p>');
}
function cambiarFecha()
{
	var url = $('.baseUrl').val();
	var period = $('#period').val();
	if (period == 'd') {
		time = 86400;
	}else if(period == 's')
	{
		time = 604800;
	}else if(period == 'm')
	{
		time = 2629744;
	}
	var fecha = $('#fechIni').val(),duration=$('#duration').val();
	var total = duration*time;

	$.ajax({
			url: url+'/usuario/publicacion/lider/fecha',
			type: 'get',
			data: {'fecha':fecha,'timestamp':total,'period':period,'duration':duration},
			beforeSend:function()
			{
				$('.miniLoader').removeClass('hidden');
				$('.to-hide').addClass('hidden');
				$('.response-date').addClass('hidden');
				$('.response-msg').parent().addClass('hidden');
			},
			success: function (data) {
				$('.miniLoader').addClass('hidden');
				if (data.code == 0) {
					$('.response-msg').html('Hubo un error, verifique el formato de la fecha.');
				}else
				{
					$('.response-date').html(data.fecha).removeClass('hidden');
					$('.response-msg').html('El total a pagar sera de '+data.costo+' Bs.').parent().removeClass('hidden');
				}
			},
			error:function()
			{
				$('.miniLoader').addClass('hidden');
				$('.to-hide').removeClass('hidden');
				$('.response-msg').html('Error en el servidor, intente de nuevo.').parent().removeClass('hidden');

				console.log('error');
			}
		});
}
function success(position) {
	var coords = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
	var lat = position.coords.latitude;
	var log = position.coords.longitude;

	$('.latitud').val(lat);
	$('.longitud').val(log);

	var options = {
		zoom: 15,
		center: coords,
		mapTypeControl: false,
		navigationControlOptions: {
			style: google.maps.NavigationControlStyle.SMALL
		},
		mapTypeId: google.maps.MapTypeId.ROADMAP
	};
	var map = new google.maps.Map(document.getElementById("mapcontainer"), options);

	var marker = new google.maps.Marker({
	  position: coords,
	  map: map,
	  title:"You are here!"
	});
  	google.maps.event.addListenerOnce(map, 'idle', function(){
	    $('.contLoaderBig').removeClass('active');
	});
}

function showError(error)
{
	$('.contLoaderBig').removeClass('active');
	$('#mapcontainer').remove();
	$('.latitud').val();
	$('.longitud').val();
	switch(error.code) 
	{
	case error.PERMISSION_DENIED:
		$('.mapContainer').html('<p class="textoPromedio">Has bloqueado el acceso a tu posición.</p>')
	  break;
	case error.POSITION_UNAVAILABLE:
		$('.mapContainer').html('<p class="textoPromedio">Imposible obtener información de tu posición.</p>')

	  break;
	case error.TIMEOUT:
		$('.mapContainer').html('<p class="textoPromedio">Tiempo de solicitud excedido</p>');
	  
	  break;
	case error.UNKNOWN_ERROR:
	  x.innerHTML="An unknown error occurred."
	  break;
	}
}
function loadMap() {
	var lat = $('.latitud').val();
	var lon = $('.longitud').val();
	if (typeof lat != 'undefined' && typeof lon != 'undefined') {
  	coords = new google.maps.LatLng(lat,lon);
  	var options = {
		zoom: 15,
		center: coords,
		mapTypeControl: false,
		navigationControlOptions: {
			style: google.maps.NavigationControlStyle.SMALL
		},
		mapTypeId: google.maps.MapTypeId.ROADMAP
	};
	var map = new google.maps.Map(document.getElementById("mapcontainer"), options);

	var marker = new google.maps.Marker({
	  position: coords,
	  map: map,
	  title:"You are here!"
	});
  	google.maps.event.addListenerOnce(map, 'idle', function(){
	    $('.contLoaderBig').removeClass('active');
	});

	};
}
function hideMustLog (el) {
	$(el).addClass('hidden');
}
jQuery(document).ready(function($) {
	var url = $('.baseUrl').val();
	$(window).on('resize', function(event) {
		resizeCategoryContainer()
		
	});
	resizeCategoryContainer()
	/*------------------ Modificar Perfil --------------*/

	$('.btn-mdf').on('click',function(event) {
		var boton = $(this);
		$('.mdfInfo').addClass('hidden');
		$('.btn-mdf').addClass('hidden');
		$('.mdfForm').removeClass('hidden');
		$('.btn-to-mdf').removeClass('hidden');
	});
	$('.btn-cancel').on('click', function(event) {
		$('.mdfInfo').removeClass('hidden');
		$('.btn-mdf').removeClass('hidden');
		$('.btn-to-mdf').addClass('hidden');
		$('.mdfForm').addClass('hidden');
	});
	$('.btn-send ').on('click',function(event) {
		var procede = 1;
		if ($('#name').val().length<4) {
			alerta('#name','El nombre es muy corto');
			procede = 0;
		}
		if ($('#lastname').val().length<4) {
			alerta('#lastname','El apellido es muy corto');
			procede = 0;
		}
		if ($('#id').val()<4) {
			alerta('#id','el numero de identificacion es muy corto');
			procede = 0;
		}
		if ($('#department').val() == "") {
			alerta('#department','Debe elegir un departamento');
			procede = 0;
		}
		if (procede == 1) {
			$('.form').submit();
		};
	});
	$('.mdfForm').on('focus', function(event) {
		$(this).next('.erroneo').remove();
	});
	$('.continue').on('click', function(event) {
		$('.info').fadeOut('fast', function() {
			$('.formPub').fadeIn('fast', function() {
				$('.formPub').removeClass('hidden');
			});
		});
	});
	$('#ubication').change(function(){
		var esto = $(this);
		if (esto.val() == 'Categoria') {
			esto.parent().removeClass('col-md-12').addClass('col-md-6');
			$('.contCatLider').removeClass('hidden')
		}else
		{
			esto.parent().removeClass('col-md-6').addClass('col-md-12');
			$('.contCatLider').addClass('hidden');
		}
	})
	$('.ui-state-default').click(function(){
		$('.fechError').remove();
		if ($('#period').val()!= "" && $('#duration').val() != "") {
			if ($('#fechIni').val()!= "") {
				$('.errorBlur').remove();
				cambiarFecha();
			}else
			{
				$('.errorBlur').remove();
				$('#fechIni').css({'border':'1px red solid'});
				$('#fechIni').after('<div class="alert alert- errorBlur"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Debe introducir una fecha</div>');
			}
		}
		
	});
	$('#duration').blur(function(){
		if ($('#fechIni').val()!="" && $('#period').val()!="") {
			cambiarFecha();
		};
	});
	$('#period').change(function()
	{
		if ($('#fechIni').val()!="" && $('#duration').val() != "") {
			cambiarFecha();
		};
	});
	$('.doMap').on('click', function(event) {
		if (!$(this).is(':checked')) {
			$('.mapContainer').addClass('hidden');
		};
		if ($('#mapcontainer').length > 0) {
			$('#mapcontainer').remove();
			$('.latitud').val('');
			$('.longitud').val('');
		}else
		{
			if (navigator.geolocation) {
				var mapcanvas = document.createElement('div');
				mapcanvas.id = 'mapcontainer';
				mapcanvas.style.height = '400px';
				mapcanvas.style.width = '100%';

				$('.mapContainer').removeClass('hidden');
				document.querySelector('article').appendChild(mapcanvas);
				$('.contLoaderBig').addClass('active');
		  		navigator.geolocation.getCurrentPosition(success,showError)
			} else {
		  		$(this).attr('checked', false);
		  		$('.latitud').val('');
				$('.longitud').val('');
			}
			
		}
	});
	var activeSystemClass = $('.list-group-item.active');
	$('.btn-filtralo').on('click', function(event) {
		$(this).attr('disabled',true);
		var proceed = 0;
		if ($('.filterDep').val() != "" && $('.filterDep').val() != -1) {
			proceed = 1;
			$('.form-filter').append('<input type="hidden" name="filter" value="'+$('.filterDep').val()+'">')
		};
		if ($('.filterRel').val() != "" && $('.filterRel').val() != -1) {
			proceed = 1;
			$('.form-filter').append('<input type="hidden" name="rel" value="'+$('.filterRel').val()+'">')
		};
		if ($('.filterBuss').val() != "" && $('.filterBuss').val() != -1) {
			proceed = 1;
			$('.form-filter').append('<input type="hidden" name="buss" value="'+$('.filterBuss').val()+'">')
		};
		if ($('.min').val() != "") {
			proceed = 1;
			$('.form-filter').append('<input type="hidden" name="min" value="'+$('.min').val()+'">')
		};
		if ($('.max').val() != "") {
			proceed = 1;
			$('.form-filter').append('<input type="hidden" name="max" value="'+$('.max').val()+'">')
		};
		if ($('.min').val() != "" || $('.max').val() != "") {
			proceed = 1;
			$('.form-filter').append('<input type="hidden" name="currency" value="'+$('.currency').val()+'">')
		};
		if ($('.filterCond').val() != "" && $('.filterCond').val() != -1) {
			proceed = 1;
			$('.form-filter').append('<input type="hidden" name="cond" value="'+$('.filterCond').val()+'">')
		};
		if ($('.to-filter').hasClass('busq')) {
			$('.form-filter').append('<input type="hidden" name="busq" value="'+$('.to-filter').val()+'">')
		}else
		{
			$('.form-filter').append('<input type="hidden" name="cat" value="'+$('.to-filter').val()+'">')

		}
		if (proceed == 1) {
			$('.form-filter').submit();
			
		}else
		{
			if ($('.to-filter').hasClass('busq')) {
				window.location.replace(url+'/inicio/buscar?busq='+$('.to-filter').val());			
			}else
			{
				window.location.replace(url+'/publicaciones/categorias/'+$('.to-filter').val());
			}
		}
	});
	$('.mustLog').on('click', function(event) {
		var btn = $(this);
		var target = btn.data('target');
		$(target).removeClass('hidden')
		setTimeout(function(){hideMustLog(target)}, 3000);
	});
	$('.inputComentario').on('focus',function(event) {
		$(this).addClass('textareaFocused');
		$('#enviarComentario').removeClass('hidden');
	});
	$('.inputComentario').on('blur',function(event) {
		$(this).removeClass('textareaFocused');
		$('#enviarComentario').addClass('hidden');
	});
});