var page = $('div.page-current')[0].f7Page;
var params = page.router.currentRoute.context;

if(params){
	// Aqui se recogen los parametros que recibe la pagina
	//console.log( params);
}	


var md5 = $.md5('value');

$("#NOMBRE_USUARIO").html(NOMBRE_USUARIO);
$(".db").html(db);



function t_MENU_PRINCIPAL(obj, tecla){
	switch(tecla){

		case 'control_formularios': 	// Control de Formularios
			cargar_pagina('/m_formularios/', '1');
		break;
		
		case 'graficos_alerta': 	// Graficos Alerta
			cargar_pagina('/graficos/', '{tipo:"ALERTA"}');
		break;

		case 'graficos_ern': 		// Graficos ERN
			cargar_pagina('/graficos/', '{tipo:"ERN"}');
		break;

		case 'graficos_cierre': 	// Graficos Cierre
			cargar_pagina('/graficos/', {filtro:'NOMBRE_GRUPO', xfiltro:'CONTABLE', xtitulo: 'SALDO CONTABLE' } );
		break;
		
		case 'esc': 	// Salir
			cargar_pagina('/login/', '1');
		break;
		
		default:
			app.dialog.alert('opcion invalida', tituloAlert);
		return false;
		
		
	}
	
}

