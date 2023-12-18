var page = $('div.page-current')[0].f7Page;
var params = page.router.currentRoute.context;

if(params){

	// Aqui se recogen los parametros que recibe la pagina <<params tipo JSON>>
	// params [ parametro ]
	$('#M_SERVICIOS').attr('data-filtro', filtro);
	$('#M_SERVICIOS').attr('data-xfiltro', xfiltro );
}

buscar_grid('M_SERVICIOS', '*');

function t_M_SERVICIOS(obj, tecla){
	
	if( $(obj).data("registro") ) {
		var registro =  $(obj).data("registro") ;
		r_M_SERVICIOS = registro;
	}

	switch(tecla){
		case 'enter':
			var rnd  = Math.floor((Math.random() * 100000000) + 1);
			app.request.get('pedidos/f_servicios.html?'+rnd, function (data) {
				$$('#form-sheet').html(data);
				$$('#XDESCRIPCION').html(r_M_SERVICIOS.DESCRIPCION);
				
				$$('#f_servicios #DESCRIPCION').val(r_M_SERVICIOS.DESCRIPCION);
				$$('#f_servicios #CANTIDAD').val('1.00');
				/* $$('#f_servicios #PRECIO').val(format(r_M_SERVICIOS.PRECIO1,3).replaceAll(',','')); */
				xprecio = precio_cliente(3)	;
				$$('#f_servicios #PRECIO').val(format(xprecio,3).replaceAll(',',''));
				//console.log('xx ' + (format(xprecio,3).replaceAll(',','')) )  ;
				/* console.log(r_M_SERVICIOS.PRECIO1.replaceAll(',','')); */
				
				$$('#f_servicios #ID_M_ALMACENES').val(xid_m_almacenes);
				$$('#f_servicios #ID_M_PRODUCTOS').val(r_M_SERVICIOS.ID_M_PRODUCTOS);
				/* $$('#f_servicios #ID_D_SERVICIOS').val(r_M_SERVICIOS.ID_D_SERVICIOS); */
				$$('#f_servicios #ID_M_IMPUESTOS').val(r_M_SERVICIOS.ID_M_IMPUESTOS);
				
				/* $$('#f_servicios #ID_I_PROD_ALMA').val(r_M_SERVICIOS.ID_I_PROD_ALMA);
				$$('#f_servicios #ID_D_I_PROD_ALMA').val(r_M_SERVICIOS.ID_D_I_PROD_ALMA); */
				$$('#f_servicios #PRESENTACION').val(r_M_SERVICIOS.PRESENTACION);
				
				$$('#f_servicios #ID_X_M_DOCUMENTOS').val(r_M_CLIENTES.PEDIDO_ABIERTO);
				
				$$('#f_servicios').data('xbusca','-1');
				app.sheet.open('#form-sheet');
				$('#f_servicios #CANTIDAD').focus();
				$('#f_servicios #CANTIDAD').select();
				
			
			});			
		break;
		
		case 'ins':
		break;

		case 'esc':
			cargar_pagina('/pedidos/');
		break;

		default:
		app.dialog.alert('Opción no programada..!');
		return false;
		

	}
}

function precio_cliente(decimales){
	switch(r_M_CLIENTES.TIPO_PRECIO) {
		case '1':
			return format(r_M_SERVICIOS.PRECIO1, decimales);
		break;

		case '2':
			return format(r_M_SERVICIOS.PRECIO2, decimales);
		break;
		case '3':
			return format(r_M_SERVICIOS.PRECIO3, decimales);
		break;

		case '4':
			return format(r_M_SERVICIOS.PRECIO4, decimales);
		break;
		case '5':
			return format(r_M_SERVICIOS.PRECIO5, decimales);
		break;

		case '6':
			return format(r_M_SERVICIOS.PRECIO6, decimales);
		break;

		default:
			return '--';
	}	
}

Template7.registerHelper('tipo_precio', function (decimales, P1, P2, P3, P4, P5, P6){
	
	switch(r_M_CLIENTES.TIPO_PRECIO) {
		case '1':
			return format(P1, decimales);
		break;

		case '2':
			return format(P2, decimales);
		break;
		case '3':
			return format(P3, decimales);
		break;

		case '4':
			return format(P4, decimales);
		break;
		case '5':
			return format(P5, decimales);
		break;

		case '6':
			return format(P6, decimales);
		break;

		default:
			return '--';
	}	
});


