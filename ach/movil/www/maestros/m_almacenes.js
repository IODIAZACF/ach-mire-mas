var r_M_ALMACENES;
var page = $('div.page-current')[0].f7Page;
var params = page.router.currentRoute.context;

if(localStorage.getItem('M_ALMACENES')) buscar_grid('M_ALMACENES', localStorage.getItem('M_ALMACENES'));

if(params){

	//localStorage.removeItem("lastname");
	
	// Aqui se recogen los parametros que recibe la pagina <<params tipo JSON>>
	// params [ parametro ]
	//console.log( params['x'] );
}


function t_M_ALMACENES(obj, tecla){
	
	if( $(obj).data("registro") ) {
		var registro =  $(obj).data("registro") ;
		r_M_ALMACENES = registro;
	}

	switch(tecla){

		case 'enter':
			cargar_pagina('/pedidos/', r_M_ALMACENES);

		break;
		
		case 'esc':
			cargar_pagina('/menu_principal/');
		break;

		default:
		app.dialog.alert('Opción no programada..!');
		return false;
	}
}