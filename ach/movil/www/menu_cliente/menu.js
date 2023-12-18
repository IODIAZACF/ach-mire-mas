var page = $('div.page-current')[0].f7Page;
var params = page.router.currentRoute.context;

if(params){
	// Aqui se recogen los parametros que recibe la pagina
	//console.log( params );
}	

$$("#NOMBRE_CLIENTE"   ).html( r_M_CLIENTES.NOMBRE_CLIENTE 				 		 );
$$("#SALDO_REAL"	   ).html( format(r_M_CLIENTES.SALDO_REAL 		 		 ,2) );
$$("#SALDO_DOCUMENTADO").html( format(r_M_CLIENTES.SALDO_DOCUMENTADO 		 ,2) );
$$("#SALDO_CANCELAR"   ).html( format(r_M_CLIENTES.SALDO_CANCELAR	 		 ,2) );
$$("#CHEQUES_PROTESTADOS"   ).html( format(r_M_CLIENTES.CHEQUES_PROTESTADOS	 ,2) );

$$("#TELEFONO1").html( r_M_CLIENTES.TELEFONO1 );
$$("#TELEFONO2").html( r_M_CLIENTES.TELEFONO2 );
$$("#TELEFONO3").html( r_M_CLIENTES.TELEFONO3 );

$$("#TELEFONO1").attr("href","tel:" + r_M_CLIENTES.TELEFONO1);
$$("#TELEFONO2").attr("href","tel:" + r_M_CLIENTES.TELEFONO2);
$$("#TELEFONO3").attr("href","tel:" + r_M_CLIENTES.TELEFONO3);

if(r_M_CLIENTES.PEDIDO_ABIERTO){
	$$('#OPCION_PEDIDO').html('Continuar Pedido');
} else {
	$$('#OPCION_PEDIDO').html('Nuevo Pedido');
}

if(r_M_CLIENTES.PEDIDO_ABIERTO){
	$$('#OPCION_PEDIDO2').html('Continuar Pedido2');
} else {
	$$('#OPCION_PEDIDO2').html('Nuevo Pedido2');
}

var xrnd  = Math.floor((Math.random() * 100000000) + 1);
var url   = server_path + "herramientas/genera_json/genera_json.php";
var param = 'origen=movil/www/menu_cliente/sql&procedimiento=PROTESTADOS&ID_M_CLIENTES=' + r_M_CLIENTES.ID_M_CLIENTES + '&rnd=' + xrnd;

enviar2(url, param, 'POST', function(data){
	
	if(data.tabla.registro){
		$$("#CHEQUES_PROTESTADOS").html( format(data.tabla.registro[0].SALDO, 2)  );
	}

});





function t_MENU(obj, tecla){
	
	switch(tecla){
		
		case 'm_clientes': 	// Maestro de Clientes
			cargar_pagina('/m_clientes/', '1');
		break;

		case 'historial': 	// Historial de Ventas
			cargar_pagina('/m_documentos/');
		break;

		case 'saldo_real':			// Cuentas por Cobrar con saldo real
			filtro = 'ID_M_CLIENTES;SALDO';
			xfiltro = r_M_CLIENTES['ID_M_CLIENTES'] + ';0';
			xoperadores = '=;<>'
			
			cargar_pagina('/d_cxc/', {filtro:filtro, xfiltro:xfiltro, xoperadores:xoperadores} );
			return false;
		break;

/* 		case 'saldo_documentado':	// Cuentas por Cobrar con saldo documentado
			cargar_pagina('/d_documentado/' , {filtro:'POSTFECHADO', xfiltro:'SI'} );
			return false;
		break; */

		case 'saldo_cancelar':	// Cuentas por Cobrar con saldo a cancelar
			filtro = 'ID_M_CLIENTES;SALDO';
			xfiltro = r_M_CLIENTES['ID_M_CLIENTES'] + ';0';
			xoperadores = '=;<>'
			cargar_pagina('/d_cxc/', {filtro:filtro, xfiltro:xfiltro, xoperadores:xoperadores} );
			return false;
		break;

		case 'cheques_protestados':	// Cuentas por Cobrar cheques protestados
			filtro = 'ID_M_CLIENTES;SALDO;TIPO;ID_M_CONCEPTOS';
			xfiltro = r_M_CLIENTES['ID_M_CLIENTES'] + ';0;ADC;XXXXMCONC001';
			xoperadores = '=;<>;=;='
		
			cargar_pagina('/d_cxc/' , {filtro:filtro, xfiltro:xfiltro, xoperadores:xoperadores} );
			return false;
		break;
		
		case 'nuevo':	    // Nuevo Pedido
			cargar_pagina('/pedidos/');
			return false;
		break;
		case 'nuevo2':	    // Nuevo Pedido2
			cargar_pagina('/m_almacenes/');
			return false;
		break;
		case 'esc':	    // Salir
			cargar_pagina('/m_clientes/');
			return false;
		break;

		case 'mapa':	    // Mapa cliente
			cargar_pagina('/mapa/');
			return false;
		break;
		
		default:
			app.dialog.alert('opcion invalida');
		return false;
	}
	
}

/* var url = server_path + "herramientas/genera_json/genera_json.php";
var xrnd  = Math.floor((Math.random() * 100000000) + 1);
var params = 'origen=menu_cliente/ini/sql&procedimiento=PROTESTADOS&ID_M_CLIENTES=' + r_M_CLIENTES.ID_M_CLIENTES + '&rnd=' + xrnd;
console.log(url+'&'+params);
enviar(url, params, 'POST', 'json', function(data){
	if(data.tabla.registro){
		console.log('SI HAY DATA');
		}
	else{
		console.log('No hay data');
		}
	}
	); */

