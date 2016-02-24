jQuery(document).ready(function($) {
	$('.refresh').click(function(event) {
		var id = $(this).val(),status = $(this).data('status');
		var boton = $(this);
		$.ajax({
			url: 'http://pasillo24.com/administrador/editar-publicidad/eliminar',
			type: 'POST',
			dataType: 'json',
			data: {'id': id,'status':status},
			beforeSend:function () {
				boton.after('<img src="http://pasillo24.com/images/loading.gif" class="loading">');
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
				url: 'http://pasillo24.com/administrador/editar-publicidad/eliminar',
				type: 'POST',
				dataType: 'json',
				data: {'id': id},
				beforeSend:function() {
					boton.after('<img src="http://pasillo24.com/images/loading.gif" class="loading">');
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
			url: 'http://pasillo24.com/publicacion/habitual/previsualizar/cambiar/posiciones',
			type: 'POST',
			dataType: 'json',
			data: {'arr': arr,'id':id},
			beforeSend:function () {
				$('.btnChangeEnviar').before('<img src="http://pasillo24.com/images/loading.gif" class="loading">');
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
	$('.btnEliminarPub').on('click',function(event) {
		var boton = $(this);
		var val = $(this).val();
		$('.btnElimPublicacion').val(val);
		boton.addClass('to-elim')
		
	});
	$('#modalElimUserPub').on('hide.bs.modal', function(event) {
			$('.responseDanger').removeClass('alert-danger');
			$('.responseDanger').removeClass('alert-success');
			$('.responseDanger').removeClass('active');
			if ($('.to-elim').length > 0) {
				$('.to-elim').removeClass('to-elim')
			};
			$('.btn-dimiss').addClass('hidden');
			$('.btnElimPublicacion').removeClass('hidden');
		});
	$('.btnElimPublicacion').on('click',function(event) {
		var boton = $(this);
			$.ajax({
				url: 'http://pasillo24.com/usuario/publicaciones/mis-publicaciones/eliminar/publicacion',
				type: 'POST',
				dataType: 'json',
				data: {'id': $(this).val()},
				beforeSend:function(){
					$('.miniLoader').addClass('active');
					$('.btnElimPublicacion').addClass('disabled')
					$('.close').addClass('hidden');
				},
				success:function (response) {
					$('.close').removeClass('hidden');
					$('.miniLoader').removeClass('active');
					$('.btnElimPublicacion').removeClass('disabled');
					$('.responseDanger').addClass('alert-'+response.type).addClass('active');
					$('.responseDanger-text').html(response.msg);
					if (response.type == 'success') {
						$('.to-elim').parent().parent().remove();
						$('.btn-dimiss').removeClass('hidden');
						boton.addClass('hidden');
					};
				}
			})
			
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
				url: 'http://pasillo24.com/user/items/delete',
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
$('.continueCasual').click(function(event){
	$('.info').animate({'opacity': 0},500, function(){
			$(this).remove();	
			$('.formPub').css({'display':'block','opacity':0}).animate({
				
				'opacity': 1
			},
				500
			);
	});
})
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
			url: 'http://pasillo24.com/publicacion/model',
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
jQuery(document).ready(function($) {
	$('#ubication').change(function(){
		var esto = $(this);
		if (esto.val() == 'Categoria') {
			esto.parent().removeClass('col-xs-12').addClass('col-xs-6');
			$('.contCatLider').addClass('showit')
		}else
		{
			esto.parent().removeClass('col-xs-6').addClass('col-xs-12');
			$('.contCatLider').removeClass('showit');
		}
	})
});
$('.continue').click(function(event) {
	$('.info').animate({'opacity': 0},500, function(){
			$(this).remove();	
			$('.formPub').css({'display':'block','opacity':0}).animate({
				
				'opacity': 1
			},
				500
			);
	});
	
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
				url: 'http://pasillo24.com/usuario/publicacion/lider/fecha',
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
			$('#enviarComentario').addClass('active')
		})
	});

	$('#enviarComentario').on('click',function(event) {
		removeResponsetype();
		var btn = $(this);
		btn.addClass('disabled')
		 var data = {
			'id' : $(this).val(),
			'comment': $('#inputComentario').val()
		}	    
		var proceed = 1;
		if ($('#inputComentario').val() == "") {
			proceed = 0;
			$('#enviarComentario').removeClass('disabled')
		};
	    if (proceed == 1) {
	        $.ajax({
	          url: 'http://pasillo24.com/publicacion/comentario',
	          type: 'GET',
	          dataType: 'json',
	          data: data,
	          beforeSend:function() {
	            $('.miniLoader').addClass('active');
	            $('#inputComentario').addClass('disabled')
	          },
	          success:function(response)
	          {
	            $('.miniLoader').removeClass('active');
	            $('#inputComentario').removeClass('disabled')
	            btn.removeClass('disabled');
	            if (response.type == 'success') {
	              var clon = $('.new-comment').clone();
	              $('.new-comment').children('.comment-text').html('<i class="fa fa-comment"></i> '+data['comment'])
	              $('.new-comment').children('.comment-date').html(response.date)
	              $('.new-comment').removeClass('new-comment').after(clon);
	              $('#inputComentario').val('')
	              alert(response.msg);
	            }
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
	$('.btnCancelar').on('click',function(event) {
		var btn = $(this);
		btn.addClass('to-elim');
		$('.send-elim').val(btn.val());
		$('.responseDanger').removeClass('alert-danger');
		$('.responseDanger').removeClass('alert-success').removeClass('active');

	});
	$('#eliminar-publicacion').on('hide.bs.modal', function(event) {
		$('.to-elim').removeClass('to-elim');
		$('.miniLoader').removeClass('active');
		$('.send-elim').removeClass('disabled').removeClass('hidden');
		$('.btn-dimiss').addClass('hidden')
		$('.motivo').val('');

	});
	$('.send-elim').on('click', function(event) {
		event.preventDefault();
		removeResponsetype();
		var proceed = 1;
		var boton = $(this);
		if ($('.motivo').val().length < 5) {
			proceed = 0;
		};
		if (proceed == 1) {
			var dataPost = {
				'id' 	: $(this).val(),
				'motivo': $('.motivo').val()
			}
			$.ajax({
				url: 'http://pasillo24.com/administrador/pagos/cancelar',
				type: 'post',
				dataType: 'json',
				data: dataPost,
				beforeSend: function(){
					$('.miniLoader').addClass('active');
					$('.send-elim').addClass('disabled');
					$('.close').addClass('hidden')
				},
				success:function(response)
				{
					$('.close').removeClass('hidden');
					$('.miniLoader').removeClass('active');
					
					$('.responseDanger-text').html(response.msg)
					$('.responseDanger').addClass('alert-'+response.type).addClass('active');
					if (response.type == 'success') {
						$('.motivo').val('');
						$('.to-elim').parent().parent().remove();
						boton.addClass('hidden')
						$('.btn-dimiss').removeClass('hidden');
					}else
					{
						boton.removeClass('disabled')
					}
				}
			})
		};
	});
});
/*-------------------Casual------------------------------*/
	jQuery(document).ready(function($) {
		if ($('.casual-form').length > 0) {
			var rand1 = Math.round(Math.random()*100);
			var rand2 = Math.round(Math.random()*100);
			$('.formula').html('Cuanto es: '+rand1+'+'+rand2).append('<input type="hidden" name="x" value="'+rand1+'">').append('<input type="hidden" name="y" value="'+rand2+'">')
			$('.resultado').html(' '+(rand1+rand2));
			
		};
	});
/*------------Responder---------------*/
$(document).ready(function() {
	$('.btn-responder').click(function(event) {
		var id 	  = $(this).val();
		var pubid = $(this).data('pub-id');
		var boton = $(this);
		boton.addClass('to-elim');
		$('.enviarRespuesta').val(id);
		$('.enviarRespuesta').attr('data-pub-id',pubid);
		$('.textoRespuesta').on('click',function(event) {
			$('.responseDanger').css({'display':'none'})
		});
	});
	$('#myComment').on('hide.bs.modal', function(event) {
		$('.responseDanger').removeClass('alert-danger');
		$('.responseDanger').removeClass('alert-success');
		$('.responseDanger').css({
			'display': 'none',
			'opacity': 0
		});
		$('.enviarRespuesta').removeClass('disabled').removeClass('hidden');
		$('.btn-dimiss').addClass('hidden');
	});
	$('.enviarRespuesta').on('click',function(event) {
		removeResponsetype();
		var texto = $('.textoRespuesta').val();
		var id    = $(this).val(),
			pubid = $(this).data('pub-id')
		datos = {'id':id,'respuesta':texto,'pub_id':pubid};
		var boton = $(this);
		$.ajax({
			url: 'http://pasillo24.com/usuario/publicaciones/comentarios/respuesta',
			type: 'POST',
			dataType: 'json',
			data: datos,
			beforeSend:function()
			{
				$('.miniLoader').addClass('active');
				$('.enviarRespuesta').addClass('disabled');
				$('.close').addClass('hidden');
			},
			success:function(response)
			{
				$('.close').removeClass('hidden');
				$('.textoRespuesta').val('');
				$('.miniLoader').removeClass('active');
				$('.responseDanger').removeClass('alert-danger');
				$('.responseDanger').removeClass('alert-success');
				$('.responseDanger').stop().css({'display':'block'}).addClass('alert-'+response.type).html('<p class="textoPromedio text-centered">'+response.msg+'</p>').animate({
				'opacity': 1},
				500);
				if (response.type == 'success') {
					$('.to-elim').parent().parent().remove();
					boton.addClass('hidden');
					$('.btn-dimiss').removeClass('hidden');
					
				};
			},
			error:function()
			{
				$('.close').removeClass('hidden');
				$('.miniLoader').removeClass('active');
				$('.enviarRespuesta').removeClass('disabled')				
			}
		})
		
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

	});
	$('.envForgot').on('click',function(event) {
		var email = $('.emailForgot').val();
		var boton = $(this);
		event.preventDefault();
		boton.addClass('disabled')
		$.ajax({
			url: 'http://pasillo24.com/chequear/email',
			type: 'POST',
			dataType: 'json',
			data: {'email': email},
			beforeSend:function()
			{
				$('.envForgot').after('<img src="http://pasillo24.com/images/loading.gif" class="loading">');
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
				boton.removeClass('disabled')
			},error:function()
			{
				console.log('error');
			}
		})
		
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
jQuery(document).ready(function($) {
	$('.btn-elim').click(function(event) {
		var id = $(this).val();
		var boton = $(this);
		boton.addClass('to-elim');
		$('#eliminarUsuarioModal').val(id);
	});
	$('#modalElimUser').on('hide.bs.modal', function(event) {
		removeResponsetype();
		if ($('.to-elim').length > 0) {
			$('.to-elim').removeClass('to-elim');
		};
		$('#eliminarUsuarioModal').removeClass('disabled').removeClass('hidden');
		$('.btn-dimiss').addClass('hidden');
	});
	$('#eliminarUsuarioModal').on('click',function(event) {
		removeResponsetype();
		var boton = $(this);
		boton.addClass('disabled');
		var id = boton.val();
		$.ajax({
			url: 'http://pasillo24.com/administrador/eliminar-usuario/enviar',
			type: 'POST',
			dataType: 'json',
			data: {'id': id},
			beforeSend: function()
			{
				$('.miniLoader').addClass('active');
				$('.close').addClass('hidden');
			},
			success:function(response){
				$('.close').removeClass('hidden');
				$('.miniLoader').removeClass('active');
				$('.responseDanger').addClass('alert-'+response.type).addClass('active');
				$('.responseDanger-text').html(response.msg)
				if (response.type == 'success') {
					boton.addClass('hidden');
					$('.btn-dimiss').removeClass('hidden');
					$('.to-elim').parent().parent().remove();
				}else if(response.type == 'danger'){
					$('.responseDanger-text').addClass('alert-danger').html('Error al eliminar el usuario');
					boton.removeClass('disabled');
				}
			},error:function()
			{
				$('.miniLoader').removeClass('activa');
				$('.responseDanger-text').addClass('alert-danger').html('Error al eliminar el usuario');
			}
		})
		
		
	});
	
});
function removeResponsetype () {
	$('.responseDanger').removeClass('alert-danger');
	$('.responseDanger').removeClass('alert-success');
	$('.responseDanger').removeClass('active');
}
jQuery(document).ready(function($) {
	
	$('.btn-elim-pub').click(function(event) {
		var id = $(this).val();
		var boton = $(this);
		boton.addClass('to-elim');
		$('#eliminarPublicacionModal').val(boton.val())
	});
	$('#modalElimPub').on('hide.bs.modal',function(event) {
		$('.responseDanger').removeClass('alert-danger');
		$('.responseDanger').removeClass('alert-success');
		$('.responseDanger').removeClass('active');
		$('#eliminarPublicacionModal').removeClass('disabled');
		$('#eliminarPublicacionModal').removeClass('hidden');
		$('.btn-dimiss').addClass('hidden');
	});
	$('#eliminarPublicacionModal').on('click',function(event) {
		removeResponsetype();
		var boton = $(this);
		if ($('.motivo').val() != "") {
			$.ajax({
				url: 'http://pasillo24.com/administrador/publicacion/eliminar-publicacion/enviar',
				type: 'POST',
				dataType: 'json',
				data: {'id': boton.val(),'motivo':$('.motivo').val()},
				beforeSend:function()
				{
					$('.miniLoader').addClass('active');
					boton.addClass('disabled');
					$('.close').addClass('hidden');
				},
				success:function(response){
					$('.miniLoader').removeClass('active');
					$('.close').removeClass('hidden');
					$('.responseDanger').addClass('alert-'+response.type);
					$('.responseDanger').addClass('active');
					$('.responseDanger-text').html(response.msg);
					if (response.type == 'success') {
						$('.to-elim').parent().parent().remove();
						$('.motivo').val('');
						boton.addClass('hidden');
						$('.btn-dimiss').removeClass('hidden');
					}else if(response.type != 'danger')
					{
						$('.responseDanger').addClass('alert-danger').addClass('active');
						$('.responseDanger-text').html('Ha ocurrido un error.');
						boton.removeClass('disabled');
					}
				},
				error:function()
				{
					$('.responseDanger').addClass('alert-danger').addClass('active')
					$('.responseDanger-text').html('Ha ocurrido un error.');
					$('.miniLoader').removeClass('active');
					$('.close').removeClass('hidden');
					boton.removeClass('disabled');
				}
			})
		}else
		{
			alert('Debe redactar un motivo');
		}
	});
});
function sendValueReputation(event) {
	var tipo = $(this).val();
	var dataPost = {'tipo':tipo,'id':$(this).data('id')};
	$.ajax({
		url: $(this).data('url'),
		type: 'POST',
		dataType: 'json',
		data: dataPost,
		beforeSend:function()
		{
			$('.sendValueType').addClass('disabled');
			$('.miniLoader').addClass('active');
		},
		success:function(response)
		{
			$('.responseDanger').addClass('alert-'+response.type).addClass('active').html('<p class="textoPromedio">'+response.msg+'</p>');
			if (response.type == 'success') {
				$('.to-elim').parent().parent().remove();
				$('#modalComprar').modal('hide');
			};
		}
	})
	
	
}
jQuery(document).ready(function($) {
	$('.sendPubValue').on('click',function(event) {
		$('.responseDanger').removeClass('alert-danger');
		$('.responseDanger').removeClass('alert-success');
		var id = $(this).val();
		var boton = $(this);
		boton.addClass('to-elim')
		$('.sendValueType').attr('data-id',boton.val());
	});
	$('#modalComprar').on('hide.bs.modal', function(event) {
		if ($('.to-elim').length > 0) {
			$('.to-elim').removeClass('to-elim');
		};
	});
	$('.sendValueType').click(sendValueReputation);
});

jQuery(document).ready(function($) {
	$('#enviarNumTrans').click(function(event) {
		$('.numTransDanger').remove();
		if ($('#numTransVal').val().length<4) {
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
			url: 'http://pasillo24.com/usuario/sub-categoria',
			type: 'GET',
			data: data,
			beforeSend:function()
			{
				$('.optiongroup').remove();
			},
			success:function(response){
				for (var i = 0 ; i < response.length; i++) {
					$('#subCat').append('<option class="optiongroup" value="'+response[i].id+'">'+response[i].desc+'</option>');
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
function verificarComentario()
{

	$.ajax({
		url: ' http://pasillo24.com/verificar-comentarios',
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
jQuery(document).ready(function($) {
	setTimeout(function(){ verificarComentario(); }, 1);
	setInterval(verificarComentario,120000)
});

jQuery(document).ready(function($) {
	$('.depFilter').change(function(event) {
		$('.formDepFilter').submit();
	});
	$('.bandera').hover(function() {
		if (!$(this).hasClass('bandera-bolivia')) {
			$('.bandera-bolivia').addClass('old-bandera-bolivia').removeClass('bandera-bolivia');
			$(this).addClass('bandera-bolivia');
		};
	}, function() {
		if ($('.old-bandera-bolivia').length > 0) {
			$('.bandera-bolivia').removeClass('bandera-bolivia');
			$('.old-bandera-bolivia').addClass('bandera-bolivia');
		};
	});
});


jQuery(document).ready(function($) {
	$('.addNewimage').on('click', function(event) {
		event.preventDefault();
		$('.new-imagen:first').removeClass('new-imagen');
	});
	function dimiss(event) {
		event.preventDefault();
		var btn = $(this)
		btn.next().next().replaceWith(btn.next().next().clone());;
		$(this).parent('.col-xs-6').addClass('new-imagen');
	}
	$('.dismiss-new-imagen').on('click', dimiss);
	$('.change-response-text').on('click', function(event) {
		event.preventDefault();
		var txt = $(this).data('txt');
		$('.response-text').html(txt);
	});
 	$('.remove-imagen').on('click', function(event) {
 		event.preventDefault();
 		var btn = $(this);
 		var x = confirm('¿Seguro desea eliminar esta imagen?');

 		if (x) {
 			var dataPost = {
 				'img' : btn.data('img'),
 				'id'  : btn.data('id')
 			};
 			$.ajax({
 				url: 'http://pasillo24.com/modificar/publicacion/eliminar/imagen',
 				type: 'POST',
 				dataType: 'json',
 				data: dataPost,
 				beforeSend:function()
 				{
 					$('.contLoading').fadeIn('fast',function(){
						$(this).show('fast')
					})
 				},success:function(response){
					btn.parent().parent().addClass('new-imagen');
					btn.parent().prev().addClass('input-new-imagen');
 					btn.parent().parent().prepend('<button type="button" class="close dismiss-new-imagen" >&times;</button>')
 					$('.dismiss-new-imagen').bind('click',dimiss);
 					btn.parent().remove();
 					$('.contLoading').fadeOut('fast',function(){
						$(this).hide('fast')
					})
 				}
 			})
 			
 			

 		};
 	});
 	$('.elimComentario').on('click', function(event) {
 		$('.responseDanger').removeClass('alert-danger');
		$('.responseDanger').removeClass('alert-success');
		$('.responseDanger').removeClass('active')
		$('.btnElimCommentSend').removeClass('disabled')
 		var btn = $(this);
 		btn.addClass('to-elim');
 		$('.btnElimCommentSend').val(btn.val());
 	});
 	$('#deleteComment').on('hide.bs.modal', function(event) {
		if ($('.to-elim').length > 0) {
			$('.to-elim').removeClass('to-elim')
		};
		$('.btnElimCommentSend').removeClass('disabled').removeClass('hidden');
		$('.btn-dimiss').addClass('hidden');
	});
 	$('.btnElimCommentSend').on('click', function(event) {
 		var dataPost = {
 			'id' : $(this).val()
 		}
 		var boton = $(this);
 		$.ajax({
 			url: $('.to-elim').data('url'),
 			type: 'post',
 			dataType: 'json',
 			data: dataPost,
 			beforeSend:function(){
 				$('.btnElimCommentSend').addClass('disabled');
 				$('.miniLoader').addClass('active');
 				$('.close').addClass('hidden');
 			},
 			success:function(response){
 				$('.close').removeClass('hidden');
 				$('.miniLoader').removeClass('active');
 				$('.responseDanger').addClass('active').addClass('alert-'+response.type).html('<p class="textoPromedio">'+response.msg+'</p>');
 				if (response.type == 'success') {
 					verificarComentario();
 					$('.to-elim').parent().parent().remove();
 					boton.addClass('hidden');
 					$('.btn-dimiss').removeClass('hidden');
 				};
 			}
 		})
 		
 	});
 	$('.btn-elim-cat').on('click', function(event) {
		var btn = $(this);
		var id = btn.val();
		btn.addClass('to-elim'); 		
		$('.eliminar-categoria').val(id);
 	});
 	$('.eliminar-categoria').on('click', function(event) {
 		var btn = $(this);
 		dataPost = { 'id': btn.val() }
 		$.ajax({
 				url: 'http://pasillo24.com/administrador/categorias/eliminar',
 				type: 'POST',
 				dataType: 'json',
 				data: dataPost,
 				beforeSend:function()
 				{
 					$('.miniLoader').addClass('active');
 					btn.addClass('disabled');
 					$('.close').addClass('hidden');
 				},success:function(response){
 					$('.close').removeClass('hidden');
 					$('.responseDanger').addClass('alert-'+response.type).addClass('active');
 					$('.responseDanger .responseDanger-text').html(response.msg);
 					$('.miniLoader').removeClass('active')
					if (response.type == 'danger') {
						btn.removeClass('disabled');
						$('.miniLoader').removeClass('active');
					}else
					{
						btn.addClass('hidden');
						$('.btn-dimiss').removeClass('hidden');
						$('.to-elim').parent().parent().remove();
					}
 				}
 			})
 	});
 	$('.modal-elim-cat').on('hide.bs.modal', function(event) {
 		$('.to-elim').removeClass('to-elim');
 		$('.responseDanger').removeClass('alert-danger').removeClass('alert-success').removeClass('active');
 		$('.responseDanger .responseDanger-text').html('');
 		$('.eliminar-categoria').removeClass('disabled').removeClass('hidden');
 		$('.btn-dimiss').addClass('hidden');
 	});
 	$('.btn-elim-subcat').on('click', function(event) {
		var btn = $(this);
		var id = btn.val();
		btn.addClass('to-elim'); 		
		$('.eliminar-subcategoria').val(id);
 	});
 	$('.eliminar-subcategoria').on('click', function(event) {
 		var btn = $(this);
 		dataPost = { 'id': btn.val() }
 		$.ajax({
 				url: 'http://pasillo24.com/administrador/subcategorias/eliminar',
 				type: 'POST',
 				dataType: 'json',
 				data: dataPost,
 				beforeSend:function()
 				{
 					$('.miniLoader').addClass('active');
 					btn.addClass('disabled');
 					$('.close').addClass('hidden');
 				},success:function(response){
 					$('.close').removeClass('hidden');
 					$('.responseDanger').addClass('alert-'+response.type).addClass('active');
 					$('.responseDanger .responseDanger-text').html(response.msg);
 					$('.miniLoader').removeClass('active')
					if (response.type == 'danger') {
						btn.removeClass('disabled');
						$('.miniLoader').removeClass('active');
					}else
					{
						$('.to-elim').parent().parent().remove();
						$('.btn-dimiss').removeClass('hidden');
						btn.addClass('hidden');
					}
 				}
 			})
 	});
 	$('.modal-elim-subcat').on('hide.bs.modal', function(event) {
 		$('.to-elim').removeClass('to-elim');
 		$('.responseDanger').removeClass('alert-danger').removeClass('alert-success').removeClass('active');
 		$('.responseDanger .responseDanger-text').html('');
 		$('.eliminar-subcategoria').removeClass('disabled').removeClass('hidden');
 		$('.btn-dimiss').addClass('hidden');
 	});
 	$('.btn-elim-account').on('click', function(event) {
 		var boton = $(this);
 		boton.addClass('to-elim');
 		$('.eliminar-cuenta').val(boton.val());
 	});
 	$('#elimAccountModal').on('hide.bs.modal', function(event) {
		$('.responseDanger').removeClass('alert-danger');
		$('.responseDanger').removeClass('alert-success');
		$('.responseDanger').removeClass('active')
		if ($('.to-elim').length > 0) {
			$('.to-elim').removeClass('to-elim')
		};
		$('.eliminar-cuenta').removeClass('disabled').removeClass('hidden');
 		$('.btn-dimiss').addClass('hidden');
	});
 	$('.eliminar-cuenta').on('click',function(event) {
		var boton = $(this);
		$.ajax({
 			url: 'http://pasillo24.com/administrador/editar-cuenta/eliminar',
 			type: 'POST',
 			dataType: 'json',
 			data: {'id': boton.val()},
 			beforeSend:function()
 			{
 				$('.miniLoader').addClass('active');
 				boton.addClass('disabled');
 				$('.close').addClass('hidden');
 			},
 			success:function(response)
 			{
 				$('.close').removeClass('hidden');
 				$('.miniLoader').removeClass('active');
 				if (response.type == 'success') {
 					$('.to-elim').parent().parent().remove();
 					boton.addClass('hidden');
 					$('.btn-dimiss').removeClass('hidden');
 				}else
 				{
 					boton.removeClass('disabled')

 				}
 				$('.responseDanger').addClass('alert-'+response.type).addClass('active');
 				$('.responseDanger-text').html(response.msg)
 			}
 		})
		 		
		 		 		
 	});
});

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
jQuery(document).ready(function($) {

	$('.doMap').on('click', function(event) {
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

});
