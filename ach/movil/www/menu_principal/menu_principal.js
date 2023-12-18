var page = $('div.page-current')[0].f7Page;
var params = page.router.currentRoute.context;

if(params){
	// Aqui se recogen los parametros que recibe la pagina
	//console.log( params);
}	


function t_MENU_PRINCIPAL(obj, tecla){
	
	switch(tecla){
		
		case 'clientes': 	// Maestro de Clientes
			cargar_pagina('/m_clientes/', '1');
		break;

		case 'lista_precios': 	// Lista de Precios
			cargar_pagina('/lista_precios_productos/', '1');
		break;

		case 'catalogo_lineas': 	// Catalogo por Lineas
			cargar_pagina('/catalogo_lineas/', '1');
		break;

		case 'carrito': 	// Carrito de Compras
			cargar_pagina('/carrito/', '1');
		break; 

		case 'esc':	    // Salir
			cargar_pagina('/login/');
			return false;
		break;

		default:
			app.dialog.alert('opcion invalida');
		return false;
	}
	
}

