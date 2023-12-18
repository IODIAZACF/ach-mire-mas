var r_M_CLIENTES;
var page = $('div.page-current')[0].f7Page;
var params = page.router.currentRoute.context;

if(localStorage.getItem('M_CLIENTES')) buscar_grid('M_CLIENTES', localStorage.getItem('M_CLIENTES'));

if(params){

	//localStorage.removeItem("lastname");
	
	// Aqui se recogen los parametros que recibe la pagina <<params tipo JSON>>
	// params [ parametro ]
	//console.log( params['x'] );
}


function t_M_CLIENTES(obj, tecla){
	
	if( $(obj).data("registro") ) {
		var registro =  $(obj).data("registro") ;
		r_M_CLIENTES = registro;
	}

	switch(tecla){

		case 'enter':
			cargar_pagina('/menu_cliente/', r_M_CLIENTES);

		break;
		
		case 'esc':
			cargar_pagina('/menu_principal/');
		break;

		case 'catalogo':
			cargar_pagina('/catalogo/', r_M_CLIENTES);
		break;
		
		default:
		app.dialog.alert('Opción no programada..!');
		return false;
		

	}
}