var page = $('div.page-current')[0].f7Page;
var params = page.router.currentRoute.context;

if(params){
	// Aqui se recogen los parametros que recibe la pagina
	//console.log( 'd_pedidos linea 6: ' + params);
	//console.log(params.accion);
	
}

var ID_X_M_DOCUMENTOS = r_M_CLIENTES.PEDIDO_ABIERTO;
if(ID_X_M_DOCUMENTOS){
	//console.log('Cliente tiene Pedido Abierto:' + ID_X_M_DOCUMENTOS );

} else {
	//console.log('No hay pedido abierto para este cliente. Se crea uno nuevo');
	
	var xCampos  = 'c_ID_M_CLIENTES_CSS=' + r_M_CLIENTES.ID_M_CLIENTES;
		xCampos += '&c_NOMBRES_CSS=' + r_M_CLIENTES.RAZON;
		xCampos += '&c_TIPO_CSS=FAC';
		xCampos += '&c_ID_M_VENDEDORES_CSS=' + x_id_m_vendedores;
	
	var url = server_path  + "herramientas/utiles/actualizar_registro_json.php";
	var params = 'tabla=X_M_DOCUMENTOS&xbusca=-1&' + xCampos;				
	
	//prompt('',url+'?'+params);		
	var data = enviar(url, params, 'POST');
	if(data.tabla.registro){
		var r_X_DOCUMENTOS = data.tabla.registro;
		//console.log(data.tabla.registro);
		r_M_CLIENTES.PEDIDO_ABIERTO = r_X_DOCUMENTOS.ID_X_M_DOCUMENTOS;
		ID_X_M_DOCUMENTOS =  r_X_DOCUMENTOS.ID_X_M_DOCUMENTOS;
		
		//console.log('Se creó el el pedido #' + ID_X_M_DOCUMENTOS  );			
	}
	
}
$('#d_pedidos').attr('data-xfiltro', ID_X_M_DOCUMENTOS);

$("#RAZON").html(r_M_CLIENTES.NOMBRE_CLIENTE);	
$('#ID_X_M_DOCUMENTOS').html(r_M_CLIENTES.PEDIDO_ABIERTO);


buscar_grid('d_pedidos', '*');

function t_d_pedidos(obj, tecla){  
	if( $(obj).data("registro") ) {
		var registro = $(obj).data("registro") ;
		r_X_DOCUMENTOS = registro;
	}

	switch(tecla){

		case 'productos':
			cargar_pagina('/m_productos/', r_X_DOCUMENTOS);
		break;
		
		case 'servicios':
			/* cargar_pagina('/m_servicios/', r_X_DOCUMENTOS); */
			
			filtro = 'TIPO';
			xfiltro = 'S';
			cargar_pagina('/m_servicios/', {filtro:filtro, xfiltro:xfiltro} );
			return false;
		break;	

		case 'enter':
			var rnd  = Math.floor((Math.random() * 100000000) + 1);
			app.request.get('pedidos/f_edicion.html?'+rnd, function (data) {
				$$('#form-sheet').html(data);
				$$('#DESCRIPCION').html(r_X_DOCUMENTOS.DESCRIPCION);
				
				$$('#f_edicion #CANTIDAD').val(format(r_X_DOCUMENTOS.CANTIDAD,3).replaceAll(',',''));
				$$('#f_edicion #PRECIO').val(format(r_X_DOCUMENTOS.PRECIO, 3).replaceAll(',',''));

				$$('#f_edicion #DESCRIPCION').val(r_X_DOCUMENTOS.DESCRIPCION);
				
				$$('#f_edicion #ID_M_ALMACENES').val(xid_m_almacenes);
				$$('#f_edicion #ID_M_PRODUCTOS').val(r_X_DOCUMENTOS.ID_M_PRODUCTOS);
				$$('#f_edicion #ID_D_PRODUCTOS').val(r_X_DOCUMENTOS.ID_D_PRODUCTOS);
				$$('#f_edicion #ID_M_IMPUESTOS').val(r_X_DOCUMENTOS.ID_M_IMPUESTOS);
				$$('#f_edicion #ID_X_M_DOCUMENTOS').val(r_M_CLIENTES.PEDIDO_ABIERTO);
				
				$$('#f_edicion').data('xbusca',r_X_DOCUMENTOS.ID_D_DOCUMENTOS);
				
				app.sheet.open('#form-sheet');
				$('#f_edicion #CANTIDAD').focus();
				$('#f_edicion #CANTIDAD').select();
			
			});			
								
		break;

		
		case 'f10':
			
			var url = server_path + 'herramientas/genera_json/genera_json.php';
			var params = 'tabla=X_M_DOCUMENTOS&campos=MONTO_BRUTO,MONTO_IMPUESTO,NETO&filtro=ID_X_M_DOCUMENTOS&xfiltro=' + ID_X_M_DOCUMENTOS;
			
			var data = enviar(url,params,'POST');
			
			var registro = data.tabla.registro[0];

			var rnd  = Math.floor((Math.random() * 100000000) + 1);
			app.request.get('pedidos/f_cierre.html?'+rnd, function (data) {
				$$('#form-sheet').html(data);
				$$("#MONTO_BRUTO").html(format(registro.MONTO_BRUTO,2));
				$$("#MONTO_IMPUESTO").html(format(registro.MONTO_IMPUESTO,2));
				$$("#NETO").html(format(registro.NETO,2));
				app.sheet.open('#form-sheet');	
			
			});			
		break;

			
		case 'esc':
			cargar_pagina('/menu_cliente/',r_M_CLIENTES);
		break;
		
		
		default:
			app.dialog.alert('opción no programada..!');
			return false;


	}
}

function t_f_edicion(obj, tecla){  

	if( $(obj).data("registro") ) {
		var registro = $(obj).data("registro") ;
	}

	switch(tecla){
		case 'esc':
			app.sheet.close('#form-sheet');	
		break;

		case 'supr':
			var url = server_path  + "herramientas/utiles/actualizar_registro_json.php";
			var params = 'tabla=X_DOCUMENTOS&busca=ID_D_DOCUMENTOS&xbusca=-' + r_X_DOCUMENTOS.ID_D_DOCUMENTOS;				
			var data = enviar(url,params,'POST');
			app.sheet.close('#form-sheet');	
			buscar_grid('d_pedidos','*');
			
		break;

		case 'f12':
		
			var xunidad   = $("#UNIDAD").val();
			var xcantidad = $("#CANTIDAD").val();
			var xprecio   = $("#PRECIO").val();
			
			$("#CANTIDAD").val( xcantidad * xunidad );
			/* $("#PRECIO").val( xprecio / xunidad ); */
			var xprecio_unidad = xprecio / xunidad;
			$("#PRECIO").val(xprecio_unidad);
			//console.log( "cambio la unidad " + xunidad );

			//console.log( $$("#f_edicion CANTIDAD").val()    );
			guardar_formulario('f_edicion', function(resp){
			app.sheet.close('#form-sheet');	
			buscar_grid('d_pedidos','*');
			cargar_pagina('/pedidos/');
			});
			
		
		break;
		
		default:
			app.dialog.alert('opción no programada..!');
			return false;


	}
}

function t_f_servicios(obj, tecla){  

	if( $(obj).data("registro") ) {
		var registro = $(obj).data("registro") ;
	}

	switch(tecla){
		case 'esc':
			app.sheet.close('#form-sheet');	
		break;

		case 'supr':
			var url = server_path  + "herramientas/utiles/actualizar_registro_json.php";
			var params = 'tabla=X_DOCUMENTOS&busca=ID_D_DOCUMENTOS&xbusca=-' + r_X_DOCUMENTOS.ID_D_DOCUMENTOS;				
			var data = enviar(url,params,'POST');
			app.sheet.close('#form-sheet');	
			buscar_grid('d_pedidos','*');
			
		break;

		case 'f12':
		
			var xunidad   = $("#UNIDAD").val();
			var xcantidad = $("#CANTIDAD").val();
			var xprecio   = $("#PRECIO").val();
			
			$("#CANTIDAD").val( xcantidad * xunidad );
			/* $("#PRECIO").val( xprecio / xunidad ); */
			var xprecio_unidad = xprecio / xunidad;
			$("#PRECIO").val(xprecio_unidad);
			//console.log( "cambio la unidad " + xunidad );

			//console.log( $$("#f_servicios CANTIDAD").val()    );
			guardar_formulario('f_servicios', function(resp){
			app.sheet.close('#form-sheet');	
			buscar_grid('d_pedidos','*');
			cargar_pagina('/pedidos/');
			});
			
		
		break;
		
		default:
			app.dialog.alert('opción no programada..!');
			return false;


	}
}

function t_f_cierre(obj, tecla){  
	if( $(obj).data("registro") ) {
		var registro = $(obj).data("registro") ;
	}
	
	switch(tecla){
		case 'esc':
			app.sheet.close('#form-sheet');	
		break;

		case 'f12':
			
			var url = server_path  + "herramientas/utiles/actualizar_registro_json.php";
			var params  = 'tabla=X_M_DOCUMENTOS&busca=ID_X_M_DOCUMENTOS&xbusca=' + ID_X_M_DOCUMENTOS;
				params += '&c_ESTATUS_CSS=PEN';				
				params += '&c_COMENTARIOS_CSS=' + $$("#COMENTARIOS").val();
				params += '&c_CAMPO1_CSS=' + $$("#CAMPO1").val();

			//prompt('',url+'?'+params);

			var data = enviar2(url, params, 'POST', function(data){
				
				if(data.tabla.estatus!='OK') {
					app.dialog.alert('Error al Cerrar el Documento' + data.tabla.msg , tituloAlert);
					return;
				}
					
				app.sheet.close('#form-sheet');	
				cargar_pagina('/m_clientes/');
				//console.log(data);
			});
	
		break;
		
		default:
			app.dialog.alert('opción no programada..!', tituloAlert);
			return false;


	}
}


