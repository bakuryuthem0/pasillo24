jQuery(document).ready(function($) {
	$('.refresh').click(function(event) {
		var id = $(this).val(),status = $(this).data('status');
		var boton = $(this);
		$.ajax({
			url: 'http://preview.pasillo24.com/administrador/editar-publicidad/eliminar',
			type: 'POST',
			dataType: 'json',
			data: {'id': id,'status':status},
			beforeSend:function () {
				boton.after('<img src="http://preview.pasillo24.com/images/loading.gif" class="loading">');
				boton.animate({
						'opacity': 0},
						250,function(){
							$(this).css({
								'display':'none'
							});
							$('.loading').css({
								'display': 'inline-block'
							}).animate({
								'opacity': 1},
								250);
						}
				);
			},success:function(response) {
				$('.loading').animate({
					'opacity': 0},
					250,function(){
						$(this).remove();
						boton.css({
							'display': 'inline-block'
						}).animate({
							'opacity': 1},
							250);
					});
				if (boton.hasClass('active')) {
					boton.removeClass('active')
					boton.html('Activar')
				}else
				{
					boton.addClass('active')
					boton.html('Desactivar')
				}
				
				$('.responseDanger').removeClass('alert-danger');
					$('.responseDanger').removeClass('alert-success');
					$('.responseDanger').stop().css({'display':'block'}).addClass('alert-'+response.type).html('<p class="textoPromedio">'+response.msg+'</p>').animate({
						'opacity': 1},
						500);
				setTimeout(function(){ 
					$('.responseDanger').animate({
						'opacity':0},
						400, function() {
						$(this).css({
							
							'display':'none'
						});
					});
				}, 3000);
			}
		})
		
	});
	$('.deleteSlide').click(function(event) {
		$('.envElim').removeClass('disabled');
		var id = $(this).val();
		var fila = $(this);
		$('.envElim').val(id);
		$('.envElim').click(function(event) {
			var boton = $(this);
			$.ajax({
				url: 'http://preview.pasillo24.com/administrador/editar-publicidad/eliminar',
				type: 'POST',
				dataType: 'json',
				data: {'id': id},
				beforeSend:function() {
					boton.after('<img src="http://preview.pasillo24.com/images/loading.gif" class="loading">');
					boton.animate({
						'opacity': 0},
						250,function(){
							$(this).css({
								'display':'none'
							});
							$('.loading').css({
								'display': 'inline-block'
							}).animate({
								'opacity': 1},
								250);
						}
				);
				},success:function() {
					$('.loading').animate({
						'opacity': 0},
						250,function(){
							$(this).remove();
							boton.css({
								'display': 'inline-block'
							}).animate({
								'opacity': 1},
								250);
						});
					fila.parent().parent().remove();
					$('.responseDanger').removeClass('alert-danger');
						$('.responseDanger').removeClass('alert-success');
						$('.responseDanger').stop().css({'display':'block'}).addClass('alert-'+response.type).html('<p class="textoPromedio">'+response.msg+'</p>').animate({
							'opacity': 1},
							500);
					$('#elimModal').modal('hide')
					$('.envElim').addClass('disabled')
					setTimeout(function(){ 
						$('.responseDanger').animate({
							'opacity':0},
							400, function() {
							$(this).css({
								
								'display':'none'
							});
						});
					}, 3000);
				}
			})
			
		});
	});
});
jQuery(document).ready(function($) {
	$('.btnChange').click(function(event) {
		$('.minis').addClass('miniSortable');

		$('.btn-no').addClass('btn-show');
		$(this).addClass('btn-no');
		
		$( ".minis" ).sortable({
	      revert: true
	    });

	    $( ".minis" ).disableSelection();
	});
	$('.btnChangeEnviar').click(function(event) {
		var lista = $('.imgMini')
		var arr   = [];
		lista.each(function(i, el) {
			arr[i] = $(el).attr('data-value');
		});
		var id = $(this).val();
		$.ajax({
			url: 'http://preview.pasillo24.com/publicacion/habitual/previsualizar/cambiar/posiciones',
			type: 'POST',
			dataType: 'json',
			data: {'arr': arr,'id':id},
			beforeSend:function () {
				$('.btnChangeEnviar').before('<img src="http://preview.pasillo24.com/images/loading.gif" class="loading">');
				$('.loading').css({
					'display': 'block',
					'margin': '2em auto'
				}).animate({
					'opacity': 1},
					500);	
			},
			success:function (response) {
				$('.loading').animate({
					'opacity': 0},
				500,function(){
					
					$(this).remove();
				});
				$('.responseDanger').addClass('alert-'+response.type).html('<p class="textoPromedio">'+response.msg+'</p>').css({
					'display': 'block'
				}).animate({
					'opacity': 1},
					500);
				$('.minis').sortable("destroy");
				$('.miniSortable').removeClass('miniSortable');
				$('.btn-show').removeClass('btn-show');
				$('.btnChange').removeClass('btn-no');
				setTimeout(function(){

					$('.responseDanger').removeClass('success')
					$('.responseDanger').removeClass('danger')
					$('.responseDanger').stop().animate({
						'opacity':0},
						500, function() {
						$(this).css({
							'display':'none'
						});
					});
				},6000);
				
			}
		})
		
	});
	$('.btnChangeCancel').click(function(event) {
		$('.minis').sortable("destroy");
		$('.miniSortable').removeClass('miniSortable');
		$('.btn-show').removeClass('btn-show');
		$('.btnChange').removeClass('btn-no');
	});
});

$(document).ready(function() {
	$('#enviar').click(function(event) {
		function alerta(esto,msg)
		{
			$(esto).focus();
			$(esto).css({'box-shadow':'0px 0px 1px 1px red'});
			$(esto).after('<p class="erroneo textoPromedio">'+msg+'</p>');
		}
		var procede = 1;
		$('.erroneo').remove();
		if ($('#username').val().length<4) {
			alerta('#username','El nombre de usuario debe ser más largo');
			procede = 0;
		}
		if ($('#pass').val() <6) {
			alerta('#pass','La contraseña es muy corta');
			procede = 0;
		}else{
			if ($('#pass').val() != $('#pass2').val()) {
				alerta('#pass2','Las contraseñas no coinciden');
				procede = 0;
			}
		}
		if ($('#email').val().indexOf('@') == -1 || $('#email').val().indexOf('.')== -1) {
			alerta('#email','Debe introducir un email válido');
			procede = 0;
		}
		if ($('#name').val().length<4) {
			alerta('#name','El nombre es muy corto');
			procede = 0;
		}
		if ($('#lastname').val().length<4) {
			alerta('#lastname','El apellido es muy corto');
			procede = 0;
		}
		if ($('#id').val()<4) {
			alerta('#id','el número de identificación es muy corto');
			procede = 0;
		}

		if ($('#sexo').val()=="") {
			alerta('#sexo','Debe elegir un sexo');
			procede = 0;
		}
		if ($('#department').val() == "") {
			alerta('#department','Debe elegir un departamento');
			procede = 0;
		}
		if (procede == 0) {
			event.preventDefault();
		};
	});
});

jQuery(document).ready(function($) {
	$('.btnEliminarPub').click(function(event) {
		$(this).unbind('click');
		var boton = $(this);
		var val = $(this).val();
		$('.btnElimPublicacion').val(val);
		$('.btnElimPublicacion').click(function(event) {
			$.ajax({
				url: 'http://preview.pasillo24.com/eliminar/publicacion',
				type: 'POST',
				dataType: 'json',
				data: {'id': $(this).val()},
				beforeSend:function(){
					$('.btnElimPublicacion').before('<img src="http://preview.pasillo24.com/images/loading.gif" class="loading">');
					$('.btnElimPublicacion').addClass('disabled')
					$('.loading').css({
						'display': 'block',
						'margin': '2em auto'
					}).animate({
						'opacity': 1},
						500);
				},
				success:function (response) {
					$('.loading').animate({
						'opacity': 0},
						500,function(){
							$(this).remove();
						});
					$('.disabled').removeClass('disabled');
					$('.responseDanger').stop().css({'display':'block'}).addClass('alert-'+response.type).html('<p class="textoPromedio">'+response.msg+'</p>').animate({
					'opacity': 1},
					500);
					if (response.type == 'success') {
						boton.parent().parent().remove();
					};
					$('#modalElimUserPub').modal('hide')

				}
			})
			
		});
	});
});

$(document).ready(function(){
	$('.agg').click(function(event) {

		$('.formAgg').css({'opacity':0,'display':'block'}).animate({
			'opacity': 1
		},500);
		var boton = $(this);
		$('.botonera').append('<input type="submit" class="btn btn-success enviar btn-agg" value="Enviar"><button type="button" class="btn btn-danger cancel btn-agg">Cancelar</button>')
		boton.css({'display':'none'});
		$('.enviar').click(function(event) {
			$('.erroneo').remove();
			var nomb = $('#nomb'),price = $('#price'),stock = $('#stock'),desc = $('#input'),img = $('#img'),procede = 1;
			if (nomb.val().length<4) {
				nomb.focus();
				nomb.css({'box-shadow':'0px 0px 1px 1px red'});
				nomb.after('<p class="erroneo textoPromedio">El Nombre debe tener al menos 4 caracteres</p>');
				procede = 0;
			}
			if(price.val()=="")
			{
				price.focus();
				price.css({'box-shadow':'0px 0px 1px 1px red'});
				price.after('<p class="erroneo textoPromedio">El campo es obligatorio</p>');
				procede = 0;
			}else if(price.val()<=0)
			{
				price.focus();
				price.css({'box-shadow':'0px 0px 1px 1px red'});
				price.after('<p class="erroneo textoPromedio">El precio debe ser mayor a 0</p>');
				procede = 0;
			}
			if (stock.val() == "") {
				stock.focus();
				stock.css({'box-shadow':'0px 0px 1px 1px red'});
				stock.after('<p class="erroneo textoPromedio">El campo es obligatorio</p>');
				procede = 0;
			}else if(stock.val()<=0)
			{
				stock.focus();
				stock.css({'box-shadow':'0px 0px 1px 1px red'});
				stock.after('<p class="erroneo textoPromedio"> debe poseer almenos 1 articulo para publicarlo</p>');
				procede = 0;
			}
			if (desc.val().length < 8) {
				desc.focus();
				desc.css({'box-shadow':'0px 0px 1px 1px red'});
				$('.textarea').append('<p class="erroneo textoPromedio">La descripcion debe de ser mas larga</p>');
				procede = 0;
			}
			if (img.val() == "") {
				img.after('<p class="erroneo textoPromedio"> debe poseer almenos 1 articulo para publicarlo</p>');
				procede = 0;
			};
			$('.aggInput').keyup(function(event) {
				$(this).next().remove();
				$(this).css({'box-shadow':'0px 0px 0px 0px transparent'});
			});
			if (procede != 1) {
				event.preventDefault();
				$('.formAgg').before('<div class="alert alert-danger erroneo"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Error al llenar los campos, verifique si todo esta en orden</div>');
			}
		});
		$('.cancel').click(function() {
			$('.btn-agg').remove();
			$('.formAgg').animate({
				'opacity': 0
			},
				500, function() {
				$(this).css({'display':'none'})
			});
			boton.css({'display':'block'})
		});
	});
})
$(document).ready(function() {
	$('.btn-modificar').click(function(event) {
		var boton = $(this);
		var id = $(this).attr('data-id-modify');
		$('.texto_'+id).css({
			'display': 'none'
		});
		boton.after('<button class="btn btn-primary cancel">Cancelar</button>');
		boton.css({
			'display': 'none'
		});
		
		$('.cancel').click(function() {
			$(this).remove();
			boton.css({
				'display': 'block'
			});
			$('.texto_'+id).css({
				'display': 'block'
			});
		});
		
	});
	$('.btn-eliminar').click(function(event) {
		var x;
		x = confirm('¿Seguro desea eliminar?. Esta accion es irreversible');
		if (x) {
			$.ajax({
				url: 'http://preview.pasillo24.com/user/items/delete',
				type: 'POST',
				dataType: 'json',
				data: {'id': $(this).attr('data-id-delete')},
				success:function()
				{
					location.reload();
				},
				error:function()
				{
					console.log('error');
				}
			})			
		}
	});	
});
/*------------------ Modificar Perfil --------------*/

$(document).ready(function() {
	$('.mdfButton').click(function(event) {
		var boton = $(this);
		$('.mdfInfo').css({
			'display': 'none'
		});
		$('.mdfForm').css({'display':'block'});
		$('.mdfSub').before('<button type="button" class="btn btn-danger cancel botones inputRegister">Cancelar</button>');
		$('.cancel').click(function(event) {
			$(this).remove();
			$('.mdfSub').css({
				'display': 'none'
			});
			$('.mdfForm').css({
				'display': 'none'
			});
			$('.mdfInfo').css({
				'display': 'block'
			});
			boton.css({
				'display': 'block'
			});
		});
		boton.css({
			'display': 'none'
		});
		$('.mdfSub').click(function(event) {
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
			if (procede == 0) {
				event.preventDefault();
			};
		});
		
	});
});

/*------------------ Publicacion normal --------------*/

$('.continueNormal').click(function(event){
	$('.info').animate({'opacity': 0},500, function(){
			$(this).remove();	
			$('.formPub').css({'display':'block','opacity':0}).animate({
				
				'opacity': 1
			},
				500
			);
	});
})
$('#veiMarca').change(function(){
	var id = $(this).val();
	var data = {'id':$(this).val()};
	if (id != "") {
		$.ajax({
			url: 'http://preview.pasillo24.com/publicacion/model',
			type: 'GET',
			data: data,
			success:function(response){
				$('.optionModel').remove();
				for (var i = 0 ; i < response.length; i++) {
					$('#veiModel').append('<option class="optionModel" value="'+response[i].id+'">'+response[i].nombre+'</option>');
				};
				
			}
		})
	};
})
/*------------------ Pagos Normal --------------*/
$(document).ready(function() {
	function periodo(este,aquel,duration,duration2)
	{
		valor = este;
		if (valor != "" && duration != "") {
			valor = (parseInt(duration)*parseInt(valor))+40;
			if ($(aquel).val() != "" && duration2 != "") {
				valor += parseInt(parseInt(duration2)*parseInt($(aquel).val()));
				$('#enviarPago').before('<h3 id="totalTexto">El total a pagar sera de: '+valor+'bs</p>');	
			}else{
				$('#enviarPago').before('<h3 id="totalTexto">El total a pagar sera de: '+valor+'bs</p>');
			}
		}else
		{
			if ($(aquel).val() != "" && duration2 != "") {
				console.log(valor+' '+typeof valor)
				valor = parseInt(parseInt(duration2)*parseInt($(aquel).val()))+40;
				$('#enviarPago').before('<h3 id="totalTexto">El total a pagar es de: '+valor+'bs</p>');	
			}else
			{
				$('#enviarPago').before('<h3 id="totalTexto">El total a pagar es de: 40bs</p>');	
			}
		}
	}
	$('#principalDuration').keyup(function(event) {
		if ($('#principalPeriodo').val() != "") {
			$('#totalTexto').remove();
			var valor = $('#principalPeriodo').val();
			var duration = $('#principalDuration').val();
			var duration2 = $('#categoriaDuration').val()
			periodo(valor,'#categoriaPeriodo',duration,duration2);
		}
	});

	$('#principalPeriodo').change(function(event) {
		if ($('#principalDuration').val() != "") {
			$('#totalTexto').remove();
			var valor = $(this).val();
			var duration = $('#principalDuration').val();
			var duration2 = $('#categoriaDuration').val()
			periodo(valor,'#categoriaPeriodo',duration,duration2);
		}
		
		
	});
	$('#categoriaDuration').keyup(function(event) {
		if ($('#categoriaPeriodo').val() != "") {
			$('#totalTexto').remove();
			var valor = $('#categoriaPeriodo').val();
			var duration = $('#categoriaDuration').val();
			var duration2 = $('#principalDuration').val();
			periodo(valor,'#principalPeriodo',duration,duration2);
		}
	});
	$('#categoriaPeriodo').change(function(event) {
		if ($('#categoriaDuration').val() != "") {
			$('#totalTexto').remove();
			var valor = $(this).val();
			var duration = $('#categoriaDuration').val();
			var duration2 = $('#principalDuration').val()
			periodo(valor,'#principalPeriodo',duration,duration2);
		}
	});
});

/*------------------ Publicacion lider --------------*/

$('.continue').click(function(event) {
	$('.info').animate({'opacity': 0},500, function(){
			$(this).remove();	
			$('.formPub').css({'display':'block','opacity':0}).animate({
				
				'opacity': 1
			},
				500
			);
	});
	$('#ubication').change(function(){
		var esto = $(this);
		if (esto.val() == 'Categoria') {
			esto.parent().removeClass('col-xs-12').addClass('col-xs-6');
			$('.contCatLider').css({
				'display': 'block'
			});
		}else
		{
			esto.parent().removeClass('col-xs-6').addClass('col-xs-12');
			$('.contCatLider').css({
				'display': 'none'
			});
		}
	})
	function cambiarFecha()
	{
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
		$('.error').remove();
		$('input').css('box-shadow','none');
		$('.finalFecha').remove();
		$.ajax({
				url: 'http://preview.pasillo24.com/usuario/publicacion/lider/fecha',
				type: 'get',
				data: {'fecha':fecha,'timestamp':total,'period':period,'duration':duration},
				beforeSend:function()
				{
					$('.loading').css({
						'display': 'inline-block'
					}).animate({'opacity':1},500);
				},
				success: function (data) {
					$('.errorBlur').remove();
					$('.loading').css({
						'display': 'none'
					});
					if (data.code == 0) {
						$('#fechIni').after('<p class="errorBlur textoPromedio" data-proceed="0">Formato incorrecto, la fecha debe de estar en formato DD-MM-AAAA</p>')
					}else if(data.code == 1){
						$('#fechIni').after('<p class="errorBlur textoPromedio" data-proceed="0">Debe elegir una fecha posterior a hoy</p>')
					}else if(data.code == 2)
					{
						$('#fechIni').after('<p class="errorBlur textoPromedio" data-proceed="0">Debe introducir una fecha valida</p>')
					}else
					{

						$('.finalFecha').remove();
						$('.fechaFin').append('<p class="finalFecha textoPromedio">'+data.fecha+'</p>');

						$('.contPrecioShow').append('<p class="finalFecha bg-info textoPromedio" style="padding:0.5em;border-radius:4px;margin-top:1em;">El total a pagar sera de '+data.costo+' Bs.</p>')
					}
				},
				error:function()
				{
					console.log('error');
				}
			});
	}
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
	})
	$('.enviarPub').click(function(event) {
		$('.erroneo').remove();
		function alerta(esto,msg)
		{
			$(esto).focus();
			$(esto).css({'box-shadow':'0px 0px 1px 1px red'});
			$(esto).after('<p class="erroneo textoPromedio">'+msg+'</p>');
		}
		var procede = 1;
		if($('#ubication').val() == ""){
			alerta('#ubication','Debe seleccionar una ubicación');
			procede = 0;
		}
		if (esto.val() == 'Categoria') {
			if ($('#category').val() == "") {
				alerta('#category','Debe seleccionar una categoría');
				procede = 0;
			}
		}	
		if ($('#name').val().length < 4) {
			alerta('#name','El nombre debe tener al menos 4 caracteres');
			procede = 0;
		}
		if ($('#duration').val() == "") {
			alerta('#duration','La duración es obligatoria');
			procede = 0;
		}
		if ($('#period').val() == "") {
			alerta('#period','Debe seleccionar un período');
			procede = 0;
		}
		if ($('#fechIni').val() == "") {
			alerta('#fechIni','Debe elegir una fecha');
			procede = 0;
		}
		if ($('.errorBlur').attr('data-proceed') == 0) {
			procede = 0;
		};
		if (procede == 0) {
			event.preventDefault();
		}
	});
});
/*------------------ Ver Habitual --------------*/
$(document).ready(function() {
	var imgPrinc = $('.imgPrinc').attr('src');
	$('.imgPrinc').hover(function(event) {
		$('.zoomed').attr('src', $(this).attr('src'));
		$('.zoomed').css({
			'display':'block',
			'height' : $(this).css('height')
		}).stop().animate({'opacity':1}, 250)
		$(this).stop().animate({'opacity':0}, 250)
		$(this).mousemove(function(event) {
			var x = event.pageX - $(this).offset().left;
			var y = event.pageY - $(this).offset().top;

			$('.zoomed').css({
				'transform-origin': x+'px '+y+'px'
			});
			
		});
	}, function(event) {
		$('.zoomed').stop().animate({'opacity':0}, 250,function()
		{
			$(this).css({
				'display':'none'
			})
		})
		$(this).stop().animate({'opacity':1}, 250)
	});
	$('.imgMini').on('click',function() {
		var imgHover = $(this).attr('src');
		$('.imgPrinc').attr('src',imgHover);
	});


	$('.inputComentario').focus(function(event) {
		$(this).stop().animate({
			'height' : '150px'
		},100,function(){;
			$('#enviarComentario').css({
				'display': 'inline-block'
			});
		})
	});

	$('#enviarComentario').click(function(event) {
		$('#enviarComentario').prop({
			'disabled': true
		})
		$('.error').remove();
		var procede = 1;
		if ($('#inputComentario').val().length<4) {
			procede = 0;
			$('#inputComentario').css({
				'box-shadow': '0px 0px 1px 1px red'
			});
			$('#inputComentario').before('<div class="alert alert-danger error"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><p class="textoPromedio">La pregunta debe de ser mas larga</p></div>');
		}
		if (procede == 1) {
			var data = {
				'id' : $(this).val(),
				'comment': $('#inputComentario').val()
			}
			$.ajax({
				url:'http://preview.pasillo24.com/publicacion/comentario',
				type: 'POST',
				data: data,
				success:function(response){
					alert(response);
					location.reload();
				},
				error:function(){
					console.log('error');
				}	
			})
		};

	});
});
/*------------------ admin --------------*/
/*------------------ Aprovar Publicacion --------------*/
$(document).ready(function() {
	$('.btnAprovar').click(function(event) {
		var btn = $(this);
		var x = confirm('¿Seguro desea aprobar esta publicación?');
		if (!x)
		{
			event.preventDefault();
		}
	});
	$('.btnCancelar').click(function(event) {
		event.preventDefault();
		boton = $(this);
		var btn = $(this);
		var id = btn.val();
		$(this).parent().append('<div class="col-xs-6" style="margin-top:0px;"><input type="text" name="motivo" placeholder="Motivo" class="form-control textoMedio"></div><button value="'+id+'" class="btn btn-danger" class="enviarRechazo" name="id">Enviar</button>')
		$(this).remove();
		$('.enviarRechazo').click(function(event) {
			$(this).parent().submit();
		});

	});
});
/*-------------------Casual------------------------------*/
	$('.continueCasual').click(function(event){
		var rand1 = Math.round(Math.random()*100);
		var rand2 = Math.round(Math.random()*100);
		$('.formula').html('Cuanto es: '+rand1+'+'+rand2).append('<input type="hidden" name="x" value="'+rand1+'">').append('<input type="hidden" name="y" value="'+rand2+'">')
		$('.resultado').html(' '+(rand1+rand2));
		$('.info').animate({'opacity': 0},500, function(){
				$(this).remove();	
				$('.formPub').css({'display':'block','opacity':0}).animate({
					
					'opacity': 1
				},
					500
				);
		});	
		var total = 400;
		var texto = $('.cke_wysiwyg_frame').contents().find('body').html();
		texto2 = $(texto).text();
		
		
		var actual = total - parseInt(texto2.length);
		$('.cantCaracteres').html('Caracteres restantes: '+actual);
		$('.cke_wysiwyg_frame').contents().keyup(function(event){
			var texto = $('.cke_wysiwyg_frame').contents().find('body').html();
			texto2 = $(texto).text();
			actual = total - parseInt(texto2.length);
			$('.cantCaracteres').html('Caracteres restantes: '+actual);
			if (texto2.length>400) {
				if (event.which != 8) {
					event.preventDefault();
					var newText = texto.substr(0,400);
					$('.cke_wysiwyg_frame').contents().find('body').html(newText);
					$('.cantCaracteres').html('Caracteres restantes: '+0);
					alert('Ha alcanzado el limite de caracteres.')
				}
				

			};
		})
	});
/*------------Responder---------------*/
$(document).ready(function() {
	$('.btn-responder').click(function(event) {
		var id = $(this).val();
		var boton = $(this);
		
		$('.textoRespuesta').click(function(event) {
			$('.responseDanger').css({'display':'none'})
		});
		$('.modal-backdrop').click(function(event) {
			$('.responseDanger').removeClass('alert-danger');
			$('.responseDanger').removeClass('alert-success');
			$('.responseDanger').css({
				'display': 'none',
				'opacity': 0
			});
			$('.enviarRespuesta').prop('disabled',false);
		});
		$('.close').click(function(event) {
			$('.responseDanger').removeClass('alert-danger');
			$('.responseDanger').removeClass('alert-success');	
			$('.responseDanger').css({
				'display': 'none',
				'opacity': 0
			});
			$('.enviarRespuesta').prop('disabled',false)
	});
		$('.enviarRespuesta').click(function(event) {

			var texto = $('.textoRespuesta').val();
			datos = {'id':id,'respuesta':texto,'pub_id':boton.attr('data-pub-id')};
			$.ajax({
				url: 'http://preview.pasillo24.com/publicacion/comentarios/respuesta',
				type: 'POST',
				dataType: 'json',
				data: datos,
				beforeSend:function()
				{
					$('.enviarRespuesta').prop('disabled',true)
				},
				success:function(response)
				{
					$('.responseDanger').removeClass('alert-danger');
					$('.responseDanger').removeClass('alert-success');
					$('.responseDanger').stop().css({'display':'block'}).addClass('alert-'+response.type).html('<p class="textoPromedio">'+response.msg+'</p>').animate({
					'opacity': 1},
					500);
					boton.parent().parent().remove();
				},
				error:function()
				{
					console.log('error');
				}
			})
			
		});
	});
});

jQuery(document).ready(function($) {
	$('.forgot').click(function(event) {
		$('.myModal').css({
			'display': 'block'
		}).animate({
			'opacity': 1},
			500);
		$('.cerrar').click(function(event) {
			$('.myModal').stop().animate({
				'opacity':0},
				500, function() {
				$(this).css({
					display: 'none'
				});
				$('body').css({
					'overflow': 'scroll'
				});
				$('.responseDanger').animate({
					'opacity': 0},
					500,function(){
						$(this).css({
							'display': 'none'
						});
				});
			});
			
		});
		$('body').css({
			'overflow': 'hidden'
		});
		$('.emailForgot').focus(function(event) {
			$('.responseDanger').animate({
					'opacity': 0},
					500,function(){
						$(this).css({
							'display': 'none'
						});
				});
		});
		$('.envForgot').click(function(event) {
			var email = $('.emailForgot').val();
			var boton = $(this);
			event.preventDefault();
			boton.prop({
				'disabled': true
			})
			$.ajax({
				url: 'http://preview.pasillo24.com/chequear/email',
				type: 'POST',
				dataType: 'json',
				data: {'email': email},
				beforeSend:function()
				{
					$('.envForgot').after('<img src="http://preview.pasillo24.com/images/loading.gif" class="loading">');
					$('.loading').css({
						'display': 'block',
						'margin': '2em auto'
					}).animate({
						'opacity': 1},
						500);
				},
				success:function(response){
					$('.loading').animate({
						'opacity': 0},
						500,function(){
							$(this).remove();
						});
					$('.responseDanger').removeClass('alert-danger');
					$('.responseDanger').removeClass('alert-success');
					$('.responseDanger').stop().css({'display':'block'}).addClass('alert-'+response.type).html('<p class="textoPromedio">'+response.msg+'</p>').animate({
						'opacity': 1},
						500);
					if (response.type == 'danger') {
						event.preventDefault();
					}
					boton.prop({
						'disabled': false,
					})
				},error:function()
				{
					console.log('error');
				}
			})
			
		});

	});

});

$(document).ready(function() {
    var activeSystemClass = $('.list-group-item.active');

    //something is entered in search form
    $('#buscar-usuario').keyup( function() {
       var that = this;
        // affect all table rows on in systems table
        var tableBody = $('.table-list-search tbody');
        var tableRowsClass = $('.table-list-search tbody tr');
        $('.search-sf').remove();
        tableRowsClass.each( function(i, val) {
        
            //Lower text for case insensitive
            var rowText = $(val).text().toLowerCase();
            var inputText = $(that).val().toLowerCase();
            if(inputText != '')
            {
                $('.search-query-sf').remove();
                tableBody.prepend('<tr class="search-query-sf"><td colspan="100"><strong>Buscando por: "'
                    + $(that).val()
                    + '"</strong></td></tr>');
            }
            else
            {
                $('.search-query-sf').remove();
            }

            if( rowText.indexOf( inputText ) == -1 )
            {
                //hide rows
                tableRowsClass.eq(i).hide();
                
            }
            else
            {
                $('.search-sf').remove();
                tableRowsClass.eq(i).show();
            }
        });
        //all tr elements are hidden
        if(tableRowsClass.children(':visible').length == 0)
        {
            tableBody.append('<tr class="search-sf"><td class="text-muted" colspan="100">No se encontraron resultados.</td></tr>');
        }
    });
});

$('.btn-elim').click(function(event) {
	var id = $(this).val();
	var boton = $(this);
	$('.modal-backdrop').click(function(event) {
		$('.responseDanger').css({
			'display': 'none',
			'opacity': 0
		});
		$('.alert-warning').css({'display':'block'});
		$('#eliminarUsuarioModal').prop('disabled',false);
	});
	$('.close').click(function(event) {
		$('.responseDanger').css({
			'display': 'none',
			'opacity': 0
		});
		$('.alert-warning').css({'display':'block'});
		$('#eliminarUsuarioModal').prop('disabled',false);
	});
	$('#eliminarUsuarioModal').click(function(event) {
		$(this).prop('disabled', true)
		$.ajax({
			url: 'http://preview.pasillo24.com/administrador/eliminar-usuario/enviar',
			type: 'POST',
			dataType: 'json',
			data: {'id': id},
			success:function(response){
				$('.alert-warning').css({'display':'none'});
				console.log(response)
				$('.responseDanger').removeClass('alert-danger');
					$('.responseDanger').removeClass('alert-success');
					$('.responseDanger').stop().css({'display':'block'}).addClass('alert-'+response.type).html('<p class="textoPromedio">'+response.msg+'</p>').animate({
					'opacity': 1},
					500);
				boton.parent().parent().remove();
			}
		})
		
		
	});
});

$('.btn-elim-pub').click(function(event) {
	var id = $(this).val();
	var boton = $(this);
	$('.modal-backdrop').click(function(event) {
		$('.responseDanger').css({
			'display': 'none',
			'opacity': 0
		});
		$('#eliminarPublicacionModal').prop('disabled',false);
	});
	$('.close').click(function(event) {
		$('.responseDanger').css({
			'display': 'none',
			'opacity': 0
		});
		$('#eliminarPublicacionModal').prop('disabled',false);
	});
	$('#eliminarPublicacionModal').click(function(event) {
		$(this).prop('disabled', true)
		$.ajax({
			url: 'http://preview.pasillo24.com/administrador/publicacion/eliminar-publicacion/enviar',
			type: 'POST',
			dataType: 'json',
			data: {'id': id},
			success:function(response){
				$('.alert-warning').remove();
				console.log(response)
				$('.responseDanger').removeClass('alert-danger');
					$('.responseDanger').removeClass('alert-success');
					$('.responseDanger').stop().css({'display':'block'}).addClass('alert-'+response.type).html('<p class="textoPromedio">'+response.msg+'</p>').animate({
					'opacity': 1},
					500);
				boton.parent().parent().remove();
			}
		})
		$('.modal-backdrop').click(function(event) {
			$('#eliminarPublicacionModal').prop('disabled',false)
		});
		$('.close').click(function(event) {
			$('#eliminarPublicacionModal').prop('disabled',false)
		});
		
	});
});

jQuery(document).ready(function($) {
	$('.sendPubValue').on('click',function(event) {
		$('.responseDanger').removeClass('alert-danger');
		$('.responseDanger').removeClass('alert-success');
		var id = $(this).val();
		var boton = $(this);

		$('.sendValueSeller').click(function(event) {
			event.stopImmediatePropagation();
			var tipo = $(this).val();
			var dataPost = {'tipo':tipo,'id':id};
			var neg = $('#neg'),pos = $('#pos');
			$.ajax({
				url: 'http://preview.pasillo24.com/usuario/valorar-vendedor',
				type: 'POST',
				dataType: 'json',
				data: dataPost,
				beforeSend:function()
				{
					$('#pos').animate({
						'opacity': 0},
						500, function() {
							$(this).css({'display':'none'});
					});
					$('#neg').animate({
						
						'opacity': 0},
						50, function() {
							$(this).after('<img src="http://preview.pasillo24.com/images/loading.gif" style="width:25px;height:25px;display:inline-block;" class="loading">')
							$(this).css({'display':'none'});
					});
				},
				success:function(response)
				{
					
					$('.loading').remove();
					$('.sendValueSeller').css({'opacity':1,'display':'inline-block'})
					$('.responseDanger').stop().css({'display':'block'}).addClass('alert-'+response.type).html('<p class="textoPromedio">'+response.msg+'</p>').animate({
					'opacity': 1},
					500,function()
					{
						if (response.type == 'danger') {
							pos.css({'opacity':1,'display':'inline-block'});
							neg.css({'opacity':1,'display':'inline-block'});
						}else
						{
							boton.parent().parent().remove();
							
						}
					});
					$('#modalComprar').modal('hide');
					window.location.reload();
				}
			})
			
			
		});
	});
});
jQuery(document).ready(function($) {
	$('.sendPubValue').on('click',function(event) {
		var id = $(this).val();
		var boton = $(this);
		var neg = $('#neg'),pos = $('#pos');
		$('.sendValueBuyer').click(function(event) {
			event.stopImmediatePropagation();
			var tipo = $(this).val();
			var dataPost = {'tipo':tipo,'id':id};
			
			$.ajax({
				url: 'http://preview.pasillo24.com/usuario/valorar-comprador',
				type: 'POST',
				dataType: 'json',
				data: dataPost,
				beforeSend:function()
				{
					$('#pos').animate({
						'opacity': 0},
						500, function() {
							$(this).css({'display':'none'});
					});
					$('#neg').animate({
						
						'opacity': 0},
						50, function() {
							$(this).after('<img src="http://preview.pasillo24.com/images/loading.gif" style="width:25px;height:25px;" class="loading">')
							$(this).css({'display':'none'});;
					});
				},
				success:function(response)
				{
					$('.loading').remove();
					$('.responseDanger').stop().css({'display':'block'}).addClass('alert-'+response.type).html('<p class="textoPromedio">'+response.msg+'</p>').animate({
					'opacity': 1},
					500,function()
					{
						if (response.type == 'danger') {
							$('.responseDanger').after(pos)
							pos.css({'opacity':1}).after(neg);
							neg.css({
								opacity: 1
							});
						}else
						{
							boton.parent().parent().remove();
							
						}
						$('#modalComprar').modal('hide');
						window.location.reload();
					});
				}
			})
			$('.modal-backdrop').click(function(event) {
				$('#pos').css({'display':'inline-block','opacity':1});
				$('#neg').css({'display':'inline-block','opacity':1});
				$('.responseDanger').removeClass('alert-success');
				$('.responseDanger').removeClass('alert-danger').css({
					'display': 'none',
					'opacity': 0
				});
			});
			$('.close').click(function(event) {
				$('#pos').css({'display':'inline-block','opacity':1});
				$('#neg').css({'display':'inline-block','opacity':1});
				$('.responseDanger').removeClass('alert-success');
				$('.responseDanger').removeClass('alert-danger').css({
					'display': 'none',
					'opacity': 0
				});
			});
			
		});
	});
});
jQuery(document).ready(function($) {
	$('#enviarNumTrans').click(function(event) {
		$('.numTransDanger').remove();
		if ($('#numTransVal').val().length<8) {
			event.preventDefault();
			$('#numTransVal').after('<p class="textoPromedio numTransDanger bg-danger" style="padding:1em;">El numero de transacción debe ser mas largo.</p>')
		};
	});
	$('#numTransVal').click(function(event) {
		$('.numTransDanger').remove();
	});
});

jQuery(document).ready(function($) {
	$('.btn-cerrarSession').parent().click(function(event) {
		var x = confirm('¿Seguro que desea salir?.')
		if (!x) {
			event.preventDefault();
		};
	});
});

$('#category').change(function(event) {
	var id = $(this).val();
	var data = {'id':$(this).val()};
	if (id != "") {
		$.ajax({
			url: 'http://preview.pasillo24.com/usuario/sub-categoria',
			type: 'GET',
			data: data,
			success:function(response){
				$('.optiongroup').remove();
				for (var i = 0 ; i < response.length; i++) {
					$('#subCat').append('<option class="optionModel" value="'+response[i].id+'">'+response[i].desc+'</option>');
				};
				
			}
		})
	};
});
jQuery(document).ready(function($) {
	$('.nosepuedeClick').click(function(event) {
		$('.noSePuede').css({'display':'block'}).animate({
			'opacity': 1},
			500);
	});
});
jQuery(document).ready(function($) {
	$('.ver').click(function(event) {
		var id = $(this).val();
		var username = $('.username-'+id).val();
		var name = $('.name-'+id).val();
		var email = $('.email-'+id).val();
		var phone = $('.phone-'+id).val();
		var pagWeb = $('.pagWeb-'+id).val();
		var carnet = $('.carnet-'+id).val();
		var nit = $('.nit-'+id).val();
		$('.usernameModal').html(username);
		$('.nameModal').html(name);
		$('.emailModal').html(email);
		$('.phoneModal').html(phone);
		$('.pagWebModal').html(pagWeb);
		$('.carnetModal').html(carnet);
		$('.nitModal').html(nit);
	});
});
jQuery(document).ready(function($) {
	$('.verTrans').click(function(event) {
		var id = $(this).val();
		var num 	= $('.numtrans-'+id).val();
		var bank 	= $('.bank-'+id).val();
		var bank2 	= $('.bankEx-'+id).val();
		var fech 	= $('.fech-'+id).val();
		$('.numModal').html(num);

		$('.bankModal').html(bank);
                
                                
		$('.bankExlModal').html(bank2);
		$('.fechModal').html(fech);
	});
});
jQuery(document).ready(function($) {
	function verificarComentario()
	{

		$.ajax({
			url: ' http://preview.pasillo24.com/verificar-comentarios',
			type: 'GET',
			success:function(response)
			{
				if(response != 0)
				{
					$('.subComentario').html(response)
				}
			}
		})
		
		
	}
	setTimeout(function(){ verificarComentario(); }, 1);
	setInterval(verificarComentario,120000)
});

jQuery(document).ready(function($) {
	$('.depFilter').change(function(event) {
		$('.formDepFilter').submit();
	});
});