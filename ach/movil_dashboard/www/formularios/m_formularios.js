var page = $('div.page-current')[0].f7Page;
var params = page.router.currentRoute.context;
var registro;

if(params){

	//localStorage.removeItem("lastname");
	
	// Aqui se recogen los parametros que recibe la pagina <<params tipo JSON>>
	// params [ parametro ]
	//console.log( params.filtro );
	
	
}

var r_M_FORMULARIOS;
	

function t_M_KOBO_FORMULARIOS(obj, tecla){
	
	//console.log(obj);
	//console.log(tecla);
	
	
	if( $(obj).data("registro") ) {
		registro =  $(obj).data("registro") ;
		r_M_FORMULARIOS = registro;
	}

	switch(tecla){
		case 'enter':
			cargar_pagina('/d_formularios/', { ID_M_KOBO_FORMULARIOS : r_M_FORMULARIOS.ID_M_KOBO_FORMULARIOS }  );
		break;

		case 'todos':
			buscar_grid("M_KOBO_FORMULARIOS","*");
		break;

		
		
		case 'esc':
			cargar_pagina('/menu_principal/');
		break;
		
		
		default:
		app.dialog.alert('Opción no programada..!', tituloAlert);
		return false;
		

	}
}
