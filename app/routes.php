<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/
Route::get('test','AjaxController@getTest');
Route::get('/', 'HomeController@showFront');
Route::get('inicio','HomeController@showIndex');
Route::get('inicio/departamentos/{id}','HomeController@showIndex');
Route::get('inicio/login','AuthController@getLogin');
Route::get('inicio/logout','AuthController@logOut');
Route::post('inicio/login/auth','AuthController@postLogin');
Route::get('inicio/registro','AuthController@getRegister');
Route::get('inicio/registro/codigo/{username}/{codigo}','AuthController@getCode');
Route::post('inicio/registro/enviar','AuthController@postRegister');
Route::get('inicio/contactenos','HomeController@getContact');
Route::post('inicio/contactenos/enviar','ContactoController@postContacto');
Route::get('mision-y-vision','HomeController@getMision');
Route::get('inicio/politica-de-privacidad','HomeController@getPolitics');

//Buscar
Route::get('inicio/buscar', 'HomeController@getSearch');
//categorias
Route::get('publicaciones/categorias/{id}','PublicationController@getPublicationCategory');
Route::get('publicaciones/departamentos/{id}','PublicationController@getPublicationDepartment');
//vista de una publicacion lider
Route::get('publicacion/lider/{id}','PublicationController@getPublication');
//vista de la publicacion habitual
Route::get('publicacion/habitual/{id}','PublicationController@getPublication');

	//Route::get('publicacion/habitual/{id}','PublicationController@getPublication');
Route::get('publicacion/casual/{id}', 'PublicationController@getPublication');

Route::get('publicacion/obtener-cercanos/{id}','PublicationController@getLocations');


Route::post('chequear/email','AuthController@postEmailCheck');
Route::get('inicio/terminos-y-condiciones', 'HomeController@getTermsAndConditions');

//app
Route::get('app/inicio','AjaxController@showIndex');
Route::get('app/publicacion','AjaxController@publicationSelf');


Route::get('app/lider','AjaxController@getLider');

Route::get('app/inicio/departamentos/{id}','AjaxController@showIndex');
Route::get('app/inicio/categorias/{id}','AjaxController@getPublicationCategory');
Route::get('app/inicio/buscar','AjaxController@search');

Route::get('app/publicaciones/categorias/{id}','AjaxController@getCategories');
Route::post('app/login','AjaxController@getLoginApp');
Route::post('app/registro','AjaxController@postRegisterApp');

Route::post('app/cambiar-clave','AjaxController@resetPassword');
Route::group(array('before' => 'check_app_auth'),function()
{
	Route::post('app/registrar-gcm','AjaxController@getGcm');
	Route::post('app/publicacion/comentar','AjaxController@postComment');

	Route::post('app/publicacion/contactar','AjaxController@getCompra');
	Route::post('app/usuario/perfil/enviar','AjaxController@postProfile');
	Route::post('app/usuario/cambiar-clave','AjaxController@postChangePass');

	Route::post('app/usuario/publicacion/lider/enviar','AjaxController@postLider');
	Route::get('app/calcular-costo','AjaxController@getDate');
	Route::post('app/usuario/publicacion/habitual/enviar','AjaxController@postHabitual');
	Route::get('app/usuario/publicacion/habitual/{id}/previsualizar','AjaxController@getHabitualPreview');
	Route::post('app/usuario/publicacion/habitual/incremento','AjaxController@postHabitualAdd');
	Route::post('app/usuario/publicacion/casual/enviar','AjaxController@postCasual');
	Route::post('app/usuario/publicaciones/reactivar/{id}','AjaxController@postReactivate');
	Route::post('app/usuario/publicaciones/pago/enviar','AjaxController@postPublicationPayment');

	Route::post('app/usuario/publicaciones/eliminar','AjaxController@postElimPub');

	Route::post('app/usuario/publicaciones/mis-publicaciones/{type}','AjaxController@getMyPublicationsType');
	Route::post('app/usuario/publicaciones/mis-publicaciones/{type}/modificar','AjaxController@postModifyPub');

	Route::get('app/usuario/mis-compras','AjaxController@getMyCart');
	Route::get('app/usuario/mis-ventas','AjaxController@getMySell');
	Route::post('app/usuario/valorar-vendedor','AjaxController@postValorVend');
	Route::post('app/usuario/valorar-comprador','AjaxController@postValorComp');
	Route::get('app/usuario/mi-reputacion','AjaxController@getMyReputation');

	Route::get('app/usuario/mis-comentarios','AjaxController@getMyComment');
	Route::post('app/usuario/comentarios/marcar','AjaxController@postElimCommentrecividos');
	Route::post('app/usuario/responder','AjaxController@postResponse');

	Route::get('app/agregar-favorito/{id}','AjaxController@addFav');
	Route::get('app/remover-favorito/{id}','AjaxController@removeFav');
	Route::get('app/ver-favorito','AjaxController@getMyFav');

	Route::get('app/usuario-datos','AjaxController@getUserData');

	Route::get('app/precios','AjaxController@getPrice');
});
//rutas globales
Route::get('app/categorias','AjaxController@getCategory');
Route::get('app/sub-categoria','AjaxController@getSubCategory');
Route::get('app/departamentos','AjaxController@getDepartments');
Route::get('app/marcas','AjaxController@getBrand');
Route::get('app/modelos','AjaxController@getModel');


Route::get('app/pagina-web-link','AjaxController@getPubUrl');
Route::post('app/subir-imagenes/{carpeta}','AjaxController@upload_image');




Route::group(array('before' =>'auth'),function()
{
	//favoritos
	Route::get('usuario/mis-favoritos','UserController@getMyFav');
	Route::get('usuario/publicaciones/agregar-favorito/{id}','UserController@addFav');
	Route::get('usuario/publicaciones/remover-favorito/{id}','UserController@removeFav');
	
	//perfil
	Route::get('usuario/perfil','UserController@getProfile');
	Route::post('usuario/perfil/enviar','UserController@postProfile');
	Route::get('usuario/cambiar-clave','UserController@getChangePass');
	Route::post('usuario/cambiar-clave/enviar','UserController@postChangePass');
	//publicaciones propias del usuario
	Route::get('usuario/publicaciones/mis-publicaciones','UserController@getMyPublications');
	Route::get('usuario/publicaciones/mis-publicaciones/{type}','PublicationController@getMyPublicationsType');
	Route::get('usuario/publicaciones/comentarios','PublicationController@getMyComment');
	Route::get('verificar-comentarios','HomeController@getVerifyComment');
	Route::post('usuario/publicaciones/comentarios/respuesta', 'PublicationController@postResponse');
	Route::post('usuario/publicaciones/mis-publicaciones/eliminar/publicacion','PublicationController@postElimPub');

	Route::get('usuario/publicaciones/reactivar/{id}','UserController@getReactivate');
	Route::post('usuario/publicaciones/reactivar/{id}/enviar','UserController@postReactivate');
	//publicar
	Route::get('usuario/publicar','UserController@getpublication');
	//lider
	Route::get('usuario/publicacion/lider','UserController@getPublicationLider');
	Route::get('usuario/publicacion/lider/fecha','UserController@getDate');
	Route::post('usuario/publicacion/lider/enviar','UserController@postPublicationLider');
	Route::get('usuario/publicacion/pagar/{id}','UserController@getPublicationPayment');
	
	//normal	
	//Route::get('usuario/publicar/habitual/{type}','PublicationController@getHabitualForm');

	Route::get('usuario/publicacion/habitual','UserController@getPublicationNormal');
	Route::get('publicacion/habitual/crear/{id}','PublicationController@getPublicationNormalType');
	Route::post('publicacion/habitual/enviar','PublicationController@postPublicationHabitual');
	Route::get('publicacion/habitual/enviar/imagenes/{id}','PublicationController@getPublicationHabitualImages');
	Route::post('publicacion/habitual/enviar/imagenes/procesar','PublicationController@post_upload');
	Route::post('publicacion/habitual/enviar/imagenes/eliminar','PublicationController@post_delete');
	Route::get('publicacion/habitual/previsualizar/{id}','PublicationController@getPreview');
	Route::get('publicacion/model', 'UserController@getModelo');
	/*Route::post('usuario/publicacion/habitual/{type}/enviar','PublicationController@postPublicationNormal');*/
	Route::get('usuario/publicacion/habitual/pago/{id}','PublicationController@getPublicationNormalPayment');
	Route::post('usuario/publicacion/habitual/pago/procesar','PublicationController@postPublicationNormalPayment');
	//Prueba del cambio de posicion de las imagenes
	Route::post('publicacion/habitual/previsualizar/cambiar/posiciones','PublicationController@postChangePost');
	
	//casual
	Route::get('usuario/publicacion/casual','PublicationController@getPublicationCasual');
	Route::post('usuario/publicacion/casual/enviar','PublicationController@postPublicationCasual');
	//pagos
	Route::get('usuario/publicaciones/pago/{id}','UserController@getPublicationPayment');
	Route::post('usuario/publicaciones/pago/enviar', 'UserController@postPublicationPayment');

	//comentario
	Route::get('publicacion/comentario', 'PublicationController@postComment');
	Route::post('publicacion/comentario/respuesta', 'PublicationController@postResponse');
	//compra
	Route::post('publicacion/comprar','PublicationController@getCompra');
	Route::get('usuario/mis-compras','UserController@getMyCart');
	Route::get('usuario/mis-ventas','UserController@getMySell');
	Route::post('usuario/valorar-vendedor','UserController@postValorVend');
	Route::post('usuario/valorar-comprador','UserController@postValorComp');

	Route::get('usuario/mi-reputacion','UserController@getMyReputation');

	Route::get('usuario/publicacion/modificar/{id}','PublicationController@getModifyPub');
	Route::post('usuario/publicacion/modificar/{type}/{id}','PublicationController@postModifyPub');
	Route::get('usuario/sub-categoria', 'PublicationController@getSubCat');
	Route::get('usuario/longitud', 'PublicationController@getLength');
	
	Route::post('usuario/comentarios/recividos/eliminar','UserController@postElimCommentrecividos');
	Route::post('usuario/comentarios/hechos/eliminar','UserController@postElimComment');
	Route::post('modificar/publicacion/eliminar/imagen','PublicationController@postElimImage');

	Route::post('usuario/mi-reputacion/borrar-compra','UserController@removeComp');
	Route::post('usuario/mi-reputacion/borrar-venta','UserController@removeVend');
	//rutas del admin
	Route::group(array('before' => 'role_check'), function()
	{
		Route::get('administrador/categorias','AdministratorController@getCategories');
		Route::get('administrador/categoria/modificar/{id}','AdministratorController@getModifyCategories');
		Route::post('administrador/categoria/modificar','AdministratorController@postModifyCategories');
		Route::post('administrador/categorias/eliminar','AdministratorController@postElimCat');
		Route::get('administrador/sub-categorias','AdministratorController@getSubCat');
		Route::get('administrador/sub-categoria/modificar/{id}','AdministratorController@getModifySubCategories');
		Route::post('administrador/sub-categoria/modificar','AdministratorController@postModifySubCategories');
		Route::post('administrador/subcategorias/eliminar','AdministratorController@postElimSubCat');
		Route::post('administrador/categoria/nueva','AdministratorController@postNewCat');
		Route::post('administrador/sub-categoria/nueva','AdministratorController@postNewSubCat');

		Route::get('administrador/pagos', 'AdministratorController@getPagos');
		Route::get('administrador/pagos/{type}', 'AdministratorController@getPagosType');
		Route::post('administrador/pagos/cancelar', 'AdministratorController@postPagosCancel');
		Route::get('administrador/publicaciones', 'AdministratorController@getPublication');
		Route::get('administrador/publicacion/{type}','AdministratorController@getPublicationType');
		Route::post('administrador/publicacion/eliminar-publicacion/enviar','AdministratorController@postElimPub');
		Route::get('administrador/modificar-publicaciones','AdministratorController@getModifyPub');
		Route::post('administrador/modificar-textos','AdministratorController@postModifyPub');
		Route::get('administrador/modificar-precios','AdministratorController@getModifyPrice');
		Route::post('administrador/modificar-precios/enviar','AdministratorController@postModifyPrice');
		Route::get('administrador/crear-nuevo', 'AdministratorController@getNewAdmin');
		Route::post('administrador/crear-nuevo/enviar', 'AdministratorController@postNewAdmin');
		Route::get('administrador/eliminar-usuario','AdministratorController@getUserElim');
		Route::post('administrador/eliminar-usuario/enviar','AdministratorController@postUserElim');
		Route::get('administrador/agregar-cuenta','AdministratorController@getAddAccount');
		Route::post('administrador/agregar-cuenta/procesar','AdministratorController@postAddAccount');
        Route::get('administrador/editar-publicidad','AdministratorController@getEditPub');
		Route::post('administrador/modificar-publicidad/{pos}','AdministratorController@postEditPublicidad');
		Route::post('administrador/editar-publicidad/eliminar','AdministratorController@postElimSlides');

		Route::get('administrador/editar-cuenta','AdministratorController@getCuentas');
		Route::get('administrador/editar-cuenta/{id}','AdministratorController@modifyAccount');
		Route::post('administrador/editar-cuenta/{id}/enviar','AdministratorController@postModifyAccount');
		Route::post('administrador/editar-cuenta/eliminar','AdministratorController@postElimAccount');
		Route::group(array('before' => 'admin_check'),function(){
			Route::post('administrador/pagos/confirmar', 'AdministratorController@postPagos');

		});
	});
});